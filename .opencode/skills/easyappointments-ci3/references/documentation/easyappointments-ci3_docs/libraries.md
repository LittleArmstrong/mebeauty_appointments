# Easyappointments-Ci3_Docs - Libraries

**Pages:** 4

---

## Image Manipulation Class — CodeIgniter 3.1.11 documentation

**URL:** https://developers.easyappointments.org/framework/libraries/image_lib.html

**Contents:**
- Image Manipulation Class¶
- Initializing the Class¶
  - Processing an Image¶
  - Processing Methods¶
  - Preferences¶
  - Setting preferences in a config file¶
- Image Watermarking¶
  - Two Types of Watermarking¶
  - Watermarking an Image¶
  - Watermarking Preferences¶

CodeIgniter’s Image Manipulation class lets you perform the following actions:

All three major image libraries are supported: GD/GD2, NetPBM, and ImageMagick

Watermarking is only available using the GD/GD2 library. In addition, even though other libraries are supported, GD is required in order for the script to calculate the image properties. The image processing, however, will be performed with the library you specify.

Like most other classes in CodeIgniter, the image class is initialized in your controller using the $this->load->library function:

Once the library is loaded it will be ready for use. The image library object you will use to call all functions is: $this->image_lib

Regardless of the type of processing you would like to perform (resizing, cropping, rotation, or watermarking), the general process is identical. You will set some preferences corresponding to the action you intend to perform, then call one of four available processing functions. For example, to create an image thumbnail you’ll do this:

The above code tells the image_resize function to look for an image called mypic.jpg located in the source_image folder, then create a thumbnail that is 75 X 50 pixels using the GD2 image_library. Since the maintain_ratio option is enabled, the thumb will be as close to the target width and height as possible while preserving the original aspect ratio. The thumbnail will be called mypic_thumb.jpg and located at the same level as source_image.

In order for the image class to be allowed to do any processing, the folder containing the image files must have write permissions.

Image processing can require a considerable amount of server memory for some operations. If you are experiencing out of memory errors while processing images you may need to limit their maximum size, and/or adjust PHP memory limits.

There are four available processing methods:

These methods return boolean TRUE upon success and FALSE for failure. If they fail you can retrieve the error message using this function:

A good practice is to use the processing function conditionally, showing an error upon failure, like this:

You can optionally specify the HTML formatting to be applied to the errors, by submitting the opening/closing tags in the function, like this:

The preferences described below allow you to tailor the image processing to suit your needs.

Note that not all preferences are available for every function. For example, the x/y axis preferences are only available for image cropping. Likewise, the width and height preferences have no effect on cropping. The “availability” column indicates which functions support a given preference.

If you prefer not to set preferences using the above method, you can instead put them into a config file. Simply create a new file called image_lib.php, add the $config array in that file. Then save the file in config/image_lib.php and it will be used automatically. You will NOT need to use the $this->image_lib->initialize() method if you save your preferences in a config file.

The Watermarking feature requires the GD/GD2 library.

There are two types of watermarking that you can use:

Just as with the other methods (resizing, cropping, and rotating) the general process for watermarking involves setting the preferences corresponding to the action you intend to perform, then calling the watermark function. Here is an example:

The above example will use a 16 pixel True Type font to create the text “Copyright 2006 - John Doe”. The watermark will be positioned at the bottom/center of the image, 20 pixels from the bottom of the image.

In order for the image class to be allowed to do any processing, the image file must have “write” file permissions For example, 777.

This table shows the preferences that are available for both types of watermarking (text or overlay)

This table shows the preferences that are available for the text type of watermarking.

This table shows the preferences that are available for the overlay type of watermarking.

TRUE on success, FALSE in case of invalid settings

Initializes the class for processing an image.

The image resizing method lets you resize the original image, create a copy (with or without resizing), or create a thumbnail image.

For practical purposes there is no difference between creating a copy and creating a thumbnail except a thumb will have the thumbnail marker as part of the name (i.e. mypic_thumb.jpg).

All preferences listed in the Preferences table are available for this method except these three: rotation_angle, x_axis and y_axis.

The resizing method will create a thumbnail file (and preserve the original) if you set this preference to TRUE:

This single preference determines whether a thumbnail is created or not.

The resizing method will create a copy of the image file (and preserve the original) if you set a path and/or a new filename using this preference:

