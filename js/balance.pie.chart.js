window.onload = function() {

var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	//backgroundColor: "#F5DEB3",
	//width: 1000,
	//height: 500,
	title: {
		text: "Bilans wydatków",
		fontSize: 20,
	},
	color: "gold",
	data: [{
		type: "pie",
		startAngle: 270,
		yValueFormatString: "##0.00",
		indexLabel: "{label} {y}",
		indexLabelFontSize: 20,
		dataPoints: [
			{y: 1450.00, label: "Mieszanie"},
			{y:141.85, label: "Telekomunikacja"},
			{y: 542.11, label: "Jedzenie"},
			{y: 213.69, label: "Samochód"},
			{y: 320.54, label: "Inne"}
		]
	}]
});
chart.render();

}