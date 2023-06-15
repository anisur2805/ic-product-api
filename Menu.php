<?php

// Add a Bearer Token page to the WordPress dashboard
function my_custom_settings_page() {
    add_options_page(
        __( 'IC Product API', 'ic-product-api' ),      // Page title
        __( 'IC Product API', 'ic-product-api' ),      // Menu title
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
        <?php printf('<h1>%s</h1>', __('Product Authorization', 'ic-product-api' ) ); ?>
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
        'ic-product-api-group',
        'ic_bearer_token_key',
    );
    
    register_setting(
        'ic-product-api-group',
        'ic_product_api',
    ); 

    register_setting(
        'ic-product-api-group',
        'itc_order_api',
    );

    add_settings_section(
        'ic-product-api-section',
        __('Custom Section', 'ic-product-api'),
        'my_custom_settings_section_callback',
        'ic-product-api'
    );

    add_settings_field(
        'my-custom-setting-field',
        __('Product Authorization', 'ic-product-api'),
        'my_custom_setting_field_callback',
        'ic-product-api',
        'ic-product-api-section',
    );

    add_settings_field(
        'ic_order_api',
        __('Order API Endpoint', 'ic-product-api'),
        'ic_order_api_callback',
        'ic-product-api',
        'ic-product-api-section',
    );

    add_settings_field(
        'ic_product_api',
        __('Product API Endpoint', 'ic-product-api'),
        'ic_product_api_control_callback',
        'ic-product-api',
        'ic-product-api-section',
    );
}
add_action('admin_init', 'my_custom_settings_init');

// Callback function to display the section description (optional)
function my_custom_settings_section_callback() {
    _e('This is a Authorization section.', 'ic-product-api');
}

// Callback function to display the input field
function my_custom_setting_field_callback() {
    $ic_token   = get_option('ic_bearer_token_key');
    $ic_token   = sanitize_text_field( $ic_token ); ?>
    <td>
        <label for="ic_bearer_token_key"><?php _e('Bearer Token', 'ic-product-api'); ?></label><br/>
        <input type="text" id="ic_bearer_token_key" name="ic_bearer_token_key" value="<?php echo esc_attr($ic_token); ?>" style="max-width: 500px; width: 100%">
    </td>
    <?php
}
 
function ic_product_api_control_callback() {
    $ic_product_api   = get_option('ic_product_api');
    $ic_product_api   = sanitize_text_field( $ic_product_api ); ?>
    <td>
        <label for="ic_product_api"><?php _e('Product API Endpoint', 'ic-product-api'); ?></label> <br/>
        <input type="text" name="ic_product_api" id="ic_product_api" value="<?php echo esc_url($ic_product_api); ?>" style="max-width: 500px; width: 100%"> 
    </td> 
    <?php
}

function ic_order_api_callback() {
    $itc_order_api  = get_option('itc_order_api');
    $itc_order_api  = sanitize_text_field( $itc_order_api ); ?>
    <td>
        <label for="itc_order_api"><?php _e('Order API Endpoint', 'ic-product-api'); ?></label> <br/>
        <input type="text" name="itc_order_api" id="itc_order_api" value="<?php echo esc_url($itc_order_api); ?>" style="max-width: 500px; width: 100%">
    </td>
    <?php
}