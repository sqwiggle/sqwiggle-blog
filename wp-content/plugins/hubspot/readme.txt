=== HubSpot for WordPress ===
Contributors: dcancel, andypitre, aacook
Tags: analytics, hubspot, tracking code, feedburner, action, call to action, team, about, contact, form, leads, api, shortcode, widget, dashboard, post, admin, sidebar, twitter, page, inbound, business, marketing, google map, call to action, cta, ad manager
Requires at least: 3.0
Tested up to: 3.5
Stable tag: 1.8.7

Integrate your WordPress blog or website with HubSpot

== Description ==

The HubSpot for WordPress plugin allows existing HubSpot customers  to integrate their existing WordPress blogs and websites with HubSpot. 

= Not a HubSpot Customer? =
Looking for an easy way to manage your marketing on WordPress? [Try LeadIn](http://leadin.com).

= Features =

* Easily insert the HubSpot tracking script into your WordPress site
* Track page and blog post analytics in HubSpot
* View your HubSpot stats and HubSpot dashboard right within WordPress
* Automatically publish new blog posts to social media accounts via HubSpot

Note: HubSpot collects usage information about this plugin so that we can better serve our customers and know what features to add. By installing and activating the HubSpot for WordPress plugin you agree to these terms.

== Installation ==

This section describes how to install the plugin and get it working.

= Installing the Plugin =

*(using the Wordpress Admin Console)*

1. From your dashboard, click on "Plugins" in the left sidebar
1. Add a new plugin
1. Search for "HubSpot"
1. Install "HubSpot for WordPress"
1. Once Installed, click on the HubSpot plugin in your sidebar and go to "settings"
1. Enter your Hub ID and authorize the plugin to access your HubSpot account (your Hub ID can be found with the product version at the bottom of your HubSpot Dashboard. Once you've entered it into the plugin settings, click the Click here to authenticate button. You'll be asked to login to HubSpot and give access).

http://help.hubspot.com/articles/How_To_Doc/how-to-install-hubspot-javascript-tracking-code-on-wordpress

*(manually via FTP)*

1. Delete any existing 'hubspot' folder from the '/wp-content/plugins/' directory
1. Upload the 'hubspot' folder to the '/wp-content/plugins/' directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Once Installed, click on the HubSpot plugin in your sidebar and go to "settings"
1. Enter your Hub ID and authorize the plugin to access your HubSpot account (your Hub ID can be found with the product version at the bottom of your HubSpot Dashboard. Once you've entered it into the plugin settings, click the Click here to authenticate button. You'll be asked to login to HubSpot and give access).

== Frequently Asked Questions ==

= Can I run this plugin together with a Google Analytics plugin? =

Yes, definitely. Go for it!

= Do you have to be a HubSpot client to use this plugin? =

For most of the functionality, yes. Some things (Like the Call to Action manager, some widgets and some shortcodes) work without being a HubSpot customer, but the really good stuff (like Lead Tracking and Analytics) require a HubSpot account.

= How do you monitor in analytics how the call to action lead to a goal? =

Right now, there is not a clear path from a specific call to action to a specific Lead. You can look at how many leads you have received from a certain form, against the number of clicks that call to action has had, and find out how successful a specific Call to Action has been at generating Leads.

= Which version of HubSpot do we have to be using in order to use plugin? =

To use the Analytics integration, you need a version of HubSpot that supports the external site tracking code, which includes Professional and Enterprise.

= Can they be coded? =

I think you're asking if you can use HTML in Calls to Action. The answer is yes :) It uses the default WordPress editor,

= What steps we need to take if we already have the tracking code and would like to use this new plugin? =

If you're already using HubSpot's tracking code, you should remove the original tracking code you added before adding this plugin. Otherwise, you will wind up with visits being counted twice, as two copies of the JavaScript are installed.

= What about validation errors, will it still maintain my WP theme? =

The plugin uses minimum CSS and inserts valid HTML when necessary.

= What are the guidelines for when we include CTAs with the plug-in or when we use Hubspot directly? =

If I were in your position, I would always use CTAs, which point to Pages on my WordPress site. I'd use HubSpot Exported HTML Forms on those pages. That way, you have a smooth consistent experience on your site,

= Can you set up categories for the Call to Action feature so that you can control which forms are shown versus a free for all of all forms? =

You can choose which CTAs to randomly rotate through when inserting CTAs into your site.

= Can you use the Hubspot plug in with themes like Headway, Thesis or others? =

Yup! The WordPress plugin is theme-agnostic.

= Can you do image CTAs? =

Definitely! We use the standard WordPress editor, so you can even use anything that is in your WordPress image library for your CTA.

= Do you put the shortcode into the HTML tab or can you put it in the visual tab, too? =

You can put it in either. On the visual editor there is a button to make inserting shortcodes a snap.

= If you have multiple wordpress pages under the same account - that have different social media sites can you use the "follow me" function with different social media site links in the follow me widget? =

The Follow Me block is set up the same as however you configured Follow Me in HubSpot. If you can set up the HubSpot Follow Me block to work the way that you'd like, it will work here as well.

= Is there any written information as a guide? =

There is written documentation inside of the plugin, which you can access by clicking on "Help" in the top right of any page.

= Can I use the subscriber Form without Feedburner, which I can't use because I have various feeds =

Unfortunately no - This Subscriber widget is a frontend to Feedburner's interface specifically.

= Where do I find my feedburner feed? =

Your feedburner feed will look something like http://feeds.feedburner.com/myfeedid. Go to http://feedburner.google.com and right-click on the RSS icon next to the Feed Title and click 'Copy Link Address'. You can also just left-click on the RSS icon next to the Feed Title and copy the URL in the addressbar on the next page.

= What are shortcodes? =

Shortcodes are small bits of code that make the creation of advanced HTML elements easy. The HubSpot WordPress plugin uses shortcodes to display contact info, [hs_contact], and team info, [hs_team], to make inserting and managing the content associated with these pages effortless.

= How do I insert the 'HubSpot Follow Me' buttons into my sidebar? =

Make sure you have input the proper HubSpot Hub ID on the HubSpot WordPress plugin configuration page. You can then use the widget under Appearance->Widgets titled "HubSpot: Follow Widget".

= How does the HubSpot WordPress plugin forward to my feedburner? =

The plugin forwards all requests to http://www.yoursite.com/feed with a 302 redirect. The plugin also replaces all links with your Feedburner address.

= How do I change the names displayed on my Team Page? =

Go to the profile page of the name you want to change. Make sure you filled out your first and last name, then select the full name you would like to display for 'Display name publicly as'.

= What are Actions? =

An action is a request for your reader to do something. The HubSpot plugin comes with a Call to Action manager that allows you to create calls to action. You can then use the [hs-action] shortcode or sidebar widget to randomly display your actions. The manager keeps track of clicks, impressions and CTR.

= Does HubSpot collect my information? =

HubSpot collects usage information about this plugin, so that we can better serve our customers and know what features to add.

== Screenshots ==

1. Finding the HubSpot plugin in your sidebar
2. Setting up the Hubspot plugin

== Changelog ==
= 1.8.7 =
* Updated the HubSpot tracking code to point to a new domain. The previous tracking code will continue to work, but we recommend updating to the latest version.

= 1.8.6 =
* Updated the HubSpot tracking code to point to a new domain. The previous tracking code will continue to work, but we recommend updating to the latest version.

= 1.8.5 =
* Fixed bug that was sending wrong page / content types for HubSpot analytics

= 1.8.4 =
* Do not use this version :) Accidental commit including debug code.

