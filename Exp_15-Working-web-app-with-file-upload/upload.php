<?php
session_start();

include "name_config.php";

$openAISecretKey = $openaiApiKey; // from name_config.php

//$assistant_id = "asst_RrYmzRVJoTTknP2Adf2Uxu3e";





// Function to transfer a file from the Webhost to OpenAi
function uploadFileToOpenAI($filePath, $openAISecretKey) {
    
    $endpoint = 'https://api.openai.com/v1/files';

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
        $response = array('success' => true, 'message' => 'File successfully transferred to OpenAi. id: ' . $result['id']);
		
		$_SESSION['file_id'] = $result['id'];
		
        return json_encode($response);
		
    } else {
        // Return a failure response
        $response = array('success' => false, 'message' => 'Error transferring file.');
        return json_encode($response);
    }
}

// Example usage
//$filePath = '/home/soywoza/concept.test.woza.work/uploads/test-upload.txt';
//$result = uploadFileToOpenAI($filePath);
//echo $result;


function attachFileToAssistant($openaiApiKey, $assistant_id, $file_id) {
	
    $apiUrl = 'https://api.openai.com/v1/assistants/' . $assistant_id . '/files';

    $headers = array(
        "Content-Type: application/json",
        "Authorization: Bearer " . $openaiApiKey,
        "OpenAI-Beta: assistants=v1"
    );

    $data = array(
        "file_id" => $file_id
    );

    $ch = curl_init($apiUrl);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    // Handle the response
    if ($httpCode == 200) {
        //echo "File attached to assistant!\n";
        //echo "Response:\n" . $response . "\n";

        // Decode the JSON response
        //$responseData = json_decode($response, true);
        // Get the assistant id
        //echo $responseData['id'];
		
		// Return a success response
        $response = array('success' => true, 'message' => 'File attached to assistant.');
		
        return json_encode($response);
		
		
    } else {
        //echo "Error creating assistant. HTTP code: " . $httpCode . "\n";
        //echo "Response:\n" . $response . "\n";
		
		// Return a failure response
        $response = array('success' => false, 'message' => 'Failed to attached file to assistant.');
		
        return json_encode($response);
    }
}







// RUN THE CODE


//-----------------------------------
// Webhost Receives the File and 
// Stores it in the 'uploads' folder
//-----------------------------------


// Note:
// This php code is triggered from script.js
// The echo messages go back to script.js, from where they are
// then displayed on the page.

if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/'; // Specify your upload directory
    $uploadFile = $uploadDir . basename($_FILES['file']['name']);

    // Get the file name
    $fileName = $_FILES['file']['name'];
	
	//echo json_encode($fileName);

	// If the Webhost successfully received the file
    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
        //echo json_encode(['message' => 'File successfully uploaded.', 'fileName' => $fileName]);
		
		//-----------------------------
		// Transfer the file to OpenAi
		//-----------------------------
		$filePath = '/home/soywoza/concept.test.woza.work/uploads/' . $fileName;
		$result = uploadFileToOpenAI($filePath, $openAISecretKey);
		
		$file_id = $_SESSION['file_id'];
		
		
		//$openAISecretKey = "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";
		//$assistant_id = "asst_RrYmzRVJoTTknP2Adf2Uxu3e";
		//$file_id = 'file-0m90r51PhIuj3lI89KvvZjSz';
		
		$result = attachFileToAssistant($openAISecretKey, $assistant_id, $file_id);
		
		
		
		// $result is a json object. It has a key called 'message', and a key called 'id'.
		// Refer to the function above.
		echo $result;
		
		
		
    } else {
        echo json_encode(['message' => 'Error moving uploaded file to uploads folder.']);
    }
} else {
    echo json_encode(['message' => 'Error uploading file.']);
}




?>
