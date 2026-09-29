# Easyappointments-Ci3_Docs - Installation

**Pages:** 2

---

## Upgrading from 1.6.1 to 1.6.2 — CodeIgniter 3.1.11 documentation

**URL:** https://developers.easyappointments.org/framework/installation/upgrade_162.html

**Contents:**
- Upgrading from 1.6.1 to 1.6.2¶
- Step 1: Update your CodeIgniter files¶
- Step 2: Encryption Key¶
- Step 3: Constants File¶
- Step 4: Mimes File¶
- Step 5: Update your user guide¶

Before performing an update you should take your site offline by replacing the index.php file with a static one.

Replace these files and directories in your “system” folder with the new versions:

If you have any custom developed files in these folders please make copies of them first.

If you are using sessions, open up application/config/config.php and verify you’ve set an encryption key.

Copy /application/config/constants.php to your installation, and modify if necessary.

Replace /application/config/mimes.php with the dowloaded version. If you’ve added custom mime types, you’ll need to re-add them.

Please also replace your local copy of the user guide with the new version.

---

## Installation Instructions — CodeIgniter 3.1.11 documentation

**URL:** https://developers.easyappointments.org/framework/installation/index.html

**Contents:**
- Installation Instructions¶

CodeIgniter is installed in four steps:

If you wish to increase security by hiding the location of your CodeIgniter files you can rename the system and application folders to something more private. If you do rename them, you must open your main index.php file and set the $system_path and $application_folder variables at the top of the file with the new name you’ve chosen.

For the best security, both the system and any application folders should be placed above web root so that they are not directly accessible via a browser. By default, .htaccess files are included in each folder to help prevent direct access, but it is best to remove them from public access entirely in case the web server configuration changes or doesn’t abide by the .htaccess.

If you would like to keep your views public it is also possible to move the views folder out of your application folder.

After moving them, open your main index.php file and set the $system_path, $application_folder and $view_folder variables, preferably with a full path, e.g. ‘/www/MyUser/system’.

One additional measure to take in production environments is to disable PHP error reporting and any other development-only functionality. In CodeIgniter, this can be done by setting the ENVIRONMENT constant, which is more fully described on the security page.

If you’re new to CodeIgniter, please read the Getting Started section of the User Guide to begin learning how to build dynamic PHP applications. Enjoy!

---
