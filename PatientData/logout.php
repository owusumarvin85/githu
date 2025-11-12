<?php
require_once __DIR__ . '/auth.php';

logoutUser();
$_SESSION['flash_success'] = 'You have signed out securely.';
header('Location: index.php');
exit;


