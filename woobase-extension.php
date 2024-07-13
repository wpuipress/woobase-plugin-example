<?php
/*
Plugin Name: WooBase Plugin example
Plugin URI: https://woobase.com
Description: This is a demo plugin to show how to hook into to woobase and add custom fields and modify the interface
Version: 1.0.0
Author: woobase
*/

// Prevent direct access to this file
defined("ABSPATH") || exit();

/**
 * Register a custom field to the product endpoint
 *
 * @return void
 */
function woobase_register_images_field()
{
  register_rest_field("product", "woobase_custom_text", [
    "get_callback" => "woobase_get_field",
    "update_callback" => "woobase_update_field",
    "schema" => null,
  ]);
}

/**
 * Returns a custom meta string
 *
 * @return string
 */
function woobase_get_field($object)
{
  $value = get_post_meta($object["id"], "woobase_custom_text", true);
  return esc_html($value);
}

/**
 * Returns a custom meta string
 *
 * @return string
 */
function woobase_update_field($value, $object)
{
  $sanitised = sanitize_text_field($value);
  update_post_meta($object->get_id(), "woobase_custom_text", $sanitised);
}

add_action("rest_api_init", "woobase_register_images_field");

/**
 * Returns a custom meta string
 *
 * @return string
 */
function woobase_load_custom_script()
{
  // Get plugin url
  $url = plugins_url("woobase-plugin-example/");
  wp_enqueue_script("woobase-plugin", $url . "assets/js/woobase-plugin.js", [], 1, ["in_footer" => true]);
}

// Tap into woobase action that will trigger on front end and backend woobase page
add_action("woobase/app/start", "woobase_load_custom_script");
