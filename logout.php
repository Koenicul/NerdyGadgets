<?php
include __DIR__ . "/header.php";
unset($_SESSION["user_email"]);
header("Location: index.php");
?>