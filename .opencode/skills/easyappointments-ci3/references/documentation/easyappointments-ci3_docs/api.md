# Easyappointments-Ci3_Docs - Api

**Pages:** 8

---

## DB Driver Reference — CodeIgniter 3.1.11 documentation

**URL:** https://developers.easyappointments.org/framework/database/db_driver_reference.html

**Contents:**
- DB Driver Reference¶

This is the platform-independent base DB implementation class. This class will not be called directly. Rather, the adapter class for the specific database will extend and instantiate it.

The how-to material for this has been split over several articles. This article is intended to be a reference for them.

Not all methods are supported by all database drivers, some of them may fail (and return FALSE) if the underlying driver does not support them.

Initialize database settings, establish a connection to the database.

Database connection resource/object or FALSE on failure

Establish a connection with the database.

The returned value depends on the underlying driver in use. For example, a mysqli instance will be returned with the ‘mysqli’ driver.

Establish a persistent connection with the database.

This method is just an alias for db_connect(TRUE).

Keep / reestablish the database connection if no queries have been sent for a length of time exceeding the server’s idle timeout.

TRUE on success, FALSE on failure

Select / switch the current database.

TRUE on success, FALSE on failure

Set client character set.

The name of the platform in use (mysql, mssql, etc…).

Database version number.

TRUE for successful “write-type” queries, CI_DB_result instance (method chaining) on “query” success, FALSE on failure

Execute an SQL query.

Accepts an SQL string as input and returns a result object upon successful execution of a “read” type query.

Whatever the underlying driver’s “query” function returns

A simplified version of the query() method, appropriate for use when you don’t need to get a result object or to just send a query to the database and not care for the result.

Returns the number of rows changed by the last executed query.

Useful for checking how much rows were created, updated or deleted during the last executed query.

Enable/disable transaction “strict” mode.

When strict mode is enabled, if you are running multiple groups of transactions and one group fails, all subsequent groups will be rolled back.

If strict mode is disabled, each group is treated autonomously, meaning a failure of one group will not affect any others.

Disables transactions at run-time.

TRUE on success, FALSE on failure

Complete Transaction.

Lets you retrieve the transaction status flag to determine if it has failed.

The updated SQL statement

Compiles an SQL query with the bind values passed for it.

TRUE if the SQL statement is of “write type”, FALSE if not

Determines if a query is of a “write” type (such as INSERT, UPDATE, DELETE) or “read” type (i.e. SELECT).

The aggregate query elapsed time, in microseconds

Calculate the aggregate query elapsed time.

Returns the total number of queries that have been executed so far.

Returns the last query that was executed.

Escapes input data based on type, including boolean and NULLs.

The escaped string(s)

Escapes string values.

The returned strings do NOT include quotes around them.

The escaped string(s)

Similar to escape_str(), but will also escape the % and _ wildcard characters, so that they don’t cause false-positives in LIKE conditions.

The escape_like_str() method uses ‘!’ (exclamation mark) to escape special characters for LIKE conditions. Because this method escapes partial strings that you would wrap in quotes yourself, it cannot automatically add the ESCAPE '!' condition for you, and so you’ll have to manually do that.

The primary key name, FALSE if none

Retrieves the primary key of a table.

If the database platform does not support primary key detection, the first column name may be assumed as the primary key.

Row count for the specified table

Returns the total number of rows in a table, or 0 if no table was provided.

Array of table names or FALSE on failure

Gets a list of the tables in the current database.

TRUE if that table exists, FALSE if not

Determine if a particular table exists.

Array of field names or FALSE on failure

Gets a list of the field names in a table.

TRUE if that field exists in that table, FALSE if not

Determine if a particular field exists.

Array of field data items or FALSE on failure

Gets a list containing field data about a table.

The input item(s), escaped

Escape SQL identifiers, such as column, table and names.

The SQL INSERT statement, as a string

Generate an INSERT statement string.

The SQL UPDATE statement, as a string

Generate an UPDATE statement string.

Runs a native PHP function , using a platform agnostic wrapper.

