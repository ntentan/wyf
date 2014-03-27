var api = {
    call : function (type, url, data, success)
    {
        $.ajax({
            url : ntentan.url(url),
            data : data,
            type : type
        }).done(function(data){
            if(typeof success === 'function') success(data);
        });
    },
    put : function(url, data, success)
    {
        api.call('PUT', url, data, success);
    }
}
