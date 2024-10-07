<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prihlasovanie</title>
    <?php include 'header.php'; ?>
    <style>
        #info {
            display: flex;
            position: absolute;
            width: 50%;
            height: 50%;
            left: 25%;
            top: 25%;
            border-radius: 5px;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            background-color: #81A594;
        }

        .btn {
            margin: 0.5rem;
        }

        p {
            margin: 1rem;
            font-size: x-large;
        }

        .logInfo p {
            margin: 0.5rem;
            font-size: medium;
        }
    </style>
    <script>
        function hide(element) {
            var element = document.getElementById(element);
            element.style.display = 'none'
        }
    </script>

</head>

<body>



    <?php
    // session_start();
    
    if ((isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) || (isset($_SESSION['access_token']) && $_SESSION['access_token'])) {

        $login = isset($_SESSION['login']) ? $_SESSION['login'] : '';
        $email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
        $id = isset($_SESSION['id']) ? $_SESSION['id'] : '';
        $fullname = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : '';
        $name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
        $surname = isset($_SESSION['surname']) ? $_SESSION['surname'] : '';

        echo "<div class='logInfo'>";
        echo "<p>Vitaj $fullname $login</p>";
        echo "</div>";
    } else {
        ?>
        <div id="info">
            <h1>Táto stránka si ukladá cookies</h1>
            <button type="button" class="btn btn-primary" id="agree" onclick="hide('info')">Súhlasím</button>
            <a href="index.php" class="btn btn-primary" id="disagree">Nesúhlasím</a>
        </div>

        <?php
    }

    require_once 'vendor/autoload.php';
    // include "../../../../etc/nginx/config.php";
    $hostname = "mysql";
    $username = "user";
    $password = "user";
    $dbname = "nobel";

    $client = new Google\Client();
    $client->setAuthConfig('client_secret.json');
    // $redirect_uri = "https://node16.webte.fei.stuba.sk/Z1/redirect.php";
    $redirect_uri = "http://localhost/redirect.php";
    $client->setRedirectUri($redirect_uri);
    $client->addScope("email");
    $client->addScope("profile");
    $auth_url = $client->createAuthUrl();


    if ((!isset($_SESSION["loggedin"]) || ($_SESSION["loggedin"] !== true)) && !((isset($_SESSION['access_token']) && $_SESSION['access_token']))) {
        echo '<p>Nie ste prihlásený, prosím <a href="loginA.php">prihláste sa</a>, <a href="registerA.php">zaregistrujte</a> alebo využite ';
        echo '<a role="button" href="' . filter_var($auth_url, FILTER_SANITIZE_URL) . '">Google prihlásenie</a></p>';
    } else if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
        echo '<h3>Vitaj ' . $_SESSION['name'] . '</h3>';
        echo '<p>Si prihlásený ako: ' . $_SESSION['email'] . '</p>';
        echo '<p><a role="button" href="restricted.php">Zabezpečená stránka</a>';
        echo '<a role="button" class="secondary" href="logout.php">Odhlás ma</a></p>';
    } else {
        echo '<h3>Vitaj ' . $_SESSION['fullname'] . ' </h3>';
        echo '<p>Si prihlásený ako: ' . $_SESSION['login'] . '</p>';
        echo '<a href="restricted.php">Zabezpečená stránka</a>';
        echo '<a role="button" class="secondary" href="logout.php">Odhlás ma</a></p>';
    }
    ?>


</body>

</html>