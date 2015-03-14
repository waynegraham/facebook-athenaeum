# Facebook #

The first step in setting up Facebook Athenaeum is to tell Facebook about your project and get an API Key and Secret. This process takes about 5 minutes if you already have an account on Facebook.


# Details #
  1. Add the [Facebook Developer application](http://www.facebook.com/developers). Once this application is added, it'll appear on the left-hand navigation. After adding the application, you have access to the Developer Application on the left-hand side of the screen. Depending on how many apps you have installed, it may be under "more".
  1. Open the Developer Application (if you don't already have that screen up)
  1. Click on the button "+ Set Up New Application" in the upper-right
  1. Give your application a name. Remember, you can't have the word 'face' anywhere in the application name (the original application was named SwemTools). Check the box after you.  You can ignore the Optional Fields for now. Click Submit to create your API Key and secret.
  1. You'll need your API Key and Secret for configs (in the configs/config.inc.php file)

## Optional Fields ##
The optional fields contain a lot of different information for you to customize your application. We'll go through some of the important ones.

  * Callback URL: This is the URL on your server where the application lives (e.g. http://library.myuniversity.edu/facebook/ - The trailing slash is very important, without it you may some errors returned by your application.)
  * Canvas Page URL: The application URL for your application. This is the URL people will use to access the application
  * Icon: Change the default icon used for your application
  * Logo: Add a logo for you application