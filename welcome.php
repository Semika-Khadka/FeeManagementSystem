<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'config.php';


// Fetch the user's data to check if they are an admin
$user_id = $_SESSION['user_id'];
$sql = "SELECT isAdmin FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Check if the user is an admin
$isAdmin = $user['isAdmin'] ?? 0;



if (isset($_POST['addStudent'])) {
    // Get the student details from the form
    $name = $_POST['name'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $amount = $_POST['amount'];
    $semester = $_POST['semester'];
    $major = $_POST['major'];
    $roll_no = $_POST['roll_no'];

    // Prepare the SQL query to insert the student data
    $sql = "INSERT INTO students (name, phone_number, address, amount, semester, major, roll_no) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $name, $phone_number, $address, $amount, $semester, $major, $roll_no);

    if ($stmt->execute()) {
        // Redirect to the same page to show the newly added student
        header("Location: welcome.php");
        exit;
    } else {
        echo "Error adding student: " . $stmt->error;
    }

    $stmt->close();
}


// Fetch student data for the logged-in user
$sql = "SELECT * FROM students";
$result = $conn->query($sql);

$countSql = "SELECT COUNT(*) as total_students FROM students";
$countResult = $conn->query($countSql);


// Check if there are any students in the table
$students = [];
$total_students = 0;
if ($countResult->num_rows > 0) {
    $row = $countResult->fetch_assoc(); // Fetch the row
    $total_students = $row['total_students']; // Access the 'total_students' alias
}
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}
//check the number of users in the system
$sql = "SELECT * FROM students";
$result = $conn->query($sql);

$countSql = "SELECT COUNT(*) as total_users FROM users";
$countResult = $conn->query($countSql);

