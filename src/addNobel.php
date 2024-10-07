<?php
session_start();

// Ak je pouzivatel prihlaseny, ziskam data zo session, pracujem s DB etc...
if ((isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) || (isset($_SESSION['access_token']) && $_SESSION['access_token'])) {

    $login = isset($_SESSION['login']) ? $_SESSION['login'] : '';
    $email = isset($_SESSION['email']) ? $_SESSION['email'] : '';
    $id = isset($_SESSION['id']) ? $_SESSION['id'] : '';
    $fullname = isset($_SESSION['fullname']) ? $_SESSION['fullname'] : '';
    $name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
    $surname = isset($_SESSION['surname']) ? $_SESSION['surname'] : '';
} else {
    // Ak pouzivatel prihlaseny nie je, presmerujem ho na hl. stranku.
    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pridaj</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .logInfo {
            position: absolute;
            top: 0;
            right: 0;
            background-color: #E6E6DC;
            width: 10rem;
            text-align: center;
            border-bottom-left-radius: 10%;
        }

        .navbar {
            background-color: #00628B;
            padding: 0.5rem;
        }

        body {


            background-color: #81A594;
        }

        .row {
            margin: 0;
        }

        .form-control,
        .form-select {
            background-color: #E6E6DC;
        }

        h2 {
            background-color: #E6E6DC;
            width: 40%;
            margin: 1rem;
            text-align: center;
            border-radius: 5px;
            padding: 0.5rem;
        }
    </style>

    <script>
        function checkReceiver() {
            var name = document.getElementById("name");
            var surname = document.getElementById("surname");
            var organisation = document.getElementById("organisation");
            var gender = document.getElementById("gender");
            if (name.value.trim() == '' && surname.value.trim() == '' && organisation.value.trim() == '') {
                name.required = true;
                surname.required = false;
                organisation.required = false;
                name.disabled = false;
                surname.disabled = false;
                organisation.disabled = false;
                gender.disabled = false;
                gender.required = false;
            }
            if (name.value.trim() !== '') {
                name.required = true;
                surname.required = true;
                organisation.disabled = true;
                gender.required = true;
            } else if (organisation.value.trim() !== '') {
                name.disabled = true;
                surname.disabled = true;
                organisation.required = true;
                gender.disabled = true;
                gender.value = "";

            }
        }

        function checkLiterature() {
            var category = document.getElementById("category");
            var genreSk = document.getElementById("genre_sk");
            var genreEn = document.getElementById("genre_en");
            var languageSk = document.getElementById("language_sk");
            var languageEn = document.getElementById("language_en");
            console.log(category.value);
            if (category.value === "5") {
                genreSk.required = true;
                genreEn.required = true;
                languageSk.required = true;
                languageEn.required = true;
                genreSk.disabled = false;
                genreEn.disabled = false;
                languageSk.disabled = false;
                languageEn.disabled = false;
            } else {
                genreSk.required = false;
                genreEn.required = false;
                languageSk.required = false;
                languageEn.required = false;
                genreSk.disabled = true;
                genreEn.disabled = true;
                languageSk.disabled = true;
                languageEn.disabled = true;
            }
        }
    </script>
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand" href="#">Zadanie 1</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup"
                aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav id = 'navigation'">
                    <a class="nav-item nav-link active" href="index.php">Nobel</a>
                    <a class="nav-item nav-link" href="indexA.php">Login</a>
                    <a class="nav-item nav-link" href="restricted.php">Zabezpečená stránka</a>
                </div>
            </div>
        </nav>
    </header>
    <div class="logInfo">
        <p>Vitaj <?php echo $fullname . $login ?></p>
    </div>


    <div class="position-fixed bottom-0 end-0 p-3">
        <div class="toast " role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Pridanie</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Úspešne si pridal nositeľa Nobelovej ceny.
            </div>
        </div>
    </div>
    <form method="post" class="row g-3 needs-validation" novalidate>
        <div class="col-md-4">
            <label for="name" class="form-label">Meno</label>
            <input type="text" name="name" id="name" class="form-control" onblur="checkReceiver()" required
                maxlength="255">
            <div class="invalid-feedback">
                Musíš zadať meno a priezvisko alebo organizáciu.
            </div>
        </div>
        <div class="col-md-4">
            <label for="surname" class="form-label">Priezvisko</label>
            <input type="text" name="surname" id="surname" class="form-control" onblur="checkReceiver()"
                maxlength="255">
            <div class="invalid-feedback">
                Musíš zadať meno a priezvisko alebo organizáciu.
            </div>
        </div>
        <div class="col-md-4">
            <label for="organisation" class="form-label">Organizácia</label>
            <input type="text" name="organisation" id="organisation" class="form-control" onblur="checkReceiver()"
                maxlength="255">
            <div class="invalid-feedback">
                Musíš zadať meno a priezvisko alebo organizáciu.
            </div>
        </div>
        <div class="col-md-4">
            <label for="gender" class="form-label">Pohlavie</label>
            <select name="gender" id="gender" class="form-select">
                <option value="M">Muž</option>
                <option value="F">Žena</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="birth" class="form-label">D. narodenia</label>
            <input type="text" name="birth" id="birth" class="form-control" required maxlength="4">
            <div class="invalid-feedback">
                Prosím zadaj dátum narodenia/vzniku.
            </div>
        </div>
        <div class="col-md-4">
            <label for="death" class="form-label">D. smrti</label>
            <input type="text" name="death" id="death" class="form-control" maxlength="4">
        </div>
        <div class="col-md-4 mx-auto">
            <label for="year" class="form-label">Rok udelenia ceny</label>
            <input type="year" name="year" id="year" min="1901" max='2024' class="form-control" required maxlength="4">
            <div class="invalid-feedback">
                Prosím zadaj rok udelenia ceny.
            </div>
        </div>
        <div class="col-md-12">
            <label for="contribution_en" class="form-label">Prínos - en</label>
            <input type="text" name="contribution_en" id="contribution_en" class="form-control" required
                maxlength="400">
            <div class="invalid-feedback">
                Prosím zadaj prínos (anglicky).
            </div>
        </div>
        <div class="col-md-12">
            <label for="contribution_sk" class="form-label">Prínos - sk</label>
            <input type="text" name="contribution_sk" id="contribution_sk" class="form-control" required
                maxlength="400">
            <div class="invalid-feedback">
                Prosím zadaj prínos (slovensky).
            </div>
        </div>
        <div class="col-md-4">
            <label for="category" class="form-label">Kategória</label>
            <select name="category" id="category" class="form-select" required onchange="checkLiterature()">
                <option value="1">Fyzika</option>
                <option value="2">Chémia</option>
                <option value="5">Literatúra</option>
                <option value="3">Medicína</option>
                <option value="4">Mier</option>
            </select>
        </div>
        <div class="col-md-4">
            <label for="country" class="form-label">Krajina</label>
            <input type="text" name="country" id="country" class="form-control" required maxlength="150">
            <div class="invalid-feedback">
                Prosím zadaj krajinu.
            </div>
        </div>
        <div class="col-md-4">
            <label for="language_en" class="form-label">Jazyk - en</label>
            <input type="text" name="language_en" id="language_en" class="form-control" disabled maxlength="255">
            <div class="invalid-feedback">
                Prosím zadaj jazyk (anglicky).
            </div>
        </div>
        <div class="col-md-4">
            <label for="language_sk" class="form-label">Jazyk - sk</label>
            <input type="text" name="language_sk" id="language_sk" class="form-control" disabled maxlength="255">
            <div class="invalid-feedback">
                Prosím zadaj jazyk (slovensky).
            </div>
        </div>
        <div class="col-md-4">
            <label for="genre_sk" class="form-label">Zaner - sk</label>
            <input type="text" name="genre_sk" id="genre_sk" class="form-control" disabled maxlength="255">
            <div class="invalid-feedback">
                Prosím zadaj žáner (slovensky).
            </div>
        </div>
        <div class="col-md-4">
            <label for="genre_en" class="form-label">Zaner - en</label>
            <input type="text" name="genre_en" id="genre_en" class="form-control" disabled maxlength="255">
            <div class="invalid-feedback">
                Prosím zadaj žáner (anglicky).
            </div>
        </div>
        <div class="col-12">
            <button class="btn btn-primary" type="submit">Submit form</button>
        </div>
    </form>

    <script>
        (() => {
            'use strict';

            const forms = document.querySelectorAll('.needs-validation');



            Array.from(forms).forEach(form => {
                console.log("Form found:", form);

                form.addEventListener('submit', event => {
                    console.log("Form submitted");

                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }

                    form.classList.add('was-validated');
                });
            });
        })();
    </script>

    <?php
    // include "../../../../etc/nginx/config.php";
    $hostname = "mysql";
    $username = "user";
    $password = "user";
    $dbname = "nobel";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        try {
            $conn = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $sql_insert_receiver = "INSERT INTO receivers (name, surname, organization, sex, birth, death) 
                                    VALUES (:name, :surname, :organisation, :gender, :birth, :death)";
            $stmt_receiver = $conn->prepare($sql_insert_receiver);



            $sql_insert_prizes = "INSERT INTO prizes (year, contribution_sk,contribution_en, category_id, country_id, prize_detail_id, receiver_id) 
                                    VALUES (:year, :contribution_sk, :contribution_en, :category_id, :country_id, :prize_detail_id, :receiver_id)";
            $stmt_prizes = $conn->prepare($sql_insert_prizes);


            $sql_insert_countries = "INSERT INTO countries (country) 
                                    VALUES (:country)";
            $stmt_countries = $conn->prepare($sql_insert_countries);


            $sql_insert_prize_details = "INSERT INTO prize_details (language_sk, language_en, genre_sk, genre_en) 
                                    VALUES (:language_sk, :language_en, :genre_sk, :genre_en)";
            $stmt_prize_details = $conn->prepare($sql_insert_prize_details);

            if (
                strlen($_POST['name']) > 255 || strlen($_POST['surname']) > 255 ||
                isset($_POST['organisation']) && strlen($_POST['organisation']) > 255 ||
                strlen($_POST['gender']) > 4
                || strlen($_POST['birth']) > 4 || strlen($_POST['death']) > 4 ||
                strlen($_POST['year']) > 4 || strlen($_POST['contribution_sk']) > 400 ||
                strlen($_POST['contribution_en']) > 400 ||
                isset($_POST['language_sk']) && strlen($_POST['language_sk']) > 255 ||
                isset($_POST['language_en']) && strlen($_POST['language_en']) > 255 ||
                isset($_POST['genre_sk']) && strlen($_POST['genre_sk']) > 255 ||
                isset($_POST['genre_en']) && strlen($_POST['genre_en']) > 255
            ) {
                echo '<h2>Chyba na vstupe<?h2>';
                exit;
            }

            $stmt_receiver->bindParam(':name', $_POST["name"]);
            $stmt_receiver->bindParam(':surname', $_POST["surname"]);
            $stmt_receiver->bindParam(':organisation', $_POST["organisation"]);
            $stmt_receiver->bindParam(':gender', $_POST["gender"]);
            $stmt_receiver->bindParam(':birth', $_POST["birth"]);
            $stmt_receiver->bindParam(':death', $_POST["death"]);
            $stmt_receiver->execute();

            if ($_POST['category'] === "5") {
                $stmt_prize_details->bindParam(':language_en', $_POST["language_en"]);
                $stmt_prize_details->bindParam(':language_sk', $_POST["language_sk"]);
                $stmt_prize_details->bindParam(':genre_en', $_POST["genre_en"]);
                $stmt_prize_details->bindParam(':genre_sk', $_POST["genre_sk"]);
                $stmt_prize_details->execute();

            }

            $stmt_countries->bindParam(':country', $_POST["country"]);
            $stmt_countries->execute();


            $getCountry_id = 'SELECT MAX(id) AS max_id FROM countries';
            $getReceiver_id = 'SELECT MAX(id) AS max_id FROM receivers';
            $getPrizeDetail_id = 'SELECT MAX(id) AS max_id FROM prize_details';

            $stmt = $conn->prepare($getCountry_id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $countryId = $result['max_id']++;
            $stmt = $conn->prepare($getReceiver_id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $receiverID = $result['max_id']++;
            $stmt = $conn->prepare($getPrizeDetail_id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $prizeDetailId = $result['max_id']++;

            $stmt_prizes->bindParam(':year', $_POST["year"]);
            $stmt_prizes->bindParam(':contribution_sk', $_POST["contribution_sk"]);
            $stmt_prizes->bindParam(':contribution_en', $_POST["contribution_en"]);
            $stmt_prizes->bindParam(':category_id', $_POST["category"]);
            $stmt_prizes->bindParam(':country_id', $countryId);
            $stmt_prizes->bindParam(':receiver_id', $receiverID);
            $stmt_prizes->bindParam(':prize_detail_id', $prizeDetailId);
            $stmt_prizes->execute();


            if ($stmt_receiver->rowCount() > 0) {
                echo "<h2>Nositeľ bol pridaný</h2>";
            } else {
                echo "Error adding receiver";
            }

            // Close the connection
            $conn = null;
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    ?>
</body>


</html>