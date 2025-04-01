// Fonction pour exporter le tableau en PDF avec jsPDF
function exportToPDF() {
    const doc = new jsPDF();

    const tableHeaders = Object.keys(tableData[0]);
    const tableRows = tableData.map(obj => Object.values(obj));

    const fontSize = 24;
    const lineHeight = 1.2; // Espacement entre les lignes

    const titleWidth = doc.getStringUnitWidth(title) * fontSize / doc.internal.scaleFactor;
    const pageWidth = doc.internal.pageSize.getWidth();
    const x = (pageWidth - titleWidth) / 2;

    doc.setFontSize(fontSize);
    doc.setFont(undefined, 'bold');
    doc.text(title, x, 10);

    doc.setLineHeightFactor(lineHeight);

    for (let i = 0; i < tableRows.length; i++) {
        const tableRow = tableRows[i];
        if (typeof hiddenTableData !== 'undefined') {
            var hiddenTableRow = hiddenTableData[i];
        }
        
        doc.autoTable({
            head: [tableHeaders],
            body: [tableRow],
            headStyles: { fillColor: [255, 0, 0] }, // Couleur rouge pour les headers du tableau tableData
        });

        if (typeof hiddenTableData !== 'undefined') {
            if (hiddenTableRow) {
                doc.setFont(undefined, 'normal');
                doc.setFontSize(12);
                doc.text("Transactions liées :", 15, doc.autoTable.previous.finalY+5);
                for (const col in hiddenTableRow) {
                    const row = hiddenTableRow[col];
                    const columns = Object.keys(row);
                    const values = Object.values(row);

                    doc.autoTable({
                        head: [columns],
                        body: [values],
                        headStyles: { fillColor: [255, 100, 100] }, // Couleur rose pour les headers du tableau hiddenTableData
                    });
                }
            }
        }
    }

    const date = new Date().toLocaleDateString();
    const dateX = 10;
    const dateY = doc.autoTable.previous.finalY + 10;

    doc.setFontSize(12);
    doc.setFont(undefined, 'normal');

    doc.text(`Date d'extraction : ${date}`, dateX, dateY);

    doc.save('tableau.pdf');
}
  
// Fonction pour exporter le tableau en XLS (Excel) avec SheetJS
function exportToXLS() {
    const workbook = XLSX.utils.book_new();
    const worksheet = XLSX.utils.json_to_sheet(tableData);

    XLSX.utils.book_append_sheet(workbook, worksheet, 'Tableau');

    const excelBuffer = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });

    saveAs(new Blob([excelBuffer], { type: 'application/octet-stream' }), 'tableau.xlsx');
}
  
// Fonction pour exporter le tableau en CSV avec FileSaver.js
function exportToCSV() {
    const csvContent = [];

    const tableHeaders = Object.keys(tableData[0]);
    const tableRows = tableData.map(obj => Object.values(obj));

    csvContent.push(tableHeaders.join(','));

    tableRows.forEach(row => {
        csvContent.push(row.join(','));
    });

    const csvData = csvContent.join('\n');

    const csvBlob = new Blob([csvData], { type: 'text/csv;charset=utf-8;' });

    saveAs(csvBlob, 'tableau.csv');
}

function exportChartToPDF(chartId, fileName, chartTitle) {
    const chartCanvas = document.getElementById(chartId);
    const chartDataURL = chartCanvas.toDataURL('image/jpeg', 1.0);

    const doc = new jsPDF();

    const fontSize = 24;

    // Mesurer la largeur du titre en fonction de la taille de police
    const titleWidth = doc.getStringUnitWidth(chartTitle) * fontSize / doc.internal.scaleFactor;

    // Calculer les coordonnées x pour centrer le titre horizontalement
    const pageWidth = doc.internal.pageSize.getWidth();
    const x = (pageWidth - titleWidth) / 2;

    //console.log(chartTitle);

    // Ajouter un titre en haut du document
    doc.setFontSize(20);
    doc.setFont(undefined, 'bold');
    doc.text(chartTitle, x, 10);
    doc.addImage(chartDataURL, 'JPEG', 10, 30, 190, 100);

    // Ajouter la date d'extraction à la fin du document
    const currentDate = new Date().toLocaleDateString();
    doc.setFontSize(10);
    doc.text(`Extrait le ${currentDate}`, 10, doc.internal.pageSize.getHeight() - 10);

    doc.save(fileName);
}
  
// Exemple de boutons pour déclencher les exports
var exportPDFButton = document.getElementById('export-pdf');
exportPDFButton.addEventListener('click', exportToPDF);

var exportXLSButton = document.getElementById('export-xls');
exportXLSButton.addEventListener('click', exportToXLS);

var exportCSVButton = document.getElementById('export-csv');
//console.log(exportCSVButton);
exportCSVButton.addEventListener('click', exportToCSV);

var exportChartPDFButton = document.getElementById('export-chart-pdf');
if (exportChartPDFButton !== null) {
    exportChartPDFButton.addEventListener('click', function() {
        var chartChoice = document.querySelectorAll('input[name="chart-choice"]:checked');
        if (chartChoice !== null && chartChoice.length > 0) {
            if (chartChoice[0].value === "line-chart") {
                exportChartToPDF('myChart', 'graphique.pdf', this.getAttribute('data-title'));
            } else if (chartChoice[0].value === "bar-chart") {
                exportChartToPDF('myBarChart', 'graphique.pdf', this.getAttribute('data-title'));
            }
        } else {
            exportChartToPDF('myChart', 'graphique.pdf', this.getAttribute('data-title'));
        }
    });
}

var exportPieChartPDFButton = document.getElementById('export-piechart-pdf');
if (exportPieChartPDFButton !== null) {
    exportPieChartPDFButton.addEventListener('click', function() {
        exportChartToPDF('myPieChart', 'graphique.pdf', "La répartition des différents types d'impayés");
    });
}