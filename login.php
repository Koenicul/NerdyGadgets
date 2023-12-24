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

            $query = "
                SELECT CustomerName, DeliveryPostalCode, DeliveryAddressLine2
                FROM customers
                JOIN people ON customers.PrimaryContactPersonID = people.PersonID
                WHERE people.EmailAddress = ?";

            $statement = mysqli_prepare($databaseConnection, $query);

            // Binden van parameters
            mysqli_stmt_bind_param($statement, "s", $user["EmailAddress"]);

            // Uitvoeren van de query
            mysqli_stmt_execute($statement);

            // Resultaten ophalen
            mysqli_stmt_bind_result($statement, $customerName, $deliveryPostalCode, $deliveryAddressLine2);

            // Fetchen van resultaten
            if (mysqli_stmt_fetch($statement)) {
                // Afdrukken van de resultaten
                $user["name"] = $customerName;
                $user["postcode"] = $deliveryAddressLine2;
                $user["house_number"] = $deliveryPostalCode;
                saveUser($user);
            }

            print '<meta http-equiv="refresh" content="0; url=index.php">';
        } else {
            print('<div class="modal" id="myModal" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Geen account gevonden</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <p>Het email of wachtwoord was incorrect</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" style="color: white" data-dismiss="modal">Sluiten</button>
                    </div>
                </div>
            </div>
        </div>
        
        <script type="text/javascript">
        $(window).on("load",function(){
            $("#myModal").modal("show");
            });
            </script>');
            
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
                    <input class="login1" type="email" name="emails" placeholder="Email" required value="<?php if (isset($_POST["emails"])) { print $_POST['emails']; } ?>">
                    <input class="login1" type="password" name="psw" placeholder="Password" required>
                    <input class="button3" type="submit" name="submit" value="Inloggen">
                    <a class="janee" href="aanmelden.php">Geen account meld je aan</a>
			</div>


</body>
</html>
