<?php
/**
 * Plugin Name: Simple Wistia Embed
 * Plugin URI: http://www.bringyourowndesign.net/plugins/simple-wistia-embed
 * Description: Allows for the simplest Wistia video embeds. Simply paste the URL and it works!
 * Version: 1.0
 * Author: Christopher Smith
 * Author URI: http://www.bringyourowndesign.net/
 * License: GPL2
 */

defined( 'ABSPATH' ) or die( 'No! Bad script kiddie! *swats with newpaper*' );

wp_oembed_add_provider( '/https?:\/\/(.+)?(wistia.com|wi.st)\/.*/', 'http://fast.wistia.net/oembed', true );
