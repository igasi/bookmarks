How to use:

1. Install module.
2. Go to /admin/config/services/rest
3. Enable Bookmark rest resource with this config

GET
authentication: basic_auth, cookie
formats: hal_json, json

POST
authentication: basic_auth, cookie
formats: hal_json, json

DELETE
authentication: basic_auth, cookie
formats: hal_json, json

4. Create a view block.
