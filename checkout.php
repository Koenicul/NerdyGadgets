<?php include __DIR__ . "/header.php";
include "cartfuncties.php";
include "addressFunctions.php";

$user = getUser();
$cart = getCart();
$price = getPrice($cart);
$verzendkosten = 2;
        
if (isset($_POST["submit"]) && isset($_POST["bank"])) {
    foreach ($cart as $id => $quantity) {
        //     decrementStockitems($id, $databaseConnection, $quantity);
        // }
        // $cart = array();
        // saveCart($cart);

        header("refresh:0.1;url=ideal.php");
    }
}
?>

<form method="post">
    
    <div class="col-2">
        <div class="achter">
            <p><h3>Overzicht</h3></p>

            <p>Wordt geleverd naar <?php print getUserAddress($user); ?></p>
            <a href="userdata.php">
                <input class="button2" type="button" value="Gegevens Aanpassen">
            </a>
            <p class="pt-2">Artikelen (<?php print amountOfItems($cart); ?>) : <?php print sprintf("€ %.2f",$price) ?></p>

            <p>Verzendkosten: <?php print sprintf("€ %.2f",$verzendkosten) ?></p>

            <hr class="solid">

            <p>Totaal : <?php print sprintf("€ %.2f",$price + $verzendkosten); ?></p>
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
            <input class="button2" type="submit" name="submit" value="Plaatsen Bestelling">
        </div>
    </div>
</form>

<?php include __DIR__ . "/footer.php"; ?>

<!-- foreach ($cart as $id => $quantity){
            decrementStockitems($id, $databaseConnection, $quantity);
        } -->