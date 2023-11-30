<?php

//---------------------------
// Retrieve a Thread
//---------------------------

$openaiApiKey = "Your-API-Key";  

// This thread was created previously
$thread_id = 'thread_9OePn7NS6I96GNo5Fm0xOii3';


$apiUrl = "https://api.openai.com/v1/threads/" . $thread_id ;


if (isset($_POST["my_message"])) {

	$headers = array(
	    "Content-Type: application/json",
	    "Authorization: Bearer " . $openaiApiKey,
	    "OpenAI-Beta: assistants=v1"
	);
	
	
	$ch = curl_init($apiUrl);
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); // Changed to GET
	//curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	
	$response = curl_exec($ch);
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	curl_close($ch);
	
	// Handle the response
	if ($httpCode == 200) {
	    echo "Thread retrieved successfully!\n";
	    echo "Response:\n" . $response . "\n";
		
		// Decode the JSON response
		$responseData = json_decode($response, true);
		// Get the assistant id
		echo $responseData['id'];
		
	} else {
	    echo "Error retrieving thread. HTTP code: " . $httpCode . "\n";
	    echo "Response:\n" . $response . "\n";
	}

}

?>



<!DOCTYPE HTML>
<html>  
<body>
	
<h2>Retrieve a Thread</h2>


<form action="" method="post">
my_message: <input type="text" name="my_message" value="dummy message"><br>
<input type="submit">
</form>

</body>
</html>

