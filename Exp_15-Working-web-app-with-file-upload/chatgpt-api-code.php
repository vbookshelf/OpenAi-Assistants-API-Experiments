<?php
session_start();

include "name_config.php";


// PHP Config
//------------
	
// Your API Key
//$openaiApiKey = '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';

// Zozobot Assistant1
//$assistant_id = 'asst_RrYmzRVJoTTknP2Adf2Uxu3e';
	
//$thread_id = 'thread_oWWOVcIbL3nywItHIHTAF8FS';


//-----------
// FUNCTIONS
//-----------


// This function cleans and secures the user input
function test_input(&$data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = strip_tags($data);
		$data = htmlentities($data);
		
		return $data;
	}
	
	

// This function creates a new thread for each new chat.
function createNewThreadAndGetId($openaiApiKey) {
	
	$apiUrl = "https://api.openai.com/v1/threads";
	
    $headers = array(
        "Content-Type: application/json",
        "Authorization: Bearer " . $openaiApiKey,
        "OpenAI-Beta: assistants=v1"
    );
    
    $ch = curl_init($apiUrl);
    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    //curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    curl_close($ch);
    
    // Handle the response
    if ($httpCode == 200) {
        // Decode the JSON response
        $responseData = json_decode($response, true);
        
        // Return the assistant id
        return $responseData['id'];
    } else {
        // You might want to handle errors differently here
        return null;
    }
}






// Create a new Thread if the session variable is not set.
if (!isset($_SESSION['thread_id'])) {
	
	$_SESSION['thread_id'] = createNewThreadAndGetId($openaiApiKey);
	
}



// This code is triggered when the user submits a message
//--------------------------------------------------------

if (isset($_REQUEST["my_message"]) && empty($_REQUEST["robotblock"])) {
	
	//$message = 'This is a test'; 
	
	$message = $_REQUEST["my_message"];
	
	$thread_id = $_SESSION['thread_id'];
	
	// Clean and secure the user's text input
	$message = test_input($message);
	
	
	//----------------
	// Create message
	//----------------
	
	$apiUrl = 'https://api.openai.com/v1/threads/' . $thread_id . '/messages';
	

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
	    //echo "Message created successfully!\n";
	    //echo "Response:\n" . $response . "\n";
		
		
	} else {
		
		// Display a test message on the page
		$test_response = array('success' => false, 'chat_text' => 'Error creating message.');
	  	//echo json_encode($test_response);
		
	}
	
		
	
	//----------------
	// Run the thread
	//----------------
	
	
	// Function to get the status of the run
	//----------------------------------------

	function getStatus($apiKey, $thread_id, $run_id)
	{
		
		$apiUrl = "https://api.openai.com/v1/threads/" . $thread_id . "/runs/" . $run_id;
		
	    $headers = array(
		    "Authorization: Bearer " . $apiKey,
		    "OpenAI-Beta: assistants=v1"
		);
		
		
		$ch = curl_init($apiUrl);
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET"); // Changed to GET
		//curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		
		$run_response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
		curl_close($ch);
	
	    return json_decode($run_response, true);
	}
	
	
	$apiUrl = 'https://api.openai.com/v1/threads/' . $thread_id . '/runs';

    $headers = array(
        "Content-Type: application/json",
        "Authorization: Bearer " . $openaiApiKey,
        "OpenAI-Beta: assistants=v1"
    );

    $data = [
        'assistant_id' => $assistant_id
    ];

    $ch = curl_init($apiUrl);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);
	

    // Decode the JSON response
    $responseData = json_decode($response, true);
    // Get the assistant id
    $runId = $responseData['id'];
	
	
	
	//echo "Run Status: " . $responseData['status'];

    // Loop to check the status until completed
    do {
        usleep(500); // in microseconds, sleep(1) in seconds

        // Check the status of the thread
        $statusResponse = getStatus($openaiApiKey, $thread_id, $runId);

        // Handle the status response as needed
        $status = $statusResponse['status'];

        //echo "Run Status: " . $status;

    } while ($status === 'queued' || $status === 'in_progress');

    // The run has completed.
	// But check that it was successful.
	//echo 'Run completed';
	


	
	//---------------
	// List messages
	//---------------
	
	
	$apiUrl = "https://api.openai.com/v1/threads/" . $thread_id . "/messages";
	
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
	    //echo "Message list retrieved successfully!\n";
	    //echo "Response:\n" . $response . "\n";
		
		// Decode the JSON response
		$responseData = json_decode($response, true);
		
	} else {
	    //echo "Error retrieving message list. HTTP code: " . $httpCode . "\n";
	    //echo "Response:\n" . $response . "\n";
	}
	
	
	

	
	//-----------------------------
	// Get the assistant message/s
	//------------------------------
	
	

	function processJsonData($jsonData) {
	    $jsonObject = json_decode($jsonData, true);
	    $resultList = [];
	
	    foreach ($jsonObject['data'] as $message) {
	        $role = $message['role'];
	        $content = $message['content'][0]['text']['value'];
	
	        if ($role === 'assistant') {
	            $resultList[] = $content;
	        } elseif ($role === 'user') {
	            // Terminate the loop if the role is 'user'
	            break;
	        }
	    }
		
		// Curently the last assistant message is first in this list.
		// Reverse the order of $resultList so that the messages
		// are in the order that the assistant spoke them.
		$resultList = array_reverse($resultList);
		
	
	    return $resultList;
	}
	
	
	// Call the function
	$assistant_messages = processJsonData($response);
	
	//print_r($assistant_messages);
	
	
	
	//----------------------------------------------
	// Send the assistant messages to the main page
	//----------------------------------------------
	

	// 
	$length = count($assistant_messages);
	
	$response_list = [];
	
	for ($i = 0; $i < $length; $i++) {
	    //echo $assistant_messages[$i] . "<br>";
		$response_list[] = array('success' => true, 'chat_text' => $assistant_messages[$i]);
		//$response_list[] = array('success' => true, 'chat_text' => $assistant_messages[$i]);
		
		//echo json_encode($response);
	}
	
	$assistant_responses = json_encode($response_list);
	
	print_r($assistant_responses);

	
} // CLOSE 



?>