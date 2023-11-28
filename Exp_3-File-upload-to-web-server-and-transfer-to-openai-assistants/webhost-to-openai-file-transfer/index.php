
<!DOCTYPE HTML>
<html>  
<body>
	
<h2>Click submit to send the file to OpenAi</h2>
<p>The file (named test-upload.txt) is stored on the server in a folder named 'uploads'.
When this form is submitted a php file called 'file-transfer-api-code.php' will be run.
When OpenAi receives test-upload.txt it will assign an id to it. That id will be sent back in the response. It will be displayed on this page.</p>

<form id="myForm" action="file-transfer-api-code.php" method="post">
my_message: <input type="text" value="Dummy text" name="my_message"><br>
<input type="submit">
</form>

<!-- The Ajax response gets displayed in this div -->
<div id="ajax-response">
	The new file id from OpenAi will be displayed here.
</div>

</body>
</html>

<script>
	
var form = document.getElementById('myForm');

form.onsubmit = function(event) {
  // Prevent the default form submission behavior
  event.preventDefault();

  // Get the form data
  var formData = new FormData(form);
  
  //console.log(formdata);

  // Send an AJAX request to the server to process the form data
  var xhr = new XMLHttpRequest();
  xhr.open('POST', form.action, true);
  
  xhr.onload = function() {
    if (xhr.status === 200) {
      var response = JSON.parse(xhr.responseText);
	  
	  // Write the message into a div that has the id ajax-response
	  document.getElementById("ajax-response").innerHTML = response.message;
	  
	  // Write the response on the console
      console.log(response.message);
    }
  };
  
  xhr.send(formData);
};

</script>


