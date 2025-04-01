
// Initialisation des variables
var nbElementSelect = document.getElementById("itemsPerPage");
var currentPage = 1;
var itemsPerPage = nbElementSelect === null ? 1 : parseInt(nbElementSelect.value);
var table2 = [];

// Ajout d'un écouteur d'événement sur le select
if (nbElementSelect !== null) {
    nbElementSelect.addEventListener("change", function () {
        itemsPerPage = parseInt(nbElementSelect.value);
        if (itemsPerPage === 0 || itemsPerPage === null || itemsPerPage === undefined) {
            itemsPerPage = 1;
        }
        renderTableData();
        renderPagination();
    });
}

var table = tableData;

function deepCopyTableData() {
    // tableData will not be modified by this function or by the variable that will be returned
    // example of tableData structure:
    // var tableData = [
    //     {
    //         "Libellé Impayé": "Impayé",
    //         "Date Vente": "2020-01-01",
    //         "Montant": "1000",
    //         "sens": "-"
    //     },
    //     {
    //         "Libellé Impayé": "Impayé",
    //         "Date Vente": "2020-01-01",
    //         "Montant": "1000",
    //         "sens": "-"
    //     }
    // ];
    var copiedTableData = [];
    // Create a deep copy of the tableData array
    for (var i = 0; i < tableData.length; i++) {
        var row = tableData[i];
        var copiedRow = {};
        for (var col in row) {
            copiedRow[col] = row[col];
        }
        copiedTableData.push(copiedRow);
    }

    // Return the copied tableData
    return copiedTableData;
}

// Fonction pour afficher les données dans le tableau
function renderTableData() {

    // Calcul de l'index de début
    //console.log(itemsPerPage);
    var startIndex = (currentPage - 1) * itemsPerPage;
    // Calcul de l'index de fin
    var endIndex = startIndex + itemsPerPage;
    // Sélection de la balise tbody du tableau
    var tableBody = document.querySelector(".table-container tbody");
    // Suppression des données du tableau
    tableBody.innerHTML = "";

    itemsPerPage = nbElementSelect === null ? 1 : parseInt(nbElementSelect.value);


    // Mettre à jour l'affichage des résultats
    var spanElemPerPage = document.getElementById("nbrResult");
    var totalElements = table.length;
    var lastElementIndex = Math.min(totalElements, currentPage * itemsPerPage);
    spanElemPerPage.textContent = lastElementIndex + " sur " + totalElements + " éléments";

    //console.log(table);

    // Boucle sur les données du tableau
    for (var i = startIndex; i < endIndex && i < totalElements; i++) {
        // Création d'une ligne
        var rowData = table[i];
        //console.log(rowData);
        var row = document.createElement("tr");
        row.classList.add("table-row");
        //console.log(rowData);
        if (!hidden) {
            row.onclick = function () {
                var hiddenTable = this.nextSibling.firstChild;
                hiddenTable.classList.toggle("hidden");
            };
        }

        // Création des cellules pour les colonnes
        for (var col in rowData) {
            var cell = document.createElement("td");
            cell.textContent = rowData[col];
            if (col === "Libellé Impayé") {
                //console.log(rowData[col]);
            }
            if (col === "Montant") {
                if (rowData["Sens"] === "-" || parseFloat(rowData["Montant"]) < 0) {
                    cell.classList.add("negative");
                } else {
                    //console.log(rowData["Montant"]);
                }
            }
            row.appendChild(cell);
        }

        // Ajout de la ligne au tableau
        tableBody.appendChild(row);
        
        if (i <= table.length && hidden === true) {
            renderHiddenTable(tableBody, Object.keys(rowData).length, i);
        }
    
    }
}

