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
    <title>Trésorerie</title>
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
                Trésorerie
            </div>
            <article>
                    <?php
                        include("../script_php/cnx.php");
                        if ($_SESSION["user_type"] == "productOwner") {
                            echo '
                                <form id="choseCompanyForm" action="treasury.php" method="POST">
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
                <form id="myForm" action="treasury.php" method="POST">
                    <input type="hidden" name="chosenDate" id="chosenDateInput">
                </form>
                <select name="chosenDate" id="chosenDate" onchange="sendToPHP()">
                    <option value="" selected="selected"> Date </option>
                    <?php
                    if ($_SESSION["user_type"] == "client") {
                        $req = $mysqli->prepare("SELECT DISTINCT dateSolde 
                                                FROM solde 
                                                WHERE idUtilisateur = ?;");
                        $req->bind_param("s", $_SESSION["user_id"]);
                    }
                    if ($_SESSION["user_type"] == "productOwner") {
                        $req = $mysqli->prepare("SELECT DISTINCT dateSolde 
                                            FROM solde;");
                    }
                    $req->execute();    
                    $result = $req->get_result();
                    $dateSoldes = array();
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['dateSolde'] . "'>" . $row['dateSolde'] . "</option>";
                        $dateSoldes[] = $row["dateSolde"];
                    }
                    $maxDate = max($dateSoldes);
                    ?>
                </select>
                <?php 
                if ($_SESSION["user_type"] == "productOwner") {
                    echo "
                <div class=\"user-settings\">
                    <label for=\"itemsPerPage\">Nombre d'éléments par page :</label>
                    <select name=\"itemsPerPage\" id=\"itemsPerPage\">
                        <option value=\"1\">1</option>
                        <option value=\"2\" selected>2</option>
                        <option value=\"3\">3</option>
                        <option value=\"4\">4</option>
                    </select>
                </div>
                    ";
                }
                ?>
                <div class="nbrResult">
                    <p>Nombre de résultats : <span id="nbrResult">0</span></p>
                </div>
                <div class="table-container" id="table">
                    <table>
                        <thead>
                            <tr>
                                <th class="nSiren"><span id="sort_by">
                                        <span>N° SIREN</span>
                                        <div class="sort-arrow"><button class="top-arrow"></button><button class="bot-arrow"></button></div>
                                    </span>
                                </th>
                                <th><span id="sort_by">
                                        <span>Raison Sociale</span>
                                        <div class="sort-arrow"><button class="top-arrow"></button><button class="bot-arrow"></button></div>
                                    </span>
                                </th>
                                <th class="dateSolde"><span id="sort_by">
                                        <span>Date Solde</span>
                                        <div class="sort-arrow"><button class="top-arrow"></button><button class="bot-arrow"></button></div>
                                    </span>
                                </th>
                                <th class="nTransaction"><span id="sort_by">
                                        <span>Nbre Transaction</span>
                                        <div class="sort-arrow"><button class="top-arrow"></button><button class="bot-arrow"></button></div>
                                    </span>
                                </th>
                                <th>Devise</th>
                                <th class="montant"><span id="sort_by">
                                        <span>Montant</span>
                                        <div class="sort-arrow"><button class="top-arrow"></button><button class="bot-arrow"></button></div>
                                    </span>
                                </th>    
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Contenu du tableau -->
                        </tbody>
                        <?php 
                        if ($_SESSION["user_type"] == "productOwner") {
                            echo "<tfoot>
                            <tr>
                                <td colspan='6'>
                                    <div id='pagination'></div>
                                </td>
                            </tr>
                            </tfoot>";
                        }
                        ?>
                    </table>
                </div>
                <div class="export">
                    <button id="export-pdf">Exporter en PDF</button>
                    <button id="export-xls">Exporter en Excel</button>
                    <button id="export-csv">Exporter en CSV</button>
                </div>
                <div class="chart-container">
                    <canvas id="myChart"></canvas>
                    <button idd="export-chart-pdf" data-title="Graphique Trésorerie">Exporter en PDF</button>
                </div>
            </article>
        </section>
    </div>
</body>
<script src="../script/menu-animation.js"></script>
<script>
    <?php
    if (isset($_POST["chosenDate"])) {
        $chosenDate = htmlspecialchars($_POST["chosenDate"]);
        if (!in_array($chosenDate, $dateSoldes)) {
            $chosenDate = $maxDate;
        }
    } else {
        $chosenDate = $maxDate;
    }
    if ($_SESSION["user_type"] == "client") {
        $req = $mysqli->prepare("SELECT numSiren, raisonSociale, dateSolde, COUNT(idTransaction) as nbTransaction, MAX(deviseSolde) as deviseSolde, montantTotal
                                    FROM transaction, client, solde
                                    WHERE transaction.idUtilisateur = ?
                                    AND transaction.idUtilisateur = client.idUtilisateur
                                    AND solde.idUtilisateur = transaction.idUtilisateur
                                    AND dateSolde <= ? 
                                    AND dateTransaction <= dateSolde
                                    GROUP BY numSiren, raisonSociale, montantTotal, dateSolde
                                    ORDER BY dateSolde");
        $req->bind_param("ss", $_SESSION["user_id"], $chosenDate);
    }
    if ($_SESSION["user_type"] == "productOwner") {
        if (isset($_POST["chosenCompany"]) && $_POST["chosenCompany"] != "all") {
            $chosenCompany = htmlspecialchars($_POST["chosenCompany"]);
            $req = $mysqli->prepare("SELECT numSiren, raisonSociale, dateSolde, COUNT(idTransaction) as nbTransaction, MAX(deviseSolde) as deviseSolde, montantTotal 
                                    FROM transaction, client, solde 
                                    WHERE transaction.idUtilisateur = client.idUtilisateur 
                                    AND solde.idUtilisateur = transaction.idUtilisateur 
                                    AND raisonSociale = ?
                                    AND dateSolde <= ?
                                    AND dateTransaction <= dateSolde
                                    GROUP BY numSiren, raisonSociale, montantTotal, dateSolde
                                    ORDER BY dateSolde;");
            $req->bind_param("ss", $chosenCompany, $chosenDate);
        } else {
            $req = $mysqli->prepare("SELECT numSiren, raisonSociale, dateSolde, COUNT(idTransaction) as nbTransaction, MAX(deviseSolde) as deviseSolde, montantTotal 
                                        FROM transaction, client, solde 
                                        WHERE transaction.idUtilisateur = client.idUtilisateur 
                                        AND solde.idUtilisateur = transaction.idUtilisateur 
                                        AND dateSolde <= ?
                                        AND dateTransaction <= dateSolde
                                        GROUP BY numSiren, raisonSociale, montantTotal, dateSolde
                                        ORDER BY dateSolde;");
            $req->bind_param("s", $chosenDate);
        }
    }
    $req->execute();
    $result = $req->get_result();
    $table = "[";
    $balanceAndamount = array();
    $allData = array();
    $raisonSociales = array();
    while ($row = $result->fetch_assoc()) {
        $balanceAndamount[$row["dateSolde"]] = $row["montantTotal"];
        $allData[] = $row;
        if (!in_array($row["raisonSociale"], $raisonSociales)) {
            $raisonSociales[] = $row["raisonSociale"];
        }
        if ($row["dateSolde"] == $chosenDate) {
            $table .= "{";
            $table .= "\"N° SIREN\": \"" . $row["numSiren"] . "\",";
            $table .= "\"Raison Sociale\": \"" . $row["raisonSociale"] . "\",";
            $table .= "\"Date Solde\": \"" . $row["dateSolde"] . "\",";
            $table .= "\"Nbre Transaction\": \"" . $row["nbTransaction"] . "\",";
            $table .= "\"Devise\": \"" . $row["deviseSolde"] . "\",";
            $table .= "\"Montant\": \"" . $row["montantTotal"] . "\"";
            $table .= "},";
        }
    }
    $table = substr($table,0,-1);
    $table .= "]";

    $xValues = "[";
    foreach ($balanceAndamount as $key => $value) {
        $xValues .= "\"" . $key . "\",";
    }
    $xValues = substr($xValues, 0, -1);
    $xValues .= "]";
    ?>
    var tableData =<?php echo $table; ?>;
    var hidden = false;
    var allData = <?php echo json_encode($allData); ?>;

    var documentName = "treasury";
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
        function sendToPHP() {
            var chosenDate = document.getElementById("chosenDate").value;
            var chosenDateInput = document.getElementById("chosenDateInput");
            chosenDateInput.value = chosenDate;
            document.getElementById("myForm").submit();
        }
        
        var title = "Trésorerie";
        var ChartTitle = "Graphique Trésorerie";
        var chartChoice = null;
        var xValues = <?php echo $xValues; ?>;
        var raisonSociales = <?php echo json_encode($raisonSociales); ?>;
        console.log(raisonSociales);
</script>
<script src="../script/chart.js"></script>
<script src="../script/export.js" type="module"></script>
<!-- Ajoutez cette balise script à votre page pour gérer les changements de date en temps réel -->

</html>