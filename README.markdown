Description
===========

Server Side Google Analytics (SSGA) is a simple PHP 5 class, which allows to track server-side events and data within Google Analytics.

Drop-in solution for WordPress plugins (uses the WP HTTP API if available).

Usage
-----

Google Analytics Server Side can be used simply in the following manner:

	include 'lib/ssga.class.php';
	$ga = new ssga();

	//Set your Google Analytics key
	$ga->set_account_id( 'UA-YOUR_NUMBER' );

	//Set your charset
	$ga->set_charset( 'UTF-8' );

	//Set your hostname
	$ga->set_host_name( 'yoursite.com' );

	//Set page title
	$ga->set_page_title( 'Page Title' );

	//Set language
	$ga->set_lang( 'en' );

	//Set a pageview
	$ga->set_pageview( '/feed/' );
	$ga->create_page_view();

Set an event (based on http://code.google.com/apis/analytics/docs/tracking/eventTrackerGuide.html) 

	$ga->set_event('Category', 'Action', 'Label', 'Value');
	$ga->create_event();

Notes
-----

Forked from <http://code.google.com/p/serversidegoogleanalytics/>