var node = document.getElementById("blog");
const blog = node.dataset.blog;
var allBlogUser = node.dataset.all;

var ctx = document.getElementById("blog").getContext('2d');

let gradient = ctx.createLinearGradient(0,0,0,400);
gradient.addColorStop(0, "rgba(58,123,213,1)")
gradient.addColorStop(1, "rgba(0,210,553,0.3)");

var myChart = new Chart(ctx, {
    data: {
        labels: [''],
        datasets: [
            {
                type: 'bar',
                label: 'ブログ更新顧客数',
                data: [blog],
                backgroundColor: [gradient],
                borderColor: [gradient],
                borderWidth: 1,
                hoverBorderWidth: 3,
                hoverBorderColor: '#000',
            },
        ],
    },
    options: {
        indexAxis: 'y',
        scales: {
            y: {
                beginAtZero: true,
            },
            x: {
                ticks: {
                    callback: function(value){
                        if(value == 2000){
                            return "10月目標" + value + "件"
                        }
                        else if(value == 4000){
                            return "11月目標" + value + "件"
                        }
                        return value + "件";
                    }
                },
                
                min: 0,
                max: 6000,
            }
        }
    }
});