Notes regarding this preference:

Resizing the Original Image

If neither of the two preferences listed above (create_thumb, and new_image) are used, the resizing method will instead target the original image for processing.

The cropping method works nearly identically to the resizing function except it requires that you set preferences for the X and Y axis (in pixels) specifying where to crop, like this:

All preferences listed in the Preferences table are available for this method except these: rotation_angle, create_thumb and new_image.

Here’s an example showing how you might crop an image:

Without a visual interface it is difficult to crop images, so this method is not very useful unless you intend to build such an interface. That’s exactly what we did using for the photo gallery module in ExpressionEngine, the CMS we develop. We added a JavaScript UI that lets the cropping area be selected.

The image rotation method requires that the angle of rotation be set via its preference:

There are 5 rotation options:

Here’s an example showing how you might rotate an image:

Creates a watermark over an image, please refer to the Watermarking an Image section for more info.

The clear method resets all of the values used when processing an image. You will want to call this if you are processing images in a loop.

Returns all detected errors formatted as a string.

---

## Creating Libraries — CodeIgniter 3.1.11 documentation

**URL:** https://developers.easyappointments.org/framework/general/creating_libraries.html

**Contents:**
- Creating Libraries¶
- Storage¶
- Naming Conventions¶
- The Class File¶
- Using Your Class¶
- Passing Parameters When Initializing Your Class¶
- Utilizing CodeIgniter Resources within Your Library¶
- Replacing Native Libraries with Your Versions¶
- Extending Native Libraries¶
  - Loading Your Sub-class¶

When we use the term “Libraries” we are normally referring to the classes that are located in the libraries directory and described in the Class Reference of this user guide. In this case, however, we will instead describe how you can create your own libraries within your application/libraries directory in order to maintain separation between your local resources and the global framework resources.

As an added bonus, CodeIgniter permits your libraries to extend native classes if you simply need to add some functionality to an existing library. Or you can even replace native libraries just by placing identically named versions in your application/libraries directory.

The page below explains these three concepts in detail.

The Database classes can not be extended or replaced with your own classes. All other classes are able to be replaced/extended.

Your library classes should be placed within your application/libraries directory, as this is where CodeIgniter will look for them when they are initialized.

Classes should have this basic prototype:

We are using the name Someclass purely as an example.

From within any of your Controller methods you can initialize your class using the standard:

Where someclass is the file name, without the “.php” file extension. You can submit the file name capitalized or lower case. CodeIgniter doesn’t care.

Once loaded you can access your class using the lower case version:

In the library loading method you can dynamically pass data as an array via the second parameter and it will be passed to your class constructor:

If you use this feature you must set up your class constructor to expect data:

You can also pass parameters stored in a config file. Simply create a config file named identically to the class file name and store it in your application/config/ directory. Note that if you dynamically pass parameters as described above, the config file option will not be available.

To access CodeIgniter’s native resources within your library use the get_instance() method. This method returns the CodeIgniter super object.

Normally from within your controller methods you will call any of the available CodeIgniter methods using the $this construct:

$this, however, only works directly within your controllers, your models, or your views. If you would like to use CodeIgniter’s classes from within your own custom classes you can do so as follows:

First, assign the CodeIgniter object to a variable:

Once you’ve assigned the object to a variable, you’ll use that variable instead of $this:

You’ll notice that the above get_instance() function is being passed by reference:

This is very important. Assigning by reference allows you to use the original CodeIgniter object rather than creating a copy of it.

However, since a library is a class, it would be better if you take full advantage of the OOP principles. So, in order to be able to use the CodeIgniter super-object in all of the class methods, you’re encouraged to assign it to a property instead:

Simply by naming your class files identically to a native library will cause CodeIgniter to use it instead of the native one. To use this feature you must name the file and the class declaration exactly the same as the native library. For example, to replace the native Email library you’ll create a file named application/libraries/Email.php, and declare your class with:

Note that most native classes are prefixed with CI_.

To load your library you’ll see the standard loading method:

At this time the Database classes can not be replaced with your own versions.

If all you need to do is add some functionality to an existing library - perhaps add a method or two - then it’s overkill to replace the entire library with your version. In this case it’s better to simply extend the class. Extending a class is nearly identical to replacing a class with a couple exceptions:

