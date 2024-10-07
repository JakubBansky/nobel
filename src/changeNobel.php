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
    <title>change</title>
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

        .selected {
            margin: 0.5rem;
            background-color: #00628B;

        }

        h2 {
            background-color: #E6E6DC;
            width: 40%;
            margin: 1rem;
            text-align: center;
            border-radius: 5px;
            padding: 0.5rem;
        }

        input[type="submit"] {
            background-color: #81A594;
            margin: 0.2rem;
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
            if (category.value === "5") {
                genreSk.required = true;
                genreEn.required = true;
                languageSk.required = true;
                languageEn.required = true;
            } else {
                genreSk.required = false;
                genreEn.required = false;
                languageSk.required = false;
                languageEn.required = false;
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


    <form method="post" class="row g-3">

        <div class="col-md-4">
            <label for="name" class="form-label">Meno</label>
            <input type="text" name="name" id="name" class="form-control">
        </div>

        <div class="col-md-4">
            <label for="surname" class="form-label">Priezvisko</label>
            <input type="text" name="surname" id="surname" class="form-control">
        </div>

        <div class="col-md-4">
            <label for="organisation" class="form-label">Organizácia</label>
            <input type="text" name="organisation" id="organisation" class="form-control">
        </div>

        <div class="col-12">
            <button class="btn btn-primary" type="submit" name="searchSubmit" value="Submit">Hľadaj</button>
        </div>
    </form>

    <div class="position-fixed bottom-0 end-0 p-3">
        <div class="toast " role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Modifikácia</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Úspešne si modifikoval nositeľa Nobelovej ceny.
            </div>
        </div>
    </div>
    <?php
    // include "../../../../etc/nginx/config.php";
    $hostname = "mysql";
    $username = "user";
    $password = "user";
    $dbname = "nobel";

    if (isset($_POST['name']) || isset($_POST['surname']) || isset($_POST['organisation'])) {
        try {
            // Connect to the database using PDO
            $conn = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepare the SQL statement for selection
            $sql = "SELECT id, name, surname, organization FROM receivers WHERE";

            // Initialize array to store WHERE clauses
            $whereClauses = array();

            // Check and add WHERE clauses based on form inputs
            if (!empty($_POST['name'])) {
                $whereClauses[] = "name LIKE :name";
            }
            if (!empty($_POST['surname'])) {
                $whereClauses[] = "surname LIKE :surname";
            }
            if (!empty($_POST['organisation'])) {
                $whereClauses[] = "organization LIKE :organisation";
            }

            // If there are any WHERE clauses, concatenate them with AND
            if (!empty($whereClauses)) {
                $sql .= " " . implode(" AND ", $whereClauses);
            }
            // echo $sql . $_POST['organisation'];
            // Prepare the statement
            $stmt = $conn->prepare($sql);

            // Bind parameters
            if (!empty($_POST['name'])) {
                $stmt->bindValue(':name', '%' . $_POST['name'] . '%', PDO::PARAM_STR);
            }
            if (!empty($_POST['surname'])) {
                $stmt->bindValue(':surname', '%' . $_POST['surname'] . '%', PDO::PARAM_STR);
            }
            if (!empty($_POST['organisation'])) {
                $stmt->bindValue(':organisation', '%' . $_POST['organisation'] . '%', PDO::PARAM_STR);
            }

            // Execute the statement
            $stmt->execute();

            // Fetch and display results
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($result as $row) {
                echo "<div class='selected col-md-6'>";
                echo "<h3>" . $row["name"] . " " . $row["surname"] . $row['organization'] . "</h3>";
                echo "<form method='post'>";
                echo "<input type='hidden' name='change_id' value='" . $row['id'] . "'>";
                echo "<input type='submit' name = 'selectSubmit' value='Modifikuj'>";
                echo "</form>";
                echo "</div>";
            }
        } catch (PDOException $e) {
            echo "Error selecting receiver: " . $e->getMessage();
        }

        // Close the connection
        $conn = null;
    }



    if (isset($_POST['selectSubmit'])) {
        $change_id = $_POST['change_id'];
        $conn = new mysqli($hostname, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }


        $sql_select = "SELECT r.name, r.surname, r.organization, r.sex, r.birth, r.death, p.year, p.contribution_sk,
                p.contribution_en, pd.language_sk,pd.language_en,pd.genre_sk, pd.genre_en, ca.category, co.country
                FROM receivers r
                JOIN prizes p ON r.id = p.receiver_id
                JOIN prize_details pd ON p.prize_detail_id = pd.id
                JOIN categories ca ON p.category_id = ca.id
                JOIN countries co ON p.country_id = co.id
                WHERE r.id = $change_id;";

        // Execute query
        $result = $conn->query($sql_select);

        if ($result->num_rows > 0) {
            // Fetch the row inside the loop
            while ($row = $result->fetch_assoc()) {
                // Store values from the fetched row
                $name = $row["name"];
                $surname = $row["surname"];
                $organisation = $row["organization"];
                $gender = $row["sex"];
                $birth = $row["birth"];
                $death = $row["death"];
                $year = $row["year"];
                $contribution_en = $row["contribution_en"];
                $contribution_sk = $row["contribution_sk"];
                $category = $row["category"];
                $country = $row["country"];
                $language_en = $row["language_en"];
                $language_sk = $row["language_sk"];
                $genre_sk = $row["genre_sk"];
                $genre_en = $row["genre_en"];
                // echo $gender;
                ?>

                <form method="post" class="row g-3 needs-validation" id="form" novalidate>
                    <div class="col-md-4">
                        <label for="name" class="form-label">Meno</label>
                        <input value="<?php echo $name; ?> " type="text" name="name" id="name" class="form-control"
                            onblur="checkReceiver()" required maxlength="255">
                        <div class="invalid-feedback">
                            Musíš zadať meno a priezvisko alebo organizáciu.
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="surname" class="form-label">Priezvisko</label>
                        <input value="<?php echo $surname; ?>" type="text" name="surname" id="surname" class="form-control"
                            onblur="checkReceiver()" maxlength="255">
                        <div class="invalid-feedback">
                            Musíš zadať meno a priezvisko alebo organizáciu.
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="organisation" class="form-label">Organizácia</label>
                        <input value="<?php echo $organisation; ?>" type="text" name="organisation" id="organisation"
                            class="form-control" onblur="checkReceiver()" maxlength="255">
                        <div class="invalid-feedback">
                            Musíš zadať meno a priezvisko alebo organizáciu.
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="gender" class="form-label">Pohlavie</label>
                        <select name="gender" id="gender" class="form-select">
                            <option value="M" <?php echo ($gender === 'M') ? ' selected' : ''; ?>>Muž</option>
                            <option value="F" <?php echo ($gender === 'F') ? ' selected' : ''; ?>>Žena</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="birth" class="form-label">D. narodenia</label>
                        <input value="<?php echo $birth; ?>" type="text" name="birth" id="birth" class="form-control" required
                            maxlength="4">
                        <div class="invalid-feedback">
                            Prosím zadaj dátum narodenia/vzniku.
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="death" class="form-label">D. smrti</label>
                        <input value="<?php echo $death; ?>" type="text" name="death" id="death" class="form-control" maxlength="4">
                    </div>
                    <div class="col-md-4 mx-auto">
                        <label for="year" class="form-label">Rok udelenia ceny</label>
                        <input value="<?php echo $year; ?>" type="number" min='1901' max='2024' name="year" id="year"
                            class="form-control" required maxlength="4">
                        <div class="invalid-feedback">
                            Prosím zadaj rok udelenia ceny.
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label for="contribution_en" class="form-label">Prínos - en</label>
                        <input value="<?php echo $contribution_en; ?>" type="text" name="contribution_en" id="contribution_en"
                            class="form-control" required maxlength="400">
                        <div class="invalid-feedback">
                            Prosím zadaj prínos (anglicky).
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label for="contribution_sk" class="form-label">Prínos - sk</label>
                        <input value="<?php echo $contribution_sk; ?>" type="text" name="contribution_sk" id="contribution_sk"
                            class="form-control" required maxlength="400">
                        <div class="invalid-feedback">
                            Prosím zadaj prínos (slovensky).
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="category" class="form-label">Kategoria</label>
                        <select name="category" id="category" class="form-select" required onchange="checkLiterature()">
                            <option value="1" <?php echo ($category === 'fyzika') ? ' selected' : ''; ?>>Fyzika</option>
                            <option value="2" <?php echo ($category === 'chémia') ? ' selected' : ''; ?>>Chémia</option>
                            <option value="5" <?php echo ($category === 'literatúra') ? ' selected' : ''; ?>>Literatúra</option>
                            <option value="3" <?php echo ($category === 'medicína') ? ' selected' : ''; ?>>Medicína</option>
                            <option value="4" <?php echo ($category === 'mier') ? ' selected' : ''; ?>>Mier</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label for="country" class="form-label">Krajina</label>
                        <input value="<?php echo $country; ?>" type="text" name="country" id="country" class="form-control" required
                            maxlength="150">
                        <div class="invalid-feedback">
                            Prosím zadaj krajinu.
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="language_en" class="form-label">Jazyk - en</label>
                        <input value="<?php echo $language_en; ?>" type="text" name="language_en" id="language_en"
                            class="form-control" maxlength="255">
                        <div class="invalid-feedback">
                            Prosím zadaj jazyk (anglicky).
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="language_sk" class="form-label">Jazyk - sk</label>
                        <input value="<?php echo $language_sk; ?>" type="text" name="language_sk" id="language_sk"
                            class="form-control" maxlength="255">
                        <div class="invalid-feedback">
                            Prosím zadaj jazyk (slovensky).
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="genre_sk" class="form-label">Zaner - sk</label>
                        <input value="<?php echo $genre_sk; ?>" type="text" name="genre_sk" id="genre_sk" class="form-control"
                            maxlength="255">
                        <div class="invalid-feedback">
                            Prosím zadaj žáner (slovensky).
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="genre_en" class="form-label">Zaner - en</label>
                        <input value="<?php echo $genre_en; ?>" type="text" name="genre_en" id="genre_en" class="form-control"
                            maxlength="255">
                        <div class="invalid-feedback">
                            Prosím zadaj žáner (anglicky).
                        </div>
                    </div>
                    <input type='hidden' name='change_id' value=' <?php echo $change_id ?>'>
                    <div class="col-12">
                        <button class="btn btn-primary" name="formSubmit" type="submit">Submit form</button>
                    </div>
                </form>
                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                    $(document).ready(function () {
                        $('#form').submit(function (event) {
                            const form = this;
                            if (!form.checkValidity()) {
                                event.preventDefault();
                                event.stopPropagation();
                            } else {
                                $('.toast').toast('show');
                            }

                            form.classList.add('was-validated');

                        });
                    });
                </script>
                <?php
            }
        }
    }
    if (isset($_POST['formSubmit'])) {
        $change_id = $_POST['change_id'];
        try {
            $conn = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepare the SQL statement
            $sql = "UPDATE receivers 
                    JOIN prizes p ON receivers.id = p.receiver_id
                    JOIN prize_details pd ON p.prize_detail_id = pd.id
                    JOIN categories ca ON p.category_id = ca.id
                    JOIN countries co ON p.country_id = co.id
                    SET receivers.name = :name,
                        receivers.surname = :surname,
                        receivers.organization = :organisation,
                        receivers.sex = :gender,
                        receivers.birth = :birth,
                        receivers.death = :death,
                        p.year = :year,
                        pd.language_sk = :language_sk,
                        pd.language_en = :language_en,
                        pd.genre_sk = :genre_sk,
                        pd.genre_en = :genre_en,
                        p.contribution_en = :contribution_en,
                        p.contribution_sk = :contribution_sk,
                        p.category_id = :category,
                        co.country = :country
                    WHERE receivers.id = :change_id";
            // echo $sql;
    
            $stmt = $conn->prepare($sql);

            if (
                strlen($_POST['name']) > 255 || strlen($_POST['surname']) > 255 || strlen($_POST['organisation']) > 255 || strlen($_POST['gender']) > 4
                || strlen($_POST['birth']) > 4 || strlen($_POST['death']) > 4 || strlen($_POST['year']) > 4 || strlen($_POST['contribution_sk']) > 400 ||
                strlen($_POST['contribution_en']) > 400 || strlen($_POST['language_sk']) > 255 || strlen($_POST['language_en']) > 255 ||
                strlen($_POST['genre_sk']) > 255 || strlen($_POST['genre_en']) > 255
            ) {
                echo '<h2>Chyba na vstupe<?h2>';
                exit;
            }
            $stmt->bindParam(':change_id', $_POST['change_id'], PDO::PARAM_INT);
            $stmt->bindParam(':name', $_POST['name'], PDO::PARAM_STR);
            $stmt->bindParam(':surname', $_POST['surname'], PDO::PARAM_STR);
            $stmt->bindParam(':organisation', $_POST['organisation'], PDO::PARAM_STR);
            $stmt->bindParam(':gender', $_POST['gender'], PDO::PARAM_STR);
            $stmt->bindParam(':birth', $_POST['birth'], PDO::PARAM_STR);
            $stmt->bindParam(':death', $_POST['death'], PDO::PARAM_STR);
            $stmt->bindParam(':year', $_POST['year'], PDO::PARAM_STR);
            $stmt->bindParam(':language_sk', $_POST['language_sk'], PDO::PARAM_STR);
            $stmt->bindParam(':language_en', $_POST['language_en'], PDO::PARAM_STR);
            $stmt->bindParam(':genre_sk', $_POST['genre_sk'], PDO::PARAM_STR);
            $stmt->bindParam(':genre_en', $_POST['genre_en'], PDO::PARAM_STR);
            $stmt->bindParam(':contribution_en', $_POST['contribution_en'], PDO::PARAM_STR);
            $stmt->bindParam(':contribution_sk', $_POST['contribution_sk'], PDO::PARAM_STR);
            $stmt->bindParam(':category', $_POST['category'], PDO::PARAM_STR);
            $stmt->bindParam(':country', $_POST['country'], PDO::PARAM_STR);



            $stmt->execute();

            echo "<h2>Nositeľ bol zmenený</h2>";
        } catch (PDOException $e) {
            echo "Error changing receiver: " . $e->getMessage();
        }
        $conn = null;
    }
    ?>
</body>


</html>