(function(){
wyf = {
	reports : {
		filterSerial : 0,
        
		addFilter : function(filterParameters)
		{
			this.filterSerial++;
			if(filterParameters === undefined)
			{
				$('#wyf-report-filters').append(
					Mustache.render($('#wyf-report-filter-template').html(), 
					{id : this.filterSerial})
				);	
			}
			else
			{
				$('#wyf-report-filters').append(
					Mustache.render(
						$('#wyf-report-filter-template').html(), 
						{id : this.filterSerial}
					)
				);	
				$('#filter_' + this.filterSerial).val(filterParameters.column);
				this.filterUpdated(
					document.getElementById('filter_' + this.filterSerial), 
					filterParameters
				);
			}
		},
		
		updateOutputOptions : function(field)
		{
			$(".wyf-report-output-options").hide();
			$("#" + field.value + "-report-options").show();
			
		},
        
        removeFilter : function(id)
        {
            $(id).remove();
        },
		
		filterUpdated : function(filter, parameters)
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
                $(operand).attr('type', 'text');
                $(operand).css('width', 'auto');
                $(operand).css('padding', '4px');
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
	                $(operand).css('width', 'auto');
	                $(operand).css('padding', '4px');
	                $(operand).attr('type', 'text');
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
                $(operand).css('width', 'auto');
                $(operand).css('padding', '4px');
                $(operand).attr('type', 'text');
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
            
            if(parameters !== undefined)
        	{
            	operatorSelector.value = parameters.operator;
            	operand.value = parameters.operand;
        	}
            
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
        fields : undefined,
        update : function(info)
        {
            $.getJSON(
                this.api + 'ipp=' + this.itemsPerPage + '&pg=' + this.page + (info ? '&info=yes' : '') + this.getConditions() + this.getFields(),
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
        },
        setFields : function(fields)
        {
            this.fields = fields;
        },   
        getFields : function()
        {
            if(this.fields !== undefined)
            {
                return "&f=" + escape(JSON.stringify(this.fields));
            }
            else
            {
                return '';
            }
        },
        setConditions : function(conditions)
        {
            this.conditions = conditions;
        },
        getConditions : function()
        {
            if(this.conditions !== undefined)
            {
                return "&c=" + escape(JSON.stringify(this.conditions));
            }
            else
            {
                return '';
            }
        }
    },
    
    notify : function(notification)
    {
        $('#notification').html(notification);
        var originalTop = 50 - ($('#notification').height() + 40);
        $('#notification').css({top :originalTop  + 'px'});
        setTimeout(
            function(){
                $('#notification').show();
                $('#notification').animate({top:'45px'}, 'slow',
                    function(){
                        setTimeout(function(){
                            $('#notification').animate({top:originalTop + 'px'});
                        },
                        6000);
                    }
                );
            },
            1000
        );
    },
    
    suggester : {
        optionsView : undefined,
        
        getUrl : function(text, model, params)
        {
            var url = "system/suggester/suggest/";
            url += model + '?s=' + escape(text);

            if(typeof params.searchFields === "object")
            {
                url += '&search_fields=';
                for(var i = 0; i < params.searchFields.length; i++)
                {
                    url += params.searchFields[i] + ( i === params.searchFields.length - 1 ? '' : '/');
                }
            }

            if(typeof params.fields === "object")
            {
                url += '&fields=';
                for(var i = 0; i < params.fields.length; i++)
                {
                    url += params.fields[i] + ( i === params.fields.length - 1 ? '' : '/');
                }
            }        

            return ntentan.url(url);
        },
                
        initOptionsView : function()
        {
            wyf.suggester.optionsView = document.createElement('div');
            $(wyf.suggester.optionsView).addClass('wyf_suggester_box');
            $('body').append(wyf.suggester.optionsView);
        },
                
        showOptionsView : function(options, offset)
        {
            $(wyf.suggester.optionsView).offset(offset);
            $(wyf.suggester.optionsView).html("");
            for(var i in options)
            {
                $(wyf.suggester.optionsView).append(
                    "<div class='wyf_suggestion'>" + 
                    options[i].label + 
                    (options[i].code === undefined ? '' : "<br/><span>" + options[i].code + "</span>")+ 
                    "</div>"
                );
            }
            $(wyf.suggester.optionsView).show();
        }
    }
};
})();

/********* MENUS AND SCREEN RESIZE HANDLERS *******************/

function adjustUI()
{
    // Adjust menus
    $('#header').css({width:'100%'});
    $('#side_menu').css({height:($(window).height() - 50) + 'px'});
    
    // Adjust notification
    $('#notification').css({left:'240px', width:($(window).width() - 480) + 'px'});
}

$(function(){
    $(window).resize(adjustUI);
    adjustUI();
    $.getJSON(
        ntentan.url('system/notifications'),
        function(response)
        {
            if(response !== false) 
            {
                if(response.notification !== false) wyf.notify(response.notifications);
                if(response.js !== false)
                {
                    var script = document.createElement("script");
                    script.type = 'text/javascript';
                    script.src = ntentan.url('system/notifications/js');
                    document.getElementsByTagName("head")[0].appendChild(script);
                }
            }
        }
    );
});
