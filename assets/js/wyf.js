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
        
        removeFilter : function(id)
        {
            $(id).remove();
        },
		
		filterUpdated : function(filter)
		{			
			var operatorSelector = document.createElement("select");
			var options = [];
            var operand;
            
			switch(filterMetaData[filter.value].type)
			{
			case "text":
				options = [
	                {text:"Contains", value:"CONTAINS"},
	                {text:"Does not Contain", value:"CONTAINS_NOT"},
	                {text:"Is", value:"IS"},
	                {text:"Is not", value:"IS_NOT"},
                    {text:"Is Empty", value : "IS_EMPTY"}
	            ];
				
                operand = document.createElement("input");                
				break;
			
			case "date":
				options = [
	                {text:"On", value:"IS_ON"},
	                {text:"Not On", value:"IS_NOT_ON"},
	                {text:"After", value:"IS_AFTER"},
	                {text:"On and After", value:"IS_ON_AND_AFTER"},
	                {text:"Before", value:"IS_BEFORE"},
	                {text:"Before and On", value:"IS_BEFORE_AND_ON"},
                    {text:"Empty", value : "IS_EMPTY"}
	            ];
				
				if(filterMetaData[filter.value].values === undefined)
				{
	                operand = document.createElement("input");
	                $(operand).kalendae({
	                	format : "YYYY-MM-DD"
	                });
				}
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
                operand = document.createElement("input");
                break;
			}
			
			if(filterMetaData[filter.value].values !== undefined)
			{
				var operand = document.createElement('select');
				for(var key in filterMetaData[filter.value].values)
				{
					var valueOption = document.createElement('option'); 
					valueOption.text = filterMetaData[filter.value].values[key];
					valueOption.value = key;
					operand.add(valueOption);				
				}				
			}
            $(operand).attr('name', filter.id + "_operand");
            $('#' + filter.id + '_operands').html(operand);
            
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
    // Adjust menus
    $('#header').css({width:'100%'});
    $('#side_menu').css({height:($(window).height() - 50) + 'px'});
    
    // Adjust reporting options
    
}

$(function(){
    $(window).resize(adjustUI);
    adjustUI();
});
