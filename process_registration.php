<?php
// Database connection
$db_host = "localhost";
$db_user = "root";         
$db_pass = "";            
$db_name = "techsource";  

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST data
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];

// Validate input
if (empty($username) || empty($email) || empty($password) || empty($firstname) || empty($lastname)) {
    echo "All fields are required";
    exit();
}

// Validate email domain
$allowed_domains = array(
    '@mpav.com.ph',
    '@carmensbest.com.ph',
    '@uhdfi.com',
    '@metropacificfreshfarms.com'
);

$valid_domain = false;
foreach ($allowed_domains as $domain) {
    if (str_ends_with(strtolower($email), strtolower($domain))) {
        $valid_domain = true;
        break;
    }
}

if (!$valid_domain) {
    echo "Please use your company email address";
    exit();
}

// Check if username already exists
$stmt = $conn->prepare("SELECT id FROM sys_usertb WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "Username already exists";
    exit();
}

// Check if email already exists
$stmt = $conn->prepare("SELECT id FROM sys_usertb WHERE emailadd = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "Email already exists";
    exit();
}

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Set default values
$user_statusid = '3';  // Set status as pending approval
$user_levelid = 5;     // Default user level
$user_groupid = 5;     // Default user group
$status = 'newly-registered'; // Active
$current_date = date('Y-m-d H:i:s');

// Insert new user
$stmt = $conn->prepare("INSERT INTO sys_usertb (
    user_firstname, 
    user_lastname, 
    username, 
    password, 
    user_statusid,
    user_levelid,
    user_groupid,
    datecreated,
    emailadd,
    status
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("ssssiiissi", 
    $firstname,
    $lastname,
    $username,
    $hashed_password,
    $user_statusid,
    $user_levelid,
    $user_groupid,
    $current_date,
    $email,
    $status
);

if ($stmt->execute()) {
    // Return success message HTML
    echo '
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title">
                <span class="glyphicon glyphicon-ok"></span> 
                Registration Successful
            </h3>
        </div>
        <div class="panel-body">
            <div class="alert alert-success">
                <h4>Thank you for registering!</h4>
                <p>Your account has been successfully created and is pending verification and approval by the administrator.</p> 
                <p>Once approved, the administrator will send you an email notification.</p>
            </div>
            <div class="text-center">
                <a href="login.php" class="btn btn-primary">Go to Login Page</a>
            </div>
        </div>
    </div>';
} else {
    echo "Error registering user";
}

$stmt->close();
$conn->close();
?> 