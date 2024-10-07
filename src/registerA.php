<?php

// include "../../../../../etc/nginx/config.php";
$hostname = "mysql";
$username = "user";
$password = "user";
$dbname = "nobel";
require_once 'PHPGangsta/GoogleAuthenticator.php';

function checkEmpty($field)
{
    // Funkcia pre kontrolu, ci je premenna po orezani bielych znakov prazdna.
    // Metoda trim() oreze a odstrani medzery, tabulatory a ine "whitespaces".
    if (empty(trim($field))) {
        return true;
    }
    return false;
}

function checkLength($field, $min, $max)
{
    // Funkcia, ktora skontroluje, ci je dlzka retazca v ramci "min" a "max".
    // Pouzitie napr. pre "login" alebo "password" aby mali pozadovany pocet znakov.
    $string = trim($field);     // Odstranenie whitespaces.
    $length = strlen($string);      // Zistenie dlzky retazca.
    if ($length < $min || $length > $max) {
        return false;
    }
    return true;
}

function checkUsername($username)
{
    // Funkcia pre kontrolu, ci username obsahuje iba velke, male pismena, cisla a podtrznik.
    if (!preg_match('/^[a-zA-Z0-9_]+$/', trim($username))) {
        return false;
    }
    return true;
}
function checkName($username)
{
    // Funkcia pre kontrolu, ci username obsahuje iba velke, male pismena, cisla a podtrznik.
    if (!preg_match('/^[a-zA-Z0-9_]+$/', trim($username))) {
        return false;
    }
    return true;
}
function checkPassword($password)
{
    if (!preg_match('/^(?=.*[A-Z])[a-zA-Z0-9_]+$/', trim($password))) {
        return false;
    }
    return true;
}

function checkGmail($email)
{
    // Funkcia pre kontrolu, ci zadany email je gmail.
    if (!preg_match('/^[\w.+\-]+@gmail\.com$/', trim($email))) {
        return false;
    }
    return true;
}

function userExist($db, $login, $email)
{
    // Funkcia pre kontrolu, ci pouzivatel s "login" alebo "email" existuje.
    $exist = false;

    $param_login = trim($login);
    $param_email = trim($email);

    $sql = "SELECT id FROM users WHERE login = :login OR email = :email";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(":login", $param_login, PDO::PARAM_STR);
    $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);

    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $exist = true;
    }

    unset($stmt);

    return $exist;
}

// ------- ------- ------- -------



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errmsg = "";
    try {
        $pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $query = "SELECT * FROM users";
        $stmt = $pdo->prepare($query);
        $stmt->execute();


        if (checkEmpty($_POST['login']) === true) {
            $errmsg .= "<p>Zadajte login.</p>";
        } elseif (checkLength($_POST['login'], 6, 32) === false) {
            $errmsg .= "<p>Login musí mať min. 6 a max. 32 znakov.</p>";
        } elseif (checkUsername($_POST['login']) === false) {
            $errmsg .= "<p>Login môže obsahovať iba veľké, malé písmená, číslice a podtržník.</p>";
        }

        if ((checkPassword(($_POST['password']))) === false && (checkLength($_POST['password'], 8, 100)) === false) {
            $errmsg .= "<p>Heslo musí obsahovať veľké písmeno, môže obsahovať iba veľké, malé písmená, číslice a podtržník. Musí byť aspoň 8 znakov dlhé</p>";
        }
        if ((checkName($_POST['firstname']) === false || (checkName($_POST['lastname']) === false))) {
            $errmsg .= "<p>Login môže obsahovať iba veľké, malé písmená, číslice a podtržník.</p>";
        }

        if (userExist($pdo, $_POST['login'], $_POST['email']) === true) {
            $errmsg .= "Používateľ s týmto e-mailom / loginom už existuje.</p>";
        }



        if (empty($errmsg)) {
            $sql = "INSERT INTO users (full_name, login, email, password, 2_fa_code) VALUES (:fullname, :login, :email, :password, :2fa_code)";

            $fullname = $_POST['firstname'] . ' ' . $_POST['lastname'];
            $email = $_POST['email'];
            $login = $_POST['login'];
            $hashed_password = password_hash($_POST['password'], PASSWORD_ARGON2ID);

            $g2fa = new PHPGangsta_GoogleAuthenticator();
            $user_secret = $g2fa->createSecret();
            $codeURL = $g2fa->getQRCodeGoogleUrl('Nobel page', $user_secret);

            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(":fullname", $fullname, PDO::PARAM_STR);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":login", $login, PDO::PARAM_STR);
            $stmt->bindParam(":password", $hashed_password, PDO::PARAM_STR);
            $stmt->bindParam(":2fa_code", $user_secret, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $qrcode = $codeURL;
            } else {
                echo "Ups. Nieco sa pokazilo";
            }

            unset($stmt);
        }
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
    <title>Login/register s 2FA - Register</title>
    <?php include 'header.php'; ?>

    <style>
        p {
            color: red;
            margin-left: 1rem;
        }
    </style>
</head>

<body>
    <header>
        <hgroup>
            <h1>Registrácia</h1>
            <h2>Vytvorenie nového konta používateľa</h2>
        </hgroup>
    </header>
    <main>

        <form class="row g-3" method="post">


            <div class="col-md-4">
                <label for="firstname" class="form-label">Meno</label>
                <input type="text" name="firstname" id="firstname" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label for="lastname" class="form-label">Priezvisko</label>
                <input type="text" name="lastname" id="lastname" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label for="login" class="form-label">Login</label>
                <input type="text" name="login" id="login" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label for="password" class="form-label">Heslo</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <div class="col-12">
                <button class="btn btn-primary" type="submit">Vytvorit konto</button>
            </div>





            <?php
            if (!empty($errmsg)) {
                echo $errmsg;
            }
            if (isset($qrcode)) {
                $message = '<p>Naskenujte QR kod do aplikacie Authenticator pre 2FA: <br><img src="' . $qrcode . '" alt="qr kod pre aplikaciu authenticator"></p>';

                echo $message;
                echo '<p>Teraz sa mozte prihlasit: <a href="loginA.php" role="button">Login</a></p>';
            }
            ?>

        </form>
        <p style="color: black;">Máte vytvorené konto? <a href="loginA.php">Prihláste sa tu.</a></p>
    </main>
</body>

</html>