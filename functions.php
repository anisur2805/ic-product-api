<?php

function ic_product_api_configuration( $url, $data, $request_type = 'POST' ) {
    $bearer_token = get_option('ic_bearer_token_key');

    $timeout = 15;
    $headers = [
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $bearer_token,
    ];
    $response = wp_remote_post( $url, array(
        'method'      => $request_type,
        'headers'     => $headers,
        'body'        => wp_json_encode( $data ),
        'data_format' => 'body',
        'timeout'     => $timeout,
    ));

    return $response;
}