Sets the directory path to use for caching storage.

Enable database results caching.

Disable database results caching.

TRUE on success, FALSE on failure

Delete the cache files associated with a particular URI.

Delete all cache files.

Close the DB Connection.

Displays the DB error screensends the application/views/errors/error_db.php template

Display an error message and stop script execution.

The message is displayed using the application/views/errors/error_db.php template.

Takes a column or table name (optionally with an alias) and applies the configured dbprefix to it.

Some logic is necessary in order to deal with column names that include the path.

Consider a query like this:

Or a query with aliasing:

Since the column name can include up to four segments (host, DB, table, column) or also have an alias prefix, we need to do a bit of work to figure this out and insert the table prefix (if it exists) in the proper position, and escape only the correct identifiers.

This method is used extensively by the Query Builder class.

---

## Creating Core System Classes — CodeIgniter 3.1.11 documentation

**URL:** https://developers.easyappointments.org/framework/general/core_classes.html

**Contents:**
- Creating Core System Classes¶
- System Class List¶
- Replacing Core Classes¶
- Extending Core Class¶
  - Setting Your Own Prefix¶

Every time CodeIgniter runs there are several base classes that are initialized automatically as part of the core framework. It is possible, however, to swap any of the core system classes with your own versions or even extend the core versions.

Most users will never have any need to do this, but the option to replace or extend them does exist for those who would like to significantly alter the CodeIgniter core.

Messing with a core system class has a lot of implications, so make sure you know what you are doing before attempting it.

The following is a list of the core system files that are invoked every time CodeIgniter runs:

To use one of your own system classes instead of a default one simply place your version inside your local application/core/ directory:

If this directory does not exist you can create it.

Any file named identically to one from the list above will be used instead of the one normally used.

Please note that your class must use CI as a prefix. For example, if your file is named Input.php the class will be named:

If all you need to do is add some functionality to an existing library - perhaps add a method or two - then it’s overkill to replace the entire library with your version. In this case it’s better to simply extend the class. Extending a class is nearly identical to replacing a class with a couple exceptions:

For example, to extend the native Input class you’ll create a file named application/core/MY_Input.php, and declare your class with:

If you need to use a constructor in your class make sure you extend the parent constructor:

Tip: Any functions in your class that are named identically to the methods in the parent class will be used instead of the native ones (this is known as “method overriding”). This allows you to substantially alter the CodeIgniter core.

If you are extending the Controller core class, then be sure to extend your new class in your application controller’s constructors.

To set your own sub-class prefix, open your application/config/config.php file and look for this item:

Please note that all native CodeIgniter libraries are prefixed with CI_ so DO NOT use that as your prefix.

---

## Email Class — CodeIgniter 3.1.11 documentation

**URL:** https://developers.easyappointments.org/framework/libraries/email.html

**Contents:**
- Email Class¶
- Using the Email Library¶
  - Sending Email¶
  - Setting Email Preferences¶
    - Setting Email Preferences in a Config File¶
  - Email Preferences¶
  - Overriding Word Wrapping¶
- Class Reference¶

CodeIgniter’s robust Email Class supports the following features:

Sending email is not only simple, but you can configure it on the fly or set your preferences in a config file.

Here is a basic example demonstrating how you might send email. Note: This example assumes you are sending the email from one of your controllers.

There are 21 different preferences available to tailor how your email messages are sent. You can either set them manually as described here, or automatically via preferences stored in your config file, described below:

Preferences are set by passing an array of preference values to the email initialize method. Here is an example of how you might set some preferences:

Most of the preferences have default values that will be used if you do not set them.

If you prefer not to set preferences using the above method, you can instead put them into a config file. Simply create a new file called the email.php, add the $config array in that file. Then save the file at config/email.php and it will be used automatically. You will NOT need to use the $this->email->initialize() method if you save your preferences in a config file.

The following is a list of all the preferences that can be set when sending email.

