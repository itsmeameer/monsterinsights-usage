=== Plugin Name ===
Tags: monsterinsights, usage, block, shortcode, api, table
Requires at least: 5.8
Tested up to: 5.9
Stable tag: 5.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A simple plugin that takes dat from an API and shows it in a custom wp-admin page and can be shown on the front-end using a shortcode or a block.

== Description ==

A simple plugin that takes dat from an API and shows it in a custom wp-admin page and can be shown on the front-end using a shortcode or a block.

Shortcode is `[mi-usage-table]`. Title can be disabled by adding a parameter `[mi-usage-table show_title="false"]`. Columns can also be disabled this way. Example of the the shortcode with all its parameters and their default value is: `[mi-usage-table show_title="true" show_col_id="false" show_col_first_name="true" show_col_last_name="true" show_col_email="true" show_col_date="true"]`.

The wp-admin page can be found under wp-admin > Tools -> MonsterInsights Usage

this plugin also has two WPCLI commands.
Use `wp mi-usage last-updated-on` to check when the data was last updated.
Use `wp mi-usage fetch-data` to fetch data from the API.

== Changelog ==

= 1.0 =
* Init.
