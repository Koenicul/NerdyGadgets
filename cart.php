<!-- dit bestand bevat alle code voor de pagina die één product laat zien -->
<?php
include __DIR__ . "/header.php";
include "cartfuncties.php";

if (isset($_POST["quantity"]) && isset($_POST["id"])) {
    $cart = getCart();
    $cart[$_POST["id"]] = $_POST["quantity"];
    $quantity = $_POST["quantity"];

    saveCart($cart);
}

if (isset($_POST["deleteProduct"])) {
    $cart = getCart();
    if (array_key_exists($_POST["deleteProduct"], $cart)) {
        unset($cart[$_POST["deleteProduct"]]);
    }
    saveCart($cart);
}

?>
<div class="p-2">
    <h1>Inhoud Winkelwagen</h1>
    <?php
    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
        ?>
        <div class="row">
            <div class="col-8">
                <?php
                $cart = getCart();
                $price = 0;
                $korting = 0;

                if (isset($_POST["couponCode"])){
                    if ($_POST["couponCode"] == "korting123"){
                        $korting = 25;
                    }
                }
                foreach ($cart as $id => $quantity) {
                    $StockItem = getStockItem($id, $databaseConnection);
                    $StockItemImage = getStockItemImage($id, $databaseConnection);

                    $price += round($StockItem["SellPrice"], 2) * $quantity;
                    ?>

                    <div id="ProductFrame">
                        <?php
                        if (isset($StockItemImage[0]['ImagePath'])) { ?>
                            <div class="ImgFrame"
                                 style="background-image: url('<?php print "Public/StockItemIMG/" . $StockItemImage[0]['ImagePath']; ?>'); background-size: 230px; background-repeat: no-repeat; background-position: center;"></div>
                        <?php } else if (isset($StockItem['BackupImagePath'])) { ?>
                            <div class="ImgFrame"
                                 style="background-image: url('<?php print "Public/StockGroupIMG/" . $StockItem['BackupImagePath'] ?>'); background-size: cover;"></div>
                        <?php }
                        ?>

                        <div id="StockItemFrameRight">
                            <div class="CenterPriceLeftChild">
                                <h1 class="StockItemPriceText"><?php print sprintf("€ %.2f", round($StockItem["SellPrice"], 2) * $quantity); ?></h1>
                                <h6>Inclusief BTW </h6>
                            </div>
                        </div>

                        <h1 class="StockItemID">Artikelnummer: <?php print $StockItem["StockItemID"]; ?></h1>
                        <a href='view.php?id=<?php print $StockItem['StockItemID']; ?>' class="StockItemName"><?php print $StockItem["StockItemName"]; ?></a>

                        <p class="StockItemComments"><?php print $StockItem["MarketingComments"]; ?></p>

                        <!-- Wijzigen van hoeveelheid product -->
                        <ul class="list-inline">
                            <li class="list-inline-item align-middle"><form action="cart.php" method="post"><input hidden name="id" value=<?php print $StockItem["StockItemID"] ?>><input class="numb form-control" style="width: fit-content" name="quantity" type="number" min="1" onchange="this.form.submit()" value=<?php print $quantity; ?>></form></li>
                            <li class="list-inline-item align-middle"><form method="post"><button class="btn btn-link" style="text-decoration: none; color: inherit" type="submit" name="deleteProduct" value="<?php print $StockItem["StockItemID"] ?>"><i class='fa fa-solid fa-trash'></i></button></form></li>
                        </ul>

                        <p class="voorraad"><?php print getVoorraadTekst($StockItem['QuantityOnHand']); ?></p>
                    </div>

                    <script>
                        function deleteProduct(id) {
                        }
                    </script>
                <?php } ?>
            </div>
            <!-- Samenvatting van winkelmandje -->
            <div class="col-2">
                <div class="achter">
                    <p><h3>Overzicht</h3></p>
                    <p>Aantal artikelen (<?php print amountOfItems($cart); ?>) </p>
                    <p>Prijs <?php print sprintf("€ %.2f",$price) ?> </p>

                    <!-- Kortingscodes -->
                    <p><form action="cart.php" method="post">
                        <label>Kortingscode:</label>
                        <input class="trans form-control" type="text" name="couponCode">
                        <input class="button2" type="submit" value="Kortingscode gebruiken">
                    </form></p>
                    <?php if ($korting != 0) { ?>
                        <p>Korting : <?php print sprintf("€ %.2f", $korting) ?></p>
                    <?php } ?>
                    <hr class="solid">
                    <p>Totaal : <?php
                        if (($price - $korting) < 0){
                            $price = 0;
                        }else{
                            $price-=$korting;
                        }

                        print sprintf("€ %.2f",$price); ?></p>
                    <input class="button2" type="submit" value="Betalen">
                </div>
            </div>
        </div>

        <?php
    } else { ?>
        <h2 class="leeg">Je winkelwagentje is leeg</h2>

        <div class="button-container">
            <a href="index.php" class="button1">Terug naar home</a>
        </div>
    <?php } ?>
</div>

<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>

<?php include __DIR__ . "/footer.php"; ?>

