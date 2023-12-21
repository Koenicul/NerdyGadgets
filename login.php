<?php
include __DIR__ . "/header.php";
require_once('cartfuncties.php');

$database = new Database();

$authentication = new Authentication($database->connection);

$errors = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!empty($_POST['emails']) && !empty($_POST['psw'])) {

        $user = $authentication->login($_POST['emails'], $_POST['psw']);
        if ($user) {
            $_SESSION['user_email'] = $_POST['emails'];
            $_SESSION['user_psw'] = $_POST['psw'];

            $_SESSION['customerIDOrder'] = getCustomerID($_POST['emails'], $databaseConnection);

            //header("Location: index.php");
            //exit();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Slide Navbar</title>
	<link rel="stylesheet" type="text/css" href="slide navbar style.css">
    <link rel="stylesheet" type="text/css" href="help.css">
<link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
</head>
<body>
	<div class="trans1">
		<input type="checkbox" id="chk" aria-hidden="true">

			<div class="signup">
				<form action="login.php" method="post">
					<label for="chk" aria-hidden="true">Login</label>
                    <input class="login1" type="email" name="emails" placeholder="Email">
                    <input class="login1" type="password" name="psw" placeholder="Password">
                    <input class="button3" type="submit" name="submit" value="Inloggen">
                    <a class="janee" href="aanmelden.php">Geen account meld je aan</a>
			</div>



</body>
</html>
