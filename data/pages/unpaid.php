<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: signin.php");
    exit;
}
header("Content-type: text/html; charset=utf-8");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impayés</title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/menu.css">
    <link rel="stylesheet" href="../style/table.css">
    <link rel="stylesheet" href="../style/remittance.css">
    <link rel="stylesheet" href="../style/chart.css">
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
                Impayés
            </div>
            <article>
                    <?php
                        include("../script_php/cnx.php");
                        if ($_SESSION["user_type"] == "productOwner") {
                            echo '
                                <form id="choseCompanyForm" action="unpaid.php" method="POST">
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
                    <div class="date-range">
                        <form id="dateForm" action="unpaid.php" method="POST">
                            <input type="hidden" name="chosenDateRange" id="chosenDateRangeInput">
                        </form>
                        <select name="chosenDateRange" id="start-end">
                            <option value="" disabled selected>Date de début</option>
                            <?php
                                if ($_SESSION["user_type"] == "productOwner") {
                                    $req = $mysqli->prepare("SELECT DISTINCT dateTransaction FROM transaction WHERE sensTransaction = '-' ORDER BY dateTransaction;");
                                } else if ($_SESSION["user_type"] == "client") {
                                    $req = $mysqli->prepare("SELECT dateTransaction FROM transaction WHERE idUtilisateur = ? AND sensTransaction = '-' ORDER BY dateTransaction;");
                                    $req->bind_param("s", $_SESSION["user_id"]);
                                }

                                $req->execute();    
                                $result = $req->get_result();
                                $dateSoldes = array();
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row['dateTransaction'] . "'>" . $row['dateTransaction'] . "</option>";
                                }
                            ?>
                        </select>
                        <select name="chosenDateRange" id="end-start">
                            <option value="" disabled selected>Date de fin</option>
                            <?php
                                if ($_SESSION["user_type"] == "productOwner") {
                                    $req = $mysqli->prepare("SELECT DISTINCT dateTransaction FROM transaction WHERE sensTransaction = '-' ORDER BY dateTransaction;");
                                } else if ($_SESSION["user_type"] == "client") {
                                    $req = $mysqli->prepare("SELECT dateTransaction FROM transaction WHERE idUtilisateur = ? AND sensTransaction = '-' ORDER BY dateTransaction;");
                                    $req->bind_param("s", $_SESSION["user_id"]);
                                }

                                $req->execute();    
                                $result = $req->get_result();
                                $dateSoldes = array();
                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row['dateTransaction'] . "'>" . $row['dateTransaction'] . "</option>";
                                    $dateSoldes[] = $row["dateTransaction"];
                                }
                            ?>
                        </select>
                        <button id="dateRangeValidate">Valider</button>
                        <button id="dateRangeReset">Réinitialiser</button>
                    </div>


                <div class="user-settings">
                    <label for="itemsPerPage">Nombre d'éléments par page :</label>
                    <select name="itemsPerPage" id="itemsPerPage">
                        <option value="1">1</option>
                        <option value="2" selected>2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
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
                                        <span>Date Vente</span>
                                        <div class="sort-arrow"><button class="top-arrow"></button><button class="bot-arrow"></button></div>
                                    </span>
                                </th>
                                <th><span id="sort_by">
                                        <span>Date Remise</span>
                                        <div class="sort-arrow"><button class="top-arrow"></button><button class="bot-arrow"></button></div>
                                    </span>
                                </th>
                                <th>NumCarte</th>
                                <th>Réseau</th>
                                <th><span id="sort_by">
                                        <span>N° Dossier Impayé</span>
                                        <div class="sort-arrow"><button class="top-arrow"></button><button class="bot-arrow"></button></div>
                                    </span>
                                </th>
                                <th>Devise</th>
                                <th><span id="sort_by">
                                        <span>Montant</span>
                                        <div class="sort-arrow"><button class="top-arrow"></button><button class="bot-arrow"></button></div>
                                    </span>
                                </th>
                                <th><span id="sort_by">
                                        <span>Libellé Impayé</span>
                                        <div class="sort-arrow"><button class="top-arrow"></button><button class="bot-arrow"></button></div>
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Contenu du tableau -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="10">
                                    <div id="pagination"></div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="somme-impayes" id="somme-impayes">
                    <table>
                        <thead>
                            <tr>
                                <th><span id="sort_by" class="sort-impayes">
                                        <span>N° SIREN</span>
                                        <div class="sort-arrow"><button class="top-arrow"></button><button class="bot-arrow"></button></div>
                                    </span>
                                </th>
                                <th><span id="sort_by" class="sort-impayes">
                                        <span>Raison Sociale</span>
                                        <div class="sort-arrow"><button class="top-arrow"></button><button class="bot-arrow"></button></div>
                                    </span>
                                </th>
                                <th><span id="sort_by" class="sort-impayes">
                                        <span>Montant</span>
                                        <div class="sort-arrow"><button class="top-arrow"></button><button class="bot-arrow"></button></div>
                                    </span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Contenu du tableau -->
                        </tbody>
                    </table>
                </div>

                <div class="export">
                    <button id="export-pdf">Exporter en PDF</button>
                    <button id="export-xls">Exporter en Excel</button>
                    <button id="export-csv">Exporter en CSV</button>
                </div>

                <div class="chart-choice">
                    <input type="radio" id="line-chart" name="chart-choice" value="line-chart" checked>
                    <label for="line-chart">Line Chart</label>
                    <input type="radio" id="bar-chart" name="chart-choice" value="bar-chart" >
                    <label for="bar-chart">Bar Chart</label>
                </div>
                <div class="chart-container">
                    <canvas id="myChart"></canvas>
                    <canvas id="myBarChart" style="display: none;"></canvas>
                    <button id="export-chart-pdf" data-title="Graphique Impayés">Exporter en PDF</button>
                </div>  
                <div class="pie-chart-container">
                    <canvas id="myPieChart"></canvas>
                    <button id="export-piechart-pdf">Exporter en PDF</button>
                </div>
            </article>
        </section>
    </div>
