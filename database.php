<!-- dit bestand bevat alle code die verbinding maakt met de database -->
<?php

function connectToDatabase() {
    $Connection = null;

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Set MySQLi to throw exceptions
    try {
        $Connection = mysqli_connect("localhost", "root", "", "nerdygadgets");
        mysqli_set_charset($Connection, 'latin1');
        $DatabaseAvailable = true;
    } catch (mysqli_sql_exception $e) {
        $DatabaseAvailable = false;
    }
    if (!$DatabaseAvailable) {
        ?><h2>Website wordt op dit moment onderhouden.</h2><?php
        die();
    }

    return $Connection;
}

function getHeaderStockGroups($databaseConnection) {
    $Query = "
                SELECT StockGroupID, StockGroupName, ImagePath
                FROM stockgroups 
                WHERE StockGroupID IN (
                                        SELECT StockGroupID 
                                        FROM stockitemstockgroups
                                        ) AND ImagePath IS NOT NULL
                ORDER BY StockGroupID ASC";
    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_execute($Statement);
    $HeaderStockGroups = mysqli_stmt_get_result($Statement);
    return $HeaderStockGroups;
}

function getStockGroups($databaseConnection) {
    $Query = "
            SELECT StockGroupID, StockGroupName, ImagePath
            FROM stockgroups 
            WHERE StockGroupID IN (
                                    SELECT StockGroupID 
                                    FROM stockitemstockgroups
                                    ) AND ImagePath IS NOT NULL
            ORDER BY StockGroupID ASC";
    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_execute($Statement);
    $Result = mysqli_stmt_get_result($Statement);
    $StockGroups = mysqli_fetch_all($Result, MYSQLI_ASSOC);
    return $StockGroups;
}

function getStockItem($id, $databaseConnection) {
    $Result = null;

    $Query = " 
           SELECT SI.StockItemID, 
            (RecommendedRetailPrice*(1+(TaxRate/100))) AS SellPrice, 
            StockItemName,
            QuantityOnHand,
            SearchDetails, 
            (CASE WHEN (RecommendedRetailPrice*(1+(TaxRate/100))) > 50 THEN 0 ELSE 6.95 END) AS SendCosts, MarketingComments, CustomFields, SI.Video,
            (SELECT ImagePath FROM stockgroups JOIN stockitemstockgroups USING(StockGroupID) WHERE StockItemID = SI.StockItemID LIMIT 1) as BackupImagePath   
            FROM stockitems SI 
            JOIN stockitemholdings SIH USING(stockitemid)
            JOIN stockitemstockgroups ON SI.StockItemID = stockitemstockgroups.StockItemID
            JOIN stockgroups USING(StockGroupID)
            WHERE SI.stockitemid = ?
            GROUP BY StockItemID";

    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_bind_param($Statement, "i", $id);
    mysqli_stmt_execute($Statement);
    $ReturnableResult = mysqli_stmt_get_result($Statement);
    if ($ReturnableResult && mysqli_num_rows($ReturnableResult) == 1) {
        $Result = mysqli_fetch_all($ReturnableResult, MYSQLI_ASSOC)[0];
    }

    return $Result;
}

function getStockItemImage($id, $databaseConnection) {

    $Query = "
                SELECT ImagePath
                FROM stockitemimages 
                WHERE StockItemID = ?";

    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_bind_param($Statement, "i", $id);
    mysqli_stmt_execute($Statement);
    $R = mysqli_stmt_get_result($Statement);
    $R = mysqli_fetch_all($R, MYSQLI_ASSOC);

    return $R;
}

//function createUser($user , $databaseConnection) {
//    $name = $user["name"];
//    $city = $user["city"];
//    $street = $user["street"];
//    $postalcode = $user["postcode"];
//    $houseNumber = $user["house_number"];
//
//    $Query = "
//        INSERT INTO customersnl (CustomerName, AccountOpeningDate, City, Street, Postalcode, HouseNumber) VALUES (?, now(), ?, ?, ?, ?)
//    ";
//    $Statement = mysqli_prepare($databaseConnection, $Query);
//    mysqli_stmt_bind_param($Statement, "ssssi", $name, $city, $street, $postalcode, $houseNumber);
//    mysqli_stmt_execute($Statement);
//}
function insertIntoOrder($databaseConnection) {
    $customerID = 1;
    $Query = "INSERT INTO orders(customerid, salespersonpersonid, contactpersonid, orderdate, expecteddeliverydate, isundersupplybackordered, lasteditedby, lasteditedwhen)
VALUES(?, 13, 2247, now(), now(), 1, 11, now())";
//1 moet klantennummer van Joshua worden
    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_bind_param($Statement, 'i', $customerID);
    mysqli_stmt_execute($Statement);
    return mysqli_insert_id($databaseConnection);
}

function insertIntoOrderLine($itemid, $quantity, $databaseConnection, $Orderid) {
    $Query = "INSERT INTO orderlines(orderid, stockitemid, description, packagetypeid, quantity, taxrate, pickedquantity, lasteditedby, lasteditedwhen)
VALUES(?, ?, ' ', 7, ?, 15.000, ?, 4, '2013-01-02 11:00:00')";
    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_bind_param($Statement, 'iiii', $Orderid, $itemid, $quantity, $quantity);
    mysqli_stmt_execute($Statement);
}

function postReview($id, $databaseConnection, $comment, $aanbevelen) {

    $Query = "INSERT INTO reviews (stockitemid, aanbeveling, contents, customerid, plaatsingsdatum)
              VALUES (?, ?, ?, 1, now())";

    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_bind_param($Statement, 'ids', $id, $aanbevelen, $comment);
    mysqli_stmt_execute($Statement);
}

function getReview($id, $databaseConnection) {

    $Query = "SELECT *
                FROM reviews r
                JOIN customersnl c ON r.customerid = c.CustomerID
                WHERE stockitemid = ?";

    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_bind_param($Statement, 'i', $id);
    mysqli_stmt_execute($Statement);
    $R = mysqli_stmt_get_result($Statement);
    $R = mysqli_fetch_all($R, MYSQLI_ASSOC);

    return $R;
}
