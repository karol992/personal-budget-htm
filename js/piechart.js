var jedzenie = 492.44;
var mieszkanie = 1450.00;
var transport = 153.87;
var telekomunikacja = 142.79;
var higiena = 0.00;
var dzieci = 0.00;
var rozrywka = 135.86;
var wycieczka = 0.00;
var szkolenia = 0.00;
var ksiazki = 191.88;
var oszczednosci = 0.00;
var emerytura = 0.00;
var dlugi = 0.00;
var darowizna = 94.35;
var inne = 652.76;

google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChartPieChart);
function drawChartPieChart(){
	var data = google.visualization.arrayToDataTable([
  ['Wydatek', 'Ilość'],
  ['Jedzenie', jedzenie],
  ['Mieszkanie', mieszkanie],
  ['Transport', transport],
  ['Telekomunikacja', telekomunikacja],
  ['Higiena', higiena],
  ['Dzieci', dzieci],
  ['Rozrywka', rozrywka],
  ['Wycieczka', wycieczka],
  ['Szkolenia', szkolenia],
  ['Książki', ksiazki],
  ['Oszczędności', oszczednosci],
  ['Emerytura', emerytura],
  ['Długi', dlugi],
  ['Darowizna', darowizna],
  ['Inne', inne]
],false);
	var options = {
		"title":"Wykres kołowy wydatków",
		"titleTextStyle":{"fontSize":"30"},
		"width":"840",
		"height":"790",
		"chartArea":{"width":"840"},
		"is3D":"true",
		"pieSliceText":"percentage",
		"pieSliceTextStyle":{"fontSize":"20"},
		"legend":{
			"alignment":"left",
			"position":"top",
			"maxLines":"5",
			"textStyle":{
				"fontSize":"27"
			}
		}
	};
	var chart = new google.visualization.PieChart(document.getElementById('piechart'));
	chart.draw(data, options);
}

