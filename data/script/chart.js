function collectData(table) {
    console.log("Table : ");
    console.log(table);
    var dataSets = [];
    for (var i = 0; i < raisonSociales.length; i++) {
        console.log(raisonSociales[i]);
        var dataRaisonSocial = getDataForRaisonSociale(table, raisonSociales[i]);
        if ("dateTransaction" in dataRaisonSocial[0]) {
            var dataList = dataRaisonSocial.map(element => { return element['dateTransaction']; });
            var montantList = dataRaisonSocial.map(element => { return element['montantTransaction']; });
        } else if ("dateSolde" in dataRaisonSocial[0]) {
            var dataList = dataRaisonSocial.map(element => { return element['dateSolde']; });
            var montantList = dataRaisonSocial.map(element => { return element['montantTotal']; });
            console.log("dataList : ");
            console.log(dataList);
        }
        
        var yValues = [];
        for (var j = 0; j < xValues.length; j++) {
            if (dataList.includes(xValues[j])) {
                var index = dataList.indexOf(xValues[j]);
                yValues.push(montantList[index]);
            } else {
                yValues.push(0);
            }
        }
        console.log(yValues);
        dataSets.push({
            label: raisonSociales[i],
            fill: false,
            lineTension: 0,
            backgroundColor: getRandomColor(),
            borderColor: getRandomColor(),
            pointBackgroundColor: getRandomColor(),
            data: yValues
        }); 
    } 
    return dataSets;
}

function getDataForRaisonSociale(table, raisonSociale) {
    var data = [];
    for (var i = 0; i < table.length; i++) {
        var row = table[i];
        if (row["raisonSociale"] == raisonSociale) {
            data.push(row);
        }
    }
    return data;
}

var dataSets = [];

/*
datasets: [{
    label: dataSets[0].label,
    fill: false,
    lineTension: 0,
    backgroundColor: dataSets[0].backgroundColor,
    borderColor: dataSets[0].borderColor,
    pointBackgroundColor: dataSets[0].pointBackgroundColor,
    data: dataSets[0].data
}]
*/

// var raisonSociales = [];
dataSets = collectData(allData);
// if (dependance === "allData") {
//     dataSets = collectData(allData);
// } else if (dependance === "tableData") {
//     dataSets = collectData(tableData);
// }
  

function maxValues() {
    // Fonction qui retourne le maximum des valeurs de dataSets
    var maxValues = [];
    for (var i = 0; i < dataSets.length; i++) {
        maxValues.push(Math.max.apply(null, dataSets[i].data));
    }
    return Math.max.apply(null, maxValues);
}

function minValues() {
    // Fonction qui retourne le minimum des valeurs de dataSets
    var minValues = [];
    for (var i = 0; i < dataSets.length; i++) {
        minValues.push(Math.min.apply(null, dataSets[i].data));
    }
    return Math.min.apply(null, minValues);
}

var maxValue = maxValues();
var minValue = minValues();


//console.log(dataSets);

