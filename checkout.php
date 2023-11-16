<?php include __DIR__ . "/header.php"; 
include "cartfuncties.php";
$cart = getCart();
$price = 10;
$verzendkosten = 2;
?>

<form method="post" action="https://www.ideal.nl/demo/en/">
    <div>
        <div class="form-group">
            <label>Naam</label>
            <input class="form-control" type="text" name="name" placeholder="Naam">
        </div>

        <div class="form-group">
            <label>Postcode</label>
            <input class="form-control" type="text" name="postalcode" placeholder="Postcode">
        </div>

        <div class="form-group">
            <label>Huisnummer</label>
            <input class="form-control" type="text" name="houseNumber" placeholder="Huisnummer">
        </div>

        <div class="form-group">
            <label>Woonplaats</label>
            <input class="form-control" type="text" name="city" placeholder="Woonplaats">
        </div>
    </div>
    <div class="col-2">
        <div class="achter">
            <p><h3>Overzicht</h3></p>
            <p>Artikelen (<?php print amountOfItems($cart); ?>) : <?php print sprintf("€ %.2f",$price) ?></p>

            <p>Verzendkosten: <?php print sprintf("€ %.2f",$verzendkosten) ?></p>

            <hr class="solid">

            <p>Totaal : <?php print sprintf("€ %.2f",$price + $verzendkosten); ?></p>
            <input class="button2" type="submit" name="submit" value="Plaatsen Bestelling">
        </div>
    </div>  
</form>
<?php include __DIR__ . "/footer.php"; ?>