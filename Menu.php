<?php

// Add a Bearer Token page to the WordPress dashboard
function my_custom_settings_page() {
    add_options_page(
        'IC Product API',      // Page title
        'IC Product API',      // Menu title
        'manage_options',       // Capability required to access the page
        'ic-product-api',   // Menu slug
        'ic_product_api_callback' // Callback function to display the settings content
    );
}
add_action('admin_menu', 'my_custom_settings_page');

// Register and display the custom input field on the settings page
function ic_product_api_callback() {
    if (!current_user_can('manage_options')) {
        wp_die('You do not have sufficient permissions to access this page.');
    }

    ?>
    <div class="wrap">
        <h1>Product Authorization</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('ic-product-api-group');
            do_settings_sections('ic-product-api');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register the custom setting and input field
function my_custom_settings_init() {
    register_setting(
        'ic-product-api-group', // Option group
        'ic_bearer_token_key' // Option name
    );

    add_settings_section(
        'ic-product-api-section', // Section ID
        'Custom Section', // Section title
        'my_custom_settings_section_callback', // Callback function to display section description (optional)
        'ic-product-api' // Page slug
    );

    add_settings_field(
        'my-custom-setting-field', // Field ID
        'Product Authorization', // Field label
        'my_custom_setting_field_callback', // Callback function to display the input field
        'ic-product-api', // Page slug
        'ic-product-api-section' // Section ID
    );
}
add_action('admin_init', 'my_custom_settings_init');

// Callback function to display the section description (optional)
function my_custom_settings_section_callback() {
    echo 'This is a Bearer Token section.';
}

// Callback function to display the input field
function my_custom_setting_field_callback() {
    $ic_token               = get_option('ic_bearer_token_key');
    // $ic_consumer_key        = get_option('ic_consumer_key');
    // $ic_customer_secrete    = get_option('ic_customer_secrete');

    ?>
    <input type="text" name="ic_bearer_token_key" value="<?php echo esc_attr($ic_token); ?>">
    <!-- <input type="text" name="ic_consumer_key" value="<?php //echo esc_attr($ic_consumer_key); ?>">
    <input type="text" name="ic_customer_secrete" value="<?php //echo esc_attr($ic_customer_secrete); ?>"> -->
    <?php
}
