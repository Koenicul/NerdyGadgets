<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- de inhoud van dit bestand wordt bovenaan elke pagina geplaatst -->
<?php
session_start();
include "database.php";

$databaseConnection = connectToDatabase();

function getVoorraadTekst($actueleVoorraad) {
    if ($actueleVoorraad > 1000) {
        return "Ruime voorraad beschikbaar.";
    } else {
        return "Voorraad: $actueleVoorraad";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>NerdyGadgets</title>

    <!-- Javascript -->
    <script src="Public/JS/fontawesome.js"></script>
    <script src="Public/JS/jquery.min.js"></script>
    <script src="Public/JS/bootstrap.min.js"></script>
    <script src="Public/JS/popper.min.js"></script>
    <script src="Public/JS/resizer.js"></script>

    <!-- Style sheets-->
    <link rel="stylesheet" href="Public/CSS/style.css" type="text/css">
    <link rel="stylesheet" href="Public/CSS/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="Public/CSS/typekit.css">
    <link rel="stylesheet" href="Public/CSS/responsive.css">
</head>
<body>
<div class="Background">
    <div class="row" id="Header">
        <div class="col-2"><a href="./" id="LogoA">
                <div id="LogoImage"><img src="Public/Img/Banner_Logo.png" alt="Logo" class="logo"></div>
            </a></div>
        <div class="col-8" id="CategoriesBar">
            <ul id="ul-class">
                <?php
                $HeaderStockGroups = getHeaderStockGroups($databaseConnection);

                foreach ($HeaderStockGroups as $HeaderStockGroup) {
                    ?>
                    <li>
                        <a href="browse.php?category_id=<?php print $HeaderStockGroup['StockGroupID']; ?>"
                           class="HrefDecoration"><?php print $HeaderStockGroup['StockGroupName']; ?></a>
                    </li>
                    <?php
                }
                ?>
                <li>
                    <a href="categories.php" class="HrefDecoration">Alle categorieën</a>
                </li>
            </ul>
        </div>



<!-- code voor US3: zoeken en winkel wagen -->
        <ul id="ul-class-navigation">
            <li>
                <i class="fas fa-shopping-cart"></i>
                <a href="cart.php" class="HrefDecoration">
                     Winkelwagen</a>
            </li>
            <li>
                <i class="fas fa-search search"></i>
                <a href="browse.php" class="HrefDecoration">
                     Zoeken</a>
            </li>
             <li>
                <i class="fas fa-phone phone"></i>
                <a href="support.php" class="HrefDecoration">
                     Support</a>
            </li>
            <li>
            <?php
            if (isset($_SESSION['user_email'])) {
                print('
                        <li>
                            <a onclick="logout()" href="logout.php" class="HrefDecoration"><i class="fas fa-sign-out-alt shopping-cart"></i> Uitloggen</a>
                        </li>'
                );
            } else {
                print('
                        <li>
                            <a href="login.php" class="HrefDecoration" <i class="fas fa-sign-in-alt shopping-cart"></i> Inloggen</a>
                        </li>'
                );
            }
            ?>
        </ul>


<!-- einde code voor US3 zoeken -->

    </div>
    <div class="row" id="Content">
        <div class="col-12">
            <div id="SubContent">


