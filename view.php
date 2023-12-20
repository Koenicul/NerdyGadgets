<!-- dit bestand bevat alle code voor de pagina die één product laat zien -->
<?php
include __DIR__ . "/header.php";
include "cartfuncties.php";

$StockItem = getStockItem($_GET['id'], $databaseConnection);
$StockItemImage = getStockItemImage($_GET['id'], $databaseConnection);

if (isset($_POST['reviewpost'])){
    if (isset($_POST['aanbeveling'])){
        $aanbeveling = 1;
    }else{
        $aanbeveling = 0;
    }
    postReview($StockItem["StockItemID"], $databaseConnection, $_POST['comment'], $aanbeveling, $_SESSION["user_email"]);
}
?>

<div id="CenteredContent">
    <?php
    if ($StockItem != null) {
        ?>
        <?php
        if (isset($StockItem['Video'])) {
            ?>
            <div id="VideoFrame">
                <?php print $StockItem['Video']; ?>
            </div>
        <?php }
        ?>

        <div id="ArticleHeader">
            <?php
            if (count($StockItemImage) > 0) {
                // één plaatje laten zien
                if (count($StockItemImage) == 1) {
                    ?>
                    <div id="ImageFrame"
                         style="background-image: url('Public/StockItemIMG/<?php print $StockItemImage[0]['ImagePath']; ?>'); background-size: 300px; background-repeat: no-repeat; background-position: center;"></div>
                    <?php
                } else if (count($StockItemImage) >= 2) { ?>
                    <!-- meerdere plaatjes laten zien -->
                    <div id="ImageFrame">
                        <div id="ImageCarousel" class="carousel slide" data-interval="false">
                            <!-- Indicators -->
                            <ul class="carousel-indicators">
                                <?php for ($i = 0; $i < count($StockItemImage); $i++) {
                                    ?>
                                    <li data-target="#ImageCarousel"
                                        data-slide-to="<?php print $i ?>" <?php print (($i == 0) ? 'class="active"' : ''); ?>></li>
                                    <?php
                                } ?>
                            </ul>

                            <!-- slideshow -->
                            <div class="carousel-inner">
                                <?php for ($i = 0; $i < count($StockItemImage); $i++) {
                                    ?>
                                    <div class="carousel-item <?php print ($i == 0) ? 'active' : ''; ?>">
                                        <img src="Public/StockItemIMG/<?php print $StockItemImage[$i]['ImagePath'] ?>">
                                    </div>
                                <?php } ?>
                            </div>

                            <!-- knoppen 'vorige' en 'volgende' -->
                            <a class="carousel-control-prev" href="#ImageCarousel" data-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </a>
                            <a class="carousel-control-next" href="#ImageCarousel" data-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </a>
                        </div>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div id="ImageFrame"
                     style="background-image: url('Public/StockGroupIMG/<?php print $StockItem['BackupImagePath']; ?>'); background-size: cover;"></div>
                <?php
            }
            ?>


            <h1 class="StockItemID">Artikelnummer: <?php print $StockItem["StockItemID"]; ?></h1>
            <h2 class="StockItemNameViewSize StockItemName">
                <?php print $StockItem['StockItemName']; ?>
            </h2>
            <div class="QuantityText"><?php print getVoorraadTekst($StockItem["QuantityOnHand"]); ?></div>
            <div id="StockItemHeaderLeft">
                <div class="CenterPriceLeft">

                    <div class="CenterPriceLeftChild">

                        <p class="StockItemPriceText"><b><?php print sprintf("€ %.2f", $StockItem['SellPrice']); ?></b></p>

                        <h6> Inclusief BTW </h6>

                    </div>
                    <form method="post" class="form">
                        <button type="submit" class="button" name="addToCart" id="addToCart" data-toggle="modal" data-target="#myModal"> In winkelmandje</button>
                    </form>
                </div>

            </div>

        </div>

        <div id="StockItemDescription">
            <h3>Artikel beschrijving</h3>
            <p><?php print $StockItem['SearchDetails']; ?></p>

        </div>
        <div id="StockItemSpecifications">
            <h3>Artikel specificaties</h3>
            <?php
            $CustomFields = json_decode($StockItem['CustomFields'], true);
            if (is_array($CustomFields)) { ?>
                <table>
                <thead>
                <th>Naam</th>
                <th>Data</th>
                </thead>
                <?php
                foreach ($CustomFields as $SpecName => $SpecText) { ?>
                    <tr>
                        <td>
                            <?php print $SpecName; ?>
                        </td>
                        <td>
                            <?php
                            if (is_array($SpecText)) {
                                foreach ($SpecText as $SubText) {
                                    print $SubText . " ";
                                }
                            } else {
                                print $SpecText;
                            }
                            ?>
                        </td>
                    </tr>
                <?php } ?>
                </table><?php
            } else { ?>

                <p><?php print $StockItem['CustomFields']; ?>.</p>
                <?php
            }
            ?>
        </div>
        <?php
    } else {
        ?><h2 id="ProductNotFound">Het opgevraagde product is niet gevonden.</h2><?php
    } ?>
</div>

<div class="container">
    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Het artikel is toegevoegd aan het winkelmandje</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <p><?php print $StockItem['StockItemName'] ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" style="color: white" data-dismiss="modal">Verder winkelen</button>
                    <button class="button1" onclick="window.location.href='cart.php'">Naar winkelwagen</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$ingelogd = true;
if (isset($_SESSION['user_email'])){
?>
<div id="ReviewContent">
<div id="ReviewDiv">
    <h3>Review plaatsen</h3>
    <form method="post"">
    <label>
        Ik beveel dit product aan: <input type="checkbox" class="checkbox" id="aanbeveling" name="aanbeveling" value="1">
    </label>
    <textarea class="reviewtext" name="comment" required></textarea>
        <input type="submit" class="reviewbutton" name="reviewpost">
    </form>
</div>
</div>
<?php }?>

<script>
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>

<?php
if (isset($_POST['addToCart'])) {
    addProductToCart($StockItem['StockItemID']);
    ?>
    <script type="text/javascript">
        $(window).on('load',function(){
            $('#myModal').modal('show');
        });
    </script>
<?php }
?>
<div id="CenteredContent">
<?php
$reviews = getReview($StockItem["StockItemID"], $databaseConnection);
if ($reviews != array()) {
    print("<h2 id='reviewbox' style='padding: 15px;'>Reviews van dit product</h2>");
}else{
    print("<h2 id='reviewbox' style='padding: 15px;'>Dit product heeft nog geen reviews.</h2>");
}

foreach ($reviews as $review) {
    $naam = getCustomer($review['email'], $databaseConnection);
    $contents = $review['contents'];
    $aanbeveling = $review['aanbeveling'];
    $datum = $review['plaatsingsdatum'];
?>
        <div id="reviewbox">
        <h1 class="StockItemID">Door: <?php print($naam[0]['fullname']); ?></h1>
        <?php
    if ($aanbeveling == 1){
        echo "<p class='midText'>Ik beveel dit product aan.</p>";
    }else{
        echo "<p class='midText'>Ik beveel dit product niet aan.</p>";
    }
    echo "Datum: $datum <br>";
    echo "<br>$contents <br>";
    echo "<br><br>";
    ?>
        </div>
    <?php
}

//
?>
</div>
<?php
//
include __DIR__ . "/footer.php";
?>