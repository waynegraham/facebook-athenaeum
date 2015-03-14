# Introduction #

Adding the PEAR DB library to your PHP include directory is relatively easy with PEAR. If you're not using the smarty\_helper.sh script, you can manually add this with the following:

```
$> pear upgrade pear
$> pear install --onlyreqdeps DB
$> pear install --onlyreqdeps XML_RSS
```

Ok, if you're paying attention, I also added the XML\_RSS pear library in there, but you need it too :)


# Resources #

  * [DB Package Information](http://pear.php.net/package/DB)
  * [PEAR](http://pear.php.net)