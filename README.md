# VendBase Example Plugin

## Description

This plugin serves as an example of how to extend and customize the VendBase plugin for WooCommerce. It demonstrates various techniques for adding custom fields, modifying the product edit interface, and extending the WooCommerce REST API.

**Note: This plugin requires the VendBase plugin v1.0.9 and up, which must be purchased and installed separately.**

## Prerequisites

- WordPress
- WooCommerce
- VendBase plugin (purchased separately)

## Installation

1. Ensure that WordPress, WooCommerce, and the VendBase plugin are installed and activated.
2. Download this example plugin from the GitHub repository.
3. Upload the plugin folder to the `/wp-content/plugins/` directory of your WordPress installation.
4. Activate the plugin through the 'Plugins' menu in WordPress.

## Features

This example plugin demonstrates the following:

1. Creating a custom Vue component for use with VendBase
2. Modifying the product edit interface
3. Adding custom fields to the WooCommerce REST API
4. Enqueuing custom scripts for use with VendBase

## Usage Examples

### 1. Custom Vue Component

The plugin defines a custom Vue component that adds a new input field to the product edit page:

```javascript
const ExampleComponent = defineComponent({
  name: "VendBaseCustom",
  // ... (component definition)
  template: `
	<div class="grid grid-cols-3 pl-4 gap-4 mt-4">
	  <div class="text-zinc-400 flex flex-col place-content-center"><span>Custom text value</span></div>
	  <input class="..." type="text" :value="modelValue.vendbase_custom_text" @input="updateValue">
	</div>
  `,
});
```

### 2. Modifying Product Edit Interface

The plugin demonstrates how to remove, add, and modify sections in the product edit view:

```javascript
// Remove linked products section
wp.hooks.addFilter("vendbase.api.views.products.edit", "removeLinked", (viewSections) => {
  return viewSections.filter((item) => item.id !== "product_linked_products");
});

// Add new section
wp.hooks.addFilter("vendbase.api.views.products.edit", "addNewSection", (viewSections) => {
  const customSection = {
    id: "product_custom_section",
    name: __("Custom section", "customlocale"),
    component: ExampleComponent,
    // ... (section configuration)
  };
  return [...viewSections, customSection];
});

// Add sub-section to existing section
wp.hooks.addFilter("vendbase.api.views.products.edit", "addNewSection", (viewSections) => {
  return viewSections.map((item) => {
    if (item.id !== "product_summary") return item;
    item.slots.push({ component: ExampleComponent, args: {} });
    return item;
  });
});
```

### 3. Extending WooCommerce REST API

The plugin shows how to add custom fields to the WooCommerce products endpoint:

```php
function vendbase_register_images_field()
{
  register_rest_field("product", "vendbase_custom_text", [
    "get_callback" => "vendbase_get_field",
    "update_callback" => "vendbase_update_field",
    "schema" => null,
  ]);
}

add_action("rest_api_init", "vendbase_register_images_field");
```

## Customization

Feel free to modify the provided examples to suit your specific needs. This plugin serves as a starting point for your own VendBase extensions.

## Support

This is an example plugin intended for educational purposes. For support with the VendBase plugin itself, please refer to the official VendBase documentation and support channels.

## Contributing

Contributions to improve this example plugin are welcome. Please feel free to submit pull requests or create issues for bugs and feature requests.

## License

This example plugin is open-source software licensed under the GPL v2 or later.