= 1.8.2 =
* New error logging, disabled by default

= 1.8.1 =
* Fixes for social media publishing

= 1.8.0 =
* New posts can now be sent to HubSpot for automatic social media publishing (via Blog Auto-publisher).

= 1.7.4 =
* Detailed error messaging for ssl_check()

= 1.7.3 =
* Fixed bug in detection of SSL connectivity issues, use wp_remote_get() instead of cURL

= 1.7.2 =
* Added detection of SSL connectivity issues
* Updated IFRAME URL to prevent a 301

= 1.7.1 =
* Fixed a bug that preventing re-authentication if authentication had failed in the past.
* Fixed a bug that was counting unapproved comments for posts.
* Added better server error logging around authentication.

= 1.7.0 =
* Fixed a bug for users with custom admin directories.

= 1.6.8 =
* Fixed a two bugs that were causing issues for some installs.

= 1.6.6 =
* Blog author, publish date, comment count, and RSS subscribers will now be available in HubSpot Page Performance for all WordPress blog posts.

= 1.6.5 =
* Fixed bug that caused a Wordpress site's homepage to show up as a "Blog Post" within HubSpot Analytics.

= 1.6.4 =
* Removed demo link since that site is now full of malware (hacked)
* Merged change from Jon Bishop that uses a more efficient query pattern
* Change tag used for StatHat to use the HSWP prefix when reporting identified users.

= 1.6.3 =
* Fixed call to custom_admin_style() that was causing a warning to display on the admin screens

