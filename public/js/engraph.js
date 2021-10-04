// Load the Visualization API and the corechart package.
google.charts.load('current', {'packages':['corechart']});
                
// Set a callback to run when the Google Visualization API is loaded.
google.charts.setOnLoadCallback(drawChart);

// Callback that creates and populates a data table,
// instantiates the pie chart, passes in the data and
// draws it.
function drawChart() {
  // Instantiate and draw our chart, passing in some options.
  var chart1 = new google.visualization.PieChart(document.getElementById('chart_div1'));
  var chart2 = new google.visualization.PieChart(document.getElementById('chart_div2'));
  var chart3 = new google.visualization.PieChart(document.getElementById('chart_div3'));

  // Create the data table. カラム追加などべた書き（用関数への書き出し）
  var data1 = new google.visualization.DataTable();
  data1.addColumn('string', 'Topping');
  data1.addColumn('number', 'Slices');
  data1.addRows([
    ['Mushrooms', 6000],
    ['Onions', 2000],
  ]);

  var data2 = new google.visualization.DataTable();
  data2.addColumn('string', 'Topping');
  data2.addColumn('number', 'Slices');
  data2.addRows([
    ['Mushrooms', 4000],
    ['Onions', 2000],
  ]);

  var data3 = new google.visualization.DataTable();
  data3.addColumn('string', 'Topping');
  data3.addColumn('number', 'Slices');
  data3.addRows([
    ['Mushrooms', 8000],
    ['Onions', 2000],
  ]);

  // ▲カラム追加などべた書き（用関数への書き出し）

  // Set chart options
  var options1 = {'title':'メールアドレス保有', //表示メッセージ
                  'width':350,
                  'height':300,
                  pieHole: 0.4,
                };

  var options2 = {'title':'ライン保有', //表示メッセージ
                'width':350,
                'height':300,
                pieHole: 0.4,
              };

  var options3 = {'title':'10/1以降の電話接触', //表示メッセージ
                'width':350,
                'height':300,
                pieHole: 0.4,
              };


  // ビューへ描画
  chart1.draw(data1, options1);
  chart2.draw(data2, options2);
  chart3.draw(data3, options3);
}