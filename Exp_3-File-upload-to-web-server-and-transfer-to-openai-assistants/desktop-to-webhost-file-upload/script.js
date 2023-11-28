function uploadFile() {
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
      document.getElementById('result').innerHTML = `File uploaded successfully. Server response: ${data.message}`;
    })
    .catch(error => {
      console.error('Error uploading file:', error);
      document.getElementById('result').innerHTML = 'Error uploading file.';
    });
  } else {
    alert('Please select a file to upload.');
  }
}
