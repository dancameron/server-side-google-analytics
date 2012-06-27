Description
===========

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
