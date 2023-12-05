<?php
session_start();

// Initialize the fle_id session variable.
// This gets changed when the user attaches a file to the message.
$_SESSION['file_id'] = 'no_file_attached';

include "name_config.php";



//include "chatgpt-api-code.php";

//echo $bot_name;

?>



<!DOCTYPE html>
<html lang="en">

	<head>
	
	<meta charset="utf-8">
	<title>Zozobot</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="OpenAi Assistant Chatbot">
		
		
	<!--CSS Stylesheets-->
	<link rel="stylesheet" href="css/w3.css">
	
	<link rel="shortcut icon" type="image/png" href="assets/cross.png">
	
	
    <style>
      body {
        background-color: #f9f9f9;
		font-family: Arial, sans-serif;
		font-size: 18px;
		color: #36454F;
      }
	   main {
	   	margin-bottom: 200px;
	   	color: #36454F;
        padding: 10px;
	}
	
	.responsive {
		 width: 100%; /*Makes media scalable as the viewport size changes*/
		 height: auto;
		 max-width: 150px;
		 
		 } 
      .container {
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        padding: 0 20px;
      }
	  
      .sticky-bar {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background-color: #36454F; /* Charcoal */
        color: #fff;
        padding: 10px; /*30px*/
        text-align: center;
      }
      .sticky-bar input[type="text"] {
        padding: 10px;
        border-radius: 5px;
        border: none;
        margin-right: 10px;
        width: 60%;
        font-size: 18px;
      }
      .sticky-bar input[type="submit"] {
        background-color: #fff;
        color: #333;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        margin-left: 10px;
      }
	  .message-container {
        margin-bottom: 10px;
        padding: 5px 20px;
        background-color: #f0f0f0;
        border-radius: 5px;
		line-height: 1.8;
		letter-spacing: 0.02em;
	}
	.set-color1 {
		color: red;
	}
	.set-color2 {
		color: purple;
	}
	
	
	#chat-buttons {
	  display: flex;
	  justify-content: center;
	  align-items: center;
	  margin-top: 10px;
	}
	
	#chat-buttons button {
	  margin-right: 20px;
	  padding: 0px 20px;
	  border-radius: 5px;
	  cursor: pointer;
	  font-size: 15px;
	  background-color: #36454F;
	  color: #f9f9f9;
	  border: none;
	}
	
	#chat-buttons input[type="file"] {
	  display: none;
	}
	
	#chat-buttons label {
	  display: inline-block;
	  padding: 0px 20px;
	  border-radius: 5px;
	  cursor: pointer;
	  font-size: 15px;
	  background-color: #36454F;
	  color: #f9f9f9;
	  border: none;
	}
		
	#chat-buttons input[type="file"] + label {
	  margin-right: 10px;
	}
	
	#chat-buttons input[type="file"] + label:before {
	  content: "Load a saved chat";
	}
	
	.sticky-image {
			position: fixed;
			top: 0;
			left: 0;
		}
		
	.beta-text {
		font-size: 15px;
		
	}
	
	.hide {
		display: none;
	}
	
	
	