function getRandomColor() {
    var letters = "0123456789ABCDEF";
    var color = "#";
    for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

//console.log(xValues);
//console.log(yValues);


// // Convertir les valeurs de xValues en objets Date
// for (var i = 0; i < xValues.length; i++) {
//     xValues[i] = new Date(xValues[i]);
// }

// // Regrouper les valeurs de xValues et yValues en paires
// var pairs = [];
// for (var i = 0; i < xValues.length; i++) {
//     var pair = { x: xValues[i], data: [] };
//     for (var j = 0; j < dataSets.length; j++) {
//         pair.data.push(dataSets[j].data[i]);
//     }
//     if (!pair.data.includes(undefined)) {
//         pairs.push(pair);
//     } else {
//         pairs.push(pair);
//     }
    
// }
// //console.log(pairs);

// // Trier les paires en fonction des valeurs de x
// pairs.sort(function(a, b) {
//     return a.x - b.x;
// });

// // Mettre à jour les valeurs de xValues et les objets de dataSets avec les paires triées
// for (var i = 0; i < pairs.length; i++) {
//     var date = pairs[i].x;
//     var year = date.getFullYear();
//     var month = ('0' + (date.getMonth() + 1)).slice(-2); // Ajoute un zéro devant les mois de 1 à 9
//     var day = ('0' + date.getDate()).slice(-2); // Ajoute un zéro devant les jours de 1 à 9
//     xValues[i] = year + '-' + month + '-' + day;
//     for (var j = 0; j < dataSets.length; j++) {
//         dataSets[j].data[i] = pairs[i].data[j];
//     }
// }

// // Mettre le reste des dates de xValues au format AAAA-MM-JJ
// for (var i = pairs.length; i < xValues.length; i++) {
//     var date = xValues[i];
//     var year = date.getFullYear();
//     var month = ('0' + (date.getMonth() + 1)).slice(-2); // Ajoute un zéro devant les mois de 1 à 9
//     var day = ('0' + date.getDate()).slice(-2); // Ajoute un zéro devant les jours de 1 à 9
//     xValues[i] = year + '-' + month + '-' + day;
// }

// console.log("DataSets après regroupement : ");
// console.log(dataSets);

const plugin = {
    id: 'custom_canvas_background_color',
    beforeDraw: (chart) => {
        const {ctx} = chart;
        ctx.save();
        ctx.globalCompositeOperation = 'destination-over';
        ctx.fillStyle = '#FDF0E7';
        ctx.fillRect(0, 0, chart.width, chart.height);
        ctx.restore();
    }
};



function lineChart() {
    if (dataSets.length == 1) {
        new Chart("myChart", {
            type: "line",
            plugins: [plugin],
            data: {
                labels: xValues,
                datasets: [{
                    label: dataSets[0].label,
                    fill: false,
                    lineTension: 0,
                    backgroundColor: dataSets[0].backgroundColor,
                    borderColor: dataSets[0].borderColor,
                    pointBackgroundColor: dataSets[0].pointBackgroundColor,
                    data: dataSets[0].data
                }]
            },
            options: {
                legend: {display: false},
                scales: {
                    yAxes: [{ticks: {min: Math.min.apply(null, dataSets[0].data) - 20 , max: Math.max.apply(null, dataSets[0].data) + 20}}],
                }
            }
        });
    } else {
        new Chart("myChart", {
            type: "line",
            plugins: [plugin],
            data: {
                labels: xValues,
                datasets: dataSets
            },
            options: {
                legend: {display: true},
                scales: {
                    yAxes: [{ticks: {min: minValue - 20 , max: maxValue + 20}}],
                }
            }
        });
    }
}

function barChart() {
    if (dataSets.length == 1) {
        new Chart("myBarChart", {
            type: "bar",
            plugins: [plugin],
            data: {
                labels: xValues,
                datasets: [{
                fill: false,
                lineTension: 0,
                backgroundColor: "#EA5863",
                borderColor: "#EA5863",
                pointBackgroundColor: "#FE9063",
                data: dataSets[0].data
                }]
            },
            options: {
                legend: {display: false},
                scales: {
                yAxes: [{ticks: {min: minValue - 50 , max: maxValue + 50}}],
                }
            }
        });
    } else {
        new Chart("myBarChart", {
            type: "bar",
            plugins: [plugin],
            data: {
                labels: xValues,
                datasets: dataSets
            },
            options: {
                legend: {display: true},
                scales: {
                    yAxes: [{ticks: {min: minValue - 50 , max: maxValue + 50}}],
                }
            }
        });
    }
}

//console.log(xValues.length);

lineChart();

if (typeof chartChoice !== undefined && chartChoice !== null && chartChoice.length > 0) {
    barChart(vide);
    chartChoice.forEach(function(choice) {
        choice.addEventListener("change", function() {
            if (choice.value == "bar-chart") {
                document.querySelector(".chart-container #myChart").style.display = "none";
                document.querySelector(".chart-container #myBarChart").style.display = "block";
            } else {
                document.querySelector(".chart-container #myChart").style.display = "block";
                document.querySelector(".chart-container #myBarChart").style.display = "none";
            }
        });
    });
}