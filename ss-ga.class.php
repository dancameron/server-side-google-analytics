<?php
/**
 * Simple Server Side Analytics
 *
 * Server Side Analytics is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * @copyright  Copyright (c) 2009 elements.at New Media Solutions GmbH (http://www.elements.at)
 * @license    http://www.gnu.org/copyleft/gpl.html  GPL
 */

class ssga {
    private $default_type = 'event';

    private $beacon_url = 'http://www.google-analytics.com/__utm.gif'; // Beacon
    private $utmwv = '4.3'; // Analytics version
    private $utmn; // Random number
    private $utmhn; // Host name
    private $utmcs; // Charset
    private $utmul; // Language
    private $utmdt; // Page title
    private $utmhid; // Random number (unique for all session requests)
    private $utmp; // Pageview
    private $utmac; // Google Analytics account
    private $utmt; // Analytics type (event)
    private $utmcc; //Cookie related variables

    private $event_category; // Event category
    private $event_action; // Event action
    private $event_label; // Event label
    private $event_value; // Event value

    private $event_string; // Internal structure of the complete event string


    public function __construct() {
        $this->set_utmhid();
        $this->set_charset();
        $this->set_cookie_vars();
    }

    private function set_cookie_vars() {
        $cookie = rand( 10000000, 99999999 ) . time();
        $random = rand( 1000000000, 2147483647 );
        $today = time();
        $this->utmcc = '__utma=1.'.$cookie.'.'.$random.'.'.$today.'.'.$today.'.15;+__utmz=1.' . $today . '.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none);';
    }

    private function get_cookie_vars() {
        return $this->utmcc;
    }

    public function set_event( $category, $action, $label= '', $value= '' ) {
        $this->event_category = (string) $category;
        $this->event_action = (string) $action;
        if ( $label ) $this->event_label = (string) $label;
        if ( $value ) $this->event_value = (int) intval( $value );

        $event_string = '5(' . $this->event_category . '*' . $this->event_action;

        if ( $label )
            $event_string .= '*' . $this->event_label . ')';
        else
            $event_string .= ')';

        if ( $this->event_value )
            $event_string .= '(' . $this->event_value . ')';

        $this->event_string = $event_string;
    }

    private function get_event_string() {
        return $this->event_string;
    }

    private function set_type( $type= '' ) {
        if ( $type )
            $this->utmt = $type;
        else
            $this->utmt = $this->default_type;
    }

    private function get_type() {
        return $this->utmt;
    }

    public function set_account_id( $account_id ) {
        $this->utmac = $account_id;
    }

    private function get_account_id() {
        return $this->utmac;
    }

    public function set_pageview( $page_view= '' ) {
        $this->utmp = $page_view;
    }

    private function get_pageview() {
        return $this->utmp;
    }

    public function set_version( $version= '' ) {
        if ( $version )
            $this->utmwv = $version;
    }

    private function get_version() {
        return $this->utmwv;
    }

    private function get_uid() {
        return $this->utmhid;
    }

    private function set_utmhid() {
        $this->utmhid = mt_rand( 100000000, 999999999 );
    }

    private function get_random_number() {
        return time().rand( 100000000, 999999999 );
    }

    public function set_charset( $charset= '' ) {
        if ( $charSet )
            $this->utmcs = $charset;
        else
            $this->utmcs = 'UTF-8';
    }

    private function get_charset() {
        return $this->utmcs;
    }

    public function set_lang( $language= '' ) {
        if ( $language )
            $this->utmul = $language;
        else
            $this->utmul = 'en-us';
    }

    public function set_page_title( $page_title= '' ) {
        $this->utmdt = $page_title;
    }

    private function get_page_title() {
        return $this->utmdt;
    }

    private function get_lang() {
        return $this->utmul;
    }

    public function set_host_name( $host_name= '' ) {
        $this->utmhn = $host_name;
    }

    private function get_host_name() {
        return $this->utmhn;
    }

    public function create_page_view() {
        $parameters = array(
            'utmwv' => $this->get_version(),
            'utmn' => $this->get_random_number(),
            'utmhn' => $this->get_host_name(),
            'utmcs' => $this->get_charset(),
            'utmul' => $this->get_lang(),
            'utmdt' => urlencode( $this->get_page_title() ),
            'utmhid' => $this->get_uid(),
            'utmp' => $this->get_pageview(),
            'utmac' => $this->get_account_id(),
            'utmcc' => $this->get_cookie_vars()
        );
        return $this->remote( $this->beacon_url, $parameters );
    }

    public function create_event() {
        $this->set_type();
        $parameters = array(
            'utmwv' => $this->get_version(),
            'utmn' => $this->get_random_number(),
            'utmhn' => $this->get_host_name(),
            'utmt' => 'event',
            'utme' => $this->get_event_string(),
            'utmcs' => $this->get_charset(),
            'utmul' => $this->get_lang(),
            // 'utmdt' => $this->get_page_title(),
            'utmhid' => $this->get_uid(),
            // 'utmp' => $this->get_pageview(),
            'utmac' => $this->get_account_id(),
            'utmcc' => $this->get_cookie_vars()
        );
        return $this->remote( $this->beacon_url, $parameters );
    }

    private function remote( $url, $parameters = array() ) {

        if ( function_exists( 'add_query_arg' ) && function_exists( 'wp_remote_head' ) ) { // Check if this is being used with WordPress, if so use it's excellent HTTP class
           
            $gif_url = add_query_arg( $parameters, $url );
            $response = wp_remote_head( $gif_url );
            return $response;

        } else {
            
            $gif_url = $url . '?' . http_build_query( $parameters );
            // is cURL installed yet?
            if ( !function_exists( 'curl_init' ) ) {
                die( 'Sorry cURL is not installed!' );
            }
            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $gif_url );

            if ( $_SERVER['HTTP_REFERER'] ) curl_setopt( $ch, CURLOPT_REFERER, $_SERVER['HTTP_REFERER'] );
            curl_setopt( $ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0" );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch, CURLOPT_TIMEOUT, 20 );

        
            $output = curl_exec( $ch );

            // Close the cURL resource, and free system resources
            curl_close( $ch );

            return $output;

        }

    }
}
