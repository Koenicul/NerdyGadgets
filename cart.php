<!-- dit bestand bevat alle code voor de pagina die één product laat zien -->
<?php
include __DIR__ . "/header.php";
include "cartfuncties.php";
if (isset($_GET['id'])){
    $StockItem = getStockItem($_GET['id'], $databaseConnection);
    $StockItemImage = getStockItemImage($_GET['id'], $databaseConnection);
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
<h1>Inhoud Winkelwagen</h1>
<table>
    <tr>
        <th>Naam</th>
        <th>Hoeveelheid</th>
        <th>Prijs</th>
    </tr>
    <?php
    $cart = getCart();
    $totaalprijs = 0;
    //gegevens per artikelen in $cart (naam, prijs, etc.) uit database halen
    //totaal prijs berekenen
    //mooi weergeven in html
    //etc.
    foreach ($cart as $key => $row){
        $prijs = 0;
        print("<tr>
            <td><a href='view.php?id=$key'>Product $key</a></td>
            <td>$row</td>
            <td>$prijs</td>
        </tr>");

        $totaalprijs += $prijs;
        }
    print("<tr>
            <td>Totaalprijs:</td>
            <td> </td>
            <td>$totaalprijs</td>
        </tr>");

    if (isset($_POST["nummer"])){
        $nummer = $_POST["nummer"];
        header('Location: '. "view.php?id=$nummer");
        die();

    }
    ?>
</table>
</head>
</html>