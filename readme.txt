=== bbPress - Sort topic replies ===
Contributors: SandyRig
Tags: bbPress, sort, topic, replies
Requires at least: 3.8
Tested up to: 6.6.1
Stable tag: 1.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Sort topic replies in ascending or descending order for each bbPress Topic.

== Description ==

Sort topic replies in ascending or descending order for each bbPress Topic. You can choose to save setting for each topic, for the whole Forum or set globally for every Topic.

= Features =

* Sort Replies
* Show leading topic(Original Post) at the top of each page

Adds a simple options on the Forum and Topic side bar to choose. If you want the replies to any topic, or all the topics in the selected Forum to be sorted in Ascending or Descending order.

bbPress default sorting order is Ascending order. This means the latest reply shows on the last page. Some people want the latest replies to show on the first pages. If that person is you, this plugin is for you.

Settings priority applies in following order: Topic>Forum>Global
Filter looks for the settings for each topic starting at the topic level. If settings not found at the Topic level, it looks for settings for the Forum the Topic belongs to. If nothing found there either, it applies the global settings. What this means is that you can fully customize sort setting for each forum and topic.

== Installation ==

= From your WordPress dashboard =

1. Visit 'Plugins > Add New'
2. Search for 'bbPress - Sort topic replies'
3. Activate 'bbPress - Sort topic replies' from your Plugins page.
4. Visit 'Forums' and select the Forum you want to have sorted topic replies.
5. On right sidebar, notice the new meta box titled 'Sort Replies'. Choose 'Descending' if you want the latest replies to appear first. 

= From WordPress.org =

1. Download 'bbPress - Sort topic replies'.
2. Upload the 'bbPress - Sort topic replies' directory to your '/wp-content/plugins/' directory, using your favorite method (ftp, sftp, scp, etc...)
3. Activate bbPress from your Plugins page. 
4. Visit 'Forums' and select the Forum you want to have sorted topic replies.
5. On right sidebar, notice the new meta box titled 'Sort Replies'. Choose 'Descending' if you want the latest replies to appear first. 

== Screenshots ==

1. Settings page in settings menu


== Changelog ==

= 1.0.3 =
* Added sort and show lead options to both individual form and topics
* Settings Priority changed to Topic>Forum>Global

= 1.0.2 =
* Global options moved under the settings page
* Global sort option added for topics that have no Parent
* Option added to show lead topic(Original Post) at the top of each page

= 1.0.1 =
* Added a global setting to set the sorting order for all the forums
* Changed a global post variable name to avoid conflicts with other plugins using the same name

= 1.0.0 =
* Sort topic replies on chosen Forums