If you have word wrapping enabled (recommended to comply with RFC 822) and you have a very long link in your email it can get wrapped too, causing it to become un-clickable by the person receiving it. CodeIgniter lets you manually override word wrapping within part of your message like this:

Place the item you do not want word-wrapped between: {unwrap} {/unwrap}

CI_Email instance (method chaining)

Sets the email address and name of the person sending the email:

You can also set a Return-Path, to help redirect undelivered mail:

Return-Path can’t be used if you’ve configured ‘smtp’ as your protocol.

CI_Email instance (method chaining)

Sets the reply-to address. If the information is not provided the information in the :meth:from method is used. Example:

CI_Email instance (method chaining)

Sets the email address(s) of the recipient(s). Can be a single e-mail, a comma-delimited list or an array:

CI_Email instance (method chaining)

Sets the CC email address(s). Just like the “to”, can be a single e-mail, a comma-delimited list or an array.

CI_Email instance (method chaining)

Sets the BCC email address(s). Just like the to() method, can be a single e-mail, a comma-delimited list or an array.

If $limit is set, “batch mode” will be enabled, which will send the emails to batches, with each batch not exceeding the specified $limit.

CI_Email instance (method chaining)

Sets the email subject:

CI_Email instance (method chaining)

Sets the e-mail message body:

CI_Email instance (method chaining)

Sets the alternative e-mail message body:

This is an optional message string which can be used if you send HTML formatted email. It lets you specify an alternative message with no HTML formatting which is added to the header string for people who do not accept HTML email. If you do not set your own message CodeIgniter will extract the message from your HTML email and strip the tags.

CI_Email instance (method chaining)

Appends additional headers to the e-mail:

CI_Email instance (method chaining)

Initializes all the email variables to an empty state. This method is intended for use if you run the email sending method in a loop, permitting the data to be reset between cycles.

If you set the parameter to TRUE any attachments will be cleared as well:

TRUE on success, FALSE on failure

The e-mail sending method. Returns boolean TRUE or FALSE based on success or failure, enabling it to be used conditionally:

This method will automatically clear all parameters if the request was successful. To stop this behaviour pass FALSE:

In order to use the print_debugger() method, you need to avoid clearing the email parameters.

CI_Email instance (method chaining)

Enables you to send an attachment. Put the file path/name in the first parameter. For multiple attachments use the method multiple times. For example:

To use the default disposition (attachment), leave the second parameter blank, otherwise use a custom disposition:

You can also use a URL:

If you’d like to use a custom file name, you can use the third parameter:

If you need to use a buffer string instead of a real - physical - file you can use the first parameter as buffer, the third parameter as file name and the fourth parameter as mime-type:

Attachment Content-ID or FALSE if not found

Sets and returns an attachment’s Content-ID, which enables your to embed an inline (picture) attachment into HTML. First parameter must be the already attached file name.

Content-ID for each e-mail must be re-created for it to be unique.

Returns a string containing any server messages, the email headers, and the email message. Useful for debugging.

You can optionally specify which parts of the message should be printed. Valid options are: headers, subject, body.

By default, all of the raw data will be printed.

---

## Config Class — CodeIgniter 3.1.11 documentation

**URL:** https://developers.easyappointments.org/framework/libraries/config.html

**Contents:**
- Config Class¶
- Working with the Config Class¶
  - Anatomy of a Config File¶
  - Loading a Config File¶
    - Manual Loading¶
    - Auto-loading¶
  - Fetching Config Items¶
  - Setting a Config Item¶
  - Environments¶
- Class Reference¶

The Config class provides a means to retrieve configuration preferences. These preferences can come from the default config file (application/config/config.php) or from your own custom config files.

This class is initialized automatically by the system so there is no need to do it manually.

By default, CodeIgniter has one primary config file, located at application/config/config.php. If you open the file using your text editor you’ll see that config items are stored in an array called $config.

You can add your own config items to this file, or if you prefer to keep your configuration items separate (assuming you even need config items), simply create your own file and save it in config folder.

If you do create your own config files use the same format as the primary one, storing your items in an array called $config. CodeIgniter will intelligently manage these files so there will be no conflict even though the array has the same name (assuming an array index is not named the same as another).

