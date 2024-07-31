<?php
/*
Plugin Name: VendBase Plugin example
Plugin URI: https://vendbase.com
Description: This is a demo plugin to show how to hook into to vendbase and add custom fields and modify the interface
Version: 1.0.0
Author: vendbase
*/

// Prevent direct access to this file
defined("ABSPATH") || exit();

/**
 * Register a custom field to the product endpoint
 *
 * @return void
 */
function vendbase_register_images_field()
{
  register_rest_field("product", "vendbase_custom_text", [
    "get_callback" => "vendbase_get_field",
    "update_callback" => "vendbase_update_field",
    "schema" => null,
  ]);
}

/**
 * Returns a custom meta string
 *
 * @param array $object The object data.
 * @return string The sanitized custom meta value.
 */
function vendbase_get_field($object)
{
  $value = get_post_meta($object["id"], "vendbase_custom_text", true);
  return esc_html($value);
}

/**
 * Updates a custom meta string
 *
 * @param string $value The value to update.
 * @param WP_Post $object The post object.
 * @return void
 */
function vendbase_update_field($value, $object)
{
  $sanitised = sanitize_text_field($value);
  update_post_meta($object->get_id(), "vendbase_custom_text", $sanitised);
}

add_action("rest_api_init", "vendbase_register_images_field");

/**
 * Enqueues a custom script
 *
 * @return void
 */
function vendbase_load_custom_script()
{
  // Get plugin url
  $url = plugins_url("woobase-plugin-example/");
  wp_enqueue_script("vendbase-plugin", $url . "assets/js/vendbase-plugin.js", [], 1, ["in_footer" => true]);
}

// Tap into vendbase action that will trigger on front end and backend vendbase page
add_action("vendbase/app/start", "vendbase_load_custom_script");
add_action("vendbase/app/start", "vendbase_load_custom_styles");

/**
 * Enqueues custom styles and adds a filter for VendBase shadow DOM styles
 *
 * @return void
 */
function vendbase_load_custom_styles()
{
  // Get plugin url
  $url = plugins_url("woobase-plugin-example/");
  $style = $url . "/assets/css/style.css";
  wp_enqueue_style("vendbase-custom-styles", $style, []);

  // Woobase uses the shadow dom so we need to tell it what styles to inject into it.
  add_filter("vendbase/app/styles", "vendbase_push_styles_to_app");
}

/**
 * Adds custom style sheet to VendBase shadow DOM
 *
 * @param array $style_tags Array of style tags.
 * @return array Modified array of style tags.
 */
function vendbase_push_styles_to_app($style_tags)
{
  // Add style id used wp_enqueue_script
  return [...$style_tags, "vendbase-custom-styles"];
}
