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
<script>
  function validateNum(input, max) {
    if (input.value < 1) input.value = 1;
    if (input.value > max) input.value = max;
  } 
</script>

<div class="p-2">
    
    <?php
    if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
        ?>
        <h1>Inhoud Winkelwagen</h1>
        <div class="row">
            <div class="col-8">
                <?php
                $cart = getCart();
                $price = 0;
                $enteredCouponCode = "";
                $couponCodes = [
                    'KORTING123' => 20,
                    'BERT123' => 10,
                    'ERNST123' => 10,
                    'BERTENERNSTZIJNGEWELDIG' => 25,
                ];

                if (isset($_POST["couponCode"])) {
                    $enteredCouponCode = $_POST["couponCode"];
                }

                foreach ($cart as $id => $quantity) {
                    $StockItem = getStockItem($id, $databaseConnection);
                    $StockItemImage = getStockItemImage($id, $databaseConnection);

                    $price += round($StockItem["SellPrice"], 2) * $quantity;

                    $originalPrice = $price;
                    $discountedPrice = applyCouponCode($originalPrice, $enteredCouponCode);
                    $actualDiscount = $originalPrice - $discountedPrice;
                    $actualDiscount = round($actualDiscount, 2);
                    $discountedPrice = round($discountedPrice, 2);
                    $_SESSION["actualDiscount"] = $actualDiscount;
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
                            <li class="list-inline-item align-middle"><form action="cart.php" method="post"><input hidden name="id" value=<?php print $StockItem["StockItemID"] ?>><input class="numb form-controsl" style="width: fit-content" name="quantity" type="number" min="1" max="<?php print $StockItem['QuantityOnHand']; ?>" onchange="validateNum(this, <?php print $StockItem['QuantityOnHand']; ?>), this.form.submit()" value=<?php print $quantity; ?>></form></li>
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
                    <p>Artikelen (<?php print amountOfItems($cart); ?>) : <?php print sprintf("€ %.2f",$price) ?></p>

                    <!-- Kortingscodes -->
                    <p><form action="cart.php" method="post">
                        <label>Kortingscode:</label>
                        <input class="trans form-control" type="text" name="couponCode" placeholder="Kortingscode">
                        <input class="button2" type="submit" value="Kortingscode gebruiken">
                    </form></p>



                    <?php foreach ($couponCodes as $couponCode => $korting);
                    if (array_key_exists($couponCode, $couponCodes)){ ?>
                        <p>Korting : <?php print sprintf("€ %.2f", $actualDiscount) ?></p>
                    <?php   } ?>
                    <hr class="solid">
                    <p>Totaal : <?php



                        $_SESSION["discountedPrice"] = $discountedPrice;
                        print sprintf("€ %.2f",$discountedPrice); ?></p>
                    <a href="userdata.php">
                        <input class="button2" type="submit" value="Betalen">
                    </a>
                </div>
            </div>
        </div>

        <?php
    } else { ?>
        <h2 class="leeg">Je winkelwagentje is leeg</h2>

        <div class="button-container">
            <a href="categories.php" class="button1">Terug naar home</a>
        </div>
    <?php } ?>
</div>



<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>

<?php include __DIR__ . "/footer.php"; ?>

