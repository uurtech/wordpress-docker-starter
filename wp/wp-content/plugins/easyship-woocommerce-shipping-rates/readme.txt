=== Easyship WooCommerce Shipping Rates ===
Contributors: goeasyship, sunnycyk, alohachen, paulld, berniechiu
Tags: shipping, shipping rates, shipping price, shipping cost, shipping quotes, free shipping, dynamic shipping, automatic shipping, shipping calculator, calculate shipping cost, easyship, taxes, international shipping, ups, dhl, fedex, post, woocommerce, multiple shipping rates, shipping api, shipping discount, shipping labels, courier calculated shipping
Requires at least: 4.4
Tested up to: 5.4
Stable tag: 0.8.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easyship for WooCommerce saves you time and money on shipping. Access the largest courier network with seamless checkout, automated taxes and duties, label generation and more.

== Description ==

Easyship is a shipping platform that lets all merchants reach customers around the world with low shipping costs and increased conversion rates. So whether you’re sending out 100 shipments a month or 50,000, we have a solution that will fit your needs.
Over 100,000 online retailers trust us to save them time and money with smart shipping solutions. Integrate Easyship with your WooCommerce store now to see how you could streamline your delivery services.

= Ship Better with Easyship for WooCommerce =

* Access pre-negotiated shipping solutions from couriers around the world with just one account, or link your own courier accounts (including Fedex, UPS, DHL and more) and use your own rates
* Offer full transparency with dynamic rates at checkout to improve conversion - customers can choose their preferred shipping option knowing all costs, delivery time, and taxes
* Access 24/7 support so you can give your customers the service they expect
* Compare domestic and international shipping solutions

= Manage your shipments in one place =

* Sync orders and print labels with one click
* Store your product dimensions, category, and weight for faster processing and shipping costs, even with volumetric weights
* Automatically update “fulfilled” orders with tracking numbers and courier names
* Choose preferred solutions based on destinations, product type, or weight and expedite shipping with preset rules
* Maintain control of your finances by downloading past invoices, receipts, and transactions statements
* Monitor your shipments with notifications from your chosen couriers
* Automatically generate domestic return labels

= Ship internationally with confidence =
No other WooCommerce shipping app makes it this easy to reach customers around the world.

* Automatically generate and download ready-to-go shipping documents
* See exact import tax, VAT, GST and other fees upfront so there are no surprises
* Get accurate, complete Customs documentation and avoid issues and delays with your shipments

= Offer your customers total flexibility =

* Flexible shipping options increases conversion, so let your customers choose between the cheapest, fastest or best-value delivery solutions
* Reduce customer complaints and emails by showing clear delivery times
* In-cart settings give you the flexibility to choose prepaid (DDP) or postpaid (DDU) tax and duty - you can even include this as a checkout option

= Give customers a holistic post-purchase brand experience =

* Use branded packing slips to elevate the customer experience when they receive their parcel
* Send emails with your branding to give customers a fully branded journey from purchase to delivery
* Brand your tracking pages so your customers always know exactly what packages they’re tracking, no matter where in the world they are
* Send customers a link and let them track orders in real-time

= Monthly subscriptions that fit your business =

The Easyship app will always be free for new and small businesses, for companies shipping under 100 orders per month - you only pay your shipping costs.

Our subscription plans are based on the volume of shipments processed each month. Get the flexibility needed to scale your logistics and reach customers wherever they are based.

== Installation ==

= Minimum Requirements =

* WordPress 4.4 or greater
* WooCommerce 2.4 or greater
* PHP version 5.3.0 or greater
* PHP OpenSSL Library is required
* PHP CURL Library is required

= Countries supported =

Easyship, the global shipping software, is currently available for stores located in the United States, Hong Kong, Canada, the United Kingdom, Singapore, and Australia. Shipping rates and tax calculations are available worldwide from these origin countries.

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don't need to leave your browser. To do an automatic install of Easyship-WooCommerce-Plugin, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.

In the search field type **Easyship WooCommerce Shipping Rates** and click Search Plugins. Once you’ve found our plugin you can view details about it such as the point release, rating and description. Most importantly of course, you can install it by simply clicking “Install Now”.

= Manual installation =

1. Unzip the files and upload the folder into your plugins folder (/wp-content/plugins/) overwriting older versions if they exist

2. Activate the plugin in your WordPress admin area.

= Set up =

There are 2 ways to integrate with Easyship.

Method 1:

1. After you activate the plugin, in the WooCommerce Setting page, go to the Shipping Tab and choose “Easyship Shipping”.

2. Click "Enable". After few steps, your rates are now available for all your customers!

Method 2:

