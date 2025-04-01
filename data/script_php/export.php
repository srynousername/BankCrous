<?php
require_once 'XXX'; // Chargement des dépendances (remplacer le XXX par le nom de la page)

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Mpdf\Mpdf;

// Récupération des données du tableau et/ou du graphique (exemple)
$tableData = [
    ['XXX', 'XXX', 'XXX'],  //remplacer XXX ou 000 en conséquence.
    ['XXX', 000, 'XXX'],
    ['XXX', 000, 'XXX'],
];

// Création du tableau
echo '<table border="1">';
foreach ($tableData as $row) {
    echo '<tr>';
    foreach ($row as $cell) {
        echo '<td>' . $cell . '</td>';
    }
    echo '</tr>';
}
echo '</table>';

// Export en PDF
if (isset($_GET['export']) && $_GET['export'] == 'pdf') {
    $mpdf = new Mpdf();
    $mpdf->WriteHTML('<h2>Tableau Exporté en PDF</h2>');
    $mpdf->WriteHTML('<table border="1">' . ob_get_clean() . '</table>');
    $mpdf->Output('export.pdf', 'D');
    exit();
}

// Export en Excel
if (isset($_GET['export']) && $_GET['export'] == 'xls') {
    $spreadsheet = new Spreadsheet();
    $spreadsheet->getActiveSheet()->fromArray($tableData, NULL, 'A1');
    $writer = new Xlsx($spreadsheet);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="export.xlsx"');
    header('Cache-Control: max-age=0');
    $writer->save('php://output');
    exit();
}

// Export en CSV
if (isset($_GET['export']) && $_GET['export'] == 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename=export.csv');
    $output = fopen('php://output', 'w');
    foreach ($tableData as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit();
}
?>

<!-- Lien pour exporter en PDF -->
<a href="?export=pdf">Exporter en PDF</a>

<!-- Lien pour exporter en Excel -->
<a href="?export=xls">Exporter en Excel</a>

<!-- Lien pour exporter en CSV -->
<a href="?export=csv">Exporter en CSV</a>
