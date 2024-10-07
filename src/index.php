<?php
session_start();
?>
<!--použité rôzne kódy z repozitára https://github.com/jancisefcik/webte2-cvicenia -->

<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nobel prizes</title>
    <?php include 'header.php'; ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8"
        src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

    <style>
        #filterForm {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 5rem;
            margin-top: 1rem;
        }

        #tabulka_wrapper {
            margin: 3rem;
        }

        #tabulka thead {
            background-color: #E6E6DC;
        }

        #tabulka tbody tr {
            background-color: #81A594;
            color: #E6E6DC;
        }

        .dataTables_length label,
        .dataTables_length select,
        #tabulka_info,
        #tabulka_filter,
        #tabulka_paginate {
            background-color: #E6E6DC;
            padding: 0.5rem;
            margin: 0.5rem;
            border-radius: 5px;

        }

        .btn {
            margin: 0.5rem;
            background-color: #81A594;
            color: #E6E6DC;
        }
    </style>
    <script>
        function changeYear() {
            var year = document.getElementById("year");

        }
    </script>

</head>

<body>

    <?php
    // session_start();
    
    if ((isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) || (isset($_SESSION['access_token']) && $_SESSION['access_token'])) {
        $login = $_SESSION['login'] ?? ''; // Use null coalescing to avoid undefined index notice
        $email = $_SESSION['email'] ?? '';
        $id = $_SESSION['id'] ?? '';
        $fullname = $_SESSION['fullname'] ?? '';
        $name = $_SESSION['name'] ?? '';
        $surname = $_SESSION['surname'] ?? '';

        echo "<div class='logInfo'>";
        echo "<p>Vitaj $fullname $login </p>";
        echo "</div>";
    }
    ?>



    <form id="filterForm" method="POST">


        <select name="year" id="year" class="btn btn-outline-light" onchange="changeYear()">
            <option value="all" <?php if (isset($_POST['year']) && $_POST['year'] == "all")
                echo "selected"; ?>>Filter rok
            </option>
            <option value="2014-2004" <?php if (isset($_POST['year']) && $_POST['year'] == "2014-2004")
                echo "selected"; ?>>2014-2004</option>
            <option value="2004-1994" <?php if (isset($_POST['year']) && $_POST['year'] == "2004-1994")
                echo "selected"; ?>>2004-1994</option>
            <option value="1994-1984" <?php if (isset($_POST['year']) && $_POST['year'] == "1994-1984")
                echo "selected"; ?>>1994-1984</option>
            <option value="1984-1974" <?php if (isset($_POST['year']) && $_POST['year'] == "1984-1974")
                echo "selected"; ?>>1984-1974</option>
            <option value="1974-1964" <?php if (isset($_POST['year']) && $_POST['year'] == "1974-1964")
                echo "selected"; ?>>1974-1964</option>
            <option value="1964-1954" <?php if (isset($_POST['year']) && $_POST['year'] == "1964-1954")
                echo "selected"; ?>>1964-1954</option>
            <option value="1954-1944" <?php if (isset($_POST['year']) && $_POST['year'] == "1954-1944")
                echo "selected"; ?>>1954-1944</option>
            <option value="1944-1934" <?php if (isset($_POST['year']) && $_POST['year'] == "1944-1934")
                echo "selected"; ?>>1944-1934</option>
            <option value="1934-1924" <?php if (isset($_POST['year']) && $_POST['year'] == "1934-1924")
                echo "selected"; ?>>1934-1924</option>
            <option value="1924-1901" <?php if (isset($_POST['year']) && $_POST['year'] == "1924-1901")
                echo "selected"; ?>>1924-1901</option>
        </select>

        <select name="category" id="category" class="btn btn-outline-light">
            <option value="all" <?php if (isset($_POST['category']) && $_POST['category'] == "all")
                echo "selected"; ?>>
                Filter kategória</option>
            <option value="fyzika" <?php if (isset($_POST['category']) && $_POST['category'] == "fyzika")
                echo "selected"; ?>>Fyzika</option>
            <option value="chémia" <?php if (isset($_POST['category']) && $_POST['category'] == "chémia")
                echo "selected"; ?>>Chémia</option>
            <option value="mier" <?php if (isset($_POST['category']) && $_POST['category'] == "mier")
                echo "selected"; ?>>
                Mier</option>
            <option value="literatúra" <?php if (isset($_POST['category']) && $_POST['category'] == "literatúra")
                echo "selected"; ?>>Literatúra</option>
            <option value="medicína" <?php if (isset($_POST['category']) && $_POST['category'] == "medicína")
                echo "selected"; ?>>Medicína</option>
        </select>
    </form>



    <table id="tabulka" style="width:100%">
        <thead>
            <tr>
                <th>Year</th>
                <th>Name</th>
                <th>Surname</th>
                <th>Organization</th>
                <th>Category</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // include "../../../../etc/nginx/config.php";
            $hostname = "mysql";
            $username = "user";
            $password = "user";
            $dbname = "nobel";

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $year = $_POST['year'];
                $years = explode("-", $year);
                $category = $_POST['category'];
                try {
                    $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);

                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $whereSql = 'WHERE 1 = 1 ';
                    if ($category !== "all") {
                        $whereSql .= "and c.category = '$category'";
                    }
                    if ($year !== "all") {
                        $whereSql .= " and p.year BETWEEN $years[1] AND $years[0]";
                    }

                    $query = "SELECT p.year, r.id, r.name, r.surname, r.organization, c.category
                  FROM prizes p
                  JOIN receivers r ON p.receiver_id = r.id
                  JOIN categories c ON p.category_id = c.id
                  $whereSql";
                    $stmt = $db->prepare($query);
                    $stmt->execute();


                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";

                        echo "<td>{$row['year']}</td>";
                        echo "<td><a href='detail_page.php?id={$row["id"]}'>{$row["name"]}</a></td>";
                        echo "<td>{$row['surname']}</td>";
                        echo "<td>{$row['organization']}</td>";
                        echo "<td>{$row['category']}</td>";
                        echo "</tr>";
                    }
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }
            } else {
                try {
                    $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);

                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



                    $query = "SELECT p.year, r.id, r.name, r.surname, r.organization, c.category
                  FROM prizes p
                  JOIN receivers r ON p.receiver_id = r.id
                  JOIN categories c ON p.category_id = c.id ";

                    $stmt = $db->prepare($query);
                    $stmt->execute();

                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>{$row['year']}</td>";
                        echo "<td><a href='detail_page.php?id={$row["id"]}'>{$row["name"]}</a></td>";
                        echo "<td>{$row['surname']}</td>";
                        echo "<td>{$row['organization']}</td>";
                        echo "<td>{$row['category']}</td>";
                        echo "</tr>";
                    }
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }
            }

            ?>

        </tbody>
    </table>

    <script>
        $(document).ready(function () {
            var table = $('#tabulka').DataTable({
                language: {
                    pageLength: "Show _MENU_ articles per page"
                },
                lengthMenu: [
                    [10, 25, -1],
                    [10, 25, "All"]
                ],
                paging: true,
                columnDefs: [{
                    "orderable": false,
                    "targets": [1, 3]
                }]
            });

            function updateColumnVisibility() {
                var yearVisibility = ($('#year').val() === "all") ? true : false;
                var categoryVisibility = ($('#category').val() === 'all') ? true : false;

                table.column(0).visible(yearVisibility);
                table.column(4).visible(categoryVisibility);
            }

            updateColumnVisibility();

            $('#year, #category').change(function () {
                updateColumnVisibility();
            });

            $('#filterForm').submit(function () {

                updateColumnVisibility();
            });
            $('#year, #category').on('change', function () {
                $('#filterForm').submit();
            });

        });
    </script>
</body>

</html>