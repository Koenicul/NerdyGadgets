<link rel="stylesheet" href="scroll.css">
<?php include __DIR__ . "/header.php";
include "cartfuncties.php";
require __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();



$cart = getCart();
$price = getPrice($cart);
if (isset($_SESSION["korting"])) {
    $price -= $_SESSION["korting"];
    if ($price < 0) {
        $price = 0;
    }
}
$verzendkosten = 2;



function GetAddress($postalcode, $houseNumber)
{
    $api_key = $_ENV["TOKEN"];
    $url = "https://json.api-postcode.nl?postcode=". $postalcode ."&number=" . $houseNumber;
    $ch = curl_init($url);
    $headers = array(
        'token: ' . $api_key
    );
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);

    curl_close($ch);

    if ($response != '{"error":"invalid postcode."}') {
        return $response;
    }
//    popup if invalid postalcode

    echo '<script>alert("Geen adres gevonden.")</script>';
//
}

if(isset($_POST['submit'])) {
    $postalcode = $_POST['postalcode'];
    $postalcode = str_replace(" ", "", $postalcode);

    $response = GetAddress($postalcode, $_POST["houseNumber"]);
    if ($response) {
        foreach ($cart as $id => $quantity){
            decrementStockitems($id, $databaseConnection, $quantity);
        }
        $cart = array();
        saveCart($cart);
        $response = json_decode($response, true);
        $orderData = array(
            "CustomerName" => strval($_POST['name']),
            "AccountOpeningDate" => date('Y-m-d'),
            "City" => $response['city'],
            "Street" => $response['street'],
            "Postalcode" => $postalcode,
            "AddressNumber" => $_POST['houseNumber']
        );

        addCustomerData($databaseConnection, $orderData);
        header("refresh:0.1;url=ideal.php");
    }
}

?>
    <div class="containers">

    <form method="post" class="form">
        <h1 class="">Vul uw gegevens in </h1>

            <div class="form-group">
                <label>Naam</label>
                <input class="form-control" type="text" name="name" required placeholder="Naam">
            </div>

            <div class="form-group">
                <label>Postcode</label>
                <input class="form-control" type="text" name="postalcode" required id="postalcode" placeholder="Postcode">
            </div>

            <div class="form-group">
                <label>Huisnummer</label>
                <input class="form-control" type="text" name="houseNumber" required id="houseNumber" placeholder="Huisnummer">
            </div>

            <div class="form-group">
                <label>Bank</label>
                <select required name="bank" class="form-control">
                    <option value="" selected disabled>Selecteer je bank</option>
                    <option>ABN-Amro Bank</option>
                    <option>ASN Bank</option>
                    <option>Bunq</option>
                    <option>DHB Bank</option>
                    <option>ING Bank</option>
                    <option>Knab</option>
                    <option>Rabobank</option>
                    <option>Regiobank</option>
                    <option>SNS Bank</option>
                    <option>Triodos Bank</option>
                </select>
            </div>

        <div>
    </form>

        <div class="col-2">
            <div class="achters">
                <p><h3>Overzicht</h3></p>
                <p>Artikelen (<?php print amountOfItems($cart); ?>) : <?php print sprintf("€ %.2f",$price) ?></p>

                <p>Verzendkosten: <?php print sprintf("€ %.2f",$verzendkosten) ?></p>

                <hr class="solid">

                <p>Totaal : <?php print sprintf("€ %.2f",$price + $verzendkosten); ?></p>
                <input class="button2" type="submit" name="submit" value="Plaatsen Bestelling">
            </div>
        </div>
    </form>
    </div>

<?php include __DIR__ . "/footer.php"; ?>