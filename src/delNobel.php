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
    <title>Vymaz</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <style>
        h2 {
            background-color: #E6E6DC;
            width: 40%;
            margin: 1rem;
            text-align: center;
            border-radius: 5px;
            padding: 0.5rem;
        }

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
            if (name.value.trim() == '' && surname.value.trim() == '' && organisation.value.trim() == '') {
                name.required = true;
                surname.required = false;
                organisation.required = false;
                name.disabled = false;
                surname.disabled = false;
                organisation.disabled = false;
            }
            if (name.value.trim() !== '') {
                name.required = true;
                surname.required = false;
                organisation.required = false
                organisation.disabled = true;
            } else if (surname.value.trim() !== '') {
                surname.required = true;
                name.required = false;
                organisation.required = false
                organisation.disabled = true;
            } else if (organisation.value.trim() !== '') {
                name.disabled = true;
                surname.disabled = true;
                organisation.required = true;
                name.required = false;
                surname.required = false;


            }
        }
    </script>
</head>

<body>

    <div class="position-fixed bottom-0 end-0 p-3">
        <div class="toast " role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Vymazanie</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
                Úspešne si vymazal nositeľa Nobelovej ceny.
            </div>
        </div>
    </div>
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
    <form method="post" class="row g-3 needs-validation" id="form" novalidate>

        <div class="col-md-4">
            <label for="name" class="form-label">Meno</label>
            <input type="text" name="name" id="name" class="form-control" onblur="checkReceiver()" required>
            <div class="invalid-feedback">
                Musíš zadať meno a priezvisko alebo organizáciu.
            </div>
        </div>

        <div class="col-md-4">
            <label for="surname" class="form-label">Priezvisko</label>
            <input type="text" name="surname" id="surname" class="form-control" onblur="checkReceiver()">
        </div>

        <div class="col-md-4">
            <label for="organisation" class="form-label">Organizácia</label>
            <input type="text" name="organisation" id="organisation" class="form-control" onblur="checkReceiver()">
        </div>

        <div class="col-12">
            <button class="btn btn-primary" type="submit" value="Submit">Hľadaj</button>
        </div>
    </form>

    <?php
    // include "../../../../etc/nginx/config.php";
    $hostname = "mysql";
    $username = "user";
    $password = "user";
    $dbname = "nobel";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['delete_id'])) {
            $delete_id = $_POST['delete_id'];
            try {
                $conn = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $sql_delete = "DELETE receivers, p, pd, co
                FROM receivers
                JOIN prizes p ON receivers.id = p.receiver_id
                JOIN prize_details pd ON p.prize_detail_id = pd.id
                JOIN countries co ON p.country_id = co.id
                                 WHERE receivers.id = :delete_id";

                $stmt = $conn->prepare($sql_delete);
                $stmt->bindParam(':delete_id', $delete_id, PDO::PARAM_INT);
                $stmt->execute();
                // echo "asda" . $delete_id . "sdad";
                echo "<h2>Nositeľ bol vymazaný</h2>";
            } catch (PDOException $e) {
                echo "Error deleting receiver: " . $e->getMessage();
            }


            $conn = null;
        } else if (isset($_POST['name']) || isset($_POST['surname']) || isset($_POST['organisation'])) {
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
                    echo "<input type='hidden' name='delete_id' value='" . $row['id'] . "'>";
                    echo "<input type='submit' value='Delete'>";
                    echo "</form>";
                    echo "</div>";
                }
            } catch (PDOException $e) {
                echo "Error selecting receiver: " . $e->getMessage();
            }

            // Close the connection
            $conn = null;
        }
    }
    ?>
</body>


</html>