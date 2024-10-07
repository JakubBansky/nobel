<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail nositeľa Nobelovej ceny</title>
    <?php include 'header.php'; ?>

    <style>
        .btn {
            margin: 0.5rem;
            background-color: #81A594;
            color: #E6E6DC;
        }

        .container {
            padding: 0.5rem;
            background-color: #81A594;
            margin: 1rem;
            font-size: larger;
        }

        .container div {
            margin: 0.3rem;
        }

        .second {
            font-weight: bold;
        }

        .col {
            border: 1px solid black;
        }

        a {
            font-size: x-large;
            background-color: #81A594;
            padding: 0.3rem;
            margin-left: 1rem;
            border-radius: 5px;
        }
    </style>

    </style>
</head>

<body>


    <?php
    // include "../../../../etc/nginx/config.php";
    $hostname = "mysql";
    $username = "user";
    $password = "user";
    $dbname = "nobel";
    $conn = new mysqli($hostname, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Načítanie detailov o nositeľovi Nobelovej ceny na základe ID z URL
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $sql = "SELECT r.name, r.surname, r.organization, r.sex, r.birth, r.death, p.year, p.contribution_sk,
        p.contribution_en, pd.language_sk,pd.language_en,pd.genre_sk, pd.genre_en, ca.category, co.country
        FROM receivers r
        JOIN prizes p ON r.id = p.receiver_id
        JOIN prize_details pd ON p.prize_detail_id = pd.id
        JOIN categories ca ON p.category_id = ca.id
        JOIN countries co ON p.country_id = co.id
        WHERE r.id = $id";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            echo "  <div class='container'> Osobné údaje";
            echo "  <div class='row justify-content-center'>";
            echo "     <div class='col-6 col-md-4'>Meno a priezvisko/Organizácia:</div>";
            echo " <div class='col-6 col-md-4'> " . $row["name"] . ' ' . $row["surname"] . $row["organization"] . "</div>";
            echo " </div>";
            echo " <div class='row justify-content-center'>";
            echo "    <div class='col-6 col-md-4'>Pohlavie:</div>";
            if ($row["sex"] === 'M') {
                echo "<div class='col-6 col-md-4'>Muž</div>";
            } elseif ($row['sex'] === 'F') {
                echo "<div class='col-6 col-md-4'>Žena</div>";
                ;
            }
            echo "</div>";
            echo "<div class='row justify-content-center'>";
            echo " <div class='col-6 col-md-4'> Rok narodenia a úmrtia:";
            echo "</div>";
            echo "<div class='col-6 col-md-4'>" . $row["birth"] . ' - ' . $row["death"] . "</div>";
            echo " </div>";
            echo "</div>";


            echo "  <div class='container'> O cene";
            echo "  <div class='row justify-content-center'>";
            echo "  <div class='col-6 col-md-4'>Rok udelenia ceny/Organizácia:</div>";
            echo " <div class='col-6 col-md-4'> " . $row["year"] . "</div>";
            echo " </div>";
            echo " <div class='row justify-content-center'>";
            echo "<div class='col-6 col-md-4'>Kategória:</div>";
            echo "<div class='col-6 col-md-4'>" . $row["category"] . "</div>";
            echo "</div>";
            echo " <div class='row justify-content-center'>";
            echo "<div class='col-6 col-md-4'>Prínos(slovensky):</div>";
            echo "<div class='col-6 col-md-4'>" . $row["contribution_sk"] . "</div>";
            echo "</div>";
            echo " <div class='row justify-content-center'>";
            echo "<div class='col-6 col-md-4'>Contribution:</div>";
            echo "<div class='col-6 col-md-4'>" . $row["contribution_en"] . "</div>";
            echo "</div>";
            echo "<div class='row justify-content-center'>";
            echo " <div class='col-6 col-md-4'>Krajina:";
            echo "</div>";
            echo "<div class='col-6 col-md-4'>" . $row["country"] . "</div>";
            echo " </div>";
            echo "</div>";
        } else {
            echo "Nenájdené žiadne informácie.";
        }
    } else {
        echo "Chýba parameter ID v URL.";
    }
    $conn->close();
    ?>
    <a href="index.php">Späť na zoznam nositeľov Nobelovej ceny</a>
</body>

</html>