<?php
session_start();

if (isset($_POST['login'])) {
    try {
        // Validate inputs
        if (empty($_POST['username'])) {
            throw new Exception("Username is required!");
        }
        if (empty($_POST['password'])) {
            throw new Exception("Password is required!");
        }
        if (empty($_POST['type'])) {
            throw new Exception("Role is required!");
        }

        include('connect.php'); // Ensure this is a PDO connection

        // Prepare SQL statement with placeholders
        $sql = "SELECT * FROM admininfo 
               WHERE username = :username 
               AND password = :password 
               AND type = :type";
        
        $stmt = $conn->prepare($sql);
        
        // Bind parameters
        $stmt->bindParam(':username', $_POST['username']);
        $stmt->bindParam(':password', $_POST['password']);
        $stmt->bindParam(':type', $_POST['type']);
        
        // Execute query
        $stmt->execute();
        
        // Check if user exists
        if ($stmt->rowCount() > 0) {
            $_SESSION['name'] = "oasis";
            
            // Get user data (for potential future use)
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Redirect based on user type
            switch ($user['type']) { // Use value from database, not POST
                case 'teacher':
                    header('Location: teacher/index.php');
                    break;
                case 'student':
                    header('Location: student/index.php');
                    break;
                case 'admin':
                    header('Location: admin/index.php');
                    break;
                default:
                    throw new Exception("Invalid user role!");
            }
            exit();
        } else {
            throw new Exception("Username, Password, or Role is incorrect!");
        }

    } catch (PDOException $e) {
        $error_msg = "Database Error: " . $e->getMessage();
        header('Location: login.php?error=' . urlencode($error_msg));
        exit();
    } catch (Exception $e) {
        $error_msg = $e->getMessage();
        header('Location: login.php?error=' . urlencode($error_msg));
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>

	<title>Online Attendance Management System</title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<!-- Latest compiled and minified CSS -->
	 
	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" >
	 
	<link rel="stylesheet" href="css/style.css" >
	 
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</head>

<body>
	<center>

<header>

  <h1>Online Attendance Management System 1.0</h1>

</header>

<h1>Login</h1>

<?php
//printing error message
if(isset($error_msg))
{
	echo $error_msg;
}
?>

<!-- Old Version -->
<!-- 
<form action="" method="post">
	
	<table>
		<tr>
			<td>Username </td>
			<td><input type="text" name="username"></input></td>
		</tr>
		<tr>
			<td>Password</td>
			<td><input type="password" name="password"></input></td>
		</tr>
		<tr>
			<td>Role</td>
			<td>
			<select name="type">
				<option name="teacher" value="teacher">Teacher</option>
				<option name="student" value="student">Student</option>
				<option name="admin" value="admin">Admin</option>
			</select>
			</td>
		</tr>
		<tr><td><br></td></tr>
		<tr>
			<td><button><input type="submit" name="login" value="Login"></input></button></td>
			<td><button><input type="reset" name="reset" value="Reset"></button></td>
		</tr>
	</table>
</form>
-->

<div class="background">
	<div class="row">

		<form method="post" class="form-horizontal col-md-6 col-md-offset-3">
			<h3>Login</h3>
			<div class="form-group">
			    <label class="s2" for="input1" class="username">Username</label>
			    <div class="col-sm-7">
			      <input type="text" name="username"  class="form-control" id="input1" placeholder="your username" />
			    </div>
			</div>

			<div class="form-group">
			    <label class="s1" for="input1" class="col-sm-3 control-label">Password</label>
			    <div class="col-sm-7">
			      <input type="password" name="password"  class="form-control" id="input1" placeholder="your password" />
			    </div>
			</div>


			<div class="form-group" class="radio">
			<label for="input1" class="col-sm-3 control-label">Role</label>
			<div class="colm-1">
			  <label>
			    <input class="role" type="radio" name="type" id="optionsRadios1" value="student" checked> Student
			  </label>
			  	  <label>
			    <input class="role" type="radio" name="type" id="optionsRadios1" value="teacher"> Teacher
			  </label>
			  <label>
			    <input class="role" type="radio" name="type" id="optionsRadios1" value="admin"> Admin
			  </label>
			</div>
			</div>


			<input class="roles" type="submit" class="btn btn-primary" value="Login" name="login" />
			<div class="social">
          <div class="go"><i class="fab fa-google"></i><a href="signup.php"><strong>Signup Here</strong></a></div>
          <div class="fb"><i class="fab fa-facebook"></i><a href="reset.php"><strong>Reset Here</strong></a></div>
        </div>
		</form>
	</div>
</div>




</center>
</body>
</html>