CodeIgniter automatically loads the primary config file (application/config/config.php), so you will only need to load a config file if you have created your own.

There are two ways to load a config file:

To load one of your custom config files you will use the following function within the controller that needs it:

Where filename is the name of your config file, without the .php file extension.

If you need to load multiple config files normally they will be merged into one master config array. Name collisions can occur, however, if you have identically named array indexes in different config files. To avoid collisions you can set the second parameter to TRUE and each config file will be stored in an array index corresponding to the name of the config file. Example:

Please see the section entitled Fetching Config Items below to learn how to retrieve config items set this way.

The third parameter allows you to suppress errors in the event that a config file does not exist:

If you find that you need a particular config file globally, you can have it loaded automatically by the system. To do this, open the autoload.php file, located at application/config/autoload.php, and add your config file as indicated in the file.

To retrieve an item from your config file, use the following function:

Where item_name is the $config array index you want to retrieve. For example, to fetch your language choice you’ll do this:

The function returns NULL if the item you are trying to fetch does not exist.

If you are using the second parameter of the $this->config->load function in order to assign your config items to a specific index you can retrieve it by specifying the index name in the second parameter of the $this->config->item() function. Example:

If you would like to dynamically set a config item or change an existing one, you can do so using:

Where item_name is the $config array index you want to change, and item_value is its value.

You may load different configuration files depending on the current environment. The ENVIRONMENT constant is defined in index.php, and is described in detail in the Handling Environments section.

To create an environment-specific configuration file, create or copy a configuration file in application/config/{ENVIRONMENT}/{FILENAME}.php

For example, to create a production-only config.php, you would:

When you set the ENVIRONMENT constant to ‘production’, the settings for your new production-only config.php will be loaded.

You can place the following configuration files in environment-specific folders:

CodeIgniter always loads the global config file first (i.e., the one in application/config/), then tries to load the configuration files for the current environment. This means you are not obligated to place all of your configuration files in an environment folder. Only the files that change per environment. Additionally you don’t have to copy all the config items in the environment config file. Only the config items that you wish to change for your environment. The config items declared in your environment folders always overwrite those in your global config files.

Array of all loaded config values

Array of all loaded config files

Config item value or NULL if not found

Fetch a config file item.

Sets a config file item to the specified value.

Config item value with a trailing forward slash or NULL if not found

This method is identical to item(), except it appends a forward slash to the end of the item, if it exists.

TRUE on success, FALSE on failure

Loads a configuration file.

This method retrieves the URL to your site, along with the “index” value you’ve specified in the config file.

This method is normally accessed via the corresponding functions in the URL Helper.

This method retrieves the URL to your site, plus an optional path such as to a stylesheet or image.

This method is normally accessed via the corresponding functions in the URL Helper.

This method retrieves the URL to your CodeIgniter system/ directory.

This method is DEPRECATED because it encourages usage of insecure coding practices. Your system/ directory shouldn’t be publicly accessible.

---

## Pagination Class — CodeIgniter 3.1.11 documentation

**URL:** https://developers.easyappointments.org/framework/libraries/pagination.html

**Contents:**
- Pagination Class¶
- Example¶
  - Notes¶
  - Setting preferences in a config file¶
- Customizing the Pagination¶
- Adding Enclosing Markup¶
- Customizing the First Link¶
- Customizing the Last Link¶
- Customizing the “Next” Link¶
- Customizing the “Previous” Link¶

CodeIgniter’s Pagination class is very easy to use, and it is 100% customizable, either dynamically or via stored preferences.

If you are not familiar with the term “pagination”, it refers to links that allows you to navigate from page to page, like this:

Here is a simple example showing how to create pagination in one of your controller methods:

The $config array contains your configuration variables. It is passed to the $this->pagination->initialize() method as shown above. Although there are some twenty items you can configure, at minimum you need the three shown. Here is a description of what those items represent:

The create_links() method returns an empty string when there is no pagination to show.

