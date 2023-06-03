<?php
// Start session on web page
session_start();

// Include Google Client Library for PHP autoload file
require_once 'vendor/autoload.php';

// Make object of Google API Client for call Google API
$google_client = new Google_Client();

// Set the OAuth 2.0 Client ID
$google_client->setClientId('326092266971-qk7uaatqc63sbb0ik8olm6kgila4di35.apps.googleusercontent.com');

// Set the OAuth 2.0 Client Secret key
$google_client->setClientSecret('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');

// Set the OAuth 2.0 Redirect URI
$google_client->setRedirectUri('http://localhost/Login-with-Google-Account-using-PHP-and-xamp/index.php');

// Set the scopes to get the email and profile
$google_client->addScope('email profile');

$login_button = '';

// Check if the user is logging in with Google
if (isset($_GET["code"])) {
    // Fetch the access token with the authorization code
    $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);

    // If there is no error in the token
    if (!isset($token['error'])) {
        // Set the access token
        $google_client->setAccessToken($token['access_token']);

        // Store the access token in a session variable
        $_SESSION['access_token'] = $token['access_token'];

        // Create an instance of the Google OAuth2 service
        $google_service = new Google_Service_Oauth2($google_client);

        // Get the user information
        $data = $google_service->userinfo->get();

        // Sanitize and validate user data
        $full_name = filter_var($data['given_name'] . ' ' . $data['family_name'], FILTER_SANITIZE_STRING);
        $email = filter_var($data['email'], FILTER_SANITIZE_EMAIL);
        $photo = filter_var($data['picture'], FILTER_SANITIZE_URL);

        // Connect to the database
        $mysqli = new mysqli("localhost", "root", "", "my_db");

        // Check connection
        if ($mysqli->connect_error) {
            die("Connection failed: " . $mysqli->connect_error);
        }

        // Check if the user already exists in the database
        $stmt = $mysqli->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // Update the user's existing record
            $stmt = $mysqli->prepare("UPDATE users SET full_name = ?, photo = ? WHERE email = ?");
            $stmt->bind_param("sss", $full_name, $photo, $email);
            $stmt->execute();
        } else {
            // Save user data in database
            $stmt = $mysqli->prepare('INSERT INTO users (full_name, email, photo) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE full_name = ?, email = ?, photo = ?');
            $stmt->bind_param('ssssss', $full_name, $email, $photo, $full_name, $email, $photo);
            $stmt->execute();
        }

        // Close the statement and the database connection
        $stmt->close();
        $mysqli->close();

        // Store the user's information in the session
        $_SESSION['user_full_name'] = $full_name;
        $_SESSION['user_email_address'] = $email;
        $_SESSION['user_image'] = $photo;
    }
}

// If the user is not logged in with Google, show the login button
if (!isset($_SESSION['access_token'])) {
    $login_button = '<a href="' . $google_client->createAuthUrl() . '">Login With Google</a>';
}
?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>PHP Login using Google Account and store data in mySql(xamp)</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"> -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script> -->
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script> -->
</head>
<body>
    <div align="center">
        <br />
        <h2 align="center">PHP Login using Google Account and store data in mySql(xamp)</h2>
        <br />
        <div align="center" class="panel panel-default">
            <?php if ($login_button == ''): ?>
                <!-- If the user is logged in, show their information -->
                <div class="panel-heading">Welcome User</div>
                <div class="panel-body">
                    <img src="<?= $_SESSION["user_image"] ?>" class="img-responsive img-circle img-thumbnail" />
                    <h3><b>Name :</b> <?= $_SESSION['user_full_name'] ?></h3>
                    <h3><b>Email :</b> <?= $_SESSION['user_email_address'] ?></h3>
                    <h3><a href="logout.php">Logout</h3>
                </div>
            <?php else: ?>
                <!-- If the user is not logged in, show the login button -->
                <div align="center"><?= $login_button ?></div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>