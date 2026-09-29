# Easyappointments-Ci3_Docs - Tutorials

**Pages:** 3

---

## PHP Style Guide — CodeIgniter 3.1.11 documentation

**URL:** https://developers.easyappointments.org/framework/general/styleguide.html

**Contents:**
- PHP Style Guide¶
- File Format¶
  - TextMate¶
  - BBEdit¶
- PHP Closing Tag¶
- File Naming¶
- Class and Method Naming¶
- Variable Names¶
- Commenting¶
- Constants¶

The following page describes the coding styles adhered to when contributing to the development of CodeIgniter. There is no requirement to use these styles in your own CodeIgniter application, though they are recommended.

Files should be saved with Unicode (UTF-8) encoding. The BOM should not be used. Unlike UTF-16 and UTF-32, there’s no byte order to indicate in a UTF-8 encoded file, and the BOM can have a negative side effect in PHP of sending output, preventing the application from being able to set its own headers. Unix line endings should be used (LF).

Here is how to apply these settings in some of the more common text editors. Instructions for your text editor may vary; check your text editor’s documentation.

The PHP closing tag on a PHP document ?> is optional to the PHP parser. However, if used, any whitespace following the closing tag, whether introduced by the developer, user, or an FTP application, can cause unwanted output, PHP errors, or if the latter are suppressed, blank pages. For this reason, all PHP files MUST OMIT the PHP closing tag and end with a single empty line instead.

Class files must be named in a Ucfirst-like manner, while any other file name (configurations, views, generic scripts, etc.) should be in all lowercase.

Furthermore, class file names should match the name of the class itself. For example, if you have a class named Myclass, then its filename must be Myclass.php.

Class names should always start with an uppercase letter. Multiple words should be separated with an underscore, and not CamelCased.

Class methods should be entirely lowercased and named to clearly indicate their function, preferably including a verb. Try to avoid overly long and verbose names. Multiple words should be separated with an underscore.

The guidelines for variable naming are very similar to those used for class methods. Variables should contain only lowercase letters, use underscore separators, and be reasonably named to indicate their purpose and contents. Very short, non-word variables should only be used as iterators in for() loops.

In general, code should be commented prolifically. It not only helps describe the flow and intent of the code for less experienced programmers, but can prove invaluable when returning to your own code months down the line. There is not a required format for comments, but the following are recommended.

DocBlock style comments preceding class, method, and property declarations so they can be picked up by IDEs:

Use single line comments within code, leaving a blank line between large comment blocks and code.

Constants follow the same guidelines as do variables, except constants should always be fully uppercase. Always use CodeIgniter constants when appropriate, i.e. SLASH, LD, RD, PATH_CACHE, etc.

TRUE, FALSE, and NULL keywords should always be fully uppercase.

Use of the || “or” comparison operator is discouraged, as its clarity on some output devices is low (looking like the number 11, for instance). && is preferred over AND but either are acceptable, and a space should always precede and follow !.

Some PHP functions return FALSE on failure, but may also have a valid return value of “” or 0, which would evaluate to FALSE in loose comparisons. Be explicit by comparing the variable type when using these return values in conditionals to ensure the return value is indeed what you expect, and not a value that has an equivalent loose-type evaluation.

Use the same stringency in returning and checking your own variables. Use === and !== as necessary.

See also information regarding typecasting, which can be quite useful. Typecasting has a slightly different effect which may be desirable. When casting a variable as a string, for instance, NULL and boolean FALSE variables become empty strings, 0 (and other numbers) become strings of digits, and boolean TRUE becomes “1”:

Do not leave debugging code in your submissions, even when commented out. Things such as var_dump(), print_r(), die()/exit() should not be included in your code unless it serves a specific purpose other than debugging.

No whitespace can precede the opening PHP tag or follow the closing PHP tag. Output is buffered, so whitespace in your files can cause output to begin before CodeIgniter outputs its content, leading to errors and an inability for CodeIgniter to send proper headers.

CodeIgniter recommends PHP 5.6 or newer to be used, but it should be compatible with PHP 5.3.7. Your code must either be compatible with this requirement, provide a suitable fallback, or be an optional feature that dies quietly without affecting a user’s application.