If you prefer not to set preferences using the above method, you can instead put them into a config file. Simply create a new file called pagination.php, add the $config array in that file. Then save the file in application/config/pagination.php and it will be used automatically. You will NOT need to use $this->pagination->initialize() if you save your preferences in a config file.

The following is a list of all the preferences you can pass to the initialization function to tailor the display.

$config[‘uri_segment’] = 3;

The pagination function automatically determines which segment of your URI contains the page number. If you need something different you can specify it.

$config[‘num_links’] = 2;

The number of “digit” links you would like before and after the selected page number. For example, the number 2 will place two digits on either side, as in the example links at the very top of this page.

$config[‘use_page_numbers’] = TRUE;

By default, the URI segment will use the starting index for the items you are paginating. If you prefer to show the the actual page number, set this to TRUE.

$config[‘page_query_string’] = TRUE;

By default, the pagination library assume you are using URI Segments, and constructs your links something like:

If you have $config['enable_query_strings'] set to TRUE your links will automatically be re-written using Query Strings. This option can also be explicitly set. Using $config['page_query_string'] set to TRUE, the pagination link will become:

Note that “per_page” is the default query string passed, however can be configured using $config['query_string_segment'] = 'your_string'

$config[‘reuse_query_string’] = FALSE;

By default your Query String arguments (nothing to do with other query string options) will be ignored. Setting this config to TRUE will add existing query string arguments back into the URL after the URI segment and before the suffix.:

This helps you mix together normal URI Segments as well as query string arguments, which until 3.0 was not possible.

$config[‘prefix’] = ‘’;

A custom prefix added to the path. The prefix value will be right before the offset segment.

$config[‘suffix’] = ‘’;

A custom suffix added to the path. The suffix value will be right after the offset segment.

$config[‘use_global_url_suffix’] = FALSE;

When set to TRUE, it will override the $config['suffix'] value and instead set it to the one that you have in $config['url_suffix'] in your application/config/config.php file.

If you would like to surround the entire pagination with some markup you can do it with these two preferences:

$config[‘full_tag_open’] = ‘<p>’;

The opening tag placed on the left side of the entire result.

$config[‘full_tag_close’] = ‘</p>’;

The closing tag placed on the right side of the entire result.

$config[‘first_link’] = ‘First’;

The text you would like shown in the “first” link on the left. If you do not want this link rendered, you can set its value to FALSE.

This value can also be translated via a language file.

$config[‘first_tag_open’] = ‘<div>’;

The opening tag for the “first” link.

$config[‘first_tag_close’] = ‘</div>’;

The closing tag for the “first” link.

$config[‘first_url’] = ‘’;

An alternative URL to use for the “first page” link.

$config[‘last_link’] = ‘Last’;

The text you would like shown in the “last” link on the right. If you do not want this link rendered, you can set its value to FALSE.

This value can also be translated via a language file.

$config[‘last_tag_open’] = ‘<div>’;

The opening tag for the “last” link.

$config[‘last_tag_close’] = ‘</div>’;

The closing tag for the “last” link.

$config[‘next_link’] = ‘&gt;’;

The text you would like shown in the “next” page link. If you do not want this link rendered, you can set its value to FALSE.

This value can also be translated via a language file.

$config[‘next_tag_open’] = ‘<div>’;

The opening tag for the “next” link.

$config[‘next_tag_close’] = ‘</div>’;

The closing tag for the “next” link.

$config[‘prev_link’] = ‘&lt;’;

The text you would like shown in the “previous” page link. If you do not want this link rendered, you can set its value to FALSE.

This value can also be translated via a language file.

$config[‘prev_tag_open’] = ‘<div>’;

The opening tag for the “previous” link.

$config[‘prev_tag_close’] = ‘</div>’;

The closing tag for the “previous” link.

$config[‘cur_tag_open’] = ‘<b>’;

The opening tag for the “current” link.

$config[‘cur_tag_close’] = ‘</b>’;

The closing tag for the “current” link.

$config[‘num_tag_open’] = ‘<div>’;