</body>
<script>
<?php
    if ($_SESSION["user_type"] == "productOwner") {
        if (isset($_POST["chosenCompany"]) && $_POST["chosenCompany"] != "all") {
            $chosenCompany = htmlspecialchars($_POST["chosenCompany"]);
            $req = $mysqli->prepare("SELECT numSiren, raisonSociale, dateTransaction, dateRemise, numCarte, reseau, numImpaye, deviseTransaction, montantTransaction, libelleImpaye
                                FROM client, transaction, remise, impaye
                                WHERE client.idUtilisateur = transaction.idUtilisateur 
                                AND transaction.numRemise = remise.numRemise 
                                AND impaye.idTransaction = transaction.idTransaction
                                AND raisonSociale = ?;
                                ORDER BY dateTransaction");
            $req->bind_param("s", $chosenCompany);
        } else {
            $req = $mysqli->prepare("SELECT numSiren, raisonSociale, dateTransaction, dateRemise, numCarte, reseau, numImpaye, deviseTransaction, montantTransaction, libelleImpaye
                                    FROM client, transaction, remise, impaye
                                    WHERE client.idUtilisateur = transaction.idUtilisateur 
                                    AND transaction.numRemise = remise.numRemise 
                                    AND impaye.idTransaction = transaction.idTransaction
                                    ORDER BY dateTransaction;");
        }
    } 
    if ($_SESSION["user_type"] == "client") {
        $req = $mysqli->prepare("SELECT numSiren, raisonSociale, dateTransaction, dateRemise, numCarte, reseau, numImpaye, deviseTransaction, montantTransaction, libelleImpaye
                                FROM client, transaction, remise, impaye
                                WHERE client.idUtilisateur = transaction.idUtilisateur 
                                AND transaction.numRemise = remise.numRemise 
                                AND impaye.idTransaction = transaction.idTransaction
                                AND client.idUtilisateur = ?;");
        $req->bind_param("s", $_SESSION["user_id"]);
    }
    $req->execute();
    $result = $req->get_result();
    $table = "[";
    $dates = array();
    $libelleImpaye = array();
    $allData = array();
    $clientDateAmount = array();
    $allClientDateAmount = array();
    $raisonSociales = array();
    while ($row = $result->fetch_assoc()) {
        if (!in_array($row["dateTransaction"], $dates)) {
            $dates[] = $row["dateTransaction"];
        }
        if (!in_array($row["raisonSociale"], $raisonSociales)) {
            $raisonSociales[] = $row['raisonSociale'];
        }

        $clientDateAmount = [$row["raisonSociale"], $row["dateTransaction"], $row["montantTransaction"]];
        $allClientDateAmount[] = $clientDateAmount;
        $allData[] = $row;
        $libelleImpaye[$row["libelleImpaye"]]++;
        $table .= "{";
        $table .= "\"N° SIREN\": \"" . $row["numSiren"] . "\",";
        $table .= "\"Raison Sociale\": \"" . $row["raisonSociale"] . "\",";
        $table .= "\"Date Vente\": \"" . $row["dateTransaction"] . "\",";
        $table .= "\"Date Remise\": \"" . $row["dateRemise"] . "\",";
        $table .= "\"NumCarte\": \"" . $row["numCarte"] . "\",";
        $table .= "\"Réseau\": \"" . $row["reseau"] . "\",";
        $table .= "\"N° Dossier Impayé\": \"" . $row["numImpaye"] . "\",";
        $table .= "\"Devise\": \"" . $row["deviseTransaction"] . "\",";
        $table .= "\"Montant\": \"" . $row["montantTransaction"] . "\",";
        $table .= "\"Libellé Impayé\": \"" . $row["libelleImpaye"] . "\"";
        $table .= "},";
    }
    $table = substr($table,0,-1);
    $table .= "]";

    $xValues = "[";
    foreach ($dates as $date) {
        $xValues .= "\"" . $date . "\",";
    }
    $xValues = substr($xValues, 0, -1);
    $xValues .= "]";
?>
var tableData = <?php echo $table; ?>;
var hidden = false;
var documentName = "impayes";
var allData = <?php echo json_encode($allData); ?>;
// console.log(allData);
// console.log(raisonSociales);
// console.log("AAAAAAAAAAAAAAAAAAAAh");
</script>
<script src="../script/table.js"></script>
<script>
    function chosenCompany() {
        var chosenCompany = document.getElementById("chosenCompany").value;
        var chosenCompanyInput = document.getElementById("chosenCompanyInput");
        chosenCompanyInput.value = chosenCompany;
        console.log(chosenCompanyInput.value);
        document.getElementById("choseCompanyForm").submit();
    }

    var chartChoice = document.querySelectorAll("input[name=\"chart-choice\"]");
    var xValues = <?php echo $xValues; ?>;  
    var raisonSociales = <?php echo json_encode($raisonSociales); ?>;
</script>
<script src="../script/chart.js"></script>
<script>
    function countLibelleImpaye(data) {
    var count = {};
    
    for (var i = 0; i < data.length; i++) {
        var libelleImpaye = data[i]["Libellé Impayé"];
        
        if (count[libelleImpaye]) {
        count[libelleImpaye] += 1;
        } else {
        count[libelleImpaye] = 1;
        }
    }
    
    return count;
    }

    var libelleImpaye = countLibelleImpaye(tableData);
    xPieValues = [];
    yPieValues = [];
    for (var libelle in libelleImpaye) {
        xPieValues.push(libelle);
        yPieValues.push(libelleImpaye[libelle]);
    }
    var title = "Impayés";
    var ChartTitle = "Graphique Impayés";
</script>
<script src="../script/unpaidChart.js"></script>
<script src="../script/export.js" type="module"></script>
<!-- Ajoutez cette balise script à votre page pour gérer les changements de date en temps réel -->

<script>
    function sendToPHP() {
        var chosenDateRange = document.getElementById("chosenDateRange").value;
        var chosenDateRangeInput = document.getElementById("chosenDateRangeInput");
        chosenDateRangeInput.value = chosenDateRange;
        document.getElementById("dateForm").submit();
    }
</script>
</html>