<?php

//------------------------
// Create a new Assistant
//------------------------

$openaiApiKey = "Your-API-Key";  

$apiUrl = "https://api.openai.com/v1/assistants";

$model = "gpt-4-1106-preview";
$name = "PHP Assistant2";

$instructions = "You are a personal math tutor. When asked a question, write and run Python code to answer the question.";

$tools = array(
	        array("type" => "code_interpreter"),
			array("type" => "retrieval")	
	    );


if (isset($_POST["my_message"])) {

	$headers = array(
	    "Content-Type: application/json",
	    "Authorization: Bearer " . $openaiApiKey,
	    "OpenAI-Beta: assistants=v1"
	);
	
	$data = array(
	    "instructions" => $instructions,
	    "name" => $name,
			"tools" => $tools,
	    "model" => $model
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
	    echo "Assistant created successfully!\n";
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
	
<h2>Create an Assistant</h2>

<p>When the submit button is clicked a new Assistant is created on OpenAi.<br>
Please refer to the php code above. It contains the API request.</p>

<form action="" method="post">
my_message: <input type="text" name="my_message" value="dummy message"><br>
<input type="submit">
</form>

</body>
</html>

