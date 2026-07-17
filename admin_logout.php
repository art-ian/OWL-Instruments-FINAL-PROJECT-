<?php

session_start();

unset($_SESSION['admin_logged_in']);
unset($_SESSION['admin_username']);
unset($_SESSION['admin_role']);

header("Location: admin_login.php");
exit;