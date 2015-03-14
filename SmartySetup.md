# Setting Up Smarty #

Facebook Athenaeum uses the Smarty Template engine as the presentation framework for the application. It has some really nice features including caching, abstraction of PHP code, compiling improvements, and a bunch of other features.

The project includes a helper script to install Smarty and the needed PEAR libraries. If you want to manually install this software, please refer to the [Smarty Quickstart](http://www.smarty.net/quick_start.php) page.

To run the helper, go the the command prompt

```
$> cd WHERE_YOU_PUT_ATHENAEUM_FILES
$> ./smarty_helper.sh
```

The only other configuration that needs to occur for Smarty is to change the permissions on the cache and compile folders (if needed) so that the web server has permission to write to those folders.

```
$> cd WHERE_YOU_PUT_ATHENAEUM_FILES
$> chown WEBSERVER cache
$> chown WEBSERVER compile 
```


# Smarty Resources #

  * [Smarty Quickstart](http://www.smarty.net/quick_start.php)
  * [Smarty Website](http://www.smarty.net)