The opening tag for the “digit” link.

$config[‘num_tag_close’] = ‘</div>’;

The closing tag for the “digit” link.

If you wanted to not list the specific pages (for example, you only want “next” and “previous” links), you can suppress their rendering by adding:

If you want to add an extra attribute to be added to every link rendered by the pagination class, you can set them as key/value pairs in the “attributes” config:

Usage of the old method of setting classes via “anchor_class” is deprecated.

By default the rel attribute is dynamically generated and appended to the appropriate anchors. If for some reason you want to turn it off, you can pass boolean FALSE as a regular attribute

CI_Pagination instance (method chaining)

Initializes the Pagination class with your preferred options.

Returns a “pagination” bar, containing the generated links or an empty string if there’s just a single page.

---

## File Uploading Class — CodeIgniter 3.1.11 documentation

**URL:** https://developers.easyappointments.org/framework/libraries/file_uploading.html

**Contents:**
- File Uploading Class¶
- The Process¶
  - Creating the Upload Form¶
  - The Success Page¶
  - The Controller¶
  - The Upload Directory¶
  - Try it!¶
- Reference Guide¶
  - Initializing the Upload Class¶
  - Setting Preferences¶

CodeIgniter’s File Uploading Class permits files to be uploaded. You can set various preferences, restricting the type and size of the files.

Uploading a file involves the following general process:

To demonstrate this process here is brief tutorial. Afterward you’ll find reference information.

Using a text editor, create a form called upload_form.php. In it, place this code and save it to your application/views/ directory:

You’ll notice we are using a form helper to create the opening form tag. File uploads require a multipart form, so the helper creates the proper syntax for you. You’ll also notice we have an $error variable. This is so we can show error messages in the event the user does something wrong.

Using a text editor, create a form called upload_success.php. In it, place this code and save it to your application/views/ directory:

Using a text editor, create a controller called Upload.php. In it, place this code and save it to your application/controllers/ directory:

You’ll need a destination directory for your uploaded images. Create a directory at the root of your CodeIgniter installation called uploads and set its file permissions to 777.

To try your form, visit your site using a URL similar to this one:

You should see an upload form. Try uploading an image file (either a jpg, gif, or png). If the path in your controller is correct it should work.

Like most other classes in CodeIgniter, the Upload class is initialized in your controller using the $this->load->library() method:

Once the Upload class is loaded, the object will be available using: $this->upload

Similar to other libraries, you’ll control what is allowed to be upload based on your preferences. In the controller you built above you set the following preferences:

The above preferences should be fairly self-explanatory. Below is a table describing all available preferences.

The following preferences are available. The default value indicates what will be used if you do not specify that preference.

If you prefer not to set preferences using the above method, you can instead put them into a config file. Simply create a new file called the upload.php, add the $config array in that file. Then save the file in: config/upload.php and it will be used automatically. You will NOT need to use the $this->upload->initialize() method if you save your preferences in a config file.

CI_Upload instance (method chaining)

TRUE on success, FALSE on failure

Performs the upload based on the preferences you’ve set.

By default the upload routine expects the file to come from a form field called userfile, and the form must be of type “multipart”.

If you would like to set your own field name simply pass its value to the do_upload() method:

Formatted error message(s)

Retrieves any error messages if the do_upload() method returned false. The method does not echo automatically, it returns the data so you can assign it however you need.

By default the above method wraps any errors within <p> tags. You can set your own delimiters like this:

Information about the uploaded file

This is a helper method that returns an array containing all of the data related to the file you uploaded. Here is the array prototype:

To return one element from the array:

Here’s a table explaining the above-displayed array items:

---

## Creating Ancillary Classes — CodeIgniter 3.1.11 documentation

**URL:** https://developers.easyappointments.org/framework/general/ancillary_classes.html

**Contents:**
- Creating Ancillary Classes¶
- get_instance()¶

In some cases you may want to develop classes that exist apart from your controllers but have the ability to utilize all of CodeIgniter’s resources. This is easily possible as you’ll see.

