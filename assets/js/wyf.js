(function(){
wyf = {
    listView : {
        api : undefined,
        itemsPerPage : 10,
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
        },
        prevPage : function()
        {
            this.page--;
            this.update(false);
        }
    }
};
})();
