<?php

//------------------------------------------------
// Attach a File to an Assistant (Assistant File)
//------------------------------------------------


// Ref: https://platform.openai.com/docs/api-reference/assistants/createAssistantFile


$openaiApiKey = "Your-API-Key";  

// This assistant has been created on OpenAi
$assistant_id = 'asst_RrYmzRVJoTTknP2Adf2Uxu3e';

// This file has been uploaded to OpenAi
$file_id = 'file-qf55I78Q70Su6oWP8VfN979o';

$apiUrl = 'https://api.openai.com/v1/assistants/' . $assistant_id . '/files';
	


if (isset($_POST["my_message"])) {

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
	    echo "File attached to assistant!\n";
	    echo "Response:\n" . $response . "\n";
		
		// Decode the JSON response
		$responseData = json_decode($response, true);
		// Get the assistant id
		echo $responseData['id'];
		
	} else {
	    echo "Error creating assistant. HTTP code: " . $httpCode . "\n";
	    echo "Response:\n" . $response . "\n";
	}

}

?>



<!DOCTYPE HTML>
<html>  
<body>
	
<h2>Attach a file to an assistant</h2>


<form action="" method="post">
my_message: <input type="text" name="my_message" value="dummy message"><br>
<input type="submit">
</form>

</body>
</html>

