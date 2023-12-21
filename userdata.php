<?php include __DIR__ . "/header.php";
require "cartfuncties.php";

require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

if (validateForm($_POST)) {
    $user = GetAddress($_POST['postalcode'], $_POST["houseNumber"]);
    if ($user) {
        $user["name"] = $_POST["name"];
        if (isset($_POST["email"])) {
            $user["email"] = $_POST["email"];
        }

        saveUser($user);

        header("refresh:0.1;url=checkout.php");
    }
}
$user = getUser();

if (isset($_SESSION['user_email'])) {
    $email = $_SESSION['user_email'];
}

$query = "
    SELECT CustomerName, DeliveryPostalCode, DeliveryAddressLine2
    FROM customers
    JOIN people ON customers.PrimaryContactPersonID = people.PersonID
    WHERE people.EmailAddress = ?";

$statement = mysqli_prepare($databaseConnection, $query);

// Binden van parameters
mysqli_stmt_bind_param($statement, "s", $email);

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

}


?>

     <div class="achters">
        <p><h3>Jouw Gegevens</h3></p>
        <form method="post">
            <div>
                <div class="form-group">
                    <label>Naam*</label>
                    <input class="form-control" type="text" name="name" required placeholder="Naam" value="<?php if (isset($user["name"]) && $user["name"] != "") { print $user["name"]; } ?>">
                </div>
                <?php if (!isset($_SESSION['user_email'])) { ?>
                <div class="form-group">
                    <label>Email*</label>
                    <input class="form-control" type="text" name="email" required placeholder="Naam" value="<?php if (isset($user["email"]) && $user["email"] != "") { print $user["email"]; } ?>">
                </div>
                <?php } ?>

                <div class="form-group">
                    <label>Postcode*</label>
                    <input class="form-control" type="text" name="postalcode" required id="postalcode" placeholder="Postcode" value=<?php if (isset($user["postcode"]) && $user["postcode"] != "") { print $user["postcode"]; } ?>>
                </div>

                <div class="form-group">
                    <label>Huisnummer*</label>
                    <input class="form-control" type="text" name="houseNumber" required id="houseNumber" placeholder="Huisnummer" value=<?php if (isset($user["house_number"]) && $user["house_number"] != "") { print $user["house_number"]; } ?>>
                </div>

                <div class="form-group">
                    <input class="button2" type="submit" name="submit" value="Naar Betalen">
                </div>
            </div>
        </form>
    </div>

<?php include __DIR__ . "/footer.php"; ?>
