<?php
    // Initialize the session
    session_start();
    
    session_unset();
    
    // Destroy the session.
    session_destroy();
    
    // Redirect to login page
    header("Location: ../index.php");
    exit;
?>