# frdl-oidplus-wtf-plugin
Shimmy shimmy WTF functions polyfill

### OIDplus location
Copy the plugin to *plugins/frdl/publicPages/zzzzzzzz_wtf* wtf this **MUST run as LAST publicPages Plugin**!

### WTF is this?
In short:

I definitely do NOT like worpress code. This SHOULD be a reason we MUST shim it to the [OIDplus](https://oidplus.com/) core!

Inspired by

- https://github.com/Badcow/Shortcodes
- https://github.com/voku/PHP-Hooks/

### Example:
https://gist.github.com/wehowski/d16a4ce9cdeb5da1e90f9a7b28b6ffdb#file-plugin-php

# Plugins:
The additional plugins Plugin (this one) is searching for pluginfiles in:
- userdata/plugins/{vendor}/{plugintype}/{pluginname}/wtf-plugin.php 
- userdata/{tenantdir}/{vendor}/{plugintype}/{pluginname}/wtf-plugin.php 
- **plugins/{vendor}/{plugintype}/{pluginname}/wtf-plugin.php**   <--- Old/regular Plugins
- userdata_pub/plugins/{vendor}/{plugintype}/{pluginname}/wtf-plugin.php 
- userdata_pub/{tenantdir}/{vendor}/{plugintype}/{pluginname}/wtf-plugin.php 

If [IO4](https://github.com/frdl/oidplus-io4-bridge-plugin) is installed, it uses its [invoker](https://github.com/PHP-DI/Invoker) method, otherwise a simple call_user_func, however for plugins this final callable/closure is optional to use or not.
