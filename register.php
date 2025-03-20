<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta charset="utf-8" />
        <title>Registration Page</title>

        <meta name="description" content="User registration page" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
        <link rel="stylesheet" href="assets/font-awesome/4.5.0/css/font-awesome.min.css" />
        <link rel="stylesheet" href="assets/css/fonts.googleapis.com.css" />
        <link rel="stylesheet" href="assets/css/ace.min.css" />
        <link rel="stylesheet" href="assets/css/ace-rtl.min.css" />

        <script type="text/javascript">
            function validateForm() {
                var password = document.getElementById("password").value;
                var confirm_password = document.getElementById("confirm_password").value;
                
                if (password != confirm_password) {
                    document.getElementById("password_error").style.display = "block";
                    return false;
                }
                return true;
            }

            function validateEmail(email) {
                const allowedDomains = [
                    '@mpav.com.ph',
                    '@carmensbest.com.ph',
                    '@uhdfi.com',
                    '@metropacificfreshfarms.com'
                ];
                
                email = email.toLowerCase();
                return allowedDomains.some(domain => email.endsWith(domain.toLowerCase()));
            }

            function processRegistration() {
                if (!validateForm()) {
                    return;
                }

                var email = document.getElementById("email").value;
                if (!validateEmail(email)) {
                    document.getElementById("error_message").innerHTML = "Please use your company email address";
                    document.getElementById("error_message").style.display = "block";
                    return;
                }

                var xhr = new XMLHttpRequest();
                var firstname = document.getElementById("firstname").value;
                var lastname = document.getElementById("lastname").value;
                var username = document.getElementById("username").value;
                var email = document.getElementById("email").value;
                var password = document.getElementById("password").value;

                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4) {
                        if (xhr.status == 200) {
                            var response = xhr.responseText;
                            document.getElementById("box").innerHTML = response;
                        }
                    }
                };

                xhr.open("POST", "process_registration.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.send(
                    "firstname=" + encodeURIComponent(firstname) +
                    "&lastname=" + encodeURIComponent(lastname) +
                    "&username=" + encodeURIComponent(username) +
                    "&email=" + encodeURIComponent(email) +
                    "&password=" + encodeURIComponent(password)
                );
            }
        </script>

        <style>
            .panel-success {
                border-color: #d6e9c6;
            }
            .panel-success > .panel-heading {
                color: #3c763d;
                background-color: #dff0d8;
                border-color: #d6e9c6;
            }
            .alert-success {
                color: #3c763d;
                background-color: #dff0d8;
                border-color: #d6e9c6;
                margin-bottom: 20px;
            }
        </style>
    </head>

    <body class="login-layout">
        <div class="main-container">
            <div class="main-content">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="login-container">
                            <div class="center">
                                <h1>
                                    <i class="fa fa-bell red" aria-hidden="true"></i>
                                </h1>
                            </div>

                            <div id="box">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">
                                            <span class="glyphicon glyphicon-user"></span> 
                                            Register
                                        </h3>
                                    </div>
                                    <div class="panel-body">
                                        <form role="form" name="form_register" onsubmit="return false;">
                                            <fieldset>
                                                <div class="form-group">
                                                    <input class="form-control" placeholder="First Name" name="firstname" id="firstname" type="text" required autofocus>
                                                </div>
                                                <div class="form-group">
                                                    <input class="form-control" placeholder="Last Name" name="lastname" id="lastname" type="text" required>
                                                </div>
                                                <div class="form-group">
                                                    <input class="form-control" placeholder="Username" name="username" id="username" type="text" required>
                                                </div>
                                                <div class="form-group">
                                                    <input class="form-control" placeholder="Email" name="email" id="email" type="email" required>
                                                    <small class="form-text text-muted">Allowed company email address only</small>
                                                </div>
                                                <div class="form-group">
                                                    <input class="form-control" placeholder="Password" id="password" name="password" type="password" required>
                                                </div>
                                                <div class="form-group">
                                                    <input class="form-control" placeholder="Confirm Password" id="confirm_password" name="confirm_password" type="password" required>
                                                </div>

                                                <div class="alert alert-danger alert-dismissable" style="display: none" id="password_error">
                                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                    Passwords do not match!
                                                </div>

                                                <div class="alert alert-danger alert-dismissable" style="display: none" id="error_message">
                                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                </div>

                                                <button type="submit" class="btn btn-primary btn-success btn-lg btn-block" onclick="processRegistration()">Register</button>
                                                
                                                <div class="text-center" style="margin-top: 15px;">
                                                    Already have an account? <a href="login.php">Login here</a>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- basic scripts -->
        <script src="assets/js/jquery-2.1.4.min.js"></script>
        <script src="assets/js/jquery.shake.js"></script>
    </body>
</html> 