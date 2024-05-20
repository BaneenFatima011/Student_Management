<?php
include "connection.php";
include "dashboard.php";

$NAME = $_SESSION['ID'];

$sql_courses = "SELECT COURSE_NAME, COURSE_ID FROM COURSES WHERE FACULTY_ID='$NAME'";
$result_courses = mysqli_query($connection, $sql_courses);

$sql_students = "SELECT NAME, ID FROM CREDENTIALS WHERE ROLE='STUDENT'";
$result_students = mysqli_query($connection, $sql_students);

$tableVisible = false;
$category = "";
$totalMarks = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create'])) {
    $category = $_POST['category'];
    $totalMarks = $_POST['total_marks'];
    $name = $_POST['name'];
    $tableVisible = true; 
}

$students = [];
if ($result_students && mysqli_num_rows($result_students) > 0) {
    while ($row = mysqli_fetch_assoc($result_students)) {
        $students[] = $row; 
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $courseId = $_POST['course'];
    $category = $_POST['category'];
    $name = $_POST['name'];
    $totalMarks = $_POST['total_marks'];
    $obtainedMarks = $_POST['obt_marks'];

    foreach ($students as $student) {
        $ID = $student['ID'];
        $obtainedMark = $obtainedMarks[$ID];

        $sql_insert = "INSERT INTO GRADES (FACULTY_ID, STUDENT_ID, COURSE_ID, CATEGORY, TOTAL_MARKS, OBT_MARKS, Name) VALUES ('$NAME', '$ID', '$courseId', '$category', '$totalMarks', '$obtainedMark', '$name')";
        mysqli_query($connection, $sql_insert);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grades</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-8">
    <h1 class="text-2xl font-bold mb-4">Grades</h1>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="mb-8">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="course" class="block text-sm font-medium text-gray-700">Select Course</label>
                    <select name="course" id="course" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select Course</option>
                        <?php while ($row = mysqli_fetch_assoc($result_courses)): ?>
                            <option value="<?php echo $row['COURSE_ID']; ?>"><?php echo $row['COURSE_NAME']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700">Select Category</label>
                    <select name="category" id="category" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select Category</option>
                        <option value="assignment">Assignment</option>
                        <option value="quiz">Quiz</option>
                        <option value="project">Project</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <input type="text" name="total_marks" placeholder="Total Marks" class="block w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" id="name" placeholder="Enter Name" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="mt-4">
                <button type="submit" name="create" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:bg-blue-700">Create</button>
            </div>
        </form>
        
        <?php if ($tableVisible): ?>
            <h1 class="text-2xl font-bold mb-4"><?php echo $name; ?></h1>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="hidden" name="course" value="<?php echo isset($_POST['course']) ? $_POST['course'] : ''; ?>">
                <input type="hidden" name="category" value="<?php echo $category; ?>">
                <input type="hidden" name="total_marks" value="<?php echo $totalMarks; ?>">
                <input type="hidden" name="name" value="<?php echo $name; ?>"> 
                
                <table class="w-full mt-4">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Marks</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Obtained Marks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo $student["NAME"]; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo $student["ID"]; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo $category; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo $totalMarks; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><input type="text" name="obt_marks[<?php echo $student['ID']; ?>]" class="px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500 focus:ring-indigo-500"></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <button type="submit" name="submit" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:bg-blue-700">Submit Grades</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