Any class that you instantiate within your controller methods can access CodeIgniter’s native resources simply by using the get_instance() function. This function returns the main CodeIgniter object.

Normally, to call any of the available methods, CodeIgniter requires you to use the $this construct:

$this, however, only works within your controllers, your models, or your views. If you would like to use CodeIgniter’s classes from within your own custom classes you can do so as follows:

First, assign the CodeIgniter object to a variable:

Once you’ve assigned the object to a variable, you’ll use that variable instead of $this:

If you’ll be using get_instance() inside another class, then it would be better if you assign it to a property. This way, you won’t need to call get_instance() in every single method.

In the above example, both methods foo() and bar() will work after you instantiate the Example class, without the need to call get_instance() in each of them.

---

## XML-RPC and XML-RPC Server Classes — CodeIgniter 3.1.11 documentation

**URL:** https://developers.easyappointments.org/framework/libraries/xmlrpc.html

**Contents:**
- XML-RPC and XML-RPC Server Classes¶
- What is XML-RPC?¶
- Using the XML-RPC Class¶
  - Initializing the Class¶
  - Sending XML-RPC Requests¶
    - Explanation¶
  - Anatomy of a Request¶
  - Creating an XML-RPC Server¶
  - Processing Server Requests¶
    - Notes:¶

CodeIgniter’s XML-RPC classes permit you to send requests to another server, or set up your own XML-RPC server to receive requests.

Quite simply it is a way for two computers to communicate over the internet using XML. One computer, which we will call the client, sends an XML-RPC request to another computer, which we will call the server. Once the server receives and processes the request it will send back a response to the client.

For example, using the MetaWeblog API, an XML-RPC Client (usually a desktop publishing tool) will send a request to an XML-RPC Server running on your site. This request might be a new weblog entry being sent for publication, or it could be a request for an existing entry for editing. When the XML-RPC Server receives this request it will examine it to determine which class/method should be called to process the request. Once processed, the server will then send back a response message.

For detailed specifications, you can visit the XML-RPC site.

Like most other classes in CodeIgniter, the XML-RPC and XML-RPCS classes are initialized in your controller using the $this->load->library function:

To load the XML-RPC class you will use:

Once loaded, the xml-rpc library object will be available using: $this->xmlrpc

To load the XML-RPC Server class you will use:

Once loaded, the xml-rpcs library object will be available using: $this->xmlrpcs

When using the XML-RPC Server class you must load BOTH the XML-RPC class and the XML-RPC Server class.

To send a request to an XML-RPC server you must specify the following information:

Here is a basic example that sends a simple Weblogs.com ping to the Ping-o-Matic

The above code initializes the XML-RPC class, sets the server URL and method to be called (weblogUpdates.ping). The request (in this case, the title and URL of your site) is placed into an array for transportation, and compiled using the request() function. Lastly, the full request is sent. If the send_request() method returns false we will display the error message sent back from the XML-RPC Server.

An XML-RPC request is simply the data you are sending to the XML-RPC server. Each piece of data in a request is referred to as a request parameter. The above example has two parameters: The URL and title of your site. When the XML-RPC server receives your request, it will look for parameters it requires.

Request parameters must be placed into an array for transportation, and each parameter can be one of seven data types (strings, numbers, dates, etc.). If your parameters are something other than strings you will have to include the data type in the request array.

Here is an example of a simple array with three parameters:

If you use data types other than strings, or if you have several different data types, you will place each parameter into its own array, with the data type in the second position:

The Data Types section below has a full list of data types.

An XML-RPC Server acts as a traffic cop of sorts, waiting for incoming requests and redirecting them to the appropriate functions for processing.

To create your own XML-RPC server involves initializing the XML-RPC Server class in your controller where you expect the incoming request to appear, then setting up an array with mapping instructions so that incoming requests can be sent to the appropriate class and method for processing.

Here is an example to illustrate:

The above example contains an array specifying two method requests that the Server allows. The allowed methods are on the left side of the array. When either of those are received, they will be mapped to the class and method on the right.

