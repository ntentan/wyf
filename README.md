ntentan.wyf
===========

A better version of the WYF framework built on top of the ntentan core. This version offers the same goodies as the original wyf except its friendlier to code in and much more easy to theme and extend.

Layout View Variables
---------------------

Layout templates (such as `views/layouts/wyf_default.tpl.php`) receive the following view variables injected by `WyfMiddleware`:

- `wyf_app_name` (`string`): The application name configured in application settings.
- `wyf_logo` (`string`): The URL to the application logo image.
- `wyf_menu` (`array`): Navigation menu structure.
- `wyf_user` (`array|null`): The authenticated user data retrieved from the session store. When a user is authenticated, it is an associative array with the following keys:
  - `id` (`int|string`): Unique user identifier.
  - `uid` (`string`): Unique user public ID or hash.
  - `username` (`string`): Login username.
  - `email` (`string`): User email address.
  - `firstname` (`string`): User first name.
  - `lastname` (`string`): User last name.
- `ntentan_uri_prefix` (`string`): Application URI prefix.

License
-------
Copyright (c) 2008-2013 James Ekow Abaka Ainooson

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
