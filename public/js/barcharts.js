google.charts.load("current", {packages:["corechart"]});
google.charts.setOnLoadCallback(drawChart);
function drawChart() {
  var data = google.visualization.arrayToDataTable([
    ['Genre', '更新ユーザー', '未更新ユーザー', { role: 'annotation' } ],
    ['ブログ更新', 1700, 4500, ''],
  ]);

  var view = new google.visualization.DataView(data);
  view.setColumns([0, 1,
                    { calc: "stringify",
                      sourceColumn: 1,
                      type: "string",
                      role: "annotation" },
                    2]);

                    // width: 600,
  var options = {
    title: "ブログ導入顧客（目標6200件）",
    isStacked: 'percent',
    height: 100,
    // width: '90%',
    
    legend: {position: 'top', maxLines: 3},
    hAxis: {
      minValue: 0,
      ticks: [0, .33, .66, 1]
    }
  };

  var chart = new google.visualization.BarChart(document.getElementById("barchart_values"));
  chart.draw(view, options);
}