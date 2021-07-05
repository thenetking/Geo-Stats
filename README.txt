=== Plugin Name ===
Contributors: thenetking
Donate link: micahpress.com/geo-stats
Tags: US Census data
Requires at least: 
Tested up to: 
Stable tag: 
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin was written as sample code that I barely finished. I may continue/finish it one day.

Give the user an additional field to add their postal code to their profile which allows for the US Census API to attach data to a user's metadata.

== Description ==

Adds settings page accessible from Admin -> Settings menu. Provides field to add a US Census API key which can be obtained for free from https://api.census.gov/data/key_signup.html usually with a rapid response.

Also allows option to disable either Median Income or Population data from being returned from the API and displayed on the users' information.

Once installed and enabled, the user's profile has a field added for Postal Code and State. When these are provided, the Census API can be queried which returns the data and attaches it to the user's metadata.

The state is only required for this because otherwise the postal code needs to somehow know which state it is from.

This code is only set up to work for West Virginia. An array of states and a bit of code still needs completed for it to work with other states. It could potentially be just as easy to query an additional API with the postal code in order to return the state.

This code is built on top of a WordPress Plugin Boilerplate which can be downloaded from https://wppb.me/

== Installation ==

Copy geo-stats to your plugin folder. Active plugin on the WordPress plugins page. Navigate to Settings in the Admin menu and go to User Geo Stats.

Request a key from the US Census at the link provided. Activate your key in the email they send(you may need to wait a few minutes for activation to work). Add the API key to the setting page and enable income and population.

Edit your user profile. At the bottom are two new fields, Postal Code and State. Enter a 5 digit postal code and select a state(only WV is hooked up ATM) and save your profile.

Visit the main Users page in WordPress admin and you should see 3 additional columns: Postal code, Postal income and Postal population.

== Changelog ==
