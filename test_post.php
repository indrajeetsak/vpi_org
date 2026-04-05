<?php
$url = 'http://localhost:8080/admin/locations/add_polling_booths';
$data = json_encode(['mla_area_id' => '1', 'names' => ['Test Booth 1', 'Test Booth 2']]);

$options = [
    'http' => [
        'header'  => "Content-type: application/json\r\n" .
                     "Content-Length: " . strlen($data) . "\r\n" .
                     "Connection: close\r\n",
        'method'  => 'POST',
        'content' => $data,
        'ignore_errors' => true
    ]
];

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
echo "RESPONSE FROM SERVER:\n";
print_r($http_response_header);
echo "\nBODY:\n" . $result . "\n";
