WST Facebook
============
wst-facebook is a small framework for facebook applications based on PHP5. It's not intended to be 
"FacebookOnRails" but to offer a meaningful structure and some helpful conventions to speed-up development 
while still being easy to modify.

Dependencies
------------
- [Smarty](http://www.smarty.net/)
- [adodb](http://adodb.sourceforge.net/)
- [Facebook PHP Client Library](http://wiki.developers.facebook.com/index.php/PHP)

Documentation
=============

File Structur
-------------

* __Sample App__
	* index.php - handels all incomming requests
	* FacebookApp.php - contains the logic of your Facebook app
	* views - contains the views for ever Action Methods
		* c - compiled smarty views (must be writeable by your Webserver)
	* logs - if logging is enabled it contains the logfile (must be writeable by your Webserver)
* __WST__ - contains the WST-Facebook Framework class
* **lib** - contains all external libraries (Adodb, Smarty,facebook API)


sampleApp Setup
---------------
### 1. Preparations
* Read [Creating a Platform Application](http://wiki.developers.facebook.com/index.php/Creating_a_Platform_Application)
* Create an app on Facebook

### 2. Configure
There is no external config file as there is not much configuration needed. All configuration is done directly in index.php.

#### Configure the Facebook Client Library
Basically you only need to insert the API key and the secret of your Facebook application. 
As it is very useful to sometimes switch of the Facebook proxying for development purposes you can do so by 
setting ``$fb['needed']`` to ``false``.

If your application requires the user to be logged in (i.e. for throwing stuff into his datastore) just enable ``$fb['user_login']``.


#### Configure your database connection
The configuration of a database connection is completely up to you. In case you need it, enable ``$db['needed']``.
If you do so there will be a ``$this->db`` object using the ADOdb database library to allow accessing your database.

For informations on how to work with ADOdb please have a look at their website at [adodb.sourceforge.net](http://adodb.sourceforge.net/).

#### What else?
As setting a timezone is madatory scince PHP 5.3 this is where you can define your application's ``date_default_timezone_set('Europe/Vienna');``.

### 3. Implement
``FacebookApp.php`` shows how to implement your own Facebook app using wst-facebook. You set up a class which implements the abstract class ``WST_Facebook``.
This class can be seen as the controller component of your application. 

For each action you have to set up a public method which ends with ``...Action()``, so that an action called ``ìndex`` would be defined 
through a method called ``indexAction()``.

Within the action you are free to do whatever has to be done to generate the output. You can assign variables for being used within the Smarty template by
calling ``$this->view->assign("key", "value")``.

Each action must - or at least is intended to - end with calling ``$this->render()``. This triggers Smarty to generate the template and display it. 
By convention wst-facebook expects templates to reside in ``/path/to/your/app/views/``, ending with ``*.tpl`` and reflecting the name of the current action.
So if your action is called ``index`` there has to be a template called ``index.tpl`` in the views folder.

By default all templates are cached in ``/path/to/your/app/views/c``. 

### 4. Run
``ìndex.php`` is the [Frontcontroller](http://martinfowler.com/eaaCatalog/frontController.html). This means that all requests are handled here. By convention 
a parameter called ``action`` is expected. If there is none the action is set to index by default. 

If a non-existing action is requested an exception is thrown and the error action is triggered as a fallback. This means that the methos ``errorAction()`` is 
executed and the ``error.tpl`` template is rendered.

