<?php
session_start(); // Session Start
session_unset(); // Session Unset: to empty the array
session_destroy(); // Session Destroy: to cancel the registered session
header('location: index.php'); // Redirect the user
exit(); // Exit the page and end the script
