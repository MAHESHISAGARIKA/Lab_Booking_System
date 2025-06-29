<?php
/* Start session so we can destroy it */
session_start();

/* Remove all session variables */
session_unset();

/* Destroy the session */
session_destroy();

/* Redirect the user to the public landing page */
header('Location: index.php');
exit;