@media only screen and (max-width: 480px) and (orientation: portrait){
/*Cellphone Screens in portrait*/


 .container {
        padding: 0;
	}

} /*Close media query*/
	</style>

	
  </head>
  <body>
	  
	  
	  
	  
    <div class="container w3-animate-opacity">
		
		<!-- 
		<div id="main-image">
			<img class="responsive" src="assets/teacher.png" alt="Avatar">
		</div>
		-->
	
	  
	  <main id="chat" class="texts">
	      <div class="message-container">
			  <span id="first-chat-block" class="set-color1"><b>&#x2022 ChatGPT</b></span>
	        
			  <p>Hi there, I'm Zozo.</p>
	      </div>
		  
		  <div class="message-container">
			  <span id="first-chat-block" class="beta-text"><b>Beta version</b></span>
	        
			  <p class="beta-text">1. Please note that this is an experimental preview.
			  </p>
				 
	        
			 </div>
		  
	      <!-- Add more message containers here -->
		  
		   <!-- The div for the spinner gets
		  added and deleted here. -->
 	 </main>
	 
	 
	 
	 
	 
	 
      <div class="sticky-bar">
		  
		<form id="myForm" action="chatgpt-api-code.php" method="post">
          <input id="user-input" type="text" name="my_message" placeholder="Send a message..."  autofocus>
		  <input type="hidden" name="robotblock">
		  <input id="submit-btn" type="submit" value="Send">
		  
		  
		  
	  </form>
	  
	  
		
		
		<div id="chat-buttons">
			
		  <!-- This button will mute the bot when it's talking 
		  <button class="w3-btn w3-blue w3-padding" onclick="setTimeout(simulateClick.bind(null, 'fileInput'), 200)">
			  Upload File</button>
		-->
	  		<button class="w3-btn w3-blue w3-padding" onclick="setTimeout(simulateClick.bind(null, 'fileInput'), 200)">
			Attach File</button>
			<div class="w3-text-small" id="result">---</div>
		  
		</div>
		
      </div>
	 
    </div>
	
	
	<!--The page gets scrolled up to this id.-->
	<div id="chatgpt">
	</div>
	
	<!--Onload a click is simulated on this to scroll the page to id="bottom-bar"-->
	<a href="#chatgpt" id="scroll-page-up"></a>
	<a href="#test100" id="scroll-to-last-message"></a>
	
	<a href="#chatgpt1" id="scroll-to-bot-message"></a>
	
	
	<div class="hide">
		<input type="file" id="fileInput" />
	</div>
  </body>
</html>




<!-- Load jQuery -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js">
</script>


<script>
	$("#fileInput").change(function() {
		
		uploadFile();
		
	});
</script>




<script>
  
//Simulates a click.
function simulateClick(tabID) {
	
	// Simulate a click.
	document.getElementById(tabID).click();
	
}

</script>


<!-- Import the utils.js file -->
<script src="utils.js"></script>



<script>
	
// ##### This needs to be changed later so that the bot_name comes from php
// These names are set in name_config.php
// That file has been included at the top of this page.
const bot_name = "<?php echo $bot_name; ?>";
const user_name = "<?php echo $user_name; ?>";



// Remove these suffixes. I think removing them makes the chat sound more natural.
// They will sliced off the bot's responses.
// This is done below in the 'Remove suffixes' part of the code.
var suffixes_list = ['How can I help you?', 'How can I assist you today?', 'How can I help you today?', 'Is there anything else you would like to chat about?', 'Is there anything else I can assist you with today?', 'Is there anything I can help you with today?', 'Is there anything else you would like to chat about today?', 'Is there anything else I can assist you with?', 'Is there anything else I can help you with?'];

</script>


<script>
	// Set the name of the bot in the first chat block
	document.getElementById("first-chat-block").innerHTML = "<b>&#x2022 " + bot_name + "</b>";
</script>


<script>
	
// PHP Ajax Code
/////////////////
	
var form = document.getElementById('myForm');

