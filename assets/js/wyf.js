(function(){
wyf = {
    listView : {
        api : undefined,
        itemsPerPage : 10,
        pages : 1,
        page: 1,
        count : 0,
        update : function(info)
        {
            $.getJSON(
                this.api + '?ipp=' + this.itemsPerPage + '&pg=' + this.page + (info ? '&info=yes' : ''),
                function (response)
                {
                    $('#wyf_list_view').html(
                        Mustache.render(
                            $('#wyf_list_view_template').html(), 
                            {list:response.data}
                        )
                    );
                    if(response.count !== undefined)
                    {
                        wyf.listView.pages = Math.ceil(response.count / wyf.listView.itemsPerPage);
                        $('#wyf_list_view_size').html(wyf.listView.pages);
                    }
                    
                }
            );
        },
        init : function()
        {
            this.update(true);
        },
        nextPage : function()
        {
            this.page++;
            this.update(false);
            $('#wyf_list_view_page').html(this.page);
        },
        prevPage : function()
        {
            this.page--;
            this.update(false);
            $('#wyf_list_view_page').html(this.page);
        }
    }
};
})();