function createImpayesStructure() {
    var tableCopy = deepCopyTableData();
    var sumBySiren = [];
    for (var i = 0; i < tableCopy.length; i++) {
        var row = tableCopy[i];
        var siren = row["N° SIREN"];
        var raisonSociale = row["Raison Sociale"];
        var montant = parseFloat(row["Montant"]);
        var found = false;
        for (var j = 0; j < sumBySiren.length; j++) {
            var sumRow = sumBySiren[j];
            if (sumRow["N° Siren"] === siren) {
                sumRow["Montant"] += montant;
                found = true;
                break;
            }
        }
        if (found === false) {
            sumBySiren.push({
                "N° Siren": siren,
                "Raison Sociale": raisonSociale,
                "Montant": montant
            });
        }
    }
    table2 = sumBySiren;
}

function renderSommeTable() {
    // example of tableData structure:
    // var tableData = [
    //     {
    //         "N° Siren": "123456789",
    //         "Raison Sociale": "Société",
    //         "Date Vente": "2020-01-01",
    //         "Date Remise": "2020-01-01",
    //         "Num Carte": "123456789",
    //         "Réseau": "CB",
    //         "N° Dossier Impayé": "123456789",
    //         "Devise": "EUR",
    //         "Montant": "1000",
    //         "Libellé Impayé": "Impayé"
    //     },
    //     {
    //         "N° Siren": "123456789",
    //         "Raison Sociale": "Société",
    //         "Date Vente": "2020-01-01",
    //         "Date Remise": "2020-01-01",
    //         "Num Carte": "123456789",
    //         "Réseau": "CB",
    //         "N° Dossier Impayé": "123456789",
    //         "Devise": "EUR",
    //         "Montant": "1000",
    //         "Libellé Impayé": "Impayé"
    //     }
    // ];
    
    // On veut la somme des impayés par N° Siren
    

    // Sélection de la balise tbody du tableau
    var tableBody = document.querySelector(".somme-impayes tbody");
    // Suppression des données du tableau
    tableBody.innerHTML = "";

    console.log(table2);


    var totalElements = table2.length;

    //console.log(table);

    // Boucle sur les données du tableau
    for (var i = 0; i < totalElements; i++) {
        // Création d'une ligne
        var rowData = table2[i];
        //console.log(rowData);
        var row = document.createElement("tr");
        row.classList.add("table-row");
        //console.log(rowData);

        // Création des cellules pour les colonnes
        for (var col in rowData) {
            var cell = document.createElement("td");
            cell.textContent = rowData[col];
            if (col === "Libellé Impayé") {
                //console.log(rowData[col]);
            }
            if (col === "Montant") {
                if (rowData["Sens"] === "-" || parseFloat(rowData["Montant"]) < 0) {
                    cell.classList.add("negative");
                } else {
                    //console.log(rowData["Montant"]);
                }
            }
            row.appendChild(cell);
        }

        // Ajout de la ligne au tableau
        tableBody.appendChild(row);
    
    }
    
}

function renderHiddenTable(tableBody, rowDataLenght, i) {
    // Ajout du tableau caché
    row = document.createElement("tr");
    var hiddenCell = document.createElement("td");
    hiddenCell.classList.add("hidden");
    hiddenCell.setAttribute("id", "additional-info");
    hiddenCell.setAttribute("colspan", rowDataLenght);
    row.appendChild(hiddenCell);
    tableBody.appendChild(row);

    // Ajout des données dans le tableau caché
    var tableContainer = document.createElement("div");
    tableContainer.classList.add("hiddenTable-container");
    var hiddenTable = document.createElement("table");
    tableContainer.appendChild(hiddenTable);
    hiddenCell.appendChild(tableContainer);
    hiddenRowData = hiddenTableData[i];
    var hiddenRow = document.createElement("tr");
    for (var col in hiddenRowData["Line1"]) {
        var hiddenCell = document.createElement("th");
        hiddenCell.textContent = col;
        hiddenRow.appendChild(hiddenCell);
    }
    hiddenTable.appendChild(hiddenRow);
    for (var line in hiddenRowData) {
        hiddenRow = document.createElement("tr");
        for (var col in hiddenRowData[line]) {
            var hiddenCell = document.createElement("td");
            hiddenCell.textContent = hiddenRowData[line][col];
            if (col === "Montant") {
                if (hiddenRowData[line]["Sens"] === "-" || parseFloat(hiddenRowData[line]["Montant"]) < 0) {
                    hiddenCell.classList.add("negative");
                }
            }
            hiddenRow.appendChild(hiddenCell);
        }
        hiddenTable.appendChild(hiddenRow);
    }
}


