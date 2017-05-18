function resizer(){
  $('#menu > div').height($(window).height() - $('#header').height());
}

$(function(){
  $(window).resize(resizer);  
  resizer();
});

var wyf = {
  showCreateItemForm : function(package, list) {
    if(list.value == 'new') {
      fzui.modal('#' + package + '_add_modal')
    } else if(list.value == '-') {
      list.value = '';
    }
  },
  saveInputs : function(package, url, field, callback) {
    var data = {}
    var formSelector = '#' + package + '_add_form';
    $(formSelector + ' :input').serializeArray().map(function(x){data[x.name] = x.value;});
    api.post({
      url: url, 
      data: JSON.stringify(data), 
      success: function(response){
        if(typeof callback === 'function') {
          callback(
            true, 
            {data:data, response:response, field:field, url:url}, 
            function(){fzui.closeModal();}
          );
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
          callback(false, {data:data, response:response, field:field});
        }
      }
    })
  },
  forms : {
    addToListCallback : function(success, data, callback) {
      if(!success) return;
      api.get({
        url : data.url + "/" + data.response.id,
        contentType : 'text/plain',
        success : function(response) {
          $('#' + data.field+" option:first").after($('<option/>', {value:data.response.id, text:response}));
          $('#' + data.field).val(data.response.id);
          callback();
        }
      });
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
