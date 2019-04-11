// Load google charts
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

// Draw the chart and set the chart values
function drawChart() {
  var data = google.visualization.arrayToDataTable([
  ['Wydatek', 'Ilość'],
  ['Jedzenie', 492.44],
  ['Mieszkanie', 1450],
  ['Transport', 153.87],
  ['Telekomunikacja', 142.79],
  ['Higiena', 0]
]);

  // Optional; add a title and set the width and height of the chart
  var options = {'title':'Wydatki', 'width':550, 'height':400};

  // Display the chart inside the <div> element with id="piechart"
  var chart = new google.visualization.PieChart(document.getElementById('piechart'));
  chart.draw(data, options);
}