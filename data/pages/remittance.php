<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: signin.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/menu.css">
    <link rel="stylesheet" href="../style/table.css">
    <link rel="stylesheet" href="../style/remittance.css">
    <title>Remises</title>
    <script src='../script/main.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.6/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
</head>

<body>
    <div class="page">
        <?php
            include('header.inc.php');
        ?>
        <section>
            <div class="title">
                Remises
            </div>
            <article>
                    <?php
                        include("../script_php/cnx.php");
                        if ($_SESSION["user_type"] == "productOwner") {
                            echo '
                                <form id="choseCompanyForm" action="remittance.php" method="POST">
                                    <input type="hidden" name="chosenCompany" id="chosenCompanyInput">
                                </form>
                                <select name="chosenCompany" id="chosenCompany" onchange="chosenCompany()">
                                    <option value="" selected="selected">Entreprise</option>
                                    <option value="all">All</option>
                            ';
                            $req = $mysqli->prepare("SELECT raisonSociale 
                                                    FROM client;");
                            $req->execute();    
                            $result = $req->get_result();
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['raisonSociale'] . "'>" . $row['raisonSociale'] . "</option>";
                            }
                            echo "</select>";
                        }
                    ?>
                </select>
                <select name="chosenRemise" id="chosenRemise">
                    <option value="" selected="selected">Remise</option>
                    <option value="all">All</option>
                    <?php
                        $req = $mysqli->prepare("SELECT numRemise 
                                                FROM remise;");
                        $req->execute();    
                        $result = $req->get_result();
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['numRemise'] . "'>" . $row['numRemise'] . "</option>";
                        }
                    ?>
                </select>
                <div class="user-settings">
                    <label for="itemsPerPage">Nombre d'éléments par page :</label>
                    <select name="itemsPerPage" id="itemsPerPage">
                        <option value="2">1</option>
                        <option value="2" selected>2</option>
                        <option value="2">3</option>
                        <option value="2">4</option>
                    </select>
                </div>
                <div class="nbrResult">
                    <p>Nombre de résultats : <span id="nbrResult">0</span></p>
                </div>
                <div class="table-container" id="table">
                    <table>
                        <thead>
                        <tr>
                            <th><span id="sort_by">
                                    <span>N° SIREN</span>
                                    <div class="sort-arrow"><button class="top-arrow"></button><button class="bot-arrow"></button></div>
                                </span>
                            </th>
                            <th><span id="sort_by">
                                    <span>Raison Sociale</span>
                                    <div class="sort-arrow"><button class="top-arrow"></button><button class="bot-arrow"></button></div>
                                </span>
                            </th>
                            <th><span id="sort_by">
                                    <span>N° Remise</span>
                                    <div class="sort-arrow"><button class="top-arrow"></button><button class="bot-arrow"></button></div>
                                </span>
                            </th>
                            <th><span id="sort_by">
                                    <span>Date Traitement</span>
                                    <div class="sort-arrow"><button class="top-arrow"></button><button class="bot-arrow"></button></div>
                                </span>
                            </th>
                            <th><span id="sort_by">
                                    <span>Nbre Traitement</span>
                                    <div class="sort-arrow"><button class="top-arrow"></button><button class="bot-arrow"></button></div>
                                </span>
                            </th>
                            <th><span id="sort_by">
                                    <span>Devise</span>
                                    <div class="sort-arrow"><button class="top-arrow"></button><button class="bot-arrow"></button></div>
                                </span>
                            </th>
                            <th><span id="sort_by">
                                    <span>Montant</span>
                                    <div class="sort-arrow"><button class="top-arrow"></button><button class="bot-arrow"></button></div>
                                </span>
                            </th>
                            <th><span id="sort_by">
                                    <span>Sens</span>
                                    <div class="sort-arrow"><button class="top-arrow"></button><button class="bot-arrow"></button></div>
                                </span>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Contenu du tableau en JS -->
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="8">
                            <div id="pagination"></div> <!-- Élément pour afficher la pagination -->
                            </td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="export">
                    <button id="export-pdf">Exporter en PDF</button>
                    <button id="export-xls">Exporter en Excel</button>
                    <button id="export-csv">Exporter en CSV</button>
                </div>
            </article>
        </section>
    </div>
