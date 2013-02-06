(function(){
wyf = {
    listView : {
        api : undefined,
        itemsPerPage : 10,
        pages : 1,
        page: 1,
        count : 0,
        conditions : undefined,
        update : function(info)
        {
            $.getJSON(
                this.api + 'ipp=' + this.itemsPerPage + '&pg=' + this.page + (info ? '&info=yes' : '') + this.getConditions(),
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
                    
                    if(response.notifications !== undefined)
                    {
                        wyf.notify(response.notifications);
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
        },
        setConditions : function(conditions)
        {
            this.conditions = conditions;
        },
        getConditions : function()
        {
            console.log(this.conditions);
            return "&c=" + escape(JSON.stringify(this.conditions));
        }
    },
    
    notify : function(notification)
    {
        $('#notification').css({display:'none'});
        setTimeout(
            function(){
                $('#notification').html('<p>' + notification + '</p>').slideToggle(
                    'slow',
                    function (){
                        setTimeout(function(){$('#notification').slideToggle()}, 5000);
                    }
                );
            },
            1000
        );
    }
};
})();

/********* MENUS AND SCREEN RESIZE HANDLERS *******************/

function adjustUI()
{
    $('#header').css({width:'100%'});
    $('#side_menu').css({height:($(window).height() - 50) + 'px'});
}

$(function(){
    $(window).resize(adjustUI);
    adjustUI();
});
