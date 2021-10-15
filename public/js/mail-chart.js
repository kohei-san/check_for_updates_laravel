var node = document.getElementById("mail");
const mail = node.dataset.mail;
var all = node.dataset.all;
var all = all - mail;

var ctx = document.getElementById("mail").getContext('2d');
var myChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ["メールアドレス保有"],
        datasets: [{
            // label: '得票数',
            data: [mail, all],
            backgroundColor: [
                'rgba(75, 192, 192, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                // 'rgba(54, 162, 235, 0.2)',
                // 'rgba(255, 206, 86, 0.2)',
                // 'rgba(153, 102, 255, 0.2)',
                // 'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
              'rgba(75, 192, 192, 1)',
                'rgba(255,99,132,1)',
                // 'rgba(54, 162, 235, 1)',
                // 'rgba(255, 206, 86, 1)',
                // 'rgba(153, 102, 255, 1)',
                // 'rgba(255, 159, 64, 1)'
            ],
            // borderWidth: 1
        }]
    },
    options: {
    }
});
