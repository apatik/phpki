About this Project
===============

NOTICE:
This application is designed to be an easy to use "certificate factory"
requiring minimum human intervention to administer.  It is intended for
use within a trusted INTRAnet for the creation and management of x.509
e-mail digital certificates by departmental managers.  IT IS NOT INTENDED
FOR USE OVER THE INTERNET.

This application stores private keys within a sub-directory, making them
potentially susceptible to compromise.  Extra care has been taken in the
design of this application to protect the security of your certificates,
on the condition that you INSTALL IT AS THE ROOT USER.  However, no
software is 100% secure, AND NO PROMISES OR GUARANTEES ARE MADE!

Main changes
------------
* Added Elliptic Curve Support
* See CHANGELOG for full details

Planned changes
---------------
* Add in some additional logic for elliptic curve support - handle edge cases like the installed version of OpenSSL not supporting EC, etc.
* I might rework this into a proper app with AJAX requests and handlers, but that's really contingent on me having free time and being bored.


Requirements
---------------
PHPki was developed and tested on RedHat GNU/Linux systems. In theory,
it should work on any modern LAMP stack, but only limited testing has been
done. PHPki requires OpenSSL command line binaries be installed. It has been
confirmed to work on both Apache w/ mod_php and Nginx w/ PHP-FPM. Both PHP 5.3
(Shipped with RedHat 6) and PHP 5.5 have been confirmed to work. We avoid using
any deprecated features of PHP, so it should work with new releases for the foreseeable
future. PHPki also requires the Smarty PHP template engine, which can be installed via composer.


Installation
---------------
Checkout the git repository to your web server. Example:

    cd /var/www/html
    git clone https://github.com/apatik/phpki.git phpki
    chown -R <apache-user>.<apache-group> phpki/

As of v1.0.0, PHPki now uses smarty templates for cleaner code. PHPki has composer support, to make installing smarty as painless as possible.

To install composer:

    curl -sS https://getcomposer.org/installer | php -- --install-dir=bin
This installs it in */bin*. You can change it to install anywhere else you'd prefer, just make sure it's a part of your PATH if you don't want to specify a full path to the file when using it.

To install smarty, from the directory where you placed the PHPki files, run:

    composer.phar install

Additionally, you need to set your timezone in php.ini (usually located at /etc/php.ini), e.g. date.timezone = America/Chicago

Example Apache config to secure the site with OpenLDAP authentication, instead of the built in user support
(you will need to adjust the LDAP URL, Bind DN, and Bind Password to mach your environment, of course):

    <VirtualHost *:80>
        	ServerName www.example.com
    		ServerAlias phpki.example.com
        	DocumentRoot /var/www/html/phpki

    	<Directory "/var/www/html/phpki">
    		AllowOverride None
    		Order allow,deny
    		Allow from all

    		AuthType Basic
    		AuthName "PHPki Access (LDAP)"
    		AuthBasicProvider ldap
    		AuthLDAPURL "ldap://192.168.1.1/OU=people,DC=example,DC=com?uid"
    		AuthLDAPBindDN "UID=apache,OU=services,DC=example,DC=com"
    		AuthLDAPBindPassword "SuperSecurePassword"
    		AuthzLDAPAuthoritative Off
    		require valid-user
    	</Directory>
    </VirtualHost>

**User credentials are sent in cleartext, so you are strongly encouraged to enable SSL (you do have a CA now, after all)**

If you intend to use the builtin authentication, make sure "AllowOverride All" is
set in your Apache configuration file. This is necessary because PHPki uses .htaccess
files to implement HTTP authentication and to enforce file access restrictions.
If you must change this Apache setting, don't forget to restart Apache.

Browse to http://www.example.com/phpki and the CA provisioning process will begin.

Once completed, to access the PHPki public content menu, point your browser to your
PHPki installation (i.e. http://www.example.com/phpki/).  To access the
PHPki certificate management menu, point your browser to the "ca"
directory under your PHPki installation (i.e. https://www.example.com/phpki/ca/).
You must use SSL (https://) to access the PHPki certificate management
menu if you have secured the application using the secure.sh script and chose to
utilize the built-in user authentication support.


Security & Users
---------------
From a root user shell prompt, run the "secure.sh" shell script in this
directory to set more restrictive Unix file permissions, and to (optionally) create
the Apache .htaccess files which are necessary to force SSL access, HTTP
authentication, and directory access restrictions.  If you don't do this
you will likely be extremely screwed!  Don't say you weren't warned. You can skip
the built-in authentication part of the script if using another authentication method (like LDAP),
but you should still run the script, as it will set proper permissions on the files.

The secure.sh script will attempt to create a file for your user list
and passwords.  If it fails in that attempt, you will have to use Apache's
htpasswd utility to manually create a "phpkipasswd" file in the location
you specified during setup.

	htpasswd -cm /var/www/phpkipasswd username

Normal users may only manage the certificates they create.  Administrators
can manage all certificates.  The default administrator account is
"pkiadmin".  The secure.sh script will attempt to add this user to your
phpkipasswd file when it is first created.  Other users can be made
administrators by carefully editing the $PHPki_admins assignment in
config/config.php under your certificate store directory.

You may add additional users and change passwords using your browser after
you have successfully installed PHPki and created your phpkipasswd file with
at least one user. Point your browser to http://www.example.com/phpki/admin/.

External authentication methods cannot be managed through PHPki.


Upgrading
---------------
Install and configure as if it were a first time installation (see above).
Be sure to specify the same root certificate password and user password file
location you used with the previous version.

From the old installation, copy all certificates, crls, and user defaults
to the store directory specified during setup.

	rm -fr store_directory/CA
	cp -v --archive oldphpki/CA   store_directory
	cp -v --archive oldphpki/config/user-*.php  newphpki/ca/config

These upgrade instructions have not been thoroughly tested and may be
incomplete.  Please be sure to fully backup your old PHPki installation before
upgrading.


CONTACT:
Post all correspondence to the GitHub project page
https://github.com/apatik/phpki
