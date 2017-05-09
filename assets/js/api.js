var api = {
  call: function (type, url, data, success) {
    $.ajax({
      url: url,
      data: data,
      type: type
    }).done(function (data, status, xhr) {
      if (typeof success === 'function') {
        success(data, xhr);
      }
    });
  },
  put: function (url, data, success) {
    api.call('PUT', url, data, success);
  },
  get: function (url, data, success) {
    api.call('GET', url, data, success);
  }
}
