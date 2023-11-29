<?php

$openaiApiKey = "Your-API-Key";  

$apiUrl = "https://api.openai.com/v1/assistants";


//------------------------
// Create a new Assistant
//------------------------


$model = "gpt-4-1106-preview";
$name = "PHP Assistant2";

$instructions = "You are a personal math tutor. When asked a question, write and run Python code to answer the question.";

$tools = array(
	        array("type" => "code_interpreter"),
			array("type" => "retrieval")	
	    );


if (isset($_POST["create-assistant"])) {

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





//------------------------
// List All Assistants
//------------------------



if (isset($_POST["list-assistants"])) {

	$headers = array(
	    "Content-Type: application/json",
	    "Authorization: Bearer " . $openaiApiKey,
	    "OpenAI-Beta: assistants=v1"
	);
	
	
	
	$ch = curl_init($apiUrl);
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");  // Note: POST changed to GET
	//curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	
	$response = curl_exec($ch);
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	curl_close($ch);
	
	// Handle the response
	if ($httpCode == 200) {
	    echo "List of Assistants\n";
	    echo "Response:\n" . $response . "\n";
		
		// Decode the JSON response
		$responseData = json_decode($response, true);
		
		// Get the id of the last assistant created
		echo $responseData['data'][0]['id'];
		
	} else {
	    echo "Error listing assistants";
	    echo $response;
	}

}




//------------------------
// Delete Assistant
//------------------------



if (isset($_POST["delete-assistant-id"])) {
	
	$assistant_id = $_POST["delete-assistant-id"];
	
	// Add the assistant id to the url
	$apiUrl = "https://api.openai.com/v1/assistants/" . $assistant_id;

	$headers = array(
	    "Content-Type: application/json",
	    "Authorization: Bearer " . $openaiApiKey,
	    "OpenAI-Beta: assistants=v1"
	);
	
	
	
	$ch = curl_init($apiUrl);
	
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");  // Note: POST changed to DELETE
	//curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	
	$response = curl_exec($ch);
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	curl_close($ch);
	
	// Handle the response
	if ($httpCode == 200) {
	    echo "Asistant Deleted:\n";
	    echo "Response:\n" . $response . "\n";
		
		// Decode the JSON response
		$responseData = json_decode($response, true);
		
		// Get the id of the deleted assistant
		echo $responseData['id'];
		
	} else {
	    echo "Error deleting assistant.";
		echo $response;
	    
	}

}

?>



<!DOCTYPE HTML>
<html>  
<body>
	
<h2>Create Assistant</h2>
<form action="" method="post">
my_message: <input type="text" name="create-assistant" value="dummy message"><br>
<input value="Create" type="submit">
</form>

<h2>List Assistants</h2>
<form action="" method="post">
my_message: <input type="text" name="list-assistants" value="dummy message"><br>
<input value="List" type="submit">
</form>


<h2>Delete Assistant</h2>
<form action="" method="post">
Enter Assistant Id: <input type="text" name="delete-assistant-id" value=""><br>
<input value="Delete" type="submit">
</form>

</body>
</html>

