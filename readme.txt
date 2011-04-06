=== Plugin Name ===
Contributors: stevevls
Tags: translate, translation, crowdsourcing
Requires at least: 2.9
Tested up to: 2.9
Stable tag: 1.1.5

The only translation plugin that provides human translation of your blog content using community translation
(crowdsourcing).

== Description ==

The GTS Translation WordPress plugin is the only free solution for human translation of blogs. And since the
translated content is cached in your Wordpress database, it can be SEO optimized and indexed by search engines.

Your blog content is initially translated by our translation server. The content is then post-edited by human translators
through our online editing system. You can use the GTS community of translators, or you can assign the post-editing to
your own community of translators to maintain complete control of the process.

After you install the GTS Translation plugin, your blog home page is translated, as well as all of the blog posts that
appear on the home page. A translation widget is available for any widget-enabled WordPress theme. From this point
onward, each time a new blog post is published it is automatically translated into the selected languages. You can
choose to publish the translated content immediately (raw MT output), or to only publish the translated content after it
has been approved by a moderator.

Current release supports English to French, Italian, German, Spanish, and Russian.

== Installation ==

1. Upload the plugin directory to the `/wp-content/plugins/` directory or install via WordPress' automated install feature.
1. Activate the GTS plugin through the 'Plugins' menu in your WordPress blog's admin area.
1. Register with GTS and follow the workflow to configure your blog and prepare it for translation.
1. *(Recommended)* Add the GTS Language Selector widget to one of your theme's [widget panels](http://codex.wordpress.org/Appearance_Widgets_SubPanel).
1. *(Optional)* Designate a virtual host for each language in the GTS Settings panel (e.g. fr.myblog.com).  First, be sure to speak to your system administrator about configuring virtual hosts.

== Frequently Asked Questions ==

= What's so special about our plugin? =
Most of the other translation widgets and plugins, like those that are powered by Google and Microsoft, provide on-the-fly
translation of your blog content. This has two negative aspects: first, the translation that your reader sees is raw machine
translation which is known to be of questionable quality.  Second, the content is not indexed by the search engines, which
means that readers can not find your translated content on search engines and you lose out on valuable search engine traffic.

The GTS plugin provides human translation quality which is far superior than the other plugins. And the translated content
is cached on the same server that your blog is hosted on. Search engines will index your translated content and your blog
will come up on keyword searches. This will drive more traffic to your blog.

= How is the translation done? =
Translation is a 2-step process. In the first stage, your content is translated by our own machine translation server. Then,
the translated content is edited by human translators who review the machine translation and make the necessary linguistic
corrections. After the content is edited, a moderator reviews the content again to verify that it meets quality standards.
Once the moderator approves the translation, the post goes live on your blog.

= Who does the translation? =
You have two choices: you can use your own people to do the human editing, or you can use the GTS community.

If you have associates that are qualified to edit the translation, or if you yourself are proficient in another language
and can edit your own translation, then you can control the process yourself through the GTS Plugin Admin panel. This Admin
panel allows you to assign Editor and Moderator priviledges to your own network of people so you can keep the process in-house.

Or, you can have the work done by the GTS community which is a worldwide network of hundreds of translators and language
experts.


= Do you outsource the machine translation? =
The translation server used to do the machine translation is a private server which is owned by GTS. It does not use any
of the public machine translations engines such as Google or Microsoft. This means that your content is secure and will
not be shared with other companies or bloggers.

= Are comments translated? =
No. Comments are are passed through as-is.


= Which languages are supported? =
* Input: English
* Output: French, German, Italian, Spanish, and Russian


= What URLs will the translated blogs have? =

By default, the translated blogs will be accessed in subfolders of your main blog URL. So for example, the French language
blog will be accessed via http://blogname/language/fr. The Spanish language blog will be accessed via http://blogname/language/es.
And so forth.

If you want to assign your foreign language blogs to a subdomain or a TLD (Top Level Domain, like blogname.fr), you can do
so by making the apporpriate configuration settings on the GTS Plugin area of your Wordpress Admin panel.

= Which character encodings are supported? =
UTF-8 *only*.  Note that WordPress is configured to use UTF-8 by default.  If you require another character encoding, please send a feature request.


= How does the plugin work? =

