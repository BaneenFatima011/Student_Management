
<?php

session_start();
include 'connection.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = mysqli_real_escape_string($connection, $_POST["id"]);
    $pass = mysqli_real_escape_string($connection, $_POST["password"]);

    if (!empty($id) && !empty($pass)) {
        $stmt = $connection->prepare("SELECT ID, PASSWORD, ROLE, PICTURE, STATUS, name,email FROM CREDENTIALS WHERE ID=? AND PASSWORD=? ");
        $stmt->bind_param("ss", $id, $pass);

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if ($row["STATUS"] != 'Pending') {
                $_SESSION['ID'] = $row['ID'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['Role'] = $row['ROLE'];
                $_SESSION['Picture'] = $row['PICTURE'];
                
                // Redirect based on role
                if ($row['ROLE'] == 'Student' || $row['ROLE'] == 'Faculty' || $row['ROLE'] == 'Admin') {
                    header("Location: dashboard.php");
                    exit(); 
                } 
            } else {
                $error = "Status is Pending";
            }
        }
        else {
            $error = "Invalid ID or password";
        }
    } else {
        $error = "ID and password cannot be empty";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
  
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <<div class="max-w-md w-full bg-white p-8 rounded shadow-lg">
    <h2 class="text-2xl font-bold mb-8 text-center">Login</h2>
    <form method="post">
        <div class="mb-4">
            <label for="id" class="block text-gray-700 text-sm font-bold mb-2">Id</label>
            <input type="id" id="id" name="id" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500" placeholder="Enter your email" required>
        </div>
        <div class="mb-4">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
            <input type="password" id="password" name="password" class="w-full px-3 py-2 border rounded-md focus:outline-none focus:border-blue-500" placeholder="Enter your password" required>
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
        <div class="mb-4">
            <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">Login</button>
        </div>

        <div class="mb-4">
            <a href="signup.php" class="block w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:bg-blue-600">Sign Up</a>
        </div>
    </form>
</div>
</body>

</html>