For example, to extend the native Email class you’ll create a file named application/libraries/MY_Email.php, and declare your class with:

If you need to use a constructor in your class make sure you extend the parent constructor:

Not all of the libraries have the same (or any) parameters in their constructor. Take a look at the library that you’re extending first to see how it should be implemented.

To load your sub-class you’ll use the standard syntax normally used. DO NOT include your prefix. For example, to load the example above, which extends the Email class, you will use:

Once loaded you will use the class variable as you normally would for the class you are extending. In the case of the email class all calls will use:

To set your own sub-class prefix, open your application/config/config.php file and look for this item:

Please note that all native CodeIgniter libraries are prefixed with CI_ so DO NOT use that as your prefix.

---

## Loader Class — CodeIgniter 3.1.11 documentation

**URL:** https://developers.easyappointments.org/framework/libraries/loader.html

**Contents:**
- Loader Class¶
- Application “Packages”¶
  - Package view files¶
- Class Reference¶

Loader, as the name suggests, is used to load elements. These elements can be libraries (classes) View files, Drivers, Helpers, Models, or your own files.

This class is initialized automatically by the system so there is no need to do it manually.

An application package allows for the easy distribution of complete sets of resources in a single directory, complete with its own libraries, models, helpers, config, and language files. It is recommended that these packages be placed in the application/third_party directory. Below is a sample map of an package directory.

The following is an example of a directory for an application package named “Foo Bar”.

Whatever the purpose of the “Foo Bar” application package, it has its own config files, helpers, language files, libraries, and models. To use these resources in your controllers, you first need to tell the Loader that you are going to be loading resources from a package, by adding the package path via the add_package_path() method.

By Default, package view files paths are set when add_package_path() is called. View paths are looped through, and once a match is encountered that view is loaded.

In this instance, it is possible for view naming collisions within packages to occur, and possibly the incorrect package being loaded. To ensure against this, set an optional second parameter of FALSE when calling add_package_path().

CI_Loader instance (method chaining)

This method is used to load core classes.

We use the terms “class” and “library” interchangeably.

For example, if you would like to send email with CodeIgniter, the first step is to load the email class within your controller:

Once loaded, the library will be ready for use, using $this->email.

Library files can be stored in subdirectories within the main “libraries” directory, or within your personal application/libraries directory. To load a file located in a subdirectory, simply include the path, relative to the “libraries” directory. For example, if you have file located at:

You will load it using:

You may nest the file in as many subdirectories as you want.

Additionally, multiple libraries can be loaded at the same time by passing an array of libraries to the load method.

The second (optional) parameter allows you to optionally pass configuration setting. You will typically pass these as an array:

Config options can usually also be set via a config file. Each library is explained in detail in its own page, so please read the information regarding each one you would like to use.

Please take note, when multiple libraries are supplied in an array for the first parameter, each will receive the same parameter information.

Assigning a Library to a different object name

If the third (optional) parameter is blank, the library will usually be assigned to an object with the same name as the library. For example, if the library is named Calendar, it will be assigned to a variable named $this->calendar.

If you prefer to set your own class names you can pass its value to the third parameter:

Please take note, when multiple libraries are supplied in an array for the first parameter, this parameter is discarded.

CI_Loader instance (method chaining)

This method is used to load driver libraries, acts very much like the library() method.

As an example, if you would like to use sessions with CodeIgniter, the first step is to load the session driver within your controller:

Once loaded, the library will be ready for use, using $this->session.

Driver files must be stored in a subdirectory within the main “libraries” directory, or within your personal application/libraries directory. The subdirectory must match the parent class name. Read the Drivers description for details.

Additionally, multiple driver libraries can be loaded at the same time by passing an array of drivers to the load method.

The second (optional) parameter allows you to optionally pass configuration settings. You will typically pass these as an array:

Config options can usually also be set via a config file. Each library is explained in detail in its own page, so please read the information regarding each one you would like to use.

Assigning a Driver to a different object name

If the third (optional) parameter is blank, the library will be assigned to an object with the same name as the parent class. For example, if the library is named Session, it will be assigned to a variable named $this->session.

If you prefer to set your own class names you can pass its value to the third parameter:

View content string if $return is set to TRUE, otherwise CI_Loader instance (method chaining)

