<?php
session_start();
// Include the connection file
include 'connection.php';

// Fetch sidebar links based on user's role
$role = $_SESSION['Role'];
$sql = "SELECT id, title, url FROM sidebar_links WHERE role = '$role'";
$result = mysqli_query($connection, $sql);

// Initialize an array to store sidebar links
$links = [];

// Check if query was successful
if ($result) {
    // Fetch associative array
    while ($row = mysqli_fetch_assoc($result)) {
        // Add link data to the array
        $links[] = $row;
    }
    // Free result set
    mysqli_free_result($result);
} else {
    // Handle query error
    echo "Error: " . mysqli_error($connection);
}

// Handle form submission to update user status


// Query to fetch users
$sql = "SELECT ID,NAME,ROLE,STATUS,EMAIL,ROLE FROM CREDENTIALS where role='Student'";
$result = mysqli_query($connection, $sql);
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
    <div class="flex-1 p-4">
        <h1 class="text-2xl font-bold mb-4">View Users</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['ID']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['NAME']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['EMAIL']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['ROLE']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['STATUS']; ?></td>
                           
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            
        </form>
    </div>
</div>
</body>
</html>

