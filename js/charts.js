// console.log(num_month)
const months = Array.from({
    length: 12
}, (item, i) => {
    return new Date(0, i).toLocaleString('en', {
        month: 'long'
    })
});

// dummy null
let lineVal = [];
let barVal = [];



const lineChart = document.getElementById('lineChart').getContext('2d');
const barChart = document.getElementById('barChart').getContext('2d');


let line = new Chart(lineChart, {
    type: "line",
    data: {
        labels: months,
        datasets: [{
            label: `Monthly Patient`,
        }]
    },
    options: {
        // legend: {
        //     display: false
        // },
        // tooltips: {
        //     callbacks: {
        //         label: function(tooltipItem) {
        //             return tooltipItem.yLabel;
        //         }
        //     }
        // },
        scales: {
            y: {
                // beginAtZero: true,
                suggestedMin: 50,
                suggestedMax: 100
            }
        }
    }
});

let bar = new Chart(barChart, {
    type: "bar",
    data: {
        labels: months,
        datasets: [{
            label: `Monthly Income`,
        }]
    },
    options: {
        // legend: {
        //     display: false
        // },
        // tooltips: {
        //     callbacks: {
        //         label: function(tooltipItem) {
        //             return tooltipItem.yLabel;
        //         }
        //     }
        // },
        scales: {
            y: {
                suggestedMin: 50,
                suggestedMax: 100
            }
        }
    }
});

// console.log(new Date().getFullYear());
$(document).ready(function () {

    // for patient
    $.ajax({
        type: "post",
        url: "patient_report",
        data: { year: $('#select_year').val() },
        success: function (response) {
            if (response != 'fail') {
                let data = JSON.parse(response);

                try {
                    lineVal = data.map(value => {
                        return value.count;
                    });

                    line.data.datasets[0].data = lineVal;
                    line.update();
                }
                catch {
                    // lineVal = [];   
                    line.data.datasets[0].data = [];
                    line.update();
                }
            }
        }
    });
    // for patient

    // for monthly income

    $.ajax({
        type: "post",
        url: "income_report",
        data: { year: $('#select_year').val() },
        success: function (response) {
            if (response != 'fail') {
                let data = JSON.parse(response);

                try {
                    barVal = data.map(value => {
                        return value.count;
                    });

                    bar.data.datasets[0].data = barVal;
                    bar.update();
                }
                catch {
                    // barVal = [];
                    bar.data.datasets[0].data = [];
                    bar.update();
                }

            }
        }
    });
    // for monthly income
});

$('#select_year').change(function () {
    // alert($(this).val());

    // for patient
    $.ajax({
        type: "post",
        url: "patient_report",
        data: { year: $(this).val() },
        success: function (response) {
            if (response != 'fail') {
                let data = JSON.parse(response);


                // console.log(lineVal);
                try {
                    lineVal = data.map(item => {
                        return item.count;
                    });

                    line.data.datasets[0].data = lineVal;


                    line.update();
                }
                catch {
                // lineVal = [];
                line.data.datasets[0].data = [];
                line.update();
            }
        }
    }
    });
// for patient

// for monthly income

$.ajax({
    type: "post",
    url: "income_report",
    data: { year: $(this).val() },
    success: function (response) {
        if (response != 'fail') {
            let data = JSON.parse(response);

            try {
                barVal = data.map(value => {
                    return value.count;
                });

                bar.data.datasets[0].data = barVal;
                bar.update();
            }
            catch {
                // barVal = [];
                bar.data.datasets[0].data = [];
                bar.update();
            }

        }
    }
});
    // for monthly income
});


function updateData(chart, data) {
    chartData.datasets.data = data.map(item => {
        return item.count;
    });
    chart.update();
}