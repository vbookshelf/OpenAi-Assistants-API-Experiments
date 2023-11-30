<?php


$openaiApiKey = "Your-API-Key";  

// This thread was created previously
$thread_id = 'thread_9OePn7NS6I96GNo5Fm0xOii3';

// This assistant has a file attached
$assistant_id = 'asst_RrYmzRVJoTTknP2Adf2Uxu3e';



//------------------
// Create a message
//------------------


if (isset($_POST["my_message"])) {
	
	$apiUrl = 'https://api.openai.com/v1/threads/' . $thread_id . '/messages';
	
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





//------------------
// Run the thread
//------------------

// Run Status Options:
// queued, in_progress, completed, requires_action, 
// expired, cancelling, cancelled, failed


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







if (isset($_POST["run_thread"])) {
	
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
	
	echo "Run Status: " . $responseData['status'];

    // Loop to check the status until completed
    do {
        usleep(500); // in microseconds, sleep(1) in seconds

        // Check the status of the thread
        $statusResponse = getStatus($openaiApiKey, $thread_id, $runId);

        // Handle the status response as needed
        $status = $statusResponse['status'];

        echo "Run Status: " . $status;

    } while ($status === 'queued' || $status === 'in_progress');

    // The run has completed.
	// But check that it was successful.
	echo 'Run completed';
		
}

// The next step would be to get the message or messages that the assistant added
// to the thread, but we won't do that here.
// To get the assistant messages we will need to list all messages on the thread (see Exp_12).

?>



<!DOCTYPE HTML>
<html>  
<body>
	
<h2>Create a Message and Run the Thread</h2>

<p>When the thread is run the assistant will look at the thread and generate a response.
The assistant will add one or more responses to the thread.
To get the assistant's response we will need to list all messages on the thread (see Exp_12).</p>


<form action="" method="post">
my_message: <input type="text" name="my_message"><br>
<input type="submit" value="Create Message">
</form>

<form action="" method="post">
<input type="text" name="run_thread" value="dummy message"><br>
<input type="submit" value="Run Thread">
</form>

</body>
</html>

