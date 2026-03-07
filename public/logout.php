<?php
require_once __DIR__ . '/../includes/config.php';

session_start();
// clear evey session variables
session_unset();
// destroy the session
session_destroy();

// Go to login page
redirectTo('index.php');