When you publish a post in WordPress, our plugin will send that content onward to GTS.  When we receive it, the content
will be immediately passed through our machine translation software.  Then, it will become available for our crowdsourced
editors to begin the post-editing process.  They will improve the quality of the translated content, after which, crowdsourced
moderators will review the improvements.  When the translated text is ready for prime time, one of the moderators will approve
it, and the human-translated post will be sent back to your blog.

Additionally, when you first sign up with us, our system will pull in some additional information.  In order to get your
translated blog looking good from the get-go, we do an initial translation of all your blog's tags, categories, pages, and
the posts that appear on your blog's home page.


= Where is the content stored? =
Before the translated content is approved, it is stored on a staging area on our own secure servers.

Once the translated content is approved by a moderator, it is stored on your own server in your WordPress database.
We'll also keep a copy in our database in case the moderators or editors decide to make more changes.


= When I switch my blog to Russian, all I see are question marks...what gives?
This happens when the data is stored in MySQL with the wrong character encoding.  Execute the following SQL (being sure to
replace 'wp_' with the prefix specified in wp-config.php if you've changed that value):

ALTER TABLE wp_gts_translated_options CONVERT TO CHARACTER SET 'utf8';
ALTER TABLE wp_gts_translated_posts CONVERT TO CHARACTER SET 'utf8';
ALTER TABLE wp_gts_translated_terms CONVERT TO CHARACTER SET 'utf8';

Then get in touch with us so that we can help you reload your content.


= What is theme translation? =
Your WordPress theme is made up of a series of PHP files called templates.


= My header link to the home page isn't localized properly.  What do I do? =

If you are using theme translation, these links should be handled automatically.  Otherwise, you're probably using the `bloginfo('url')` or `home_url()` function.
Replace `bloginfo('url')` or `echo home_url('/')` with `gts_get_homepage_link()`.

= I translated my theme, but now it's mangled.  What should I do? =

Unfortunately, translating PHP source files correctly is tricky business and doesn't always work correctly.  If you have translated your
templates and want to revert to the originals for your translated blogs, untick the "Use translated templates" checkbox on the GTS
configuration page.

= I translated my theme, but there's still a bunch of English text? =

Like above, it's tough to sort out what should be translated.  Plus, there are probably random English text strings embedded in the PHP
source, function arguments, etc.  In order to get the most out of template translation, try these tips:

* Wherever possible, try to keep your text *outside* of PHP code blocks.
* If you must embed English text in PHP, then surround it with the WP `__` function or its variants e.g. `random_php_method('hello')`
 becomes `random_php_method(__('hello'))`.  IMPORTANT this only works for single-quoted arguments.  recognized functions are:
    * `_e`
    * `translate`
    * `esc_attr__`
    * `esc_attr_e`
    * `esc_html__`
    * `esc_html_e`.

Some english language text comes from deep down in WordPress' code, so you probably won't be able to get 100% of the text, but you should
be able to get the lion's share taken care of.

= I wrote a post with a ton of text, but it never gets translated...what's happening? =

There is a maximum entry size of 256KB...roughly equivalent to 75 pages of single-spaced text.  Have you run into that limit?

= Are there any incompatible plugins? =
Unforunately, yes.  Here is a list of plugins that cause problems with the GTS Plugin:

* ICanLocalize  (inserts invalid HTML into the post body)

This list is a work in progress and may grow as we roll out to more users.


= How secure is the system? =
Please see the Security tab.


== Changelog ==

= 1.1.5 =
* Added support for translation from English to Japanese and Portuguese.
* Fetch supported languages from remote API rather than hard-coding them (allows auto-addition of languages going forward).
* Removed packaged .mo files and implemented logic to automatically fetch them from WP's SVN repository.
* Moved HTML Comment w/ plugin version from widget to <head> section with alt links.
* Added logic to drop indexes when upgrading DB schema as workaround for WP bug.
* Increased language code length in DB schema to support non-standard language codes (e.g. zh-CN, zh-TW).
* WP 2.9.X : compatibility fix for custom taxonomies.

= 1.1.4 =
* Fixed possible PHP Warnings when calling add_language_to_home() function
* Added explicit UTF-8 character set declaration to database table creation script.

= 1.1.3 =
* General support for non-latin languages
* Support for translation from English to Russian
* Improved localization using dynamically-loaded .mo files
* WP 3.1 : category and tag links weren't properly due to iternal changes and admin pages
* WP 3.1 : avoid link rewriting for admin bar links when looking at page with non-source language.

= 1.1.2 =
* Presentation changes to widget
* Localization of home link with options filter on home and siteurl
* Bugfix for 404 on pages with language specific hostname.

= 1.1.1 =
* Support for custom taxonomies.
* Support for static home page.
* Bugfix for English text in excerpt under certain conditions.

= 1.1 =
* First official release!
* Resolve bug where WP table prefix starting with number caused table creation to fail.
* Added remote API calls for activation, deactivation, and uninstall to keep plugin in sync with backend.
* Improve schema migration as plugin version progresses.
* Fix compatibility issues with permalinks consisting of only the post name.
* Filter non-translated content from translated pages
* add <link /> elements to HTML head to link to different language versions of pages.
* Increase minimum WP version to 2.9

= 1.0.b9 =
* Resolve bugs with nested categories and other various bad permalinks as a result of the changes in 1.0.b8.

= 1.0.b8 =
* Remove ID positional parameters from post, tag, and category links so they appear more WP like and to avoid conflicts with other
plugins or .htaccess files that control the permalink format.

= 1.0.b7 =
* Fix widget bug with multi-word category link rewrites.

= 1.0.b6 =
* Support for multi-tag and multi-category URLs
* Resolve conflict between widget and Simply Exclude plugin

= 1.0.b5 =
* Rework splash page and remove Beta Text.
* Bugfix with theme translation iframe callback.

= 1.0.b4 =
* Fix missing image on the widget

= 1.0.b3 =
* Bugfix for incorrect widget behavior triggered by certain themes.
* Minor CSS tweaks to widget

= 1.0.b2 =
* A handful of minor bugfixes.

= 1.0.b1 =
* Initial limited beta release

== Uninstalling ==

Simply click the "Delete" link in the plugin management window.  This will delete all of your translated data, so
please be careful!

== Security ==

While providing high quality translation with our crowdsourced post-editing functionality, it's of the utmost importance
to ensure the security of your blog.  We provide functionality that is out-of-band of the traditional WordPress publishing
cycle.  As such, our plugin adds a hook into your blog that allows GTS to programmatically update the translated content
in your local WordPress database.  We've put a lot of thought into ensuring that our system can use this hook while locking
it against any potential malicious users.

The first step to securing our system is to make sure that it's really *you* and your blog requesting that your blog be
signed up for translation.  First, we only accept registration requests that originate from the WP admin page of the blog
that will be translated.  When the user clicks off of the admin page, a digital fingerprint is created and saved to your
WP database, and then it's sent over an SSL-encrypted channel to our server.  When the registration request reaches our
server, we will open an HTTP connection back to the requesting blog and verify that a) it has our plugin installed, and b)
using an encryption technique called hashing, that the fingerprint matches.  If one of those checks fail, we won't register
 the blog for translation.

Now that we've verified the blog, we'll assign you a Blog ID and an API Access Key.  These will be automatically added to
your configuration panel via the registration panel, plus they will be sent to your administrator email address.  We will
use these two strings for identifying your blog and to ensure that information isn't tampered either en-route to us.  It
will also be used by your blog to verify that translated content posted back to your blog is legitimate.  Again, we use
an encryption technique called hashing.  Whenever your blog sends a translation request to us or we send translated content
back to you, we create a signature (or hash) of that content PLUS the API Access Key (if you're really interested in details,
using the SHA-1 algorithm).  Provided that the API Access Key is kept secret, this technique is extremely secure and resistant
to tampering.  The odds of a malicious person guessing your API key are astronomical : more than 1 in 10^229.  However,
as with any password, security depends on keeping it secret.  If at any time, you think that your key has been compromised,
GTS will deactivate the current key and issue you a new one.

Other measures we take to secure data coming back to your blog include using a whitelist of IP addresses that are allowed
to use the hook for posting translated content and limiting the size of the post to ~250KB so that, even if all other measures
fail, an attacker can't eat up all the memory on your machine.

We have dedicated lots of thought to locking down this system, and we are very confident in the security of this system.
After reading this, we hope you will be too.
