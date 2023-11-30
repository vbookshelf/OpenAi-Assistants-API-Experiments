<?php

//------------------------
// Create a Message
//------------------------

$openaiApiKey = "Your-API-Key";  

// This thread was created previously
$thread_id = 'thread_e944rZyqvP9VVaDIU1Utigrj';

$apiUrl = 'https://api.openai.com/v1/threads/' . $thread_id . '/messages';



if (isset($_POST["my_message"])) {
	
	$message = $_POST["my_message"];

	$headers = array(
	    "Content-Type: application/json",
	    "Authorization: Bearer " . $openaiApiKey,
	    "OpenAI-Beta: assistants=v1"
	);
	
	$data = [
    'role' => 'user',
    'content' => $message
	];
	
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
	    echo "Message created successfully!\n";
	    echo "Response:\n" . $response . "\n";
		
		// Decode the JSON response
		$responseData = json_decode($response, true);
		// Get the assistant id
		echo $responseData['id'];
		
	} else {
	    echo "Error creating message. HTTP code: " . $httpCode . "\n";
	    echo "Response:\n" . $response . "\n";
	}

}

?>



<!DOCTYPE HTML>
<html>  
<body>
	
<h2>Create a Message on a previously created thread</h2>


<form action="" method="post">
my_message: <input type="text" name="my_message"><br>
<input type="submit">
</form>

</body>
</html>

