<?php include __DIR__ . "/header.php";
include "cartfuncties.php";

$user = getUser();
$cart = getCart();
$price = getPrice($cart);
$verzendkosten = 2;

$database = new Database();

$authentication = new Authentication($database->connection);
        
if (isset($_POST["submit"]) && isset($_POST["bank"]) && $_POST["g-recaptcha-response"] != "") {   
    $cart = array();
    saveCart($cart);
    $_SESSION["couponCode"] = 0;
    if (!isset($_SESSION["user_email"])) {
        $authentication->addCustomer($_SESSION["user"]["name"], $_SESSION["user"]["email"],$_SESSION["user"]["postcode"], $_SESSION["user"]["house_number"]);
    }

    $order = insertIntoOrder($databaseConnection, $_SESSION['customerIDOrder']);
    foreach ($cart as $id => $quantity) {
        decrementStockitems($id, $databaseConnection, $quantity);
        insertIntoOrderLine($id, $quantity, $databaseConnection, $order);
    }

    print '<meta http-equiv="refresh" content="0; url=ideal.php">';
}
?>

<form method="post">
    
    <div>
        <div class="achters">
            <p><h3>Overzicht</h3></p>

            <p>Wordt geleverd naar <?php print getUserAddress($user); ?></p>
            <a href="userdata.php">
                <input class="button2" type="button" value="Gegevens Aanpassen">
            </a>
            <p class="pt-2">Artikelen (<?php print amountOfItems($cart); ?>) : <?php print sprintf("€ %.2f",$_SESSION["discountedPrice"]) ?></p>

            <p>Verzendkosten: <?php print sprintf("€ %.2f",$verzendkosten) ?></p>

            <hr class="solid">

            <p>Totaal : <?php print sprintf("€ %.2f",$_SESSION["discountedPrice"] + $verzendkosten); ?></p>
            <div class="form-group">
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

            <div class="g-recaptcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div>

            <input class="button2" type="submit" name="submit" value="Plaatsen Bestelling">
        </div>
    </div>
</form>

<script src='https://www.google.com/recaptcha/api.js'></script>

<?php

include __DIR__ . "/footer.php"; ?>