<?php

// Hook into order status transitions
add_action('woocommerce_order_status_changed', 'send_order_stock_update', 10, 4);

function send_order_stock_update($order_id, $old_status, $new_status, $order) {
    $stock_url = 'http://127.0.0.1:8000/api/order-stock-update';

    // Prepare an array to store line item data
    $line_items = [];
    $p_name = '';
    // Loop through each item in the order
    foreach ($order->get_items() as $item_id => $item) {

        // Get the product data
        $product_data = $item->get_data();
        $product_id = $product_data['product_id'];
        $product_name = $product_data['name'];
        $variation_id = $product_data['variation_id'];
        $product_quantity = $product_data['quantity'];

        $p_name .= $product_name;
        // Add the line item to the array
        $line_items[] = [
            'name' => $product_name,
            'product_id' => $product_id,
            'variation_id' => $variation_id,
            'quantity' => $product_quantity,
        ];
    }

    // Prepare the stock data for the API request
    $stock_data = array(
        'id' => $order_id,
        'status' => $new_status,
        'line_items' => $line_items
    );

    // Make the API request
    $response = ic_product_api_configuration($stock_url, $stock_data);

    // Check for errors and handle the response
    if (is_wp_error($response)) {
        error_log('Msg - Something went wrong during product stock update - ' . $response ); 
    } else {
        $response_code = wp_remote_retrieve_response_code($response);
        $response_body = wp_remote_retrieve_body($response);
        error_log($response_body);
        error_log( $p_name . ' - Successfully updated product stock');
    }
}
