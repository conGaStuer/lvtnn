<?php
require 'vendor/autoload.php'; // Include the Elasticsearch PHP client

use Elasticsearch\ClientBuilder;

$client = ClientBuilder::create()->build(); // Initialize the Elasticsearch client

if (isset($_GET['keyword'])) {
    $keyword = $_GET['keyword']; // Get the search keyword from the URL parameters

    // Build the query for Elasticsearch
    $params = [
        'index' => 'books', // The index name where the books data is stored
        'body' => [
            'query' => [
                'multi_match' => [
                    'query' => $keyword,
                    'fields' => ['TenSach', 'DanhMuc', 'NgonNgu', 'TacGia', 'ChiTiet'] // Fields to search in
                ]
            ]
        ]
    ];

    // Execute the search query
    $response = $client->search($params);

    // Check if there are any search results
    if ($response['hits']['total']['value'] > 0) {
        // Map the results to extract the source data
        $books = array_map(function ($hit) {
            return $hit['_source'];
        }, $response['hits']['hits']);

        // Return the search results as JSON
        echo json_encode($books);
    } else {
        // Return a message indicating no data was found
        echo json_encode("Deo co du lieu");
    }
}
