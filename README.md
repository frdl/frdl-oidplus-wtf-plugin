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
- userdata/wtf-plugins/{pluginname}/plugin.php 
- userdata/wtf-plugins/{pluginname}.php
- userdata/{tenantdir}/wtf-plugins/{pluginname}/plugin.php
- userdata/{tenantdir}/wtf-plugins/{pluginname}.php
- userdata/plugins/{pluginname}/plugin.php 
- userdata/plugins/{pluginname}.php
- userdata/{tenantdir}/plugins/{pluginname}/plugin.php
- userdata/{tenantdir}/plugins/{pluginname}.php
- plugins/{vendor}/{plugintype}/{pluginname}/plugin.php
- userdata_pub/wtf-plugins/{pluginname}/plugin.php 
- userdata_pub/wtf-plugins/{pluginname}.php
- userdata_pub/{tenantdir}/wtf-plugins/{pluginname}/plugin.php
- userdata_pub/{tenantdir}/wtf-plugins/{pluginname}.php
- userdata_pub/plugins/{pluginname}/plugin.php 
- userdata_pub/plugins/{pluginname}.php
- userdata_pub/{tenantdir}/plugins/{pluginname}/plugin.php
- userdata_pub/{tenantdir}/plugins/{pluginname}.php

If [IO4](https://github.com/frdl/oidplus-io4-bridge-plugin) is installed, it uses its [invoker](https://github.com/PHP-DI/Invoker) method, otherwise a simple call_user_func, however for plugins this final callable/closure is optional to use or not.
