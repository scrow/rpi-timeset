# rpi-timeset README

## Introduction

The `rpi-timeset` utility is a simple PHP script which allows quick update of a remote system clock via the web browser.  The system time is synchronized to the remote client system time obtained using Javascript.  Originally intended for use on the Raspberry Pi, this script could be easily adapted to other hardware platforms as desired.

The mostly likely use case would be an RPi which has been configured to failover to a peer-to-peer or ad hoc network connection if wireless LAN association fails at boot.  In this instance an Internet connection would be unavailable so the RPi could not sync via `ntp`.  However, a user could utilize this utility to adjust the system clock to match that of a tablet, smartphone, laptop, or other device on the ad hoc network which may have a better idea of the current time.

## Requirements

This documentation assumes the Raspbian Stretch operating system.  Commands and prerequisites should be similar on other platforms.

The only dependencies are a web server and PHP, which can be obtained with:

	sudo apt-get update
	sudo apt-get install apache2 php

## Installation

To install this script, simply copy it to a folder accessible from your web browser.  Creation of a unique folder for this utility is recommended.  For Raspbian Stretch:

	sudo mkdir /var/www/html/rpi-timeset
	sudo chown www-data:www-data /var/www/html/rpi-timeset

Place the `index.php` file in the desired folder.  If necessary, this file can be renamed and the script should still function normally.

Then, grant your web server `sudo` permissions for `/bin/date`.  On Raspbian Stretch, use `sudo visudo` to add a line like this:

	www-data  ALL=NOPASSWD:  /bin/date *

Change `www-data` to your web server process owner name.

## Usage

To run this script simply access the file on the web browser.  The URL will vary depending on the path chosen during installation and the method used to connect (direct vs. SSH tunnel).  Using the paths in this documentation, the URL might be:

	http://raspberrypi.local/rpi-timeset/
	http://192.168.1.100/rpi-timeset/
	http://127.0.0.1:8000/rpi-timeset/

The script will display the before-and-after time stamps and a success/fail message.

## Securing the Installation

The amount of security that needs to be applied is up to the discretion of the administrator of the target system.  At a minimum, the installation should be password protected.  Assuming Apache web server was selected:

	cd /var/www/html/rpi-timeset   # or other install location
	htpasswd -c .htpasswd username

and then create an `.htaccess` file in the installation folder:

	AuthUserFile .htpasswd
	Require valid-user

You may also wish to configure the web server to connect only via the loopback interface, which will require client connections over an SSH tunnel.  To configure Apache for this, modify `/etc/apache2/ports.conf`:

    Listen 127.0.0.1:80
    
    <IfModule ssl_module>
        Listen 127.0.0.1:443
    </IfModule>
    
    <IfModule mod_gnutls.c>
        Listen 127.0.0.1:443
    </IfModule>

From the client end, you would `ssh username@domain.com -L 8000:127.0.0.1:80` and use your web browser to connect to `http://127.0.0.1:8000`.

## Extending Functionality

If additional commands should be run after updating the time, place those commands in a script called `postrun.sh` in the same folder and do a `chmod +x postrun.sh` to make it executable.

## Feedback

Comments, questions, and bug reports can be directed to the [GitHub project page](http://github.com/scrow/rpi-timeset).  The author may be contacted directly via [email](mailto:steve@stevecrow.net).
