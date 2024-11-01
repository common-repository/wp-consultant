=== WP Consultant ===
Contributors: Nordway
Donate link: http://webstydio.ru/
Tags: contact, contact form, email, integration, javascript, jquery
Requires at least: 2.8.x
Tested up to: 3.4
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

WP Consultant allows you to online communicate with the user in your WordPress weblog.

== Description ==

WP Consultant - online consultant. This small plug-in is suitable for live communication with your users or buyers.

* Communicate with users going from the admin panel
* If the consultant is offline, the user can send your questions to consultant's e-mail
* Notification consultant about unread messages in the admin panel with sound signal
* Photo for consultant

== Installation ==

To use WP Consultant, you will need:

* FTP or SFTP access to your web host

= New Installations =

1.	Download the WP Consultant archive in zip or gzipped tar format and
	extract the files on your computer. 

2.	Create a new directory named `wp-consultant` in the `wp-content/plugins`
	directory of your WordPress installation. Use an FTP or SFTP client to
	upload the contents of your WP Consultant archive to the new directory
	that you just created on your web host.
	
3.	Before activating the plugin should be set to access the folder `wp-consultant` - 0775.

4.	Log in to the WordPress Dashboard and activate the WP Consultant plugin.

5.	At will. Return the old access to the folder `wp-consultant`.

6.	In the current theme template after <body> write code:
	`<?php require_once("consult_form.php"); ?>`

5.	Enter email consultant on the option page.

== Frequently Asked Questions ==

= Q: How do I add photo by consultant? =
R: Go to the options plug-in and select file. Recommended image formats - gif, jpg, png, jpeg, GIF, JPG, PNG, JPEG. Recommended sizes - 36 x 36 px.

== Screenshots ==

1. Сonsultant offline
2. Сonsultant online
3. The consultant said
4. Dialogs consultant in the admin panel

== Changelog ==

= 1.0 =
Plugin release.

== Upgrade Notice ==

= 1.0 =
Plugin release.

== License ==

The WP Consultant plugin is copyright © 2012 with [GNU General Public License][] by Nordway. 

This program is free software; you can redistribute it and/or modify it under
the terms of the [GNU General Public License][] as published by the Free
Software Foundation; either version 2 of the License, or (at your option) any
later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE. See the GNU General Public License for more details.

[GNU General Public License]: http://www.gnu.org/copyleft/gpl.html