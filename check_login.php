<?php
  session_start(); // Start session to access session variables

  // Check if the user is logged in (e.g., you store 'user_id' in session when logged in)
  if (isset($_SESSION['user_id'])) {
    // User is logged in
    echo 'logged_in';
  } else {
    // User is not logged in
    echo 'not_logged_in';
  }
?>