$users = [];
$total_users= 0;
if ($countResult->num_rows > 0) {
    $row = $countResult->fetch_assoc(); // Fetch the row
    $total_users = $row['total_users']; // Access the 'total_students' alias
}
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}
if (isset($_POST['updateStudent'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $amount = $_POST['amount'];
    $semester = $_POST['semester'];
    $major = $_POST['major'];
    $roll_no = $_POST['roll_no'];

    // Update the student in the database
    $sql = "UPDATE students SET name='$name', phone_number='$phone_number', address='$address', amount='$amount', semester='$semester', major='$major', roll_no='$roll_no' WHERE id='$id'";




    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Student details updated successfully!'); window.location.href = 'welcome.php';</script>";
    } else {
        echo "<script>alert('Error updating student: " . $conn->error . "');</script>";
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["deleteBill"])) {
    // Ensure a valid ID is provided
    $id = $_POST['id'];

    // Validate if $id is numeric
    if (!is_numeric($id)) {
        echo "<script>alert('Invalid ID!'); window.location.href = 'welcome.php';</script>";
        exit;
    }

    // SQL Query to delete the record
    $sql = "DELETE FROM students WHERE id = ?";

    // Prepare the SQL statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the ID to the statement as an integer
        $stmt->bind_param("i", $id);

        // Execute the statement
        if ($stmt->execute()) {
            echo "<script>alert('Student details deleted successfully!'); window.location.href = 'welcome.php';</script>";
        } else {
            echo "<script>alert('Error deleting student: " . $conn->error . "');</script>";
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "<script>alert('Error preparing statement: " . $conn->error . "');</script>";
    }

    // Close the database connection
    $conn->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Laila:wght@300;400;500;600;700&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css?v=1">

</head>


<body>

    <!-- Navbar -->
    <div class="navbar">
        Welcome, <?php echo $_SESSION['username']; ?>!
    </div>



    <!-- Welcome Message -->
    <div class="welcome-message">
        <h2>Student Management</h2>
        <!-- starting of the added code -->
        <div class="container-cards">
        <div class="col-lg-4 mb-2">
            <div class="card-box bg-blue">
                <div class="icon">
                    <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                </div>
                <div class="inner">
                <div class="icons">
                    <img src="./images/student_icon.png">
                    </div>
                    <h3> <?php echo $total_students; ?> </h3>
                    <p> Total Students </p>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-box bg-green">
                <div class="icon text-white">
                    <i class="fa fa-certificate " aria-hidden="true"></i>
                </div>
                <div class="inner">
                <div class="icons">
                    <img src="./images/courses_icon.png">
                    </div>
                    <h3>4</h3>
                    <p>Courses</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card-box bg-orange">
                <div class="icon">
                    <i class="fa fa-user-plus" aria-hidden="true"></i>
                </div>

                <div class="inner">
                <div class="icons">
                    <img src="./images/adduser.png">
                    </div>
                    <h3> 500 </h3>
                    <p>Admissions </p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 ">
            <div class="card-box bg-red">
                <div class="icon">
                    <i class="fa fa-users"></i>
                </div>

                <div class="inner">
                    <div class="icons">
                    <img src="./images/userswhite.png">
                    </div>
                    <h3><?php echo $total_users; ?> </h3>
                    <p>Users</p>
                </div>

            </div>
        </div>
    </div>
    <button class="btn" onclick="document.getElementById('addStudentForm').style.display='block'">Add New Student</button>
    </div>
    <!-- ends the added one here -->
    </div>

    <!-- Sidebar -->
    <div class="container">
        <div class="sidebar">
            <div class="logo"><a href="./welcome.php">FeeTrack</a></div>
            <!-- <h3>Fee Management</h3> -->
            <div class="nav-links">
                <a href="./welcome.php"><img class="sidebar-icon" src="./images/dashboardFFF.png">Dashboard</a>
                <a href="./profile_screen.php"><img class="sidebar-icon" src="./images/profileFFF.png">Profile</a>
                <a href="./contact__screen.php"><img class="sidebar-icon" src="./images/contactFFF.png">Contact</a>
               
                <form action="logout.php" method="post">
    <img class="sidebar-icon" src="./images/logoutFFF.png">
    <button type="submit" class="logout-btn" style="background: none;
    border: none;
    color: inherit;
    text-decoration: none;
    cursor: pointer;">
        Logout
    </button>
</form>
<?php if ($isAdmin == 1): ?>
        <a href="./register.php"><img class="sidebar-icon" src="./images/register.png">Register</a>
    <?php endif; ?>

            </div>
        </div>

        <!-- Student List -->
        <h3 style="text-align:center;">Student List</h3>
        <table class="student-table">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Phone Number</th>
                <th>Address</th>
                <th>Amount</th>
                <th>Semester</th>
                <th>Major</th>
                <th>Roll No.</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?php echo $student['id']; ?></td>
                    <td><?php echo $student['name']; ?></td>
                    <td><?php echo $student['phone_number']; ?></td>
                    <td><?php echo $student['address']; ?></td>
                    <td><?php echo $student['amount']; ?></td>
                    <td><?php echo $student['semester']; ?></td>
                    <td><?php echo $student['major']; ?></td>
                    <td><?php echo $student['roll_no']; ?></td>
                    <td>
                        <!-- Edit Button -->
                        <button class="actions" onclick="openEditModal(<?php echo $student['id']; ?>)">Edit</button>
                        <!-- Print Bill Button -->
                        <button class="actions" onclick="printBill(<?php echo $student['id']; ?>)">Print Bill</button>


                        <form method="POST" class="delete">
                            <input type="hidden" name="id" value="<?php echo $student['id']; ?>">
                            <button class="actions" type="submit" name="deleteBill">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <!-- Add Student Form (Modal) -->
    <div id="addStudentForm" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('addStudentForm').style.display='none'">&times;</span>
        <h3>Add New Student</h3>
        <form action="welcome.php" method="POST">
            <input type="text" name="name" placeholder="Name" required><br>
            
            <input type="number" name="phone_number" placeholder="Phone Number" 
                   required pattern="\d{10}" title="Enter a valid 10-digit phone number"><br>
            
            <input type="text" name="address" placeholder="Address" required><br>
            
            <input type="number" name="amount" placeholder="Amount" required min="0"><br>

            <select required name="semester">
                <option selected disabled>Please select Semester</option>
                <option value="1">1st</option>
                <option value="2">2nd</option>
                <option value="3">3rd</option>
                <option value="4">4th</option>
                <option value="5">5th</option>
                <option value="6">6th</option>
                <option value="7">7th</option>
                <option value="8">8th</option>
            </select>
            <br>
            
            <select required name="major">
                <option selected disabled>Please select Major</option>
                <option value="BCA">BCA</option>
                <option value="BSCCSIT">BSCCSIT</option>
                <option value="BSW">BSW</option>
                <option value="BBS">BBS</option>
            </select>
            <br>

            <input type="number" name="roll_no" placeholder="Roll No." required min="1"><br>
            
            <button type="submit" name="addStudent">Add Student</button>
        </form>
        <button class="btn" type="button" onclick="document.getElementById('addStudentForm').style.display='none'">Cancel</button>
    </div>
</div>



    <!-- Print Bill Modal -->
    <div id="printBillModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="document.getElementById('printBillModal').style.display='none'">&times;</span>
            <h3>Student Bill</h3>
            <div class="profile-section">
            
            <img src="profile-pic.jpg" alt="Profile Picture" class="profile-pic">
        </div>
            <div id="billDetails"></div>
            <button class="print-btn" onclick="window.print()">Print Bill</button>
            <!-- Cancel Button -->
            <button class="btn" onclick="document.getElementById('printBillModal').style.display='none'">Cancel</button>
        </div>
    </div>
    <!-- Edit Student Form (Modal) -->
    <div id="editStudentForm" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('editStudentForm').style.display='none'">&times;</span>
            <h3>Edit Student</h3>
            <form action="welcome.php" method="POST">
                <input type="hidden" id="editId" name="id"><br>
                <input type="text" id="editName" name="name" placeholder="Name" required><br>
                <input type="number" min="9700000000" max="9899999999" id="editPhone" name="phone_number" placeholder="Phone Number" required><br>
                <input type="text" id="editAddress" name="address" placeholder="Address" required><br>
                <input type="number" id="editAmount" name="amount" placeholder="Amount" required><br>
                <!-- <input type="number" id="editSemester" name="semester" placeholder="Semester" required><br> -->

                <select required name="semester">
                    <option selected disabled>Please select Semester</option>
                    <option value="1">1st</option>
                    <option value="2">2nd</option>
                    <option value="3">3rd</option>
                    <option value="4">4th</option>
                    <option value="5">5th</option>
                    <option value="6">6th</option>
                    <option value="7">7th</option>
                    <option value="8">8th</option>
                </select>
                <br>
                <!-- <input type="text" id="editMajor" name="major" placeholder="Major" required><br> -->
                <select required name="major">
                    <option selected disabled>Please select Major</option>
                    <option value="BCA">BCA</option>
                    <option value="BSCCSIT">BSCCSIT</option>
                    <option value="BSW">BSW</option>
                    <option value="BBS">BBS</option>
                </select>
                <br>
                <input type="number" id="editRollNo" name="roll_no" placeholder="Roll No." required><br>
                <button type="submit" name="updateStudent">Save Changes</button>
            </form>
            <button class="btn" type="button" onclick="document.getElementById('editStudentForm').style.display='none'">Cancel</button>
        </div>
    </div>


    <script>
        // Open the Edit Student modal and pre-fill values
        function openEditModal(studentId) {
            document.getElementById('editStudentForm').style.display = 'block';

            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_student_details.php?id=' + studentId, true);

            xhr.onload = function() {
                if (xhr.status === 200) {
                    const student = JSON.parse(xhr.responseText);
                    document.getElementById('editId').value = student.id;
                    document.getElementById('editName').value = student.name;
                    document.getElementById('editPhone').value = student.phone_number; // Ensures phone number is pre-filled
                    document.getElementById('editAddress').value = student.address;
                    document.getElementById('editAmount').value = student.amount;
                    document.getElementById('editSemester').value = student.semester;
                    document.getElementById('editMajor').value = student.major;
                    document.getElementById('editRollNo').value = student.roll_no;
                } else {
                    alert("Failed to fetch student details.");
                }
            };

            xhr.send();
        }



        // Print Bill
        function printBill(studentId) {
            // Send AJAX request to fetch student details
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_student_details.php?id=' + studentId, true);

            xhr.onload = function() {
                if (xhr.status === 200) {
                    // Get student details from the response
                    const student = JSON.parse(xhr.responseText);

                    // Prepare the bill details with dynamic data from the database
                    const billDetails = `
                    <p><strong>Student ID:</strong> ${student.id}</p>
                    <p><strong>Name:</strong> ${student.name}</p>
                    <p><strong>Phone Number:</strong> ${student.phone_number}</p>
                    <p><strong>Address:</strong> ${student.address}</p>
                    <p><strong>Amount:</strong> Rs.${student.amount}</p>
                    <p><strong>Semester:</strong> ${student.semester}</p>
                    <p><strong>Major:</strong> ${student.major}</p>
                    <p><strong>Roll No:</strong> ${student.roll_no}</p>
                `;

                    // Display the bill details in the modal
                    document.getElementById('billDetails').innerHTML = billDetails;
                    document.getElementById('printBillModal').style.display = 'block';
                } else {
                    alert("Failed to load student details.");
                }
            };

            xhr.send();


        }
    </script>

</body>

</html>