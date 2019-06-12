# Flywheel Cloud wp-load.php Fix

This is a simple WordPress plugin for Flywheel Cloud sites to automatically fix (careless) code in themes and plugins that assumes the `wp-load.php` file will be in the site root when it isn't (such as on Flywheel Cloud sites).

The plugin works by adding a "fake" `wp-load.php` file to the site root on activation. This file loads wp-config.php from the site root, then uses `ABSPATH` to require (once) the _real_ `wp-load.php` file. That way, trying to include or load `wp-load.php` from the site root will be exactly the same as loading it in from the correct server path.

The plugin also removes the fake `wp-load.php` file on deactivation and uninstallation, and will deactivate itself if it detects it is running on a non-Flywheel Cloud site.
