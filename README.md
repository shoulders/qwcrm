# QWcrm Latest News

Check my blog for the latest news on [QuantumWarp Blog](https://quantumwarp.com/blog)
Over at [QuantumWarp.com](https://quantumwarp.com/)

# What is QWcrm?

[QWcrm](http://quantumwarp.com) is a free, open source and easy to use CRM designed for 
small businesses for all of their job and invoicing needs in one package.

There are no over complicated things that you need to do or learn
to use this software because it was developed with end users in mind.

QWcrm is a complete redevelopment of MyITCRM. All the core code has been rewritten,
all issues and bugs fixed.

Whilst maintaining the essence of MyITCRM, QWcrm is a separate and modern CRM which will grow in features and performance.

## Suitable for

QWcrm can be used for many different businesses because it is flexible:

* Computer Repairs Industry
* Electrical Device Repairs and Servicing
* Mobile Phone Repairs
* Small/Large Plant Maintenance and Servicing
* Garages / Vehicle Repairs
* Gardening Business
* Many more...

## Features

QWcrm has many features, not all can be listed here:

* Employees and roles
* Configurable permissions
* Client Management
* Work Order (Jobs/Tasks) Management 
* Work Order Scheduling
* Invoicing
* Assign payments to invoices
* Templatable
* Migrate data from MyITCRM
* Vouchers
* Expenses and Refunds
* Suppliers Database
* Financial Report
* Fully Translatable
* Automatic Language Translation
* Activity Logging
* Access Logging
* Error Logging
* Advanced Debugging
* SEF router

## How To Install

These are quick install instructions. I will improve them as I can.

- Create a MySQL database called xxxxxxxx_qwcrm
- Set the collation of the database to utf8_unicode_ci  - this is done under the operation tab of phpmyadmin
- Upload the qwcrm_x.x.x.zip to your webhost public_html/htdocs folder or sub-folder (as required)
- Extract the zip file contents.
- Delete the zip file because it no longer needed
- I would also configure the .htaccess password for security. This is optional and there will be more security in the next release. See http://www.htaccesstools.com/htpasswd-generator/
- Browse to the directory you have extract the files to i.e. http://www.example.com/ or http://www.example.com/folder/
- The QWcrm installation will now appear.
- Enter the details as needed.
- Done

That is all that is needed to install on a webhost. Xampp needs a few more settings see the second to last post https://github.com/shoulders/qwcrm/issues/756

**Xampp**

Xampp needs a few more settings to allow the use of QWcrm.

*In php.ini*
- Uncomment **;extension=php_intl.dll** to enable the function locale_accept_from_http()
- Reduce error reporting to **error_reporting = E_ALL & ~E_NOTICE** (This might not be needed anymore)
- Restart Xampp

*To Explain*
- **locale_accept_from_http()** is required to auto-detect your browser language and is not turned on by default in Xampp but is enabled by default on most real webservers.
- The error reporting is reduced to remove all the notices of non-existent variables. These errors will get removed 'hopefully' in the next release. They cause no issues with how the program works, but for now this is the best way of fixing them. This is also a standard setting most web companies use. (This might not be needed anymore)

## How To Upgrade

The are just some quick instructions for now (Clean Install)

- Backup you old QWcrm, files and database. You have been warned.
- Create a sub-folder called `old-qwcrm` and move all of your current QWcrm files into there. This folder can be called anything and is not part of the actual upgrade.
- Move all of you QWcrm installation files into that folder.
- Upload/Extract the new QWcrm installation package in to your QWcrm folder (This is not `old-qwcrm`).
- Copy the config.php from the old QWcrm files to your QWcrm folder.
- Copy the .htacces from the old QWcrm files to your QWcrm folder.
- Compare the .htaccess and htaccess.txt to see if you need to make any changes to your .htaccess file. Once compared delete the htaccess.txt file.
- Copy your logo file from media folder in the old QWcrm files to your media folder in your QWcrm folder.
- Rename robots.txt.dist to robots.txt
- Goto https://localhost/ or whatever the location of your QWcrm install is.
- Accept the license and follow the onscreen upgrade instructions. They are pretty simple.
- Delete the `old-qwcrm` as it is not needed anymore.

**NB:** Potentially you can just copy all of the files over the top of your current QWcrm installation without moving any files but this might leave old files in place that are no longer used. This method should only be used for quick testing.

## Support

There is a [forum](https://quantumwarp.com/forum/) available for support over at [QuantumWarp](https://quantumwarp.com/forum/)
with new features and bugs being actively reported and resolved. It’s also a great way to get personal involvement from all
our end users to help build on this software. 

Always search the forum and double check your problem before posting.

If you do need to post, the more information you can supply, the easier it is to fix.

## MyITCRM

You can completely replace MyITCRM with QWcrm because it has all of the features you are use too and more.
QWcrm has a migrator to transfer your data from MyITCRM to QWcrm allowing for a seamless transition.

## Requirements

A web server with PHP 5.6+ and MySQL 5.0.0+

## Special Thanks

A big thanks goes out to those how have
[contributed](https://github.com/shoulders/qwcrm/contributors) to MyITCRM and QWcrm