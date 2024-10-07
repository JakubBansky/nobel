<?php

session_start();

// Ak je pouzivatel prihlaseny, ziskam data zo session, pracujem s DB etc...


if ((isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) || (isset($_SESSION['access_token']) && $_SESSION['access_token'])) {

    $email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
    $id = isset($_SESSION['id']) ? $_SESSION['id'] : '';
    $fullname = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : '';
    $name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
    $surname = isset($_SESSION['surname']) ? $_SESSION['surname'] : '';
} else {
    echo "not logged in";
    header('Location: index.php');
}
?>

<!doctype html>
<html lang="sk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Zabezpečená stránka</title>

    <?php include 'header.php'; ?>
    <style>
        p {
            margin-top: 1rem;
        }

        p a {
            text-decoration: none;
            color: #E6E6DC;
            background-color: #81A594;
            margin: 1rem;
            padding: 0.4rem;
            border-radius: 5px;
        }
    </style>
</head>

<body>

    <main>

        <p>
            <a href="addNobel.php" id="addNobel">Pridaj nositeľa Nobelovej ceny</a>
            <a href="changeNobel.php" id="changeNobel">Modifikuj nositeľa Nobelovej ceny</a>
            <a href="delNobel.php" id="delNobel">Vymaž nositeľa Nobelovej ceny</a>
        </p>


        <?php
        if (!empty($surname)) {

            echo " <h3>Vitaj " . $fullname . "</h3>";
            echo "<p>Si prihlaseny pod emailom:" . $email . " </p>";
            // <p>Meno: <?php echo $name . $_SESSION['login']
        


            // echo ", Priezvisko:  $surname</p>";
        } else {
            echo "<p><strong>Si prihlaseny pod emailom: </strong>" . $_SESSION['email'] . " </p>";
            echo "<p><strong>Tvoj identifikator (login) je:</strong>" . $_SESSION['login'] . " </p>";
        }

        ?>


        <!-- <p><strong>Si prihlaseny pod emailom:</strong> <?php echo $_SESSION['email']; ?></p>
        <p><strong>Tvoj identifikator (login) je:</strong> <?php echo $_SESSION['login']; ?></p> -->

        <p>
            <a role="button" class="secondary" href="logout.php">Odhlasenie</a>
            <a role="button" href="index.php">Spat na hlavnu stranku</a>
        </p>


    </main>
</body>

</html>