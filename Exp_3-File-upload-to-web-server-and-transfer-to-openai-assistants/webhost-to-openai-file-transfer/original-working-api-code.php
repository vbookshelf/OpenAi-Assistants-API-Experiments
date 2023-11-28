<?php

// Got the original code, with errors, from here: https://stackoverflow.com/questions/77506942/chatgpt-upload-file-via-api-php
// Used chatgpt to correct the errors and changed the purpose to 'assistants'
// This outputs the file id.


$openAISecretKey = "API-Key";
$endpoint = 'https://api.openai.com/v1/files';

$filePath = '/home/soywoza/concept.test.woza.work/uploads/test-upload.txt';

// Use CURLFile for file uploads
$file = new CURLFile($filePath);

$data = array(
    'file' => $file,
    'purpose' => 'assistants'
);

$ch = curl_init($endpoint);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: multipart/form-data',
    'Authorization: Bearer ' . $openAISecretKey
));

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);

if (isset($result['object']) && $result['object'] === 'file') {
    echo $result['id'];
} else {
    echo isset($result['error']['message']) ? $result['error']['message'] : 'Error uploading file.';
}
?>