// Fonction pour afficher la pagination
function renderPagination() {
    // Calcul du nombre de pages
    var totalPages = Math.ceil(table.length / itemsPerPage);
    // Sélection de la balise div pour la pagination

    var paginationElement = document.querySelector(".table-container #pagination");


    // Suppression des données de la pagination
    if (paginationElement !== null) {
        paginationElement.innerHTML = "";
    }

    // Boucle sur le nombre de pages
    for (var i = 1; i <= totalPages; i++) {
        // Création d'un lien
        var pageLink = document.createElement("a");
        pageLink.href = "#table";
        pageLink.textContent = i;

        // Ajout de la classe active sur le lien de la page courante
        if (i === currentPage) {
            pageLink.classList.add("active");
        }

        // Ajout d'un écouteur d'événement sur le lien
        pageLink.addEventListener("click", function () {
            currentPage = parseInt(this.textContent);
            renderTableData();
            renderPagination();
        });

        // Ajout du lien dans la pagination
        if (paginationElement !== null) {
            paginationElement.appendChild(pageLink);
        }
    }
}

function sort_by() {
    var sort_by = document.querySelectorAll("#sort_by");
    var state = false;
    sort_by.forEach(element => {
        var children = element.children;
        children[1].style.setProperty("--after-color", "black");
        children[1].style.setProperty("--before-color", "black");
        element.addEventListener("click", function() {
            var children = element.children;
            var arrow_buttons = document.querySelectorAll(".sort-arrow");
            arrow_buttons.forEach(element => {
                element.style.setProperty("--after-color", "black");
                element.style.setProperty("--before-color", "black");
            });
            if (state === false) {
                state = true;
                children[1].style.setProperty("--after-color", "black");
                children[1].style.setProperty("--before-color", "white");
            } else {
                state = false;
                children[1].style.setProperty("--after-color", "white");
                children[1].style.setProperty("--before-color", "black");
            }
            console.log(element.classList);
            if (element.classList.length > 0 && element.classList.contains("sort-impayes")) {
                sortImpayesTable(children[0].textContent, state);
            } else {
                sortTable(children[0].textContent, state);
            }
        });
    });
}

function filter_by_remise() {
    var chosen_remise = document.getElementById("chosenRemise");
    if (typeof chosen_remise === undefined || chosen_remise === null) {
        return;
    }
    for (var i = 0; i < chosen_remise.options.length; i++) {
        var optionValue = chosen_remise.options[i].value;
        var isOptionValueFound = table.some(function(data) {
            return data.hasOwnProperty("N° Remise") && data["N° Remise"] === optionValue;
        });
        if (!isOptionValueFound) {
            chosen_remise.options[i].setAttribute("disabled", "true");
            chosen_remise.options[i].setAttribute("hidden", "true");
        } else {
            chosen_remise.options[i].removeAttribute("disabled");
            chosen_remise.options[i].removeAttribute("hidden");
        }
    }
    chosen_remise.addEventListener("change", function() {
        var selectedRemise = chosen_remise.selectedOptions[0].value;
        console.log(selectedRemise);
        var tableDataCopy = deepCopyTableData();
        if (selectedRemise !== "all") {
            tableDataCopy = tableDataCopy.filter((a) => {
                if (a["N° Remise"] === selectedRemise) {
                    return true;
                }
                return false;
            });
        }
        table = tableDataCopy;
        renderTableData();
        renderPagination();
    });
}

function sortImpayesTable(elementName, state) {
    table2.sort((a, b) => {
        if (a[elementName] > b[elementName]) {
            return -1;
        }
        if (a[elementName] < b[elementName]) {
            return 1;
        }
        return 0;
    });
    if (state === true) {
        table2.reverse();
    }
    //console.log(tableData);
    renderSommeTable();
}

