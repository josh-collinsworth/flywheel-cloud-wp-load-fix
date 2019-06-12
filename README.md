# Flywheel Cloud wp-load.php Fix

This is a simple WordPress plugin for Flywheel Cloud sites to automatically fix (careless) code in themes and plugins that assumes the `wp-load.php` file will be in the site root when it actually is located elsewhere.

## Installation:

* Download the plugin zip file [from here](https://github.com/josh-collinsworth/flywheel-cloud-wp-load-fix/archive/master.zip) (or the dropdown menu on this plugin's GitHub repo)

* Navigate to your site's plugin page in wp-admin

* Click "add new," then "upload plugin." Upload the plugin .zip file and activate it.

## Details:

The plugin works by adding a "fake" `wp-load.php` file to the site root on activation. This file loads wp-config.php from the site root, then uses `ABSPATH` to require (once) the _real_ `wp-load.php` file. That way, trying to include or load `wp-load.php` from the site root will be exactly the same as loading it in from the correct server path.

The plugin also removes the fake `wp-load.php` file on deactivation and uninstallation, and will deactivate itself if it detects it is running on a non-Flywheel Cloud site.

The plugin is only intended for Flywheel Cloud sites, but with slight modification to the below conditional, it could work on any platform where the `wp-load.php` file is located somewhere other than the site root (and where the `ABSPATH` constant points to that same directory):

```
if ( !defined( 'FLYWHEEL_DEFAULT_PROTOCOL' ) || !file_exists( $_SERVER['DOCUMENT_ROOT'] . '/.wordpress/wp-load.php' ) ) {
//Plugin deactivation code here
}
```
