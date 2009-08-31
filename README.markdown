WST Facebook
============
wst-facebook is a small framework for facebook applications.

Dependencies
------------
- [Smarty](http://www.smarty.net/)
- [adodb](http://adodb.sourceforge.net/)

Documentation
=============

File Structur
-------------

* Sample App 
	* index.php - handels all incomming requests
	* FacebookApp.php - contains the logic of your Facebook app
	* views - contains the views for ever Action Methods
		* c - compiled smarty views (must be writeable by your Webserver)
	* logs - if logging is enabled it contains the logfile
* WST - contains WST-Facebook Framework class
* lib - contains all external libraries (Adodb, Smarty,facebook API)


sampleApp Setup
---------------
* read first [Creating a Platform Application](http://wiki.developers.facebook.com/index.php/Creating_a_Platform_Application)
* create a App on Facebook
* add apikey and secret to index.php
* finish

 