function sortTable(elementName, state) {
    if (elementMane === "Montant" && "Sens" in table[0] === true) {
        table.sort((a, b) => {
            if (parseFloat(a["Sens"]+a[elementName]) > parseFloat(b["Sens"]+b[elementName])) {
                return -1;
            }
            if (parseFloat(a["Sens"]+a[elementName]) < parseFloat(b["Sens"]+b[elementName])) {
                return 1;
            }
            return 0;
        });
    } else {
        table.sort((a, b) => {
            if (a[elementName] > b[elementName]) {
                return -1;
            }
            if (a[elementName] < b[elementName]) {
                return 1;
            }
            return 0;
        });
    }
    if (stsats === true) {
        table.reverse();
    }
    //console.log(tableData);
    renderTableData();
    renderPagination();
}

function sortTableByDate(start_date, end_date) {
    var tableDataCopy = deepCopyTableData();
    tableDataCopy = tableDataCopy.filter((a) => {
        var date = new Date(a["Date Vente"]);
        if (date >= start_date && date <= end_date) {
            return true;
        }
        return false;
    });
    tableDataCopy.sort((a, b) => {
        if (a["Date Vente"] > b["Date Vente"]) {
            return -1;
        }
        if (a["Date Vente"] < b["Date Vente"]) {
            return 1;
        }
        return 0;
    });
    table = tableDataCopy;
    //console.log(tableDataCopy);
    renderTableData();
    renderPagination();
}

function dateRange() {
    var start_date = document.getElementById("start-end");
    var end_date = document.getElementById("end-start");
    var validate = document.getElementById("dateRangeValidate");
    var reset = document.getElementById("dateRangeReset");

    if (start_date !== null && end_date !== null && validate !== null) {

        start_date.addEventListener("change", function() {
            var selectedDate = new Date(start_date.value);
            if (selectedDate !== null) {
                for (var i = 0; i < end_date.options.length; i++) {
                    var option = end_date.options[i];
                    var optionDate = new Date(option.value);
                    if (optionDate < selectedDate) {
                    option.setAttribute("disabled", "true");
                    option.setAttribute("hidden", "true");
                    } else {
                    option.removeAttribute("disabled");
                    option.removeAttribute("hidden");
                    }
                }
            }
        });

        end_date.addEventListener("change", function() {
            var selectedDate = new Date(end_date.value);
            if (selectedDate !== null) {
                for (var i = 0; i < start_date.options.length; i++) {
                    var option = start_date.options[i];
                    var optionDate = new Date(option.value);
                    if (optionDate > selectedDate) {
                        option.setAttribute("disabled", "true");
                        option.setAttribute("hidden", "true");
                    } else {
                        option.removeAttribute("disabled");
                        option.removeAttribute("hidden");
                    }
                }
            }
        });

        validate.addEventListener("click", function() {
            var selectedStartDate = new Date(start_date.value);
            var selectedEndDate = new Date(end_date.value);
            console.log(selectedStartDate);
            console.log(selectedEndDate);
            if (isNaN(selectedStartDate) === true || isNaN(selectedEndDate) === true) {
                table = tableData;
                renderTableData();
                renderPagination();
                return;
            }
            for (var i = 0; i < start_date.options.length; i++) {
                var option = start_date.options[i];
                option.removeAttribute("disabled");
                option.removeAttribute("hidden");
            }
            for (var i = 0; i < end_date.options.length; i++) {
                var option = end_date.options[i];
                option.removeAttribute("disabled");
                option.removeAttribute("hidden");
            }
            if (selectedStartDate !== null && selectedEndDate !== null) {
                sortTableByDate(selectedStartDate, selectedEndDate);
            }
        });

        reset.addEventListener("click", function() {
            table = tableData;
            renderTableData();
            renderPagination();
        });

    }
}
  
filter_by_remise();
sort_by();  
dateRange();
renderTableData();
renderPagination();
// Récupérer le nom du document
if (typeof documentName !== undefined && documentName !== null) {
    if (documentName === "impayes") {
        createImpayesStructure();
        renderSommeTable();
    }
}