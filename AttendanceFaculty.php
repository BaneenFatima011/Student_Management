<?php

include 'dashboard.php';
include 'connection.php';

$NAME = $_SESSION['ID'];

$sql_students = "SELECT NAME, ID FROM CREDENTIALS WHERE ROLE='STUDENT'";
$sql_courses = "SELECT COURSE_NAME, COURSE_ID FROM COURSES WHERE FACULTY_ID='$NAME'";

$result_students = mysqli_query($connection, $sql_students);
$result_courses = mysqli_query($connection, $sql_courses);

$students = [];
$courses = [];

if ($result_students && mysqli_num_rows($result_students) > 0) {
    while ($row = mysqli_fetch_assoc($result_students)) {
        $students[] = $row;
    }
}

if ($result_courses && mysqli_num_rows($result_courses) > 0) {
    while ($row = mysqli_fetch_assoc($result_courses)) {
        $courses[] = $row;
    }
}

$error = ''; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course = $_POST['coursevar'];
    $date = $_POST['date'];
  
    $sql = "SELECT * FROM ATTENDANCE WHERE DATE='$date' and course_id='$course'";
    $result = mysqli_query($connection, $sql);
    if (!$result || mysqli_num_rows($result) == 0) {
        foreach ($students as $student) {
            $ID = $student['ID'];
           
            $statusValue = $status[$ID];
            $sql = "INSERT INTO ATTENDANCE (`COURSE_ID`, `FACULTY_ID`, `STATUS`, `STUDENT_ID`, `DATE`) VALUES ('$course', '$NAME', '$statusValue', '$ID', '$date')";
            mysqli_query($connection, $sql);
        }
    } else {
        $error = 'Attendance for the selected date already exists.';
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
                <div class="col-span-2 sm:col-span-1">
                    <label for="date" class="block text-sm font-medium text-gray-700">Date Selection</label>
                    <input type="date" name="date" id="date" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
            <?php if (!empty($error)) { ?>
                        <div class="mb-4">
                            <p class="text-red-500"><?php echo $error; ?></p>
                        </div>
                    <?php } ?>
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Submit</button>
        </form>
        <h2 class="text-xl font-bold mb-4">Student List</h2>
        <div class="overflow-x-auto">
            <table class="table-auto w-full mb-8">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $student["ID"]; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $student["NAME"]; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <select name="status[<?php echo $student['ID']; ?>]" id="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="present">Present</option>
                                    <option value="absent">Absent</option>
                                    <option value="late">Late</option>
                                    <option value="leave">Leave</option>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

