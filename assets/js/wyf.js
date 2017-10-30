function resizer() {
  $('#menu > div').height($(window).height() - $('#header').height());
}

var wyf = {

  forms: {

    multiFieldIds: {},

    multiFieldValues: {},

    init: function (selector) {
      var parent = $(selector === undefined ? 'body' : selector);
      // Setup the datepicker
      parent.find('.pikaday').pikaday({format: 'YYYY-MM-DD'});

      // Initialize and render all multifields
      // @todo Look into moving this when the centralized form system is implemented
      // @todo scope multifields to the ids of the forms
      for (var field in wyf.forms.multiFieldValues) {
        var fieldDetails = {
          name: field,
          model: wyf.forms.multiFieldValues[field].model,
          primaryKey: wyf.forms.multiFieldValues[field].primaryKey
        };
        wyf.forms.multiFieldIds[field] = 0;
        for (var item in wyf.forms.multiFieldValues[field].values) {
          wyf.forms.renderMultiFieldItem(
            fieldDetails, wyf.forms.multiFieldValues[field].values[item]
          );
        }
      }
    },

    showCreateItemForm: function (list, templateId) {
      $('#' + templateId + '_view').html("");
      if (list.value == 'new') {
        var template = Handlebars.compile($('#' + templateId + '_template').html());
        if ($('body').hasClass('modal-active')) {
          $('#' + templateId + '_view').html(template());
        } else {
          $('#' + templateId + '_modal div.form-wrapper').html(template())
          fzui.modal('#' + templateId + '_modal');
        }
        list.value = '';
      } else if (list.value == '-') {
        list.value = '';
      }
    },

    /**
     * Render a single item under the multifield button as a preview.
     * @param string field
     * @param object data
     */
    renderMultiFieldItem: function (field, data) {
      var template = Handlebars.compile($('#' + field.name + '-multi-field-preview').html());
      var wrapper = $('<div/>').addClass('multi-field-preview');
      var index = '[' + wyf.forms.multiFieldIds[field.name] + ']';

      wrapper.append(
        $('<div/>').addClass('multi-field-buttons').append(
          $('<button>').addClass('multi-field-delete').click(function () {
            wrapper.remove()
          })
        )
      );

      if (data[field.primaryKey] === undefined) {
        for (var dataField in data) {
          wrapper.append(
            $('<input/>').attr({type: 'hidden', name: field.model + '.' + dataField + index, value: data[dataField]})
          );
        }
      } else {
        wrapper.append(
          $('<input/>').attr({
            type: 'hidden',
            name: field.model + '.' + field.primaryKey + index,
            value: data[field.primaryKey]
          })
        );
      }
      wrapper.append(template(data));
      $('#form-element-' + field.name + ' .input-wrapper').append(wrapper);
      wyf.forms.multiFieldIds[field.name]++;
    },
    addMultiFields: function (field, model, primaryKey, type, apiUrl) {
      var form = '#' + type + '-multi-field-form';
      var value = $(form + ' select[name=' + field + ']').val();

      if (value == '') {
        $(form + ' #form-element-' + field).addClass('form-error');
      } else if (value == '-1') {
        var data = {};
        $(form + ' #form-element-' + field).removeClass('form-error');
        $(form + ' #form-element-' + field + ' .hidden-fields input[type=hidden]').each(function (i, input) {
          data[input.name.split('.').pop()] = input.value
        });
        wyf.forms.renderMultiFieldItem({name: type, model: model, primaryKey: primaryKey}, data)
        fzui.closeModal();
      } else {
        $(form + ' #form-element-' + field).removeClass('form-error');
        api.get({
          url: apiUrl + '/' + value,
          success: function (response) {
            $(form + ' #form-element-' + field).removeClass('form-error');
            wyf.forms.renderMultiFieldItem({name: type, model: model, primaryKey: primaryKey}, response)
          }
        })
        fzui.closeModal();
      }
    },

    /**
     * Call the WYF API to validate inputs found in a given container.
     *
     * @param {type} formSelector
     * @param {type} url
     * @param {type} callbackData
     * @param {type} callback
     * @returns {undefined}
     */
    validateInputs: function (formSelector, url, callbackData, callback) {
      var data = {}
      $(formSelector + ' :input').serializeArray().map(function (x) {
        data[x.name] = x.value;
      });
      api.post({
        url: url + "/validator",
        data: JSON.stringify(data),
        success: function (response) {
          if (typeof callback === 'function') {
            callback(
              true,
              {response: response, callbackData: callbackData, data: data}
            );
            fzui.closeModal();
          }
        },
        failure: function (response) {
          for (name in response.invalid_fields) {
            var errors = response.invalid_fields[name].reduce(
              function (arr, x) {
                arr.push({error: x});
                return arr;
              }, []
            );
            var template = Handlebars.compile("<ul>{{#errors}}<li>{{error}}</li>{{/errors}}</ul>");
            $(formSelector + " #form-element-" + name).addClass('form-error');
            $(formSelector + " #form-element-" + name + " :input").after(template({errors: errors}));
          }
          if (typeof callback === 'function') {
            callback(false, {response: response, callbackData: callbackData, data: data});
          }
        }
      })
    },

    /**
     * Adds an item to the select list after it has been created on the form.
     * @param {type} success
     * @param {type} data
     * @returns {undefined}
     */
    addToListCallback: function (success, data) {
      if (!success) return;
      var field = data.callbackData;
      $('#' + field + " option:last").after($('<option/>', {value: '-1', text: data.response.string}));
      $('#' + field).val("-1");
      var fieldContainer = $('#form-element-' + field + " > .hidden-fields");
      var package = $('#' + field).attr('package');

      fieldContainer.html("");
      fieldContainer.append($('<input/>').attr({type: 'hidden', name: package}).val(data.response.string));
      for (var key in data.data) {
        fieldContainer.append($("<input/>").attr({type: "hidden", name: package + "." + key}).val(data.data[key]));
      }
    },

    selectModelSearchItem: function (item, name) {
      $("input[name='" + name + "']").val($(item).attr('value')).trigger('change');
      $('#' + name).val($(item).attr('label'));
      $('#' + name + '_response_list').hide();
    },

    /**
     *
     *
     * @param string field
     * @param string apiUrl
     * @param string fields
     * @param string name
     * @returns void
     */
    updateModelSearchField: function (field, event, apiUrl, fields, name) {
      var list = $('#' + name + '_response_list');

      if (event.code === "ArrowUp") {
        var children = list.find(".model-search-field-list-item-selected").prev();
        list.find(".model-search-field-list-item-selected").removeClass("model-search-field-list-item-selected");
        if (children.length === 0) {
          list.find("div:last-child").addClass('model-search-field-list-item-selected');
        } else {
          children.addClass('model-search-field-list-item-selected');
        }
        event.preventDefault();
        return;
      }

      if (event.code === "ArrowDown") {
        var children = list.find(".model-search-field-list-item-selected").next();
        list.find(".model-search-field-list-item-selected").removeClass("model-search-field-list-item-selected");
        if (children.length === 0) {
          list.find("div:first-child").addClass('model-search-field-list-item-selected');
        } else {
          children.addClass('model-search-field-list-item-selected');
        }
        event.preventDefault();
        return;
      }

      if (event.code === "Enter") {
        this.selectModelSearchItem(list.find(".model-search-field-list-item-selected"), name)
        list.hide();
        return;
      }

      if (field.value === '') {
        list.hide();
        return;
      }
      api.call({
        url: apiUrl,
        data: {q: field.value, limit: 10, fields: 'id,' + fields, search_fields: fields},
        success: function (results) {
          var template = Handlebars.compile($('#' + name + '_preview_template').html());

          list.html('');
          if (results.length > 0) {
            list.show();
          } else {
            list.hide();
          }
          for (var i in results) {
            $('#' + name + '_response_list').append(template(results[i]));
          }
        }
      });
    }
  },


  list: {
    pages: 0,
    currentPage: 1,
    itemsPerPage: 10,
    apiUrl: null,
    query: '',
    importJobUrl: null,
    importParameters: null,
    render: function (url) {
      var getData = {page: wyf.list.currentPage, limit: wyf.list.itemsPerPage, sort: 'id'};
      if (wyf.list.query != '') {
        getData.q = wyf.list.query;
      }
      api.get({
        url: wyf.list.apiUrl, data: getData,
        success: function (data, xhr) {
          var template = Handlebars.compile($('#wyf_list_view_template').html());
          var count = xhr.getResponseHeader('X-Item-Count');
          wyf.list.pages = Math.ceil(count / wyf.list.itemsPerPage);
          if (wyf.list.pages > 1) {
            $('#wyf_list_view_nav').show();
          }
          if (count > 0) {
            $('#wyf_list_view').html(template({list: data}));
          } else {
            $('#wyf_list_view').html($('#wyf_list_view_empty').html());
          }
          $('#wyf_list_view_size').html(wyf.list.pages);
          $('#wyf_list_view_page').html(wyf.list.currentPage);
        }
      })
    },
    next: function () {
      wyf.list.currentPage++;
      if (wyf.list.currentPage > wyf.list.pages) {
        wyf.list.currentPage = wyf.list.pages;
      } else {
        wyf.list.render(wyf.list.apiUrl);
      }
      $('#wyf_list_view_page').html(wyf.list.currentPage);
    },
    prev: function () {
      wyf.list.currentPage--;
      if (wyf.list.currentPage == 0) {
        wyf.list.currentPage = 1;
      } else {
        wyf.list.render(wyf.list.apiUrl);
      }
      $('#wyf_list_view_page').html(wyf.list.currentPage);
    },
    checkImportStatus: function () {
      api.call({
        url: wyf.list.importJobUrl,
        success: function (response) {
          var results;
          var template;
          var entities = wyf.list.importParameters.entities;
          if(response.status == 'queued') {
            template = Handlebars.compile($('#import-message-template').html());
            $('#import-message').html(template({
              'title': 'Please wait ...',
              'message': 'Your ' + entities + ' import is currently queued.'
            }));
            setTimeout(wyf.list.checkImportStatus, 5000);
          } else if(response.status == 'running') {
            template = Handlebars.compile($('#import-message-template').html());
            $('#import-message').html(template({
              'title': 'Importing ...',
              'message': "<i class=\"fa fa-spinner fa-pulse fa-fw\"></i>  Your "+entities+" are currently being imported ..."
            }));
            setTimeout(wyf.list.checkImportStatus, 5000);
          } else if (response.status == 'finished') {
            results = JSON.parse(response.response);
            results.entities = entities;
            results.base_url = wyf.list.importParameters.base_url;
            if (results.errors.length > 0) {
              template = Handlebars.compile($('#import-errors-template').html());
            } else {
              template = Handlebars.compile($('#import-success-template').html());
            }
            $('#import-message').html(template(results));
          }
        }
      });
    },
    uploadData: function (url) {
      $('<input/>').attr({type: 'file'})
        .change(function (event) {
          var form = new FormData();
          form.append('data', event.target.files[0]);
          $('#import-actions').slideToggle();
          $('#import-loader').slideToggle();
          $.ajax({
            type: 'POST',
            url: url,
            processData: false,
            contentType: false,
            data: form
          }).done(function (jobId) {
            wyf.list.importJobUrl = url + "_status/" + jobId;
            wyf.list.checkImportStatus();
          })
        })
        .click();
    }
  }
};

$(function () {
  // Setup and handle window resizing
  $(window).resize(resizer);
  resizer();

  // Setup the search
  $('#wyf-list-search-field').click(function (event) {
    event.stopPropagation();
  }).keyup(function (event) {
    wyf.list.query = event.target.value;
    wyf.list.render()
  });

  // Initialize
  wyf.forms.init('body');

  // Show any notifications
  var notification = $('#notification');
  if(notification.html() != "") {
    notification.offset({left: $(window).width() - notification.outerWidth(true)});
    setTimeout(function(){
      notification.animate({opacity:"show", top:70}, function(){
        setTimeout(function(){notification.animate({opacity:"hide", top: 85})}, 3000)
      });
    }, 1500)
  }

  // Set focus to search field when the search button is pressed.
  $('#wyf-list-search-button').click(function () {
    $('#wyf-list-search-wrapper').slideToggle(function () {
      $('#wyf-list-search-field').focus();
    });
  })
});