1. Sign up for free at [www.easyship.com](https://app.easyship.com/signup) and go to [Connect > Add New](https://app.easyship.com/connect) to connect your WooCommerce store. You can then retrieve your Access Token from your store’s page by clicking on “Activate Rates”. This is also the place where you will be able to set all your shipping options and rules.

2. After you activate the plugin, in the WooCommerce Setting page, go to the Shipping Tab and choose “Easyship Shipping”.

3. The plugin is enabled by default after activation. Enter your API Token and save. Your rates are now available for all your customers!


== Frequently Asked Questions ==

= What WordPress version does the plugin support? =

The plugin is tested on WordPress version 4.4 to 5.4.

= What WooCommerce version does the plugin support? =

The plugin is tested on WooCommerce version 2.4 to 4.0.1

For all other questions, please visit [www.easyship.com](https://www.easyship.com)

= The plugin is installed successfully, but the request timed out, what do I do? =

Your `wp-config.php` may have `WP_HTTP_BLOCK_EXTERNAL` set as true. You will need to add `https://*.easyship.com` url to your `WP_ACCESSIBLE_HOSTS` settings in order to allow requests going to the Easyship API server.

== Screenshots ==

1. Share your Easyship access token to activate
2. Install the Easyship plugin
3. Connect your WooCommerce store
4. Integrated rates in your store's checkout
5. Track all your shipments in one place
6. Compare and choose from 250+ shipping solutions
7. Manage your shipping settings, store your product catalog & create shipping rules

== Changelog ==
= 0.8.5 - 2021-03-03 =
* Fix - Wrong declared customs value for rate at checkout

= 0.8.4 - 2020-12-24 =
* Enhance - Send sku for rate at checkout

= 0.8.3 - 2020-09-18 =
* Fix - Send address
* Fix - Add session_write_close

= 0.8.2 - 2020-07-16 =
* Fix - Duplicate session sent

= 0.8.1 - 2020-06-11 =
* Fix - Show rates when disabled
* Fix - Dimensions type error

= 0.8.0 - 2020-05-12 =
* Enhance - Support Multisite and enable button

= 0.7.0 - 2020-04-13 =
* Enhance - Update app description

= 0.6.0 - 2019-12-23 =
* Enhance - Update app description

= 0.5.9 - 2019-07-11 =
* Fix - Some clients experience errors

= 0.5.8 - 2019-07-08 =
* Fix - Cannot manually save token
* Enhance - Update description

= 0.5.6 - 2019-06-20 =
* Enhance - Support newer woocommerce version

= 0.5.5 - 2019-04-23 =
* Enhance - Change default insurance
* Fix - Woocs plugin shipping price compatibility

= 0.5.3 - 2019-02-13 =
* Enhance - Supports Woocs Plugin (Paid Version) with multi-currency checkout

= 0.5.2 - 2019-01-14 =
* Enhance - support meta tag

= 0.5.1 - 2018-08-01 =
* Hotfix - no need to enable if Access Token existed

= 0.5.0 - 2018-07-30 =
* Hotfix - js file

= 0.4.9 - 2018-07-30 =
* Enhance - integrate Easyship from WooCommerce
* NOTE - PHP package: 'php-curl' is required

= 0.4.8 - 2018-07-04 =
* Enhance - send city when requesting rates

= 0.4.7 - 2018-06-14 =
* Enhance - add Easyship into shipping method
* Enhance - remove non-ship items

= 0.4.6 - 2018-06-11 =
* Enhance - send state when requesting rates

= 0.4.5 - 2018-06-11 =
* Hotfix - no rates on cart page

= 0.4.4 - 2018-05-15 =
* Enhance - support discount

= 0.4.3 - 2017-11-23 =
* Enhance - extend timeout

= 0.4.2 - 2017-11-01 =
* Enhance - Links to Settings from installed plugins page

= 0.4.1 - 2017-11-01 =
* Enhance - WooCommerce Currency Switcher support

= 0.4.0 - 2017-9-25 =
* support access token
* support product feature
* remove settings

= 0.2.9 - 2017-7-19 =
* Update Easyship endpoints

= 0.2.8 - 2017-7-6 =
* Enhance - fix WCML cache
* Update Easyship sandbox endpoint

= 0.2.7 - 2017-6-26 =
* Enhance - WooCommerce 3.0 support

= 0.2.6 - 2017-6-6 =
* Enhance - WCML support
* Add Easyship header to API request

= 0.2.5 - 2017-05-22 =
* Enhance - fix warning and update Easyship url

= 0.2.4 - 2017-02-10 =
* Enhance - wording

= 0.2.3 =
* Feature - Easyship shipping method
* Feature - Auto create category
* Feature - Sandbox mode
