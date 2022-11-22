<?php
/* Obtain data for charts */

/* Profit per Hour for Selected Stores */
if ($catfix == false) {
    $result_ch = mysqli_query($conn, $sql);
    $arr = array(
        array("00", 0, 0),
        array("01", 0, 0),
        array("02", 0, 0),
        array("03", 0, 0),
        array("04", 0, 0),
        array("05", 0, 0),
        array("06", 0, 0),
        array("07", 0, 0),
        array("08", 0, 0),
        array("09", 0, 0),
        array("10", 0, 0),
        array("11", 0, 0),
        array("12", 0, 0),
        array("13", 0, 0),
        array("14", 0, 0),
        array("15", 0, 0),
        array("16", 0, 0),
        array("17", 0, 0),
        array("18", 0, 0),
        array("19", 0, 0),
        array("20", 0, 0),
        array("21", 0, 0),
        array("22", 0, 0),
        array("23", 0, 0)
    );

    while ($row = mysqli_fetch_array($result_ch, MYSQLI_ASSOC)) {
        $hour = substr($row['time'], 0, 2);
        /* Updating total money spend */
        $arr[(int) $hour][1] += (int) $row['total'];
        $arr[(int) $hour][2]++;
    }
    $i = 0;
    while ($i < 24) {
        if ($arr[$i][2] == 0) {
            $i++;
            continue;
        }
        $av = number_format($arr[$i][1] / $arr[$i][2], 2, '.', '');
        $time .= '"' . $arr[$i][0] . ":00-" . $arr[$i][0] . ':59", ';
        $avi .=  $av . ", ";
        $i++;
    }
    $time = substr($time, 0, -2);
    $avi = substr($avi, 0, -2);
}

/* Profit per Category for Selected Stores */
if ($catfix == true) {
    foreach ($cats as $cat) {
        if ($_GET['catch' . '' . $cat['catid']]) {
            $arr2[$cat['name']] = 0;
        }
    }
    $result_ch2 = mysqli_query($conn, $sql);
    while ($row = mysqli_fetch_array($result_ch2, MYSQLI_ASSOC)) {
        $arr2[$row['name']] += $row['amount'] * $row['cost'];
    }
    foreach ($arr2 as $x => $y) {
        $categories .= "'" . ucwords($x) . "',";
        $profit .= $y . " ,";
    }
    $profit = substr($profit, 0, -2);
    $categories = substr($categories, 0, -1);
}
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>

<!-- Profit per Hour for Selected Stores -->
<?php if ($catfix == false) { ?>
    <canvas id="myChart" class="chart" height="140"></canvas>
    <script>
        let myChart = document.getElementById('myChart').getContext('2d');
        let massPopChart = new Chart(myChart, {
            type: 'bar', // bar, horizontalBar, pie, line, doughnut, radar, polarArea
            data: {
                labels: [<?php echo $time; ?>],
                datasets: [{
                    data: [<?php echo $avi; ?>],
                    backgroundColor: ["#1EB980","#045D56","#FF6859","#FFCF44","#B15DFF","#72DEFF","#24b583","#9143cb","#FF6859","#FFCF44","#B15DFF","#72DEFF","#045D56"],
                    borderWidth: 1,
                    borderColor: '#e3e6e8',
                    hoverBorderWidth: 3,
                    hoverBorderColor: '#e3e6e8'
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            min: 0,
                            // Include a dollar sign in the ticks
                            callback: function(value, index, values) {
                                return value + ' €';
                            }
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Average Profit'
                        }
                    }],
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'Time Zone'
                        }
                    }]
                },

                title: {
                    display: true,
                    text: 'Average Profit per Hour for selected Store(s)',
                    fontColor: '#354856',
                    fontSize: 20
                },
                legend: {
                    display: false,
                    position: 'right',
                    labels: {
                        fontColor: '#000'
                    }
                },
                layout: {
                    padding: {
                        left: 50,
                        right: 0,
                        bottom: 0,
                        top: 0
                    }
                },
                tooltips: {
                    enabled: true
                }
            }
        });
    </script>
<?php } else { ?>
    <!-- Profit per Category for Selected Stores -->
    <canvas id="myChart2" class="chart" height="140"></canvas>
    <script>
        let myChart2 = document.getElementById('myChart2').getContext('2d');
        let massPopChart2 = new Chart(myChart2, {
            type: 'doughnut', // bar, horizontalBar, pie, line, doughnut, radar, polarArea
            data: {
                datasets: [{
                    data: [<?php echo $profit; ?>],
                    backgroundColor:["#1EB980","#045D56","#FF6859","#FFCF44","#B15DFF","#72DEFF","#24b583","#9143cb"],
                    borderWidth: 1,
                    borderColor: '#e3e6e8',
                    hoverBorderWidth: 3,
                    hoverBorderColor: '#e3e6e8'
                }],
                labels: [<?php echo $categories; ?>]
            },
            options: {
                title: {
                    display: true,
                    text: 'Profit per Category for selected Store(s) in €',
                    fontColor: '#354856',
                    fontSize: 20
                },
                layout: {
                    padding: {
                        left: 50,
                        right: 0,
                        bottom: 0,
                        top: 0
                    }
                },
                tooltips: {
                    enabled: true
                }
            }
        });
    </script>
<?php } ?>