form.onsubmit = function(event) {
	
	
  // Prevent the default form submission behavior
  event.preventDefault();
  // Get the form data
  var formData = new FormData(form);
  
  // Clear the form input
  form.reset();
  
  // Get the value of my_message
  var $my_message = formData.get("my_message");
  //console.log($my_message);
  
  // Format the input into paragraphs. This
  // adds paragrah html to the students chat.
  // It's main use is in Maiya's chat where the long response needs 
  // to be formatted into separate paragraphs.
  $my_message = formatResponse($my_message);

  
  var input_message = {
  sender: user_name,
  text: $my_message
	};
	
	
	console.log(input_message.text);
	
	
	// Add a user message to the chat
	addMessageToChat(input_message);
	
	// Show the spinner while waiting for the response from openai
	create_spinner_div();
	
	
	// Scroll the page up by cicking on a div at the bottom of the page.
	simulateClick('scroll-page-up');
	
	
	// Delete the id from the message container.
	// It will get added again when the message container is created.
	// ******
	var element = document.getElementById("chatgpt1");
	element.removeAttribute("id");
  
  
  
  //console.log(formdata);
  // Send an AJAX request to the server to process the form data
  var xhr = new XMLHttpRequest();
  xhr.open('POST', form.action, true);
  
  xhr.onload = function() {
	  
  if (xhr.status === 200) {
		
	  // Get the response
      var response_list = JSON.parse(xhr.responseText);
	  
	  // The response from the Assistants API can be more than one message.
	  // Previously it was only one message. The new Assistants API is different.
	  // Here we loop through each message and add it to the chat.
	  for (var i = 0; i < response_list.length; i++) {
		  
		  var response = response_list[i];
			
		  var response_text = response.chat_text;
		  
		  // Write the response on the console
	      //console.log(response.chat_text);
		  
		  
		  // Replace the suffixes with "":
			// This removes sentences like: How can I help you today?
			// For each suffix in the list...
			 suffixes_list.forEach(suffix => {
		      
				// Replace the suffix with nothing.
		        response_text = response_text.replace(suffix, "");
				
		  	});
			
		  
		  // *** Remove any html and then speak *** //
			////////////////////////////////////////////
			const cleaned_text = removeHtmlTags(response_text);
			//speak(cleaned_text);
	  
	  
		  // Format the response into separate paragrahs
		  var paragraph_response = formatResponse(response_text);
		 
		  
		  console.log(paragraph_response);
		  
		  var input_message = {
			  sender: bot_name,
		  	text: paragraph_response
			};
			
		
			//console.log(input_message.text);
			
			if (i == 0) {
				// Delete the div containing the spinner
				delete_spinner_div();
			}
		
			// Add the message from Maiya to the chat
			addMessageToChat(input_message);
			
			
			// Scroll the page up by cicking on a div at the bottom of the page.
			// ***** Canhge this to click on the bot message div, then delete the div id ****
			simulateClick('scroll-to-bot-message');
			
			
			// Delete the id from the message container.
			// It will get added again when the message container is created.
			// ******
			var element = document.getElementById("chatgpt1");
			element.removeAttribute("id");
			
			
			
			// Only put the cursor into the input field
			// if the user is not using a cellphone.
			// If the cursor is in the input field on a phone then the keyboard
			// gets displayed. This affects the page scrolling to the bot message.
			var screenWidth = window.screen.width;
			var screenHeight = window.screen.height;
			
			// Assuming a threshold of 768 pixels as a cutoff for mobile devices
			var isMobile = screenWidth <= 768;
			
			if (isMobile) {
			  	console.log("User is using a cellphone");
			} else {
			  	console.log("User is not using a cellphone");
			  	// Put the cursor in the form input field
				const inputField = document.getElementById("user-input");
				inputField.focus();
			}
			
	  
	  }  // Close the loop over each message
    }
  };
  
  xhr.send(formData);
};

</script>



<script>
	
// This is the code that handles the file upload. 
// The file is first uploaded to the webhost.
// Then php code (upload.php) is used to store the file in a folder called 'uploads'.
// Then the php code transfers the file from the 'uploads' folder to OpenAi.

function uploadFile() {
	
	console.log('test1');

  const fileInput = document.getElementById('fileInput');
  const file = fileInput.files[0];
  

  if (file) {
    const formData = new FormData();
    formData.append('file', file);

    fetch('upload.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
		
	// This is the response from the Php code in uploads.php
	// The 'message' key contains the file id if the transfer to OpenAi was successful.
	// Refer to the function uploadFileToOpenAI() in upload.php
      document.getElementById('result').innerHTML = `Status: ${data.message}`;
	  
    })
    .catch(error => {
      console.error('Error uploading file:', error);
      document.getElementById('result').innerHTML = 'Error uploading file';
    });
	
  } else {
    alert('Please select a file to upload.');
  }
  
}	
	
</script>






<?php
// This is important.
// If this is not done then the session variables will still
// be available even after the tab is closed. By doing this the
// session variables get deleted when the tab is closed.
// You can print out the message history to confirm that the
// session variable has been deleted: print_r($_SESSION['message_history']);

// remove all session variables
session_unset();

// destroy the session
session_destroy();
?>


