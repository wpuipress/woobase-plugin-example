const { __ } = wp.i18n;

const ExampleComponent = {
  name: "WooBaseCustom",
  model: {
    prop: "modelValue",
    event: "update:modelValue",
  },
  props: {
    modelValue: {
      type: String,
      default: "",
    },
  },
  methods: {
    updateValue(event) {
      this.$emit("update:modelValue", {
        ...this.modelValue,
        woobase_custom_text: event.target.value,
      });
    },
  },
  template: `
  	  <div class="grid grid-cols-3 pl-4 gap-4 mt-4">
	    <div class="text-zinc-400 flex flex-col place-content-center"><span>Custom text value</span></div>
		<input class="px-2 py-2 border border-zinc-200 dark:border-zinc-700 rounded-lg w-full transition-all outline outline-transparent outline-offset-[-2px] focus:outline-indigo-300 dark:focus:outline-indigo-700 focus:shadow-xs text-sm dark:bg-transparent text-zinc-700 dark:text-zinc-300 col-span-2" type="text" :value="modelValue.woobase_custom_text" @input="updateValue">
	  </div>
	  `,
};

/**
 * Remove linked products section from product edit
 *
 */
wp.hooks.addFilter("woobase.api.views.products.edit", "removeLinked", (viewSections) => {
  return viewSections.filter((item) => item.id !== "product_linked_products");
});

/**
 * Add new section to product edit
 *
 */
wp.hooks.addFilter("woobase.api.views.products.edit", "addNewSection", (viewSections) => {
  const customSection = {
    id: "product_custom_section", // Section id, should be unique
    name: __("Custom section", "customlocale"), // Name of section
    component: ExampleComponent, // Vue component
    slots: [], // Can be used to add custom components inside custom sections {component: comp, args: {} }
    condition: (product) => true, // Conditional display function
    start_open: true, // Whether the section defaults to open or not
    args: {}, // Optional args (these will be spread over your component using v-bind)
  };

  return [...viewSections, customSection]; // Return a new array with the custom section added
});

/**
 * Add sub section to existing section
 *
 * This will add the custom text field to the bottom of the product summary section
 */
wp.hooks.addFilter("woobase.api.views.products.edit", "addNewSection", (viewSections) => {
  return viewSections.map((item) => {
    if (item.id !== "product_summary") return item;
    item.slots.push({ component: ExampleComponent, args: {} });
    return item;
  });
});
