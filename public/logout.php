<?php

session_start();
// clear evey session variables
session_unset();
// destroy the session
session_destroy();

// Go to login page
header("Location: index.php");
exit();