This method is used to load your View files. If you haven’t read the Views section of the user guide it is recommended that you do since it shows you how this method is typically used.

The first parameter is required. It is the name of the view file you would like to load.

The .php file extension does not need to be specified unless you use something other than .php.

The second optional parameter can take an associative array or an object as input, which it runs through the PHP extract() function to convert to variables that can be used in your view files. Again, read the Views page to learn how this might be useful.

The third optional parameter lets you change the behavior of the method so that it returns data as a string rather than sending it to your browser. This can be useful if you want to process the data in some way. If you set the parameter to TRUE (boolean) it will return data. The default behavior is FALSE, which sends it to your browser. Remember to assign it to a variable if you want the data returned:

CI_Loader instance (method chaining)

This method takes an associative array as input and generates variables using the PHP extract() function. This method produces the same result as using the second parameter of the $this->load->view() method above. The reason you might want to use this method independently is if you would like to set some global variables in the constructor of your controller and have them become available in any view file loaded from any method. You can have multiple calls to this method. The data get cached and merged into one array for conversion to variables.

Value if key is found, NULL if not

This method checks the associative array of variables available to your views. This is useful if for any reason a var is set in a library or another controller method using $this->load->vars().

This method retrieves all variables available to your views.

Clears cached view variables.

CI_Loader instance (method chaining)

If your model is located in a subdirectory, include the relative path from your models directory. For example, if you have a model located at application/models/blog/Queries.php you’ll load it using:

If you would like your model assigned to a different object name you can specify it via the second parameter of the loading method:

Loaded CI_DB instance or FALSE on failure if $return is set to TRUE, otherwise CI_Loader instance (method chaining)

This method lets you load the database class. The two parameters are optional. Please see the database section for more info.

Loaded CI_DB_forge instance if $return is set to TRUE, otherwise CI_Loader instance (method chaining)

Loads the Database Forge class, please refer to that manual for more info.

Loaded CI_DB_utility instance if $return is set to TRUE, otherwise CI_Loader instance (method chaining)

Loads the Database Utilities class, please refer to that manual for more info.

CI_Loader instance (method chaining)

This method loads helper files, where file_name is the name of the file, without the _helper.php extension.

File contents if $return is set to TRUE, otherwise CI_Loader instance (method chaining)

This is a generic file loading method. Supply the filepath and name in the first parameter and it will open and read the file. By default the data is sent to your browser, just like a View file, but if you set the second parameter to boolean TRUE it will instead return the data as a string.

CI_Loader instance (method chaining)

This method is an alias of the language loading method: $this->lang->load().

TRUE on success, FALSE on failure

This method is an alias of the config file loading method: $this->config->load()

Singleton property name if found, FALSE if not

Allows you to check if a class has already been loaded or not.

The word “class” here refers to libraries and drivers.

If the requested class has been loaded, the method returns its assigned name in the CI Super-object and FALSE if it’s not:

If you have more than one instance of a class (assigned to different properties), then the first one will be returned.

CI_Loader instance (method chaining)

Adding a package path instructs the Loader class to prepend a given path for subsequent requests for resources. As an example, the “Foo Bar” application package above has a library named Foo_bar.php. In our controller, we’d do the following:

CI_Loader instance (method chaining)

When your controller is finished using resources from an application package, and particularly if you have other application packages you want to work with, you may wish to remove the package path so the Loader no longer looks in that directory for resources. To remove the last path added, simply call the method with no parameters.

Or to remove a specific package path, specify the same path previously given to add_package_path() for a package.:

An array of package paths

Returns all currently available package paths.

---

## Using CodeIgniter Libraries — CodeIgniter 3.1.11 documentation

**URL:** https://developers.easyappointments.org/framework/general/libraries.html

**Contents:**
- Using CodeIgniter Libraries¶
- Creating Your Own Libraries¶

All of the available libraries are located in your system/libraries/ directory. In most cases, to use one of these classes involves initializing it within a controller using the following initialization method:

Where ‘class_name’ is the name of the class you want to invoke. For example, to load the Form Validation Library you would do this:

Once initialized you can use it as indicated in the user guide page corresponding to that class.

Additionally, multiple libraries can be loaded at the same time by passing an array of libraries to the load method.

Please read the section of the user guide that discusses how to create your own libraries.

---
