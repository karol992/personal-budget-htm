window.onload = function() {

var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	//backgroundColor: "#F5DEB3",
	//width: 1000,
	//height: 500,
	title: {
		text: "Wykres wydatków",
		fontSize: 20,
	},
	color: "gold",
	data: [{
		type: "pie",
		startAngle: 270,
		yValueFormatString: "##0.00",
		indexLabel: "{label} {y}",
		indexLabelFontSize: 11,
		dataPoints: [
			{y: 623.12, label: "Jedzenie"},
			{y: 1450.00, label: "Mieszanie"},
			{y: 412.35, label: "Transport"},
			{y: 140.00, label: "Telekomunikacja"},
			{y: 20.00, label: "Opieka zdrowotna"},
			{y: 120.00, label: "Ubranie"},
			{y: 29.80, label: "Higiena"},
			{y: 151.00, label: "Rozrywka"},
			{y: 650.00, label: "Wycieczka"},
			{y: 99.00, label: "Książki"},
			{y: 55.00, label: "Darowizna"},
			{y: 44.00, label: "Inne wydatki"},
		]
	}]
});
chart.render();

}