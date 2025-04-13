<?php
ob_start();
session_start();

// Redirect if not logged in
if (!isset($_SESSION['name']) || $_SESSION['name'] != 'oasis') {
    header('location: ../index.php');
    exit();
}

include('connect.php'); // Include MySQLi connection

try {
    // Insert Student
    if (isset($_POST['std'])) {
        // Validate inputs
        if (empty($_POST['st_id']) || empty($_POST['st_email'])) {
            throw new Exception("All student fields are required.");
        }

        if (!filter_var($_POST['st_email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid student email format.");
        }

        // Check for duplicate student ID
        $check = $conn->prepare("SELECT st_id FROM students WHERE st_id = ?");
        $check->bind_param("s", $_POST['st_id']);
        $check->execute();
        $check->store_result();
        
        if ($check->num_rows > 0) {
            throw new Exception("Student ID already exists.");
        }

        // Insert data
        $sql = "INSERT INTO students (st_id, st_name, st_dept, st_batch, st_sem, st_email) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", 
            $_POST['st_id'],
            $_POST['st_name'],
            $_POST['st_dept'],
            $_POST['st_batch'],
            $_POST['st_sem'],
            $_POST['st_email']
        );

        if ($stmt->execute()) {
            $success_msg = "Student added successfully.";
        } else {
            throw new Exception("Failed to add student: " . $stmt->error);
        }
        $stmt->close();
    }

    // Insert Teacher
    if (isset($_POST['tcr'])) {
        // Validate inputs
        if (empty($_POST['tc_id']) || empty($_POST['tc_email'])) {
            throw new Exception("All teacher fields are required.");
        }

        if (!filter_var($_POST['tc_email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid teacher email format.");
        }

        // Check for duplicate teacher ID
        $check = $conn->prepare("SELECT tc_id FROM teachers WHERE tc_id = ?");
        $check->bind_param("s", $_POST['tc_id']);
        $check->execute();
        $check->store_result();
        
        if ($check->num_rows > 0) {
            throw new Exception("Teacher ID already exists.");
        }

        // Insert data
        $sql = "INSERT INTO teachers (tc_id, tc_name, tc_dept, tc_email, tc_course) 
                VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", 
            $_POST['tc_id'],
            $_POST['tc_name'],
            $_POST['tc_dept'],
            $_POST['tc_email'],
            $_POST['tc_course']
        );

        if ($stmt->execute()) {
            $success_msg = "Teacher added successfully.";
        } else {
            throw new Exception("Failed to add teacher: " . $stmt->error);
        }
        $stmt->close();
    }
} catch (Exception $e) {
    $error_msg = "Error: " . $e->getMessage();
}

$conn->close(); // Close MySQLi connection
?>

<!DOCTYPE html>
<html lang="en">
<!-- head started -->
<head>
<title>Online Attendance Management System 1.0</title>
<meta charset="UTF-8">

  <link rel="stylesheet" type="text/css" href="../css/main.css">
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" >
   
  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" >
   
  <link rel="stylesheet" href="styles.css" >
   
  <!-- Latest compiled and minified JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<style type="text/css">

  .message{
    padding: 10px;
    font-size: 15px;
    font-style: bold;
    color: black;
  }
</style>
</head>
<!-- head ended -->

<!-- body started -->
<body>

    <!-- Menus started-->
    <header>

      <h1>Online Attendance Management System 1.0</h1>
      <div class="navbar">
      <a href="signup.php">Create Users</a>
      <a href="index.php">Add Data</a>
      <a href="../logout.php">Logout</a>

    </div>

    </header>
    <!-- Menus ended -->

<center>
<!-- Error or Success Message printint started -->
<div class="message">
        <?php if(isset($success_msg)) echo $success_msg; if(isset($error_msg)) echo $error_msg; ?>
</div>
<!-- Error or Success Message printint ended -->

<!-- Content, Tables, Forms, Texts, Images started -->
<div class="content">

  <center> Select: <a href="#teacher">Teacher</a> | <a href="">Student</a> <br></center>

  <div class="row" id="student">



      <form method="post" class="form-horizontal col-md-6 col-md-offset-3">
      <h4>Add Student's Information</h4>
      <div class="form-group">
          <label for="input1" class="col-sm-3 control-label">Reg. No.</label>
          <div class="col-sm-7">
            <input type="text" name="st_id"  class="form-control" id="input1" placeholder="student reg. no." />
          </div>
      </div>

      <div class="form-group">
          <label for="input1" class="col-sm-3 control-label">Name</label>
          <div class="col-sm-7">
            <input type="text" name="st_name"  class="form-control" id="input1" placeholder="student full name" />
          </div>
      </div>

      <div class="form-group">
          <label for="input1" class="col-sm-3 control-label">Department</label>
          <div class="col-sm-7">
            <input type="text" name="st_dept"  class="form-control" id="input1" placeholder="department ex. CSE" />
          </div>
      </div>

      <div class="form-group">
          <label for="input1" class="col-sm-3 control-label">Batch</label>
          <div class="col-sm-7">
            <input type="text" name="st_batch"  class="form-control" id="input1" placeholder="batch e.x 2020" />
          </div>
      </div>

      <div class="form-group">
          <label for="input1" class="col-sm-3 control-label">Semester</label>
          <div class="col-sm-7">
            <input type="text" name="st_sem"  class="form-control" id="input1" placeholder="semester ex. Fall-15" />
          </div>
      </div>

      <div class="form-group">
          <label for="input1" class="col-sm-3 control-label">Email</label>
          <div class="col-sm-7">
            <input type="email" name="st_email"  class="form-control" id="input1" placeholder="valid email" />
          </div>
      </div>


      <input type="submit" class="btn btn-primary col-md-2 col-md-offset-8" value="Add Student" name="std" />
    </form>

  </div>
<br><br><br>
  <div class="rowtwo" id="teacher">
  

       <form method="post" class="form-horizontal col-md-6 col-md-offset-3">
        <h4>Add Teacher's Information</h4>
      <div class="form-group">
          <label for="input1" class="col-sm-3 control-label">Teacher ID</label>
          <div class="col-sm-7">
            <input type="text" name="tc_id"  class="form-control" id="input1" placeholder="teacher's id" />
          </div>
      </div>

      <div class="form-group">
          <label for="input1" class="col-sm-3 control-label">Name</label>
          <div class="col-sm-7">
            <input type="text" name="tc_name"  class="form-control" id="input1" placeholder="teacher full name" />
          </div>
      </div>

      <div class="form-group">
          <label for="input1" class="col-sm-3 control-label">Department</label>
          <div class="col-sm-7">
            <input type="text" name="tc_dept"  class="form-control" id="input1" placeholder="department ex. CSE" />
          </div>
      </div>

      <div class="form-group">
          <label for="input1" class="col-sm-3 control-label">Email</label>
          <div class="col-sm-7">
            <input type="email" name="tc_email"  class="form-control" id="input1" placeholder="valid email" />
          </div>
      </div>

      <div class="form-group">
          <label for="input1" class="col-sm-3 control-label">Subject Name</label>
          <div class="col-sm-7">
            <input type="text" name="tc_course"  class="form-control" id="input1" placeholder="subject ex. Software Engineering" />
          </div>
      </div>

      <input type="submit" class="btn btn-primary col-md-2 col-md-offset-8" value="Add Teacher" name="tcr" />
    </form>
    
  </div>


</div><br>
<!-- Contents, Tables, Forms, Images ended -->

</center>
</body>
<!-- Body ended  -->
</html>
