(function(){
wyf = {
	reports : {
		filterSerial : 0,
		addFilter : function()
		{
			this.filterSerial++;
			$('#wyf-report-filters').append(
				Mustache.render($('#wyf-report-filter-template').html(), 
				{id : this.filterSerial})
			);
		},
		
		filterUpdated : function(filter)
		{			
			var operatorSelector = document.createElement("select");
			var options = [];
			switch(reportColumnDataTypes[filter.value])
			{
			case "text":
				options = [
	                {text:"Contains", value:"CONTAINS"},
	                {text:"Does not Contain", value:"CONTAINS_NOT"},
	                {text:"Is", value:"IS"},
	                {text:"Is not", value:"IS_NOT"},
                    {text:"Is Empty", value : "IS_EMPTY"}
	            ];
                var textOperand = document.createElement("input");
                $(textOperand).attr('name', filter.id + "_operand");
                $('#' + filter.id + '_operands').html(textOperand);
                
				break;
                
            case "float":
            case "integer":
				options = [
	                {text:"Equals", value:"IS_EQUAL"},
	                {text:"Not Equals", value:"IS_NOT_EQUAL"},
	                {text:"Greater Than", value:"IS_GREATER"},
	                {text:"Equal and Greater Than", value:"IS_EQUAL_AND_GREATER"},
	                {text:"Less Than", value:"IS_LESS"},
	                {text:"Equal and Less Than", value:"IS_EQUAL_AND_LESS"},
                    {text:"Empty", value : "IS_EMPTY"}
	            ];                
                var numberOperand = document.createElement("input");
                $(numberOperand).attr('name', filter.id + "_operand");
                $('#' + filter.id + '_operands').html(numberOperand);
                break;
			}
            
			for(var i = 0; i < options.length; i++)
			{
				var option = document.createElement('option');
				option.text = options[i].text;
				option.value = options[i].value;
				operatorSelector.add(option);
			}
            $(operatorSelector).attr('name', filter.id + "_operator");
			$('#' + filter.id + '_operators').html(operatorSelector);
		}
	},
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
