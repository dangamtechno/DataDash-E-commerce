<?php
require_once '../../backend/utils/session.php';

// Destroy the current session
destroySession();

header("Location: ../../frontend/html/homepage.php");
exit();