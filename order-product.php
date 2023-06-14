<?php

add_action('woocommerce_thankyou', 'send_data_to_api');
function send_data_to_api($order_id) {
    // Get the order object
    $order = wc_get_order($order_id);
    $product = wc_get_product($order_id);
    $sku = $product ? $product->get_sku() : '';

    // Get Order Payment Details
    $payment_method = $order->get_payment_method();
    $payment_method_name = $order->get_payment_method_title();
    $transaction_id = $order->get_transaction_id();

    $order_key = $order->get_order_key();
    $billing_first_name = $order->get_billing_first_name();
    $billing_last_name = $order->get_billing_last_name();
    $billing_company = $order->get_billing_company();
    $billing_address_1 = $order->get_billing_address_1();
    $billing_address_2 = $order->get_billing_address_2();
    $billing_city = $order->get_billing_city();
    $billing_state = $order->get_billing_state();
    $billing_postcode = $order->get_billing_postcode();
    $billing_country = $order->get_billing_country();
    $billing_emial = $order->get_billing_email();
    $billing_phone = $order->get_billing_phone();

    $shipping_first_name = $order->get_shipping_first_name();
    $shipping_last_name = $order->get_shipping_last_name();
    $shipping_company = $order->get_shipping_company();
    $shipping_address_1 = $order->get_shipping_address_1();
    $shipping_address_2 = $order->get_shipping_address_2();
    $shipping_city = $order->get_shipping_city();
    $shipping_state = $order->get_shipping_state();
    $shipping_postcode = $order->get_shipping_postcode();
    $shipping_country = $order->get_shipping_country();

    $currency   = $order->get_currency();
    $shipping_address = $order->get_address();

    $created_date = $order->get_date_created();
    $modified_date = $order->get_date_modified();
    $discount = $order->get_discount_total();
    $discount_tax = $order->get_discount_tax();
    $status = $order->get_status();
    $shipping_total = $order->get_shipping_total();
    $shipping_tax = $order->get_shipping_tax();
    $cart_tax = $order->get_cart_tax();
    $total_tax = $order->get_total_tax();
    $customer_id = $order->get_customer_id();

    $p_name = '';
    $product_items = [];
    foreach ( $order->get_items() as $item_id => $item ) {
        $product_id = $item->get_product_id();
        $variation_id = $item->get_variation_id();
        $product_name = $item->get_name();
        $quantity = $item->get_quantity();
        $subtotal = $item->get_subtotal();
        $total = $item->get_total();
        $tax = $item->get_subtotal_tax();
        $tax_class = $item->get_tax_class();
        $sku = $sku;

        $p_name .= $product_name;

        $product_items[] = [
            "id" => $product_id,
            "name" => $product_name,
            "product_id" => 886,
            "variation_id" => $variation_id,
            "quantity" => $quantity,
            "tax_class" => $tax_class,
            "subtotal" => $subtotal,
            "subtotal_tax" => "0.00",
            "total" => $total,
            "total_tax" => $tax,
            "taxes" => [],
            "sku" => $sku,
            "price" => $total,
            "parent_name" => $product_name
        ];

    }

    // Prepare your API data
    $api_data = array(
        "id" => $order->get_id(),
        "parent_id" => 0,
        "status" => $status,
        "currency" => $currency,
        "date_created" => $created_date,
        "date_modified" => $modified_date,
        "discount_total" => $discount,
        "discount_tax" => $discount_tax,
        "shipping_total" => $shipping_total,
        "shipping_tax" => $shipping_tax,
        "cart_tax" => $cart_tax,
        "total" => $total,
        "total_tax" => $total_tax,
        "customer_id" => $customer_id,
        "order_key" => $order_key,
        "billing" => [
            "first_name" => $billing_first_name,
            "last_name" => $billing_last_name,
            "company" => $billing_company,
            "address_1" => $billing_address_1,
            "address_2" => $billing_address_2,
            "city" => $billing_city,
            "state" => $billing_state,
            "postcode" => $billing_postcode,
            "country" => $billing_country,
            "email" => $billing_emial,
            "phone" => $billing_phone,
        ],
        "shipping" => [
            "first_name" => $shipping_first_name,
            "last_name" => $shipping_last_name,
            "company" => $shipping_company,
            "address_1" => $shipping_address_1,
            "address_2" => $shipping_address_2,
            "city" => $shipping_city,
            "state" => $shipping_state,
            "postcode" => $shipping_postcode,
            "country" => $shipping_country,
            "phone" => ""
        ],
        "payment_method" => $payment_method,
        "payment_method_title" => $payment_method_name,
        "line_items" => $product_items
    );

    // Make the API request
    $product_url = 'http://127.0.0.1:8000/api/woocom-order';
    $response = ic_product_api_configuration( $product_url, $api_data );

    // Check for errors and handle the response as needed
    if (is_wp_error($response)) {
        error_log( $p_name . ' - Something went wrong: ' . $response );
    } else {
        $response_code = wp_remote_retrieve_response_code($response);
        $response_body = wp_remote_retrieve_body($response);
        // echo '<pre>';
        //       print_r( $response_body );
        // echo '</pre>';
        error_log( $p_name . ' - Successfully created product ');     
        error_log( $response_body );     
    }
}
