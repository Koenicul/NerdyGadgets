<!-- dit bestand bevat alle code voor de pagina die één product laat zien -->
<?php
include __DIR__ . "/header.php";
include "cartfuncties.php";

if (isset($_GET["id"])) {
    $cart = getCart();
    unset($cart[$_GET["id"]]);
    saveCart($cart);
}

if (isset($_POST["quantity"]) && isset($_POST["id"])) {
    $cart = getCart();
    $cart[$_POST["id"]] = $_POST["quantity"];
    $quantity = $_POST["quantity"];

    saveCart($cart);
}
?>
<div class="p-2">
    <h1>Inhoud Winkelwagen</h1>
    <div class="row">
        <div class="col-8">
            <?php
            if (count($_SESSION['cart']) > 0) {
            $cart = getCart();
            $price = 0;
            $korting = 0;
            
            foreach ($cart as $id => $quantity) {
                $StockItem = getStockItem($id, $databaseConnection);
                $StockItemImage = getStockItemImage($id, $databaseConnection);

                $price += round($StockItem["SellPrice"], 2) * $quantity;
                ?>
                
                <!-- <a class="ListItem" href='view.php?id=<?php print $StockItem['StockItemID']; ?>'> -->
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
                    <p class="StockItemName"><?php print $StockItem["StockItemName"]; ?></p>
                


                    <p class="StockItemComments"><?php print $StockItem["MarketingComments"]; ?></p>
                    
                    <!-- Wijzigen van hoeveelheid product -->
                    <form action="cart.php" method="post">
                        <input hidden name="id" value=<?php print $StockItem["StockItemID"] ?>>
                        <input style="width: fit-content" name="quantity" type="number" min="1" value=<?php print $quantity; ?>>
                        <input style="width: fit-content" type="submit" value="Test">
                    </form>

                    <a href="cart.php?id=<?php print $id ?>" class="HrefDecoration"><i class="fa fa-solid fa-trash"></i></a>
                </div>
                <!-- </a> -->
            <?php } ?>
        </div>
        <!-- Samenvatting van winkelmandje -->
        <div class="col-2">
            <p><h3>Overzicht</h3></p>
            <p>Artikelen : € <?php print $price ?></p>

            <!-- Kortingscodes -->
            <!-- Alleen nog maar de form geen werkend systeem -->
            <p><form action="cart.php" method="post">
                <label>Kortingscode:</label>
                <input type="text" name="couponCode">
                <input type="submit" value="Verstuur">
            </form></p>
            <?php if ($korting != 0) { ?>
                <p>Korting : € <?php print $korting ?></p>
            <?php } ?>
            <p>------------</p>
            <p>Totaal : € <?php print $price - $korting; ?></p>
        </div>
        <?php

        } else { ?>
            <p>Winkelwagen is leeg</p>
        <?php } ?>
    </div>
</div>
<?php include __DIR__ . "/footer.php"; ?>
