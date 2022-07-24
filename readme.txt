=== Awebsome! Comment Author Mail Validation ===
Contributors: awebsome, raulillana
Tags: awebsome, comments, authors, mail, validation, verification, spam
Requires at least: 3.0
Tested up to: 3.1
Stable tag: 2.1
License: GPLv2
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=P76XLAP8QH4FW

Adds a new comment validation method in the "Before a comment appears" Discussion subsection panel.

== Description ==

Adds a new comment validation method in the "Before a comment appears" Discussion subsection panel.

= Features =

* Avoided comment automattic approvals
* Adds a new highlighted option to "Before a comment appears" Discussion section subpanel
* Verification email sending every time a not registered user posts a comment
* Link checkback required to publish the comment and it wasn't flagged as spam
* Included WP nonces security to verification email link

= How it works? =

1. Not registered user posts a comment
2. Not registered user receives an email with a validation link
3. Not registered user follows validation link
4. Comment is published

== Installation ==

Go easy!

1. Upload!
2. Activate option in **Settings** > **Discussion** > **Before a comment appears** and save changes.
3. Enjoy!

== Frequently Asked Questions ==

= Can I use this "Before a comment appears" validation method along with the others? =

Integration with other validation methods is not developed yet. Next release? :)

= How can I change the mail texts? =

Actually there are 2 options:

1. Edit or create the **xx_XX.po** file for your WP active language and generate a **xx_XX.mo**.
2. Also you can use [WPML](http://wpml.org "WPML Multilingual CMS") plugin to translate all your plugins like themes. But remember to delete the languages folder. :)

== Screenshots ==

Not needed, imho. ;)

== Changelog ==

= 2.1 =

* Token generation revamped to fit WP API nonces functions
* Fixed validation link generation to support non-active permalinks structure
* Improved pre_comment_approved hook callback function (kudos xaviersarrate)
* Some readme.txt improvements

= 2.0.1 =

* Solved SVN newbie problems ;P

= 2.0 =

* Validation interception revamped
* Some functions advanced improvements
* Avoided functions WP already handles in API
* Fixed deactivation/uninstall options cleanup
* Added NONCE_KEY to token generator and validator as a random seed for security (kudos xaviersarrate)
* phpDoc improvements to functions
* Changed readme.txt descriptions
* POT regeneration
* Deleted en_EN translation (main pot lang)

= 1.3 =

* First plugin SVN update :)
* Internationalization problems solved

= 1.2 =

* First commiting to the wp-plugins SVN :)
* Internationalization + POT Generation
* Added es_ES translation
* Added es_CA translation
* Added en_EN translation
* Avoid mail validation for registered users

= 1.1 =

* Added nice PHPDOC compatible documentation
* Added JavaScript for admin field moving
* Added CSS to highlight CAMV fields
* General code clean up

= 1.0 =

* Born with basic functionality and w/o documentation!

== Upgrade Notice ==

= 2.1 =

Done important improvements and more testing. Update required!

= 2.0.1 =

Not code related improvements done. Update anyway, please!

= 2.0 =

Done important improvements and hard testing. Update required!

= 1.3 =

Not code related improvements done.

= 1.2 =

Improvements done.

= 1.1 =

Improvements done.

== ToDo ==

* Integrate deeper with other validation methods
* Add mail customization options UI (headers, content-type, attachments, html...)
* Pretty permalinks URL integration (...permalink/nonce/cid)
