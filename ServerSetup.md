# Server Setup #
Currently the Facebook Athenaeum codebase only works with PHP5 and uses the Smarty templating engine and PEAR DB library. While not required, the application also uses Apache's mod\_rewrite to make "pretty" URLs.

The web server setup is relatively easy. You simply download the latest snapshot and expand it to you desired location.

```
cd /var/www/htdocs
wget http://facebook-athenaeum.googlecode.com/files/facebook-0.1.2.tar.gz
tar -zxvf facebook-0.1.2.tar.gz
```

The only other change you need to make is to point the [Callback URL in the Facebook Application](FacebookSetup.md) to the URL for the space you just created.

Because this application uses Smarty (which writes to the cache and compile folders), you will need to change the ownership of those two files to be owned by the webserver so it can write the files.

```
$> cd WHERE_YOU_PUT_ATHENAEUM_FILES
$> chown WEBSERVER cache
$> chown WEBSERVER compile 
```

## Other Software ##
  * Install [Smarty](SmartySetup.md)
  * Install [DB](DbSetup.md)
  * Set up [Datasource](DatasourceSetup.md)