=== Simple Seller ===
Contributors: tdesposito
Plugin link: http://www.wpsimpleseller.com
Donate link: http://www.wpsimpleseller.com/donate
Tags: ecommerce, e-commerce, shop, paypal
Requires at least: 3.0
Tested up to: 3.0.1
Stable tag: 1.1

Adds a very simple sell-a-product capability to a WP blog.

== Description ==

SimpleSeller adds a new post type to a WP blog, which represents a sellable
item.  Each author ("seller") enters their own payment credentials, and 
posts are linked to that seller's payment gateway with a simple buy-now type
button.  A separate capabilities set allows the blog owner to differentiate
between those allowed to post blog entries and those allowed to sell items.

== Installation ==

1. Download `simpleseller-latest.zip`
1. Back up your database and previous version of Simple Seller (if applicable)
1. Unzip Simple Seller into your WordPress plugins directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Updating ==
1. Download `simpleseller-latest.zip`
1. Back up your database and previous version of Simple Seller
1. Unzip Simple Seller into your WordPress plugins directory

== Setup ==
1. On the Admin page, select "Simple Seller" under Settings.
1. Choose a "slug" for all items for sale.  SimpleSeller will create a page under that slug.
1. Edit the newly created seller page, adding whatever content makes sense. Publish the page.
1. (Optional) Create accounts for your "sellers", or add the "seller" capabilities to existing accounts.  We recommend using the [Capabilities Manager (capsman) plugin] (http://alkivia.org/wordpress/capsman) to manage this.
1. Enter your PayPal credentials on your Profile page.  Each seller must do this.

== Frequently Asked Questions ==

= Why aren't there any questions here? =

Because nobody has asked any yet.

== Screenshots ==
None at this time.

== Upgrade Notice ==
Nothing to report.

== Changelog ==

= 1.1 =
* Fixed the activation-hook-not-firing bug.

= 1.0 =
* First public release

