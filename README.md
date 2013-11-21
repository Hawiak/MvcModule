MvcModule
=========

Mvc Module for Processwire 2.3


WORK IN PROGRESS PLEASE BE AWARE

Processwire MVC by Harmster
A quick guide


Download

Download PWMvc at github: https://github.com/Hawiak/MvcModule

Configure

1) Drag/upload/add the /site/modules/MvcModule folder into your project's module folder.  
2) Search for new Modules  
3) Install MVC  
UyneNh9.png4) Press "Submit", this is important, without the submit your paths are not saved.  
5) Leave every textfield as it is for now, these are the defaults.  
6) Create 3 folders in your /site/templates folder and call them assets, snippets and layouts  
7) You are going to need a default layout file, just create a new php file in the folder layouts you just created called layout.php  
8) Place the AppController.class in your /site/templates  
9) Place the _create_mvc.php in your /site/templates  
10) Edit your /site/config.php and add the following line  
$config->appendTemplateFile = '_create_mvc.php';  


Files, Folders and Fields  

You might be wondering what you just installed. Here's a quickreference guide to the files:  

FILE: AppController.class  

This is actually just an empty class and it can be used for things that are all the same over your website, so not controller reliable. For example a title that you want to append on each one of the controllers, or scripts or stylesheets you want to have all over your website.   
  
FILE: _create_mvc.php  

This script sets up the MVC, it fetches the action, calls the methods. This script runs after EVERY template but only works if the template has an MVC field.   

FIELD: mvc

If you've installed the MVC module you will notice you have an extra field installed called MVC. Add this field to every template you want to enable MVC on.

FOLDER: assets

In this folder there will be 2 folders called, scripts and styles by default but you can place any folders in here. By default MVC will look in these folders for scripts or styles you use using the $script or $styles property of the controller.

FOLDER: layouts

In this folder you will put your layout files, layout files is the structure of your website, think about a <head> and <body> tag, in general this is the same for most of your views, however you can set layouts per controller, even per action. Read about that later.


FOLDER: snippets

In this folder you will put your snippet files, snippets are piece of code that you re-use.

The Controller

The Controller is the Base provided by MvcModule. It has basic functionality and some properties (These will be extended if needed over time)
For now the Controller class has these properties:
layout - The "layout" property is used to set a file to be used as a layout file, this can be done in the method or the controller.
view - The "view" property can be used to change the view for the action, by default the view will be the same as the action e.g. index.view for the index action and edit.view for the edit action.
vars = array() - An array with all the variables that are being used in the controller and in the view, try and not use this variable but it is accesible in any controller or the AppController. Use the set() method instead.
render_layout = 1 - NOT TESTED. This will be a switch to turn of the layout rendering, has not been tested yet. TODO
layout_vars = array() - This is a array with all the vars being used in the layout, same applies here as for the vars, try and not use this variable but still it is accesible throughout your project. Use the set_layout_var instead.
scripts = array() - An array with all the scripts for a project, use as follow: $scripts = array('1' => 'jquery.js'); The 1 is the sequence of which the scripts will be loaded, it is handy when you want to load jquery before you load foundation or bootstrap. The scripts need to be in the /site/templates/assets/scripts folder by default.
styles = array() - An array with all the styles for a project, use as follow: $styles= array('1' => 'jquery.css'); The 1 is the sequence of which the styles will be loaded, it is handy when you want to  bootstrap first before your theme The styles need to be in the /site/templates/assets/styles folder by default.

And these methods:
set($var, $val) - Will set a variable for your View
set_layout_var($var, $val) - Will set a variable for your Layout

The AppController

The AppController is the class you want to extend when creating a controller, its basicly an empty class where you can put custom stuff in, this class extends the Controller inside the MvcModule folder.
Make custimizations in this class to prevent issues while updating MvcModule.


Setting up a controller

To set up a controller you need to create a file in the root of the template folder (/site/template). Name it like you named the template. It needs to be the same name as the template. 
Then create a class in it with a capital at the beginning, and append it with the word Controller.
This is an example for the Controller Test in /site/template/test.php:
<?php
class TestController extends AppController{

}
?>
You'll then have to set up methods inside this class, lets say you have an edit, view, delete and a list view of a few pages.
<?php
class TestController extends AppController{
   public $title;

   public function index(){
      $this->set('list-view', $pages->find('/'));
   }
   public function view(){
      $this->set('view', $pages->get($this->input->urlSegments[2]));
   }
   public function edit(){
      $this->set('edit', $pages->get($this->input->urlSegments[2]));
   }
   public function delete(){
      $page_id = $sanitizer->value($this->input->urlSegments[2]);
      $this->pages->get($page_id)->delete();
   }
}
?>
The code here is just to demonstrate how different views work and far from efficiant.
In order for the views to work you'll have to create views in the view folder, like index.view, view.view, edit.view and delete.view.
Because the TestController extends AppController and AppController extends Controller and Controller extends Wire you can use ProcessWire function by using $this.

Using Layouts

A layout is the markup of your website. Its most of the time the same on all the pages for every view. It goes like. A very simple example of a working layout is:

<html>
   <head>
      <meta http-equiv="content-type" content="text/html; charset=utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
      <meta name="description" content="<?php echo $this->page->summary; ?>" />
      <meta name="generator" content="ProcessWire <?php echo $this->config->version; ?>" />
      <?php echo $this->render_headers(); ?>
      <title><?php echo $this->controller->title; ?></title>
    </head>
    <body>
       <?php echo $this->view_body; ?>
    </body>
</html>	
As you can see I used $this->controller->title you can set any variable you like and used it in here.
Also, there is the $this->view_body. The view body is the result from the rendered view. 
And I use the method $this->render_headers() this renders the script and styles for your layout defined in your controller. 
You can define a layout for each controller or even for each method. Using $this->layout = 'yourlayout.php'; anywhere in your controller.

Using Snippets

A snippet can be used for a lot of stuff, like your navbar or your login, something you want to re use but not necessary on your layout. You can render a snippet using the $this->render_snippet(string $snippet_name, array $vars); Just give the name e.g. login.php and an array of variables you want to use within the snippet, the variables are optional.
The snippets can also access controller variables and/or wire variables objects.

Using Views

A view is where you will put your markup and all the stuff that your visitor will see. A simple basic page could just display a body 
A view that just displays the body that is set in the controller ($this->set('body', $this->pages->get('/'))) and then just echo $body on the view
This is an example view:
<div>
   <?php echo $body; ?>
</div>


TODO:

 Create more controller methods that make it easier to generate views 
 Create an Error class for MVC enabled templates.

The module now features an admin panel.

 Create view files/folders from admin panel
 Create controllers from the admin panel
 Create new methods in the controller from the admin panel

Changelog

- 11/20/2013 - Updated whole module

WORK IN PROGRESS PLEASE BE AWARE
