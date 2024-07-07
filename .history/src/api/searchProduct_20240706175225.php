<?php
require 'D:\ComposerSetup\vendor\autoload.php'; // Adjust path to autoload.php as per your setup
include "config.php"; // Include your database configuration file

use Elastic\Elasticsearch\ClientBuilder;

// Create Elasticsearch client
$client = ClientBuilder::create()->build();

// Function to index books into Elasticsearch
function indexBooks($client, $books)
{
    foreach ($books as $book) {
        // Prepare the index request
        $params = [
            'index' => 'books', // Index name
            'id' => $book['maSach'], // Document ID
            'body' => [
                'maSach' => $book['maSach'],
                'tenSach' => $book['tenSach'],
                'soLuong' => $book['soLuong'],
                'donGia' => $book['donGia'],
                'chiTiet' => $book['chiTiet'],
                'hinhAnh' => $book['hinhAnh'],
                'maKM' => $book['maKM'],
                'maNXB' => $book['maNXB']
                // Add more fields as needed
            ]
        ];

        // Index the document
        $response = $client->index($params);
        echo "Indexed book ID {$book['maSach']}: {$response['result']}<br>";
    }
}

// Sample books data (replace with your actual data retrieval logic)
$books = [
    [
        'maSach' => 1,
        'tenSach' => 'A beautiful image is a perfect moment frozen',
        'soLuong' => 8,
        'donGia' => 1000,
        'chiTiet' => 'Một hình ảnh đẹp là một khoảnh khắc hoàn hảo được đóng băng: Hình ảnh là cách tuyệt vời để lưu giữ những khoảnh khắc đáng nhớ. Một bức ảnh đẹp có thể nắm bắt được tinh hoa của thời khắc, từ nụ cười hồn nhiên của trẻ thơ, ánh mắt yêu thương của người yêu.',
        'hinhAnh' => 'https://buku.one/wp-content/uploads/2021/03/mastering-photography-572x764-1.jpg',
        'maKM' => 2,
        'maNXB' => 1
    ],
    // Add more books as needed
];

// Call the function to index books into Elasticsearch
indexBooks($client, $books);

echo "Indexing completed.";
?>