The ‘object’ key is a special key that you pass an instantiated class object with, which is necessary when the method you are mapping to is not part of the CodeIgniter super object.

In other words, if an XML-RPC Client sends a request for the new_post method, your server will load the My_blog class and call the new_entry function. If the request is for the update_post method, your server will load the My_blog class and call the update_entry() method.

The function names in the above example are arbitrary. You’ll decide what they should be called on your server, or if you are using standardized APIs, like the Blogger or MetaWeblog API, you’ll use their function names.

There are two additional configuration keys you may make use of when initializing the server class: debug can be set to TRUE in order to enable debugging, and xss_clean may be set to FALSE to prevent sending data through the Security library’s xss_clean() method.

When the XML-RPC Server receives a request and loads the class/method for processing, it will pass an object to that method containing the data sent by the client.

Using the above example, if the new_post method is requested, the server will expect a class to exist with this prototype:

The $request variable is an object compiled by the Server, which contains the data sent by the XML-RPC Client. Using this object you will have access to the request parameters enabling you to process the request. When you are done you will send a Response back to the Client.

Below is a real-world example, using the Blogger API. One of the methods in the Blogger API is getUserInfo(). Using this method, an XML-RPC Client can send the Server a username and password, in return the Server sends back information about that particular user (nickname, user ID, email address, etc.). Here is how the processing function might look:

The output_parameters() method retrieves an indexed array corresponding to the request parameters sent by the client. In the above example, the output parameters will be the username and password.

If the username and password sent by the client were not valid, and error message is returned using send_error_message().

If the operation was successful, the client will be sent back a response array containing the user’s info.

Similar to Requests, Responses must be formatted as an array. However, unlike requests, a response is an array that contains a single item. This item can be an array with several additional arrays, but there can be only one primary array index. In other words, the basic prototype is this:

Responses, however, usually contain multiple pieces of information. In order to accomplish this we must put the response into its own array so that the primary array continues to contain a single piece of data. Here’s an example showing how this might be accomplished:

Notice that the above array is formatted as a struct. This is the most common data type for responses.

As with Requests, a response can be one of the seven data types listed in the Data Types section.

If you need to send the client an error response you will use the following:

The first parameter is the error number while the second parameter is the error message.

To help you understand everything we’ve covered thus far, let’s create a couple controllers that act as XML-RPC Client and Server. You’ll use the Client to send a request to the Server and receive a response.

Using a text editor, create a controller called Xmlrpc_client.php. In it, place this code and save it to your application/controllers/ folder:

In the above code we are using a “url helper”. You can find more information in the Helpers Functions page.

Using a text editor, create a controller called Xmlrpc_server.php. In it, place this code and save it to your application/controllers/ folder:

Now visit the your site using a URL similar to this:

You should now see the message you sent to the server, and its response back to you.

The client you created sends a message (“How’s is going?”) to the server, along with a request for the “Greetings” method. The Server receives the request and maps it to the process() method, where a response is sent back.

If you wish to use an associative array in your method parameters you will need to use a struct datatype:

You can retrieve the associative array when processing the request in the Server.

According to the XML-RPC spec there are seven types of values that you can send via XML-RPC:

Initializes the XML-RPC library. Accepts an associative array containing your settings.

Sets the URL and port number of the server to which a request is to be sent:

Basic HTTP authentication is also supported, simply add it to the server URL:

Set a time out period (in seconds) after which the request will be canceled:

This timeout period will be used both for an initial connection to the remote server, as well as for getting a response from it. Make sure you set the timeout before calling send_request().

Sets the method that will be requested from the XML-RPC server:

Where method is the name of the method.

Takes an array of data and builds request to be sent to XML-RPC server:

The request sending method. Returns boolean TRUE or FALSE based on success for failure, enabling it to be used conditionally.

Returns an error message as a string if your request failed for some reason.

Returns the response from the remote server once request is received. The response will typically be an associative array.

XML_RPC_Response instance

This method lets you send an error message from your server to the client. First parameter is the error number while the second parameter is the error message.

---
