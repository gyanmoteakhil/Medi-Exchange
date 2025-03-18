<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sell Your Medicine - MediExchange</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 10px;
        }
        input[type="text"], input[type="number"], input[type="date"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="file"] {
            margin-bottom: 20px;
        }
        button {
            width: 100%;
            padding: 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Sell Your Medicine</h1>
    
    <form action="/submit-medicine" method="post" enctype="multipart/form-data">
        <label for="medicine_name">Medicine Name:</label>
        <input type="text" id="medicine_name" name="medicine-name" required>
        
        <label for="expiry_date">Expiry Date:</label>
        <input type="date" id="expiry_date" name="expiry-date" required>
        
        <label for="quantity">Quantity (in packs or strips):</label>
        <input type="number" id="quantity" name="quantity" min="1" required>
        
        <label for="price">Price (per pack):</label>
        <input type="number" id="price" name="price" min="0.01" step="0.01" required>
        
        <label for="description">Description (optional):</label>
        <textarea id="description" name="description" rows="4"></textarea>
        
        <label for="medicine_photo">Upload Medicine Photo:</label>
        <input type="file" id="medicine_photo" name="medicine-photo" accept="image/*" required>
        
        <button type="submit">Submit Medicine for Sale</button>
    </form>
</div>

</body>
</html>
<?php
$conn = mysqli_connect("localhost", "root", "", "vishesh");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $Medicine_name = mysqli_real_escape_string($conn, $_POST['medicine-name']);
    $expiry_date= mysqli_real_escape_string($conn, $_POST['expiry-date']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $Price = mysqli_real_escape_string($conn, $_POST['Price']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $submitTypes = $_POST['submitType']; // This is an array

    $image = null;
    if (in_array('image', $submitTypes)) {
        $image = uploadFile('imageInput');
    }

    // Insert data into the database
    $sql = "INSERT INTO medical (medicine_name, expiry_date, quantity, Price, description, image) 
            VALUES ('$medicine_name', '$expiry_date', '$quantity', '$Price', '$description', '$image')";

    if (mysqli_query($conn, $sql)) {
        echo "Record added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
    
    // Close the database connection
    mysqli_close($conn);
}

// Function to handle file upload
function uploadFile($inputName) {
    $targetDir = "uploads/";
    
    // Check if the upload directory exists
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    
    $targetFile = $targetDir . basename($_FILES[$inputName]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Allow certain file formats
    $allowedFormats = ["jpg", "jpeg", "png", "gif", "mp3", "wav", "mp4", "avi"];
    if (!in_array($fileType, $allowedFormats)) {
        echo "Sorry, only JPG, JPEG, PNG, GIF, MP3, WAV, MP4, AVI files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        return null;
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES[$inputName]["tmp_name"], $targetFile)) {
            return $targetFile;
        } else {
            echo "Sorry, there was an error uploading your file.";
            return null;
        }
    }
}
?>