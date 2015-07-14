Description
===========

Server Side Google Analytics (SSGA) is a simple PHP 5 class, which allows to track server-side events and data within Google Analytics.

Drop-in solution for WordPress plugins (uses the WP HTTP API if available).

Usage
-----

Google Analytics Server Side can be used simply in the following manner:


Easy:

	ssga_track( 'UA-YOUR_NUMBER', 'yoursite.com', '/page.php' )

Advanced:
	
	//create new ssga object
	include 'lib/ss-ga.class.php';
	$ssga = new ssga( 'UA-YOUR_NUMBER', 'yoursite.com' );

	//Set a pageview
	$ssga->set_page( '/page.php' );
	$ssga->set_page_title( 'Page Title' );

	// Send
	$ssga->send();
	$ssga->reset();

Set an event (based on http://code.google.com/apis/analytics/docs/tracking/eventTrackerGuide.html) 
	
	//$ssga as created above
	$ssga->set_event( 'Feed', 'Categories', $label, $value );
	$ssga->send();

Ecommerce tracking (update and test by @nczz)
	
	$ssga_step1 = new ssga( 'UA-12345678-1','domain.tw' );
	//$transaction_id, $affiliation, $total, $tax, $shipping, $city, $region, $country
	$ssga_step1->send_transaction("20159527001", "MXP", 280, 0, 80,"Taiwan", "", "TW");
	
	$ssga_step2 = new ssga( 'UA-12345678-1','domain.tw' );
	//$transaction_id, $sku, $product_name, $variation, $unit_price, $quantity
	$ssga_step2->send_item("20159527001", "1229001", "TEST-PRODUCT", "", 50, 4);
	