</body>
<script src="../script/menu-animation.js"></script>
<?php
    if ($_SESSION["user_type"] == "client") {
        $req = $mysqli->prepare("
        SELECT c.numSiren, c.raisonSociale, t.numRemise, COUNT(t.numRemise) AS nbTransaction, r.dateRemise, r.montantRemise, r.deviseRemise, r.sensRemise
        FROM client c
        LEFT JOIN transaction t ON c.idUtilisateur = t.idUtilisateur
        LEFT JOIN remise r ON t.numRemise = r.numRemise
        WHERE c.idUtilisateur = ?
        AND t.idUtilisateur = ?
        GROUP BY c.numSiren, c.raisonSociale, t.numRemise, r.dateRemise, r.montantRemise, r.deviseRemise, r.sensRemise;
        ");
        $req->bind_param("ss", $_SESSION["user_id"], $_SESSION["user_id"]);
    }
    if ($_SESSION["user_type"] == "productOwner") {
        if (isset($_GET["chosenCompanie"]) && $_GET["chosenCompanie"] != "all") {
            $chosenCompany = htmlspecialchars($_GET["chosenCompany"]);
            $req = $mysqli->prepare("
                SELECT c.numSiren, c.raisonSociale, t.numRemise, COUNT(t.numRemise) AS nbTransaction, r.dateRemise, r.montantRemise, r.deviseRemise, r.sensRemise
                FROM client c
                LEFT JOIN transaction t ON c.idUtilisateur = t.idUtilisateur
                LEFT JOIN remise r ON t.numRemise = r.numRemise
                WHERE c.raisonSociale = ?
                GROUP BY c.numSiren, c.raisonSociale, t.numRemise, r.dateRemise, r.montantRemise, r.deviseRemise, r.sensRemise;
            ");
            $req->bind_param("s", $chosenCompany);
        } else {
            $req = $mysqli->prepare("
                SELECT c.numSiren, c.raisonSociale, t.numRemise, COUNT(t.numRemise) AS nbTransaction, r.dateRemise, r.montantRemise, r.deviseRemise, r.sensRemise
                FROM client c
                LEFT JOIN transaction t ON c.idUtilisateur = t.idUtilisateur
                LEFT JOIN remise r ON t.numRemise = r.numRemise
                GROUP BY c.numSiren, c.raisonSociale, t.numRemise, r.dateRemise, r.montantRemise, r.deviseRemise, r.sensRemise;
            ");
        }
    }
    $req->execute();
    $result = $req->get_result();
    $table = "[";
    $numRemises = array();
    while ($row = $result->fetch_assoc()) {
        $req = $mysqli->prepare("SELECT numRemise, dateRemise, montantRemise, deviseRemise, sensRemise FROM remise WHERE numRemise = ?;");
        $req->bind_param("s", $row["numRemise"]);
        $req->execute();
        $result_remise = $req->get_result();
        $row_remise = $result_remise->fetch_assoc();
        $numRemises[] = $row["numRemise"];
        $num_siren = $row["numSiren"];

        $table .= "{";
        $table .= "\"N° SIREN\": \"" . $row["numSiren"] . "\",";
        $table .= "\"Raison Sociale\": \"" . $row["raisonSociale"] . "\",";
        $table .= "\"N° Remise\": \"" . $row_remise["numRemise"] . "\",";
        $table .= "\"Date Traitement\": \"" . $row_remise["dateRemise"] . "\",";
        $table .= "\"Nbre Traitement\": \"" . $row["nbTransaction"] . "\",";
        $table .= "\"Devise\": \"" . $row_remise["deviseRemise"] . "\",";
        $table .= "\"Montant\": \"" . $row_remise["montantRemise"] . "\",";
        $table .= "\"Sens\": \"" . $row_remise["sensRemise"] . "\"";
        $table .= "},";
    }
    $table = substr($table, 0, -1);
    $table .= "]";

    $hiddenTable = "[";


    for ($i = 0; $i < count($numRemises); $i++) {
        $req = $mysqli->prepare("SELECT * FROM transaction WHERE numRemise = ?;");
        $req->bind_param("s", $numRemises[$i]);
        $req->execute();
        $result = $req->get_result();
        $j = 0;
        $hiddenTable .= "{";
        while ($transactions = $result->fetch_assoc()) {
            $j++;
            $hiddenTable .= "Line".$j.": {";
            $hiddenTable .= "\"N° SIREN\": \"" . $num_siren . "\",";
            $hiddenTable .= "\"DateVente\": \"" . $transactions["dateTransaction"] . "\",";
            $hiddenTable .= "\"N° Carte\": \"" . $transactions["numCarte"] . "\",";
            $hiddenTable .= "\"Réseau\": \"" . $transactions["reseau"] . "\",";
            $hiddenTable .= "\"N° Autorisation\": \"" . $transactions["numAutorisation"] . "\",";
            $hiddenTable .= "\"Devise\": \"" . $transactions["deviseTransaction"] . "\",";
            $hiddenTable .= "\"Montant\": \"" . $transactions["montantTransaction"] . "\",";
            $hiddenTable .= "\"Sens\": \"" . $transactions["sensTransaction"] . "\"";
            $hiddenTable .= "},";
        }
        $hiddenTable = substr($hiddenTable, 0, -1);
        $hiddenTable .= "},";
    }
    $hiddenTable = substr($hiddenTable, 0, -1);
    $hiddenTable .= "]";

    echo "
    <script>
        var tableData =" . $table . ";
        var hiddenTableData =" . $hiddenTable . ";
        var hidden = true;
        var documentName = 'Remises';
    </script>";
?>
<script src="../script/table.js"></script>
<script src="../script/export.js" type="module"></script>
<script>
        function chosenCompany() {
            var chosenCompany = document.getElementById("chosenCompany").value;
            var chosenCompanyInput = document.getElementById("chosenCompanyInput");
            chosenCompanyInput.value = chosenCompany;
            console.log(chosenCompanyInput.value);
            document.getElementById("choseCompanyForm").submit();
        }

        var title = "Remises";
</script>

</html>