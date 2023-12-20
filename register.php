<?php
include __DIR__ . "/header.php";
function test_input($data) {
    if (isset($data)) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    } else {
        exit("Invalid request");
    }
}
$data = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data['email'] = test_input($_POST["email"]);
    $data['password'] = test_input($_POST["password"]);
    $data['password2'] = test_input($_POST["password2"]);
        registerNewPerson($data,$databaseConnection);
        attemptLoginUser($data,$databaseConnection);
        if ($_SESSION['loggedin'] === true) {
            if (isset($_GET['action'])) {
                print('<script>window.location.href = "' . test_input($_GET['action']) . '";</script>');
            } else {
                print('<script>window.location.href = "index.php";</script>');
            }
        } else {
            print('<script>window.location.href = "form.php?error=unknown";</script>');
        }
    } else {
        print('<script>window.location.href = "form.php?error=pwmatch";</script>');
    }

?>