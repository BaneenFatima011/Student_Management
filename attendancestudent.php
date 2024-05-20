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
    

    $sql_attendance = "SELECT A.STUDENT_ID, C.NAME, A.STATUS, A.FACULTY_ID, A.DATE, F.COURSE_NAME  
    FROM ATTENDANCE A 
    INNER JOIN CREDENTIALS C ON A.STUDENT_ID = C.ID
    INNER JOIN COURSES F ON A.COURSE_ID = F.COURSE_ID
    WHERE A.COURSE_ID = '$course' AND A.STUDENT_ID = '$NAME'";
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
    <title>Attendance Record</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-8">
        <h1 class="text-2xl font-bold mb-4">Attendance</h1>
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
            <?php if (empty($attendances)): ?>
                <p class="text-red-500">No attendance records found for the selected course and date.</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="table-auto w-full mb-8">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Faculty</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course Name</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($attendances as $attendance): ?>
                                <tr>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo $attendance['STATUS']; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo $attendance['FACULTY_ID']; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo $attendance['DATE']; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap"><?php echo $attendance['COURSE_NAME']; ?></td>
                                    
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</


</html>


</body>
</html>