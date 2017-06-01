function resizer(){
  $('#menu > div').height($(window).height() - $('#header').height());
}

var wyf = {
  forms : {
    multiFieldIds : {},
    multiFieldValues : {},
    showCreateItemForm : function(list, templateId) {
      $('#' + templateId + '_view').html("");
      if(list.value == 'new') {
        var template = Handlebars.compile($('#' + templateId + '_template').html());
        if($('body').hasClass('modal-active')) {
          console.log(templateId + '_template');
          $('#' + templateId + '_view').html(template());
        } else {
          $('#' + templateId + '_modal div.form-wrapper').html(template())
          fzui.modal('#' + templateId + '_modal');
        }
        list.value = '';
      } else if(list.value == '-') {
        list.value = '';
      }
    },
    /**
     * 
     * @param string field
     * @param object data
     */
    renderMultiFieldItem : function(field, data) {
      var template = Handlebars.compile($('#' + field.name + '-multi-field-preview').html());
      var wrapper = $('<div/>').addClass('multi-field-preview');
      var index = '[' + wyf.forms.multiFieldIds[field.name] + ']';
      
      wrapper.append(
        $('<div/>').addClass('multi-field-buttons').append(
          $('<button>').addClass('multi-field-delete').click(function(){
            wrapper.remove()
          })
        )
      );
      
      if(data[field.primaryKey] === undefined) {
        for(var dataField in data){
          wrapper.append(
            $('<input/>').attr({type:'hidden', name:field.model + '.' + dataField + index, value:data[dataField]})
          );          
        }
      } else {
        wrapper.append(
          $('<input/>').attr({type:'hidden', name:field.model + '.' + field.primaryKey + index, value:data[field.primaryKey]})
        );
      }
      wrapper.append(template(data));
      $('#form-element-' + field.name + ' .input-wrapper').append(wrapper);
      wyf.forms.multiFieldIds[field.name]++;
    },
    addMultiFields : function(field, model, primaryKey, type) {
      var form = '#' + type + '-multi-field-form';
      var value = $(form + ' select[name=' + field + ']').val();
      
      if(value == '') {
        $(form + ' #form-element-' + field).addClass('form-error');
      } else if(value == '-1') {
        var data = {};
        $(form + ' #form-element-' + field).removeClass('form-error');
        $(form + ' #form-element-' + field + ' .hidden-fields input[type=hidden]').each(function(i, input){
          data[input.name.split('.').pop()] = input.value
        });
        wyf.forms.renderMultiFieldItem({name:type, model:model, primaryKey:primaryKey}, data)
        fzui.closeModal();
      } else {
        $(form + ' #form-element-' + field).removeClass('form-error');
        /*$('#form-element-' + type + ' .hidden-fields').append(
          $('<input/>').attr({type:'hidden', name:model + '.' + primaryKey + index, value:value})
        );*/        
        fzui.closeModal();
        //wyf.forms.multiFields[field]++;
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
    validateInputs : function(formSelector, url, callbackData, callback) {
      var data = {}
      $(formSelector + ' :input').serializeArray().map(function(x){data[x.name] = x.value;});
      api.post({
        url: url + "/validator", 
        data: JSON.stringify(data), 
        success: function(response){
          if(typeof callback === 'function') {
            callback(
              true, 
              {response: response, callbackData: callbackData, data: data}
            );
            fzui.closeModal();
          }
        },
        failure: function(response){
          for(name in response.invalid_fields) {
            var errors = response.invalid_fields[name].reduce(
              function(arr,x){
                arr.push({error:x}); 
                return arr;
              }, []
            );
            var template = Handlebars.compile("<ul>{{#errors}}<li>{{error}}</li>{{/errors}}</ul>");
            $(formSelector + " #form-element-" + name).addClass('form-error');
            $(formSelector + " #form-element-" + name +" :input").after(template({errors: errors}));
          }
          if(typeof callback === 'function') {
            callback(false, {response: response, callbackData: callbackData, data: data});
          }
        }
      })
    },
    addToListCallback : function(success, data) {
      if(!success) return;
      var field = data.callbackData;
      $('#' + field + " option:first").after($('<option/>', {value:'-1', text:data.response.string}));
      $('#' + field).val("-1");      
      var fieldContainer = $('#form-element-' + field + " > .hidden-fields");
      var package = $('#' + field).attr('package');
      
      fieldContainer.html("");
      fieldContainer.append($('<input/>').attr({type:'hidden', name:package}).val(data.response.string));
      for(var key in data.data) {
        fieldContainer.append($("<input/>").attr({type:"hidden", name:package+"."+key}).val(data.data[key]));
      }
    }
  },
  list : {
    pages : 0,
    currentPage : 1,
    itemsPerPage : 20,
    apiUrl : null,
    render : function(url, page) {
      api.get({url:url, data:{page:page, limit:wyf.list.itemsPerPage},
        success:function(data, xhr){
            var template = Handlebars.compile($('#wyf_list_view_template').html());
            $('#wyf_list_view').html(template({list:data}));
            wyf.list.currentPage = page;
            wyf.list.apiUrl = url;
            wyf.list.pages = Math.ceil(xhr.getResponseHeader('X-Item-Count') / wyf.list.itemsPerPage);
            if(wyf.list.pages < 2) {
              $('#wyf_list_view_nav').hide();
            }
            $('#wyf_list_view_size').html(wyf.list.pages);
        }
      })
    },
    next : function() {
      wyf.list.currentPage++;
      if(wyf.list.currentPage > wyf.list.pages) {
        wyf.list.currentPage = wyf.list.pages;
      } else {
        wyf.list.render(wyf.list.apiUrl, wyf.list.currentPage);
      }
    },
    prev : function() {
      wyf.list.currentPage--;
      if(wyf.list.currentPage == 0) {
        wyf.list.currentPage = 1;
      } else {
        wyf.list.render(wyf.list.apiUrl, wyf.list.currentPage);
      }
    }
  }
};

$(function(){
  $(window).resize(resizer);  
  resizer();
  for(var field in wyf.forms.multiFieldValues) {
    var fieldDetails = {
      name: field, 
      model: wyf.forms.multiFieldValues[field].model,
      primaryKey: wyf.forms.multiFieldValues[field].primaryKey
    };
    wyf.forms.multiFieldIds[field] = 0;
    for(var item in wyf.forms.multiFieldValues[field].values) {
      wyf.forms.renderMultiFieldItem(
        fieldDetails, wyf.forms.multiFieldValues[field].values[item]
      );
    }
  }
});
