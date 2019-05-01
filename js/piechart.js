/**
 * ---------------------------------------
 * This demo was created using amCharts 4.
 *
 * For more information visit:
 * https://www.amcharts.com/
 *
 * Documentation is available at:
 * https://www.amcharts.com/docs/v4/
 * ---------------------------------------
 */
 
 //Apply a theme
am4core.useTheme(am4themes_kelly);

// Create chart instance
var chart = am4core.create("chartdiv", am4charts.PieChart);

// Add data
chart.data = [{
  "category": "Jedzenie",
  "value": 623.12
}, {
  "category": "Mieszanie",
  "value": 1450.00
}, {
  "category": "Transport",
  "value": 412.35
}, {
  "category": "Telekomunikacja",
  "value": 140.00
}, {
  "category": "Opieka zdrowotna",
  "value": 20.00
}, {
  "category": "Ubranie",
  "value": 120.00
}, {
  "category": "Higiena",
  "value": 29.80
}, {
  "category": "Rozrywka",
  "value": 151.00
}, {
  "category": "Wycieczka",
  "value": 650.00
}, {
  "category": "Książki",
  "value": 99.00
}, {
  "category": "Darowizna",
  "value": 55.00
}, {
  "category": "Inne wydatki",
  "value": 44.00
}];

// Add and configure Series
var pieSeries = chart.series.push(new am4charts.PieSeries());
pieSeries.dataFields.value = "value";
pieSeries.dataFields.category = "category";

// Disable ticks and labels
pieSeries.labels.template.disabled = true;
pieSeries.ticks.template.disabled = true;

// Disable tooltips
//pieSeries.slices.template.tooltipText = "";

/* Create legend */
chart.legend = new am4charts.Legend();

/* Create a separate container to put legend in */
var legendContainer = am4core.create("legenddiv", am4core.Container);
legendContainer.width = am4core.percent(100);
legendContainer.height = am4core.percent(100);
chart.legend.parent = legendContainer;

legendContainer.itemValueText="";
