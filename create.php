<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$name = $address = $salary = $username = $password = $confirm_password = "";
$name_err = $address_err = $salary_err = $username_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
// Validate name
if(empty(trim($_POST["name"]))){
    $name_err = "Please enter a name.";     
} elseif(strlen(trim($_POST["name"])) < 0){
    $name_err = "name must have atleast 6 characters.";
} else{
    $name = trim($_POST["name"]);
}

// Validate address
if(empty(trim($_POST["address"]))){
    $address_err = "Please enter a address.";     
} elseif(strlen(trim($_POST["address"])) < 0){
    $address_err = "address must have atleast 6 characters.";
} else{
    $address = trim($_POST["address"]);
}


// Validate salary
if(empty(trim($_POST["salary"]))){
    $salary_err = "Please enter a salary.";     
} elseif(strlen(trim($_POST["salary"])) < 0){
    $salary_err = "salary must have atleast 6 characters.";
} else{
    $salary = trim($_POST["salary"]);
}

    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($name_err) && empty($address_err) && empty($salary_err) && empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (name701, address, salary, username, password) VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssiss", $param_name, $param_address, $param_salary, $param_username, $param_password);
            
            // Set parameters
            $param_name = $name;
            $param_address = $address;
            $param_salary = $salary;
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: welcome.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE HTML>
<html>
<head>
<title>Sign up</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Eatery Responsive web template, Bootstrap Web Templates, Flat Web Templates, Andriod Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyErricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<link href="css/bootstrap.css" rel='stylesheet' type='text/css' />
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="js/jquery.min.js"></script>
<!-- Custom Theme files -->
<link href="css/style.css" rel='stylesheet' type='text/css' />
<!-- Custom Theme files -->
<head>
    <title>Gomag</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link href="layout/styles/layout.css" rel="stylesheet" type="text/css" media="all">
</head>

<body id="top">

    <!-- Top Background Image Wrapper -->
    <div class="bgded" style="background-image:url('images/demo/backgrounds/background-01.jpg');">

        <div class="wrapper row1">
            <header id="header" class="hoc clear">

                <div id="logo" class="fl_left">
                <img src="images/demo/07.jpg" alt="">
                    <h1><a href="index.html">Lucas</a></h1>
                </div>
        <nav id="mainav" class="fl_right">
          <ul class="clear">
            <li class="active"><a href="index.html">Home</a></li>
            <li><a class="drop" href="aboutus.html">About us</a></li>
            <li><a class="drop" href="careers.html">Careers</a></li>
            <li><a href="orderonline.php">Order online</a></li>
            <li><a href="contactus.html">Contact Us</a></li>
            <li><a href="create.php">Register</a></li>
          </ul>
        </nav>

            </header>
<!--banner-->
	<div class="banner">
		<!--header-->
		<div class="headder">
			<div class="container">				
				<nav class="navbar navbar-default">
					<div class="container-fluid">
					<!-- Brand and toggle get grouped for better mobile display -->
						<div class="navbar-header">
							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							<a class="navbar-brand" href="index.html"> <img src="images/logo.png" alt=""/> </a>
						</div>
						
<div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                            <span class="invalid-feedback"><?php echo $name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>"><?php echo $address; ?></textarea>
                            <span class="invalid-feedback"><?php echo $address_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>username</label>
                            <textarea name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>"><?php echo $username; ?></textarea>
                            <span class="invalid-feedback"><?php echo $username_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>password</label>
                            <textarea name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>"><?php echo $password; ?></textarea>
                            <span class="invalid-feedback"><?php echo $password_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                            <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Salary</label>
                            <input type="text" name="salary" class="form-control <?php echo (!empty($salary_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $salary; ?>">
                            <span class="invalid-feedback"><?php echo $salary_err;?></span>
                        </div>
                        <input type="submit" href="welcome.html" class="btn btn-primary" value="Submit">
                        <a href="index.html" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
</div>  
<div class="copyright_section">
        <div class="container">
          <p class="copyright_text">20IT2 Design by <a href="https://html.design">Jiang Yiyi(Tom)</a></p>
        </div>
      </div>



<script src="js/bootstrap.min.js"></script>
<!------ Light Box ------>
<link rel="stylesheet" href="css/swipebox.css">
<script src="js/jquery.swipebox.min.js"></script> 
    <script type="text/javascript">
		jQuery(function($) {
			$(".swipebox").swipebox();
		});
	</script>
<!------ Eng Light Box ------>	   
<!-- Prettify -->
<link href="css/owl.carousel.css" rel="stylesheet">
<script src="js/owl.carousel.js"></script>
	<script>
		$(document).ready(function() {
		$("#owl-demo3").owlCarousel({
			items : 6,
			lazyLoad : true,
			autoPlay : true,
			navigation: false,
			pagination: false,
		 });
		});
	</script>
</body>
</html>		