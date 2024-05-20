<?php

session_start();


include 'connection.php';


$role = $_SESSION['Role'];
$sql = "SELECT id, title, url FROM sidebar_links WHERE role = '$role'";
$result = mysqli_query($connection, $sql);

$links = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $links[] = $row;
    }
    mysqli_free_result($result);
} else {
    echo "Error: " . mysqli_error($connection);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Include Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<nav class="bg-blue-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center ml-auto text-white font-bold text-l"> 
                <p class='mr-3'>Hello, <?php echo $_SESSION['name'] ?></p>
                <img src="<?php echo $_SESSION['Picture'] ?>" alt="User Picture" class="h-8 w-8 rounded-full">
            </div>
        </div>
    </div>
</nav>
<div class="flex h-screen bg-gray-200">
    <div class="w-64 bg-gray-800">
        <div class="p-4 text-white font-bold"></div>
        <ul class="py-4">
            <?php foreach ($links as $link): ?>
                <li class="px-4 py-2 hover:bg-gray-700">
                    <a href="<?php echo $link['url']; ?>" class="block text-white"><?php echo $link['title']; ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
  
    <div id="main" class="flex-1 p-4">
   
   
</div>
<br>
<br>



</body>
</html>
