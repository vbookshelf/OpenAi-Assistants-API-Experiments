<?php

// Got the original code, with errors, from here: https://stackoverflow.com/questions/77506942/chatgpt-upload-file-via-api-php
// Used chatgpt to correct the errors and changed the purpose to 'assistants'.


$openAISecretKey = "Your-API-Key";
$endpoint = 'https://api.openai.com/v1/files';

// Change this to match the path tp your file stored on your server
$filePath = '/home/soywoza/concept.test.woza.work/uploads/test-upload.txt';

// Use CURLFile for file uploads
$file = new CURLFile($filePath);

$data = array(
    'file' => $file,
    'purpose' => 'assistants',
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
	
	// Return a success response
	// $result['id'] is the OpenAi id that was assigned to the uploaded file
	$response = array('success' => true, 'message' => $result['id']);
	echo json_encode($response);
    
} else {
	
	// Return a failure response
	$response = array('success' => false, 'message' => 'Error uploading file.');
	echo json_encode($response);
}
?>