= 1.6.2 =
* Version bump to 1.6.2 to force update.
* Converted Tabs to Spaces in files

= 1.6.1 =
* Removed dashboard widget to display HubFeed
* Fixed a minor bug

= 1.6.0 =
* Version bump to 1.6.0

= 1.5.9 =
* Changed Portal ID to Hub ID
* Added new tracking code and identity capture
* Passing correct content type (post or page)

= 1.5.8 =
* Added trim to portal id in analytics

= 1.5.7 =
* Switched to new async tracking code

= 1.5.6 =
* Resetting post wp_query after call to action query
* Cleaned up admin settings area

= 1.5.5 =
* Cleaned up admin
* Cleaned up Team shortcodes

= 1.5.4 =
* Added support for nested shortcodes

= 1.5.3 =
* Fixed duplicate content on calls to action
* Added support for Googlebot to index RSS

= 1.5.2 =
* Fixed losing forms on upgrade

= 1.5.1 =
* Updated readme with new agreement information.

= 1.5 =
* Fixed options resetting with large forms in Shortcode Settings
* Checks email on first install

= 1.4.2 =
* Added fix for shortcode textareas disappearing

= 1.4.1 =
* Removed unfinished new feature

= 1.4 =
* HubFeed now displays data using jQuery in dashboard widget
* Tested for WordPress 3.2 changes
* Fixed visual editor popup display issues

= 1.3.6 =
* Fixed Call to Action widget
* Added demo link to Readme.txt

= 1.3.5 =
* Added display to hs_contact shortcode to selectively display map, address or both
* Removed unneeded formatting
* Changed support info in admin

= 1.3.4 =
* Fixed glitch in Call to Action widgets
* Added two new widgets to display Grader badges

= 1.3.3 =
* Fixed tracker bug

= 1.3.2 =
* Add customer usage tracking

= 1.3.1 =
* Modified notice class so I can add notices on the fly
* Form validation for the main settings page
* Fixed widget and shortcode button not displaying all CTAs
* Filter admin from CTA impressions

= 1.3 =
* Added button with pop-up to visual editor to make inserting shortcodes easier
* Changed call to action notices
* Can select which team members to display
* Can now add custom maps on pages/posts
* Changed contextual help to reflect new features
* New screenshot and FAQ

= 1.2.3 =
* Fixed some privilege problems
* Worked on sortable columns for custom post type
* Actions shortcode/widget can now display specific actions

= 1.2.2 =
* Fix stylesheet

= 1.2.1 =
* Fix contact shortcode display
* Add notice class to manage WordPress notices
* Remove 'Save Changes' button from dashboard
* Upate Readme description
* Change 'Actions' post type to 'Calls to Action'

= 1.2 =
* Add dashboard widget to display HubFeed
* Support multiple Custom HTML Forms
* Update contextual help
* Cleaned up errors in actions
* Updated Installation instructions

= 1.1.1 =
* Add css to help HubSpot forms display better in assorted themes

= 1.1 =
* Fix typos in admin area
* Add Custom Form HTML to Leads section of Shorctodes page
* Filter bots from impressions/clicks count
* Added Subscribe by Email widget
* No longer using Google Javascript API Using simple iFrame embed.
* Increased height of dashboard and stats pages

= 1.0.3 =
* Fix shortcodes to output within content
* Fix line/paragraph breaks on team page in bio
* Optimize team page processing and output
* Moved conditional filter/action loading to individual classes
* Fixed widgets, can now hide title for actions

= 1.0.2 =
* Only load admin class in admin area
* Added http:// to lead API form helper link in admin area
* Code cleanup
* Hide action settings when WP version less than 3.0

= 1.0.1 =
* Fixed typos

= 1.0 =
* Added Call To Action section
* Created [hs_team], [hs_contact], [hs_form] and [hs_action] shortcodes with settings area
* Created new admin area with HubSpot branding, support area and feed module
* Updated code
* Checked for compatibility with 2.9+

= .53 =
* Fixed occasional error message for people with unusual apache configurations.

= .52 =
* Fixed occasional error message for people with unusual apache configurations.
* Tested for compatibility with WordPress 3.0 RC1.

= 0.51 =
* Fixed occasional error message displayed on dashboard page.

= 0.5 =
* Tracking code inserted. No preferences or options at this time.

== Upgrade Notice ==

= 1.3.6 =
Fixed CTA widgets displaying wrong Actions

= 1.3 =
Button on visual editor makes adding HubSpot shortcodes easier than ever. New settings also let you selectively display team members and calls to action.

= 1.2 =
Support for multiple HubSpot forms and added HubFeed as a dashboard widget
