<?php // altijd hiermee starten als je gebruik wilt maken van sessiegegevens
include "addressfunctions.php";

function getCart()
{
    if (isset($_SESSION['cart'])) {               //controleren of winkelmandje (=cart) al bestaat
        $cart = $_SESSION['cart'];                  //zo ja:  ophalen
    } else {
        $cart = array();                            //zo nee: dan een nieuwe (nog lege) array
    }
    return $cart;                               // resulterend winkelmandje terug naar aanroeper functie
}

function saveCart($cart)
{
    $_SESSION["cart"] = $cart;                  // werk de "gedeelde" $_SESSION["cart"] bij met de meegestuurde gegevens
}

function addProductToCart($stockItemID)
{
    $cart = getCart();                          // eerst de huidige cart ophalen

    if (array_key_exists($stockItemID, $cart)) {  //controleren of $stockItemID(=key!) al in array staat
        $cart[$stockItemID] += 1;                   //zo ja:  aantal met 1 verhogen
    } else {
        $cart[$stockItemID] = 1;                    //zo nee: key toevoegen en aantal op 1 zetten.
    }

    saveCart($cart);                            // werk de "gedeelde" $_SESSION["cart"] bij met de bijgewerkte cart
}

function amountOfItems($cart) {
    $amount = 0;
    foreach ($cart as $quantity) {
        for ($i = 0; $i < $quantity; $i++) {
            $amount++;
        }
    }
    return $amount;
}

function getPrice($cart) {
    $databaseConnection = connectToDatabase();
    $price = 0;
    foreach ($cart as $item => $quantity) {
        $StockItem = getStockItem($item, $databaseConnection);
        $price += round($StockItem["SellPrice"], 2) * $quantity;
    }
    if (isset($_SESSION["actualDiscount"])) {
        $price -= $_SESSION["actualDiscount"];
    }
    if ($price < 0) {
        $price = 0;
    }
    return $price;
}

function decrementStockitems($id, $databaseConnection, $quantity) {

    $Query = "UPDATE stockitemholdings SET QuantityOnHand = GREATEST(QuantityOnHand - $quantity, 0) WHERE stockitemid = ?";

    $Statement = mysqli_prepare($databaseConnection, $Query);
    mysqli_stmt_bind_param($Statement, "i", $id);
    mysqli_stmt_execute($Statement);
}
class Database
{
    public $connection;

    function __construct() {
        $mysqli = new mysqli("localhost","root","","nerdygadgets");
        if ($mysqli->connect_errno) {
            echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
            exit();
        }

        $this->connection = $mysqli;
    }
}
class Authentication
{
    public mysqli $database;

    function __construct(mysqli $mysqlConnection)
    {
        $this->database = $mysqlConnection;
    }

    public function getUser(string $emails): ?array
    {
        $sql = 'SELECT * FROM people WHERE EmailAddress = ? LIMIT 1';
        $query = $this->database->prepare($sql);
        $query->bind_param('s', $emails);

        // Is query gelukt?
        if ($query->execute()) {
            $result = $query->get_result();
            $row = $result->fetch_assoc();

            if ($row) {
                return $row;
            }
        }

        return null;
    }

    public function addUser(string $username, string $emails, string $password_new, $Postalcode, $houseNumber): bool
    {
        $address = GetAddress($Postalcode, $houseNumber);
        $password = password_hash($password_new, PASSWORD_DEFAULT);
        $databaseConnection = connectToDatabase();
        $peopleID = $databaseConnection->query("SELECT MAX(PersonID) AS max from people")->fetch_assoc()['max'] + 1;
        $customerID = $databaseConnection->query("SELECT MAX(CustomerID) AS max from customers")->fetch_assoc()['max'] + 1;

        $datum = date('Y-m-d H:i:s');


        $sql = 'INSERT INTO people (`PersonID`, `FullName`, `PreferredName`, `SearchName`, `IsPermittedToLogon`, `LogonName`, `IsExternalLogonProvider`, `IsSystemUser`, `IsEmployee`, `IsSalesperson`, `UserPreferences`, `PhoneNumber`, `FaxNumber`, `EmailAddress`, `HashedPassword`, `CustomFields`, `OtherLanguages`, `LastEditedBy`, `ValidFrom`, `ValidTo`)
    VALUES (?, ?, ?, ?, 0, "NO LOGON", 0, 0, 0, 0, "-", "(229) 555-0100", "(229) 555-0101", ?, ?, "", "", 1, ?, "9999-12-12 23:59:59" )';
        $query = $this->database->prepare($sql);
        $query->bind_param('issssss', $peopleID, $username, $username, $username, $emails, $password, $datum);
        $successPeople = $query->execute();


        $sql = 'INSERT INTO `customers` (`CustomerID`, `CustomerName`, `BillToCustomerID`, `CustomerCategoryID`, `PrimaryContactPersonID`, `DeliveryMethodID`, `DeliveryCityID`, `PostalCityID`, `AccountOpenedDate`, `StandardDiscountPercentage`, `IsStatementSent`, `IsOnCreditHold`, `PaymentDays`, `PhoneNumber`, `FaxNumber`, `WebsiteURL`, `DeliveryAddressLine1`, `DeliveryAddressLine2`, `DeliveryPostalCode`,`PostalAddressLine1`, `PostalPostalCode`, `LastEditedBy`, `ValidFrom`, `ValidTo`) 
    VALUES (?, ?, ?, 1, ?, 3, 1, 1, NOW(), "-", "-", "-", "-", "-", "-", ?, "-", ?, ?,"-", "-",?, ?, "9999-12-12 23:59:59")';
        $query = $this->database->prepare($sql);
        $query->bind_param('issssssss', $customerID, $username, $customerID, $peopleID, $address['street'], $Postalcode, $houseNumber, $peopleID, $datum);
        $successCustomer = $query->execute();

        return $successPeople && $successCustomer;
    }

    public function login(string $email, string $psw): ?array
    {
        if ($this->verifyPassword($email, $psw)) {
            $user = $this->getUser($email);

            return $user;
        }

        return null;
    }

    public function verifyPassword($emails, $psw): bool
    {
        $user = $this->getUser($emails);

        if ($user && password_verify($psw, $user['HashedPassword'])) {
            return true;
        }

        return false;
    }



    function vulIn(): ?array
    {
        $email = $_SESSION['user_email'];

        $sql = "
        SELECT CustomerName, DeliveryPostalCode, DeliveryAddressLine2
        FROM customers
        JOIN people ON customers.PrimaryContactPersonID = people.PersonID
        WHERE people.EmailAddress = ?";

        $databaseConnection = connectToDatabase();
        $query = mysqli_prepare($databaseConnection, $sql);
        mysqli_stmt_bind_param($query, 's', $email);

        // Is query gelukt?
        if (mysqli_stmt_execute($query)) {
            $result = mysqli_stmt_get_result($query);
            $row = mysqli_fetch_assoc($result);

            if ($row) {
                return $row;
            }
        }

        return null;
    }


}

function applyCouponCode($price, $couponCode)
{

    global $couponCodes;

    $discountedPrice = $price;
    if (array_key_exists($couponCode, $couponCodes)) {
        $korting = $couponCodes[$couponCode];
        $korting2 = ($price * ($korting / 100));
        $korting2 = round($korting2, 2);
        $discountedPrice = $price - $korting2;
    }
    return $discountedPrice;

}