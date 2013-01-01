var wyf;
(function(){
    wyf = {
        listView : {
            update : function(route)
            {
                $.getJSON(
                    route,
                    function (response)
                    {
                        $('#wyf_list_view').html(
                            Mustache.render(
                                $('#wyf_list_view_template').html(), 
                                {list:response}
                            )
                        );
                    }
                );
            }
        }
    };
})();