Additionally, do not use PHP functions that require non-default libraries to be installed unless your code contains an alternative method when the function is not available.

Use separate files for each class, unless the classes are closely related. An example of a CodeIgniter file that contains multiple classes is the Xmlrpc library file.

Use tabs for whitespace in your code, not spaces. This may seem like a small thing, but using tabs instead of whitespace allows the developer looking at your code to have indentation at levels that they prefer and customize in whatever application they use. And as a side benefit, it results in (slightly) more compact files, storing one tab character versus, say, four space characters.

Files must be saved with Unix line breaks. This is more of an issue for developers who work in Windows, but in any case ensure that your text editor is setup to save files with Unix line breaks.

Use Allman style indenting. With the exception of Class declarations, braces are always placed on a line by themselves, and indented at the same level as the control statement that “owns” them.

In general, parenthesis and brackets should not use any additional spaces. The exception is that a space should always follow PHP control structures that accept arguments with parenthesis (declare, do-while, elseif, for, foreach, if, switch, while), to help distinguish them from functions and increase readability.

CodeIgniter libraries should take advantage of corresponding language files whenever possible.

Methods and variables that are only accessed internally, such as utility and helper functions that your public methods use for code abstraction, should be prefixed with an underscore.

Code must run error free and not rely on warnings and notices to be hidden to meet this requirement. For instance, never access a variable that you did not set yourself (such as $_POST array keys) without first checking to see that it isset().

Make sure that your dev environment has error reporting enabled for ALL users, and that display_errors is enabled in the PHP environment. You can check this setting with:

On some servers where display_errors is disabled, and you do not have the ability to change this in the php.ini, you can often enable it with:

Setting the display_errors setting with ini_set() at runtime is not identical to having it enabled in the PHP environment. Namely, it will not have any effect if the script has fatal errors.

Always use full PHP opening tags, in case a server does not have short_open_tag enabled.

PHP 5.4 will always have the <?= tag available.

Never combine statements on one line.

Always use single quoted strings unless you need variables parsed, and in cases where you do need variables parsed, use braces to prevent greedy token parsing. You may also use double-quoted strings if the string contains single quotes, so you do not have to use escape characters.

SQL keywords are always capitalized: SELECT, INSERT, UPDATE, WHERE, AS, JOIN, ON, IN, etc.

Break up long queries into multiple lines for legibility, preferably breaking for each clause.

Whenever appropriate, provide function argument defaults, which helps prevent PHP errors with mistaken calls and provides common fallback values which can save a few lines of code. Example:

---

## Tutorial — CodeIgniter 3.1.11 documentation

**URL:** https://developers.easyappointments.org/framework/tutorial/index.html

**Contents:**
- Tutorial¶

This tutorial is intended to introduce you to the CodeIgniter framework and the basic principles of MVC architecture. It will show you how a basic CodeIgniter application is constructed in step-by-step fashion.

In this tutorial, you will be creating a basic news application. You will begin by writing the code that can load static pages. Next, you will create a news section that reads news items from a database. Finally, you’ll add a form to create news items in the database.

This tutorial will primarily focus on:

The entire tutorial is split up over several pages, each explaining a small part of the functionality of the CodeIgniter framework. You’ll go through the following pages:

Enjoy your exploration of the CodeIgniter framework.

---

## Conclusion — CodeIgniter 3.1.11 documentation

**URL:** https://developers.easyappointments.org/framework/tutorial/conclusion.html

**Contents:**
- Conclusion¶

This tutorial did not cover all of the things you might expect of a full-fledged content management system, but it introduced you to the more important topics of routing, writing controllers, and models. We hope this tutorial gave you an insight into some of CodeIgniter’s basic design patterns, which you can expand upon.

Now that you’ve completed this tutorial, we recommend you check out the rest of the documentation. CodeIgniter is often praised because of its comprehensive documentation. Use this to your advantage and read the “Introduction” and “General Topics” sections thoroughly. You should read the class and helper references when needed.

Every intermediate PHP programmer should be able to get the hang of CodeIgniter within a few days.

If you still have questions about the framework or your own CodeIgniter code, you can:

---
