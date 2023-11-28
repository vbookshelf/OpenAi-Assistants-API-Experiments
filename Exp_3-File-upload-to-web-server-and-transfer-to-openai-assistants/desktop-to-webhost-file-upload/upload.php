<?php
if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/'; // Specify your upload directory
    $uploadFile = $uploadDir . basename($_FILES['file']['name']);

    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {
        echo json_encode(['message' => 'File successfully uploaded.']);
    } else {
        echo json_encode(['message' => 'Error moving uploaded file.']);
    }
} else {
    echo json_encode(['message' => 'Error uploading file.']);
}
?>
