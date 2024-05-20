<?php

include 'dashboard.php';
include 'connection.php';

$NAME = $_SESSION['ID'];

$sql_courses = "SELECT COURSE_NAME, COURSE_ID FROM COURSES ";
$result_courses = mysqli_query($connection, $sql_courses);

$courses = [];

if ($result_courses && mysqli_num_rows($result_courses) > 0) {
    while ($row = mysqli_fetch_assoc($result_courses)) {
        $courses[] = $row;
    }
}

$attendances = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course = $_POST['coursevar'];
    
    $sql_attendance = "SELECT C.NAME, A.OBT_MARKS, A.TOTAL_MARKS, A.category, A.FACULTY_ID, F.COURSE_NAME  
                       FROM GRADES A 
                       INNER JOIN CREDENTIALS C ON A.STUDENT_ID = C.ID
                       INNER JOIN COURSES F ON A.COURSE_ID = F.COURSE_ID
                       WHERE A.STUDENT_ID = '$NAME' AND F.COURSE_ID = '$course'";
    $result_attendance = mysqli_query($connection, $sql_attendance);

    if ($result_attendance && mysqli_num_rows($result_attendance) > 0) {
        while ($row = mysqli_fetch_assoc($result_attendance)) {
            $attendances[] = $row;
        }
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
                <div class="col-span-2 sm:col-span-1">
                    <label for="course" class="block text-sm font-medium text-gray-700">Select Course</label>
                    <select name="coursevar" id="course" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Select Course</option>
                        <?php foreach ($courses as $course): ?>
                            <option value="<?php echo $course['COURSE_ID']; ?>"><?php echo $course['COURSE_NAME']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Submit</button>
        </form>
       
        <?php if ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
            <div class="overflow-x-auto">
                <table class="table-auto w-full mb-8">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Faculty</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course Name</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Obtained Marks</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Marks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($attendances as $attendance): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo $attendance['FACULTY_ID']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo $attendance['COURSE_NAME']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo $attendance['category']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo $attendance['OBT_MARKS']; ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?php echo $attendance['TOTAL_MARKS']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
