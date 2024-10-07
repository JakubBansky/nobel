<?php

session_start();

session_unset();


$_SESSION = array();

session_destroy();

header("location: loginIndex.php");

?>

<!doctype html>
<html lang="sk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Google odhlásenie</title>
    <style>
        h1 {
            margin: 3rem;
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
    <?php include 'header.php'; ?>
    <main>

        <h1>Boli ste úspešne odhlásený</h1>
        <p>
            <a role="button" href="loginIndex.php" class="secondary">Vráť sa na hlavnú stránku</a>
        </p>
    </main>
</body>

</html>