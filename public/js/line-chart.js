var node = document.getElementById("LineRegister");
const line = node.dataset.line;
var all = node.dataset.all;
var all = all - line;

var ctx = document.getElementById("LineRegister").getContext('2d');
var myChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ["Line登録ユーザー"],
        datasets: [{
            data: [line, all],
            backgroundColor: [
                'rgba(75, 192, 192, 0.2)',
                'rgba(255, 99, 132, 0.2)',
            ],
            borderColor: [
              'rgba(75, 192, 192, 1)',
                'rgba(255,99,132,1)',
            ],
            // borderWidth: 1
        }]
    },
    options: {
        cutout: 65,
        animation: {animateScale: true},
    }
});