WST Facebook

wst-facebook is a small framework for facebook applications.

Dependencies
- Smarty (templating)
- adodb  (db connections)

Documentation

Most code is documented inline


File Structur

- Sample App - 
-- index.php - handels all incommming requests
-- FacebookApp.php - contains the logic of your Facebook app
-- views - contains the views 
--- c - compiled smarty views (must be writeable by your Webserver)
-- logs - if logging is enabled it contains the logfiles
- WST - contains WST-Facebook Framework class(es)
- lib - contains all external libraries (Adodb, Smarty,facebook php-API)


sampleApp Setup

- read first 'http://wiki.developers.facebook.com/index.php/Creating_a_Platform_Application'
- create a App on Facebook
- add apikey and secret to index.php
- finish

 