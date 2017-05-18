var api = {
  call: function (parameters) {
    $.ajax({
      url: parameters.url,
      data: parameters.data,
      type: parameters.type,
      headers: {
        "Content-Type" : parameters.contentType ? parameters.contentType : "application/json"
      }
    }).done(function (data, status, xhr) {
      if (typeof parameters.success === 'function') {
        parameters.success(data, xhr);
      }
    }).fail(function(xhr) {
      if(typeof parameters.failure === 'function') {
        parameters.failure(JSON.parse(xhr.responseText), xhr);
      }
    });
  },
  put: function (parameters) {
    parameters.type = 'PUT';
    api.call(parameters);
  },
  post: function(parameters) {
    parameters.type = 'POST';
    api.call(parameters);
  },
  get: function (parameters) {
    parameters.type = 'GET';
    api.call(parameters);
  }
}
