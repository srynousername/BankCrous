for (var i = 0; i < yPieValues.length; i++) {
    yPieValues[i] = parseFloat(yPieValues[i]);
}
var barColors = [
  "#F47363",
  "#EA5863",
  "#9F353D",
  "#76232A",
  "#AD3A1B",
  "#E15820",
  "#F67844",
  "#FE9063"
];

const plugin2 = {
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

new Chart("myPieChart", {
    type: "pie",
    plugins: [plugin2],
    data: {
    labels: xPieValues,
    datasets: [{
        backgroundColor: barColors,
        data: yPieValues
    }]
    },
    options: {
    title: {
        display: true,
        text: "La répartition des différents types d'impayés"
    }
    }
});