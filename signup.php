<!DOCTYPE html>
<html>
<head>
    <title>Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white p-8 rounded shadow-lg">
        <h2 class="text-2xl font-bold mb-8 text-center">Sign Up</h2>
        <form method="post" action="signup.php" enctype="multipart/form-data">
            <div class="mb-4">
                <label for="id" class="block text-gray-700 text-sm font-bold mb-2">ID:</label>
                <input type="text" id="id" name="id" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500" placeholder="Enter your ID" required>
            </div>
            <div class="mb-4">
                <label for="id" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                <input type="Name" id="id" name="Name" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500" placeholder="Enter your ID" required>
            </div>
            <div class="mb-4">
                <label for="id" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                <input type="text" id="Email" name="Email" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500" placeholder="Enter your ID" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password:</label>
                <input type="password" id="password" name="password" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500" placeholder="Enter your password" required>
            </div>
            <div class="mb-4">
                <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Role:</label>
                <select id="role" name="role" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500">
                    <option value="Student">Student</option>
                    <option value="Faculty">Faculty</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="profile_picture" class="block text-gray-700 text-sm font-bold mb-2">Profile Picture:</label>
                <input type="file" id="profile_picture" name="profile_picture" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500">
            </div>
            <div class="mb-4">
                <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">Sign Up</button>
            </div>
            <?php if (!empty($error)) { ?>
            <div class="mb-4">
                <p class="text-red-500">Error: <?php echo $error; ?></p>
            </div>
        <?php } else { ?>
            <div class="mb-4">
                <p>No error.</p>
            </div>
        <?php } ?>
        </form>
    </div>
</body>
<?php

session_start();
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST"){

    $id = mysqli_real_escape_string($connection, $_POST["id"]);
    $password = mysqli_real_escape_string($connection, $_POST["password"]);
    $role = mysqli_real_escape_string($connection, $_POST["role"]);
    $name = mysqli_real_escape_string($connection, $_POST["Name"]);
    $email = mysqli_real_escape_string($connection, $_POST["Email"]);
$status='Pending';
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES["profile_picture"]["name"]);
    move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFile);
    $sql="Select * from credentials where id='$id'";
    $result = $connection->query($sql); 

    if ($result && $result->num_rows == 0) {
        $sql= "INSERT INTO  credentials (id, password, role, status, picture,name,email) VALUES ('$id','$password','$role','$status','$targetFile','$name','$email')";
    if (mysqli_query($connection, $sql)) {
        echo "<script>alert('Sign Up Sucess. Contact Admin for approval');</script>";
        header("Location: login.php");
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($connection);
    }}
    else{

        $error = "Id already exists";
    }


}


?>



</html>
