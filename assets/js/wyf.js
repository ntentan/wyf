function resizer(){
  $('#menu > div').height($(window).height() - $('#header').height());
}

$(function(){
  $(window).resize(resizer);  
  resizer();
});

var wyf = {
  forms : {
    showCreateItemForm : function(entity, list) {
      if(list.value == 'new') {
        fzui.modal('#' + entity + '_add_modal')
        list.value = '';
      } else if(list.value == '-') {
        list.value = '';
      }
    },
    validateInputs : function(entity, url, field, callback) {
      var data = {}
      var formSelector = '#' + entity + '_add_form';
      $(formSelector + ' :input').serializeArray().map(function(x){data[x.name] = x.value;});
      api.post({
        url: url + "/validator", 
        data: JSON.stringify(data), 
        success: function(response){
          if(typeof callback === 'function') {
            callback(
              true, 
              {response: response, field: field, data: data}
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
            callback(false, {response: response, field: field, data: data});
          }
        }
      })
    },
    addToListCallback : function(success, data) {
      if(!success) return;
      $('#' + data.field + " option:first").after($('<option/>', {value:0, text:data.response.string}));
      $('#' + data.field).val(0);      
      var fieldContainer = $('#form-element-' + data.field + " > .hidden-fields");
      var package = $('#' + data.field).attr('package');
      
      fieldContainer.html("");
      for(var field in data.data) {
        fieldContainer.append($("<input/>").attr({type:"hidden", name:package+"."+field}).val(data.data[field]));
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
