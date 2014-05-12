<?php
/*
Plugin Name: Promotions: Instant Win
Plugin URI: http://bozuko.com
Description: Instant Win Engine for promotions
Version: 1.0.0
Author: Bozuko
Author URI: http://bozuko.com
License: Proprietary
*/

add_action('promotions/plugins/load', function()
{
  define('PROMOTIONS_INSTANTWIN_DIR', dirname(__FILE__));
  define('PROMOTIONS_INSTANTWIN_URL', plugins_url('/', __FILE__));
  
  Snap_Loader::register( 'PromotionsInstantWin', PROMOTIONS_INSTANTWIN_DIR . '/lib' );
  Snap::inst('PromotionsInstantWin_Plugin');
}, 100);