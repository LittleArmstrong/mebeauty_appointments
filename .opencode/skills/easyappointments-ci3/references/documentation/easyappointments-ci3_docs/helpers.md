# Easyappointments-Ci3_Docs - Helpers

**Pages:** 1

---

## Helper Functions — CodeIgniter 3.1.11 documentation

**URL:** https://developers.easyappointments.org/framework/general/helpers.html

**Contents:**
- Helper Functions¶
- Loading a Helper¶
- Loading Multiple Helpers¶
- Auto-loading Helpers¶
- Using a Helper¶
- “Extending” Helpers¶
  - Setting Your Own Prefix¶
- Now What?¶

Helpers, as the name suggests, help you with tasks. Each helper file is simply a collection of functions in a particular category. There are URL Helpers, that assist in creating links, there are Form Helpers that help you create form elements, Text Helpers perform various text formatting routines, Cookie Helpers set and read cookies, File Helpers help you deal with files, etc.

Unlike most other systems in CodeIgniter, Helpers are not written in an Object Oriented format. They are simple, procedural functions. Each helper function performs one specific task, with no dependence on other functions.

CodeIgniter does not load Helper Files by default, so the first step in using a Helper is to load it. Once loaded, it becomes globally available in your controller and views.

Helpers are typically stored in your system/helpers, or application/helpers directory. CodeIgniter will look first in your application/helpers directory. If the directory does not exist or the specified helper is not located there CI will instead look in your global system/helpers/ directory.

Loading a helper file is quite simple using the following method:

Where name is the file name of the helper, without the .php file extension or the “helper” part.

For example, to load the URL Helper file, which is named url_helper.php, you would do this:

A helper can be loaded anywhere within your controller methods (or even within your View files, although that’s not a good practice), as long as you load it before you use it. You can load your helpers in your controller constructor so that they become available automatically in any function, or you can load a helper in a specific function that needs it.

The Helper loading method above does not return a value, so don’t try to assign it to a variable. Just use it as shown.

If you need to load more than one helper you can specify them in an array, like this:

If you find that you need a particular helper globally throughout your application, you can tell CodeIgniter to auto-load it during system initialization. This is done by opening the application/config/autoload.php file and adding the helper to the autoload array.

Once you’ve loaded the Helper File containing the function you intend to use, you’ll call it the way you would a standard PHP function.

For example, to create a link using the anchor() function in one of your view files you would do this:

Where “Click Here” is the name of the link, and “blog/comments” is the URI to the controller/method you wish to link to.

To “extend” Helpers, create a file in your application/helpers/ folder with an identical name to the existing Helper, but prefixed with MY_ (this item is configurable. See below.).

If all you need to do is add some functionality to an existing helper - perhaps add a function or two, or change how a particular helper function operates - then it’s overkill to replace the entire helper with your version. In this case it’s better to simply “extend” the Helper.

The term “extend” is used loosely since Helper functions are procedural and discrete and cannot be extended in the traditional programmatic sense. Under the hood, this gives you the ability to add to or or to replace the functions a Helper provides.

For example, to extend the native Array Helper you’ll create a file named application/helpers/MY_array_helper.php, and add or override functions:

The filename prefix for “extending” Helpers is the same used to extend libraries and core classes. To set your own prefix, open your application/config/config.php file and look for this item:

Please note that all native CodeIgniter libraries are prefixed with CI_ so DO NOT use that as your prefix.

In the Table of Contents you’ll find a list of all the available Helper Files. Browse each one to see what they do.

---
