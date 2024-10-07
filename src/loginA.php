<?php

session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: restricted.php");
    exit;
}
// include "../../../../../etc/nginx/config.php";
$hostname = "mysql";
$username = "user";
$password = "user";
$dbname = "nobel";
require_once 'PHPGangsta/GoogleAuthenticator.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $errmsg = "";


    try {
        $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT full_name, email, login, password,  2_fa_code FROM users WHERE login = :login";

        $stmt = $pdo->prepare($sql);

        // TODO: Upravit SQL tak, aby mohol pouzivatel pri logine zadat login aj email.
        $stmt->bindParam(":login", $_POST["login"], PDO::PARAM_STR);

        if ($stmt->execute()) {
            if ($stmt->rowCount() == 1) {
                // Uzivatel existuje, skontroluj heslo.
                $row = $stmt->fetch();
                $hashed_password = $row["password"];

                if (password_verify($_POST['password'], $hashed_password)) {
                    // Heslo je spravne.
                    $g2fa = new PHPGangsta_GoogleAuthenticator();
                    if ($g2fa->verifyCode($row["2_fa_code"], $_POST['2fa'], 2)) {
                        // Heslo aj kod su spravne, pouzivatel autentifikovany.

                        // Uloz data pouzivatela do session.
                        $_SESSION["loggedin"] = true;
                        $_SESSION["login"] = $row['login'];
                        $_SESSION["fullname"] = $row['full_name'];
                        $_SESSION["email"] = $row['email'];
                        // $_SESSION["created_at"] = $row['created_at'];

                        // Presmeruj pouzivatela na zabezpecenu stranku.
                        header("location: restricted.php");
                    } else {
                        echo "Neplatny kod 2FA.";
                    }
                } else {
                    echo "Nespravne meno alebo heslo.";
                }
            } else {
                echo "Nespravne meno alebo heslo.";
            }
        } else {
            echo "Ups. Nieco sa pokazilo!";
        }

        unset($stmt);
        unset($pdo);
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        exit();
    }
}

?>

<!doctype html>
<html lang="sk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login/registruj sa</title>
    <?php include 'header.php'; ?>

</head>

<body>

    <header>
        <hgroup>
            <h1>Prihlasenie</h1>
            <h2>Prihlasenie pouzivatela po registracii</h2>
        </hgroup>
    </header>


    <form class="row g-3 action=" <?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?> method="post">


        <div class="col-md-4">
            <label for="login" class="form-label">Prihlasovacie meno:</label>
            <input type="text" name="login" id="login" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label for="password" class="form-label">Heslo:</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <div class="col-md-4">
            <label for="2fa" class="form-label">2FA kód:</label>
            <input type="number" name="2fa" id="2fa" class="form-control" required>
        </div>

        <div class="col-12">
            <button class="btn btn-primary" type="submit">Prihlasit sa</button>
        </div>
    </form>
    <p>Este nemate vytvorene konto? <a href="registerA.php">Registrujte sa tu.</a></p>

</body>

</html>