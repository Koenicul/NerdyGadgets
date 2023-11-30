<?php include __DIR__ . "/header.php";
include "cartfuncties.php";
include "addressfunctions.php";
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

if (validateForm($_POST)) {
    $postalcode = $_POST['postalcode'];
    $postalcode = str_replace(" ", "", $postalcode);

    $response = GetAddress($postalcode, $_POST["houseNumber"]);
    if ($response) {
        $response = json_decode($response, true);
        $response["name"] = $_POST["name"];

        saveUser($response);

        header("refresh:0.1;url=checkout.php");
    }
}
$user = getUser();
?>

<div class="col-2">
    <div class="achter">
        <p><h3>Jouw Gegevens</h3></p>

        <form method="post">
            <div>
                <div class="form-group">
                    <label>Naam</label>
                    <input class="form-control" type="text" name="name" required placeholder="name" value="<?php if (isset($user["name"]) && $user["name"] != "") { print $user["name"]; } ?>">
                </div>

                <div class="form-group">
                    <label>Postcode</label>
                    <input class="form-control" type="text" name="postalcode" required id="postalcode" placeholder="Postcode" value=<?php if (isset($user["postcode"]) && $user["postcode"] != "") { print $user["postcode"]; } ?>>
                </div>

                <div class="form-group">
                    <label>Huisnummer</label>
                    <input class="form-control" type="text" name="houseNumber" required id="houseNumber" placeholder="Huisnummer" value=<?php if (isset($user["house_number"]) && $user["house_number"] != "") { print $user["house_number"]; } ?>>
                </div>

                <div class="form-group">
                    <input class="button2" type="submit" name="submit" value="Naar Betalen">
                </div>
            </div>
        </form>
    </div>
</div>
<?php include __DIR__ . "/footer.php"; ?>