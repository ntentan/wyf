function resizer(){
  $('#menu > div').height($(window).height() - $('#header').height());
}

$(function(){
  $(window).resize(resizer);  
  resizer();
});

var wyf = {
  showCreateItemForm : function() {
    
  },
  list : {
    pages : 0,
    currentPage : 1,
    itemsPerPage : 20,
    apiUrl : null,
    render : function(url, page) {
      api.get(url, {page:page, limit:wyf.list.itemsPerPage},
        function(data, xhr){
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
      )
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
