<?php
defined('BASEPATH') or exit('No direct script access allowed');

function get_pexels_image($query = 'blockchain', $count = 1) {
    $api_key = '9yJq6oA66YDuGgEvRDBjgVCTqbNegxk9PrveByPrVJ4kFPad46Z10izp';
    $url = "https://api.pexels.com/v1/search?query={$query}&per_page={$count}";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: ' . $api_key
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    $data = json_decode($response, true);
    
    if (isset($data['photos'][0]['src']['medium'])) {
        return $data['photos'][0]['src']['medium'];
    }
    return null;
} 