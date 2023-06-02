<?php
//logout.php

session_start(); // start the session

include 'config.php';

if (isset($_SESSION['access_token'])) {
    // Reset OAuth access token
    $google_client->revokeToken();
}

// Destroy entire session data.
session_destroy();

// Redirect the user back to the login page
header('location:index.php');
?>