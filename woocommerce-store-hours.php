<?php
/*
Plugin Name: GR WooCommerce Store Hours
Plugin URI: https://github.com/GrandRapidsWeb/WooCommerce-Store-Hours
Description: Only allows orders to be made while the store is open.
Version: 0.9
Author: John Wierenga
Author URI: http://grandrapidsweb.com

/*  Copyright 2016 John Wierenga (email : johnewierenga@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/      

require_once( 'BFIGitHubPluginUploader.php' );
if ( is_admin() ) {
    new BFIGitHubPluginUpdater( __FILE__, 'grandrapidsweb', "WooCommerce-Store-Hours" );
}
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
add_action('init','store_open');
function store_open(){
date_default_timezone_set('America/Chicago');
$blogday = jddayofweek ( cal_to_jd(CAL_GREGORIAN, date("m"),date("d"), date("Y")) , 1 ); 
$bloghour = date('H');
$blogminute = date('i');
 if ($blogday=="Sunday" && $bloghour  > 15 && $bloghour  < 21 && $blogminute  > 14 && $blogminute  < 46) {
    add_action('woo_content_before', 'store_open_msg', 40);
 
}
if ($blogday=="Friday" && $bloghour  > 15 && $bloghour  < 21 && $blogminute  > 14 && $blogminute  < 46) {
   add_action('woo_content_before', 'store_open_msg', 40);
 
}
else {
  close_store();
}

}
if ( is_plugin_active( 'gr-pause-woocommerce/gr-pause-woocommerce.php' ) ) {
 close_store();	
}

function close_store(){
	remove_action( 'woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );
	remove_action( 'woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30 );
	remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );
	remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
	/*New Button*/
	add_action( 'woocommerce_simple_add_to_cart', 'store_replace_btn', 30 );
		add_action( 'woocommerce_variable_add_to_cart', 'store_replace_btn', 30 );
	add_action( 'woocommerce_proceed_to_checkout', 'store_replace_btn', 20 );
	add_action( 'woocommerce_checkout_order_review', 'store_replace_btn', 20 );	
	/*End New Button*/
		add_action('woo_content_before', 'store_msg', 40);
}
function store_msg(){
		if ((is_woocommerce())||(is_cart())||(is_checkout())){
echo '<div class="gr-store-close-msg">Sorry! Northern Lights Poutine & Deli is not accepting orders at this time. We are either at a catering event or this is outside of our initial operating hours. Thanks for checking us out and we hope you\'ll check us out again soon!" -- Mountie Moose</div>';
}
}
function store_replace_btn(){
	echo 'Come back when Northen Lights Deli is open to make a purchase';
}
?>

