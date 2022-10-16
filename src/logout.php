<?php
session_start();

if(isset($_SESSION['USER'])) {
  header('Location: ../index.php');
  exit;
}

unset($_SESSION['user']);
header('Location: ../index.php');
?>