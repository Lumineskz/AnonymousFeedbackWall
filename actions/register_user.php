<?php
include "../config/config.php";
$u = $_POST['username'];
$p = password_hash($_POST['password'], PASSWORD_DEFAULT);
$conn->query("INSERT INTO users(username,password) VALUES('$u','$p')");
header("Location: ../pages/login.php");
