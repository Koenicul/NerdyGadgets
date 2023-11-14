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
?>
<div>
    <h1>Inhoud Winkelwagen</h1>
    <div>
        <?php
        if (isset($_SESSION['cart'])) {
        $cart = getCart();
        $price = 0;
        $korting = 5;
        
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
                <div>
                    <form action="cart.php" method="post">
                    <input hidden name="id" value=<?php print $StockItem["StockItemID"] ?>>
                    <input style="width: fit-content" name="quantity" type="number" min="1" value=<?php print $quantity; ?>>
                    <input style="width: fit-content" type="submit" value="Test">

                    </form>

                </div>
            </div>
            <!-- </a> -->
        <?php } ?>
    </div>
    <!-- Samenvatting van winkelmandje -->
    <div>
        <p>Overzicht</p>
        <p>Artikelen : € <?php print $price ?></p>
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

<?php include __DIR__ . "/footer.php"; ?>
