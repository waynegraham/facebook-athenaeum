# Setup #
Once you've set up your [datasource](DatasourceSetup.md), obtained an [API key and secret from Facebook](FacebookSetup.md), and [installed Smarty and the required PEAR modules](ServerSetup.md), you can finish setting up your application by opening the `configs/config.inc.php` file.

## Configuration Options ##

There are a few things to customize:

### Facebook Options ###

The first section of the config file are settings for Facebook:

  * `$facebook_config['debug'] = false;`: True/False to show debugging information at the top of the page. If you're deploying, make sure this is false.
  * `$facebook_config['api_key'] = 'YOUR_API_KEY_HERE';`: The API Key you got from Facebook when you set up the application
  * `$facebook_config['secret'] = 'YOUR SECRET HERE';`: The Secret you got from Facebook when you set up the application
  * `$facebook_config['callback_url'] = 'YOUR CALLBACK URL HERE';`: The server URL of where you application lives (e.g. http://www.myuniversity.edu/facebook).
  * `$facebook_config['canvas_url_end'] = 'EVERYTHING AFTER http://apps.facebook.com HERE';` The URL you assigned in Facebook (without the Facebook URI). For example, http://apps.facebook.com/swemtools would be 'swemtools' (**this needs to be in lower case**).


### Tabs ###
To hide any tabs you don't wish to use, simply comment out the lines you don't want to see.

### Datasource (DSN) ###
  * `$dsn_config['type'] = 'mysql';`: Your connection type
  * `$dsn_config['host'] = 'MYSQL HOST';`: The DSN host
  * `$dsn_config['username'] = 'MYSQL USER';` The database user you created for the application
  * `$dsn_config['password'] = 'MYSQL PASSWORD';` The password for the database user you created
  * `$dsn_config['database'] = 'fbanthenaeum';` The database you created for the application.

### Customizations ###
  * `$APP_NAME = 'Facebook Athenaeum';`: The application name you would like to appear
  * `$FEED_URL = 'YOUR RSS NEWS FEED';`: URL for your RSS feed
  * `$APP_DIR  = 'WHERE ON YOUR SERVER FB-ANTHENAEUM LIVES';`: Full path to where your application lives (e.g. /var/www/htdocs/facebook)

### Search ###
  * `$SEARCH['WEBSITE'] = "";`: URL for your website search
  * `$SEARCH['DATABASE'] = "";`:  URL for your database search
  * `$SEARCH['CATALOG'] = "";`: URL for your catalog search

### Floor Map ###
> The floor map is an array of floors. You will need to have the full URL to the images of your floor plans on your server and it's friendly name. The images start a 0 (for addressing in a looping structure).

  * `DEFAULT_FLOOR = 1;`: The default floor to display
  * `LOCATION_VALID_TIME = 24;`: How long a specific location should be set for

### Google Analytics ###
You can easily add a [Google Analytics key](http://www.google.com/analytics/) to track the usage of your application.

### Configure Administrators ###
Any Administrator can edit the hours page using their Facebook account.  In order for them to edit the page, you will need to find their Facebook ID.  The ID can be found in the URL for any profile page.

`http://www.facebook.com/profile.php?id=#######`

The ID number does not have a set length, so be sure and copy all of the digits.