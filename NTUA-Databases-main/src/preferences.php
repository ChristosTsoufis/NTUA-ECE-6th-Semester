<?php include 'preferences_sql.php'; ?>
<?php include 'templates/header.php'; ?>
<div class="row my-3 mx-4" style="color:#354856;">
    <div class="h2">Preferences</div>
</div>
<div class="row my-3 mx-2">
    <form class="col-3 justify-content-start" action="/preferences.php" method="GET" id="purform">
        <div style="background-color:#e3e6e8;">
            <div class="card my-2 border-0">
                <div class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="background-color:#c8cfd2;">
                    <h5 class="mb-0" style="color:#354856;">
                        Store
                    </h5>
                </div>
                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body p-0 overflow-auto" id="scrolli">
                        <ul class="list-group px-0" style="max-height: 400px;">
                            <?php foreach ($stores as $stor) { ?>
                                <li class="list-group-item rounded-0" style="border-bottom:1px dashed #354856;">
                                    <div class="custom-control custom-checkbox" style="padding-left: 1.75rem">
                                        <input class="custom-control-input mr-2" type="checkbox" value="1" <?php if (isset($_GET['storch' . '' . $stor['storeid']])) echo "checked='checked'"; ?> id="storch<?php echo $stor['storeid'] ?>" name="storch<?php echo $stor['storeid'] ?>">
                                        <label class="custom-control-label" for="storch<?php echo $stor['storeid'] ?>">
                                            <?php echo $stor['street_name'] . ' ' . $stor['street_number'] . ', ' . $stor['city'] ?>
                                        </label>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <button type="submit" name="submit" value="submit" class="btn btn-block" style="float: right; color:#e3e6e8; background-color:#354856;">Submit</button>
    </form>
    <div id="carouselExampleIndicators" class="carousel slide col-9" data-ride="carousel" data-interval="10000">
        <ol class="carousel-indicators" style="bottom:-50px;">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" style="height: 3px;" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1" style="height: 3px;"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2" style="height: 3px;"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="3" style="height: 3px;"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="4" style="height: 3px;"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="5" style="height: 3px;"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="6" style="height: 3px;"></li>
        </ol>
        <div class="carousel-inner d-flex justify-content-center">
            <div class="carousel-item active" style="width: 80%; background-color: #e3e6e8;">
                <div class="h4" style="color: #354856;">Top 10 Pairs of Products</div>
                <ul class="list-group" style="font-size: 0.9rem; font-weight: 400; line-height: 1.5; color: #354856;">
                    <?php foreach ($famous_pairs as $pair1) { ?>
                        <li class="list-group-item d-flex justify-content-between"><?php echo ucfirst($pair1['name1']) . ', ' . $pair1['brand1'] . ' - ' . ucfirst($pair1['name2']) . ', ' . $pair1['brand2'] ?><span class="badge"><?php echo $pair1['count'] ?></span></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="carousel-item" style="width: 80%; background-color: #e3e6e8;">
                <div class="h4" style="color: #354856;">Top 10 Positions in Store(s)</div>
                <ul class="list-group" style="font-size: 0.9rem; font-weight: 400; line-height: 1.5; color: #354856;">
                    <?php foreach ($famous_pos as $pos) { ?>
                        <li class="list-group-item d-flex justify-content-between"><?php echo 'Corridor: ' . $pos['corridor'] . ', Shelve: ' . $pos['shelve']; ?><span class="badge"><?php echo $pos['count'] ?></span></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="carousel-item align-items-center" style="width: 80%; height: 517.8px; background-color: #e3e6e8;">
                <div class="h4" style="color: #354856;">Label Trust per Category</div>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
                <canvas id="myChart" class="chart" style="height: 517.8px; margin-left: -4%"></canvas>
                <script>
                    let myChart = document.getElementById('myChart').getContext('2d');
                    let massPopChart = new Chart(myChart, {
                        type: 'radar', // bar, horizontalBar, pie, line, doughnut, radar, polarArea
                        data: {
                            labels: [<?php echo $labels1; ?>],
                            datasets: [{
                                    label: "Label chosen (%)",
                                    data: [<?php echo $yeslabelarr; ?>],
                                    fill: true,
                                    backgroundColor: "rgb(4, 93, 86, 0.2)",
                                    borderColor: "rgb(4, 93, 86)",
                                    pointBackgroundColor: "rgb(4, 93, 86)",
                                    pointBorderColor: "#fff",
                                    pointHoverBackgroundColor: "#fff",
                                    pointHoverBorderColor: "rgb(4, 93, 86)"
                                },
                                {
                                    label: "No Label chosen (%)",
                                    data: [<?php echo $nolabelarr; ?>],
                                    fill: true,
                                    backgroundColor: "rgb(255, 104, 89, 0.2)",
                                    borderColor: "rgb(255, 104, 89)",
                                    pointBackgroundColor: "rgb(255, 104, 89)",
                                    pointBorderColor: "#fff",
                                    pointHoverBackgroundColor: "#fff",
                                    pointHoverBorderColor: "rgb(255, 104, 89)"
                                }
                            ]
                        },
                        options: {
                            elements: {
                                line: {
                                    tension: 0,
                                    borderWidth: 3
                                }
                            },
                            scale: {
                                ticks: {
                                    backdropColor: "#e3e6e8"
                                }
                            }
                        }
                    });
                </script>
            </div>
            <div class="carousel-item align-items-center" style="width: 80%; height: 517.8px; background-color: #e3e6e8;">
                <div class="h4" style="color: #354856;">Profit per Hour (€)</div>
                <canvas id="myChart2" class="chart" style="height: 517.8px; margin-left: -4%"></canvas>
                <script>
                    let myChart2 = document.getElementById('myChart2').getContext('2d');
                    let massPopChart2 = new Chart(myChart2, {
                        type: 'bar', // bar, horizontalBar, pie, line, doughnut, radar, polarArea
                        data: {
                            labels: [<?php echo $time; ?>],
                            datasets: [{
                                data: [<?php echo $prof; ?>],
                                backgroundColor: ["#1EB980", "#045D56", "#FF6859", "#FFCF44", "#B15DFF", "#72DEFF", "#24b583", "#9143cb", "#FF6859", "#FFCF44", "#B15DFF", "#72DEFF", "#045D56"],
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
                                        labelString: 'Profit'
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
                                display: false,
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
            </div>
            <div class="carousel-item align-items-center" style="width: 80%; height: 517.8px; background-color: #e3e6e8;">
                <div class="h4" style="color: #354856;">Age Group per Hour</div>
                <canvas id="myChart3" class="chart" style="height: 517.8px; margin-left: -4%"></canvas>
                <script>
                    let myChart3 = document.getElementById('myChart3').getContext('2d');
                    let massPopChart3 = new Chart(myChart3, {
                        type: 'bar', // bar, horizontalBar, pie, line, doughnut, radar, polarArea
                        data: {
                            labels: [<?php echo $labelall; ?>],
                            datasets: [{
                                    label: "Age: 15-24",
                                    data: [<?php echo $g1; ?>],
                                    backgroundColor: "#1EB980",
                                    borderWidth: 1,
                                    borderColor: '#e3e6e8',
                                    hoverBorderWidth: 3,
                                    hoverBorderColor: '#e3e6e8'
                                },
                                {
                                    label: "Age: 25-40",
                                    data: [<?php echo $g2; ?>],
                                    backgroundColor: "#045D56",
                                    borderWidth: 1,
                                    borderColor: '#e3e6e8',
                                    hoverBorderWidth: 3,
                                    hoverBorderColor: '#e3e6e8'
                                },
                                {
                                    label: "Age: 41-64",
                                    data: [<?php echo $g3; ?>],
                                    backgroundColor: "#FF6859",
                                    borderWidth: 1,
                                    borderColor: '#e3e6e8',
                                    hoverBorderWidth: 3,
                                    hoverBorderColor: '#e3e6e8'
                                },
                                {
                                    label: "Age: 65+",
                                    data: [<?php echo $g4; ?>],
                                    backgroundColor: "#FFCF44",
                                    borderWidth: 1,
                                    borderColor: '#e3e6e8',
                                    hoverBorderWidth: 3,
                                    hoverBorderColor: '#e3e6e8'
                                }
                            ]
                        },
                        options: {
                            scales: {
                                yAxes: [{
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Percentage (%)'
                                    }
                                }],
                                xAxes: [{
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Time Zone'
                                    }
                                }]
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
            </div>
            <div class="carousel-item align-items-center" style="width: 80%; height: 517.8px; background-color: #e3e6e8;">
                <div class="h4" style="color: #354856;">Top 3 Products per Age Group</div>
                <div class="container-fluid">
                    <div class="row row-cols-2">
                        <div class="col">
                            <div class="h4 pl-2 mt-3" style="color: #354856;">Age: 15-24</div>
                            <ul class="list-group" style="font-size: 0.9rem; font-weight: 400; line-height: 1.5; color: #354856;">
                                <?php foreach ($top3s as $top3) {
                                    if ($top3['age_group'] == 'Age: 15-24') { ?>
                                        <li class="list-group-item d-flex justify-content-between"><?php echo ucfirst($top3['prname']) . ', ' . $top3['brand'] ?><span class="badge"><?php echo $top3['total'] ?></span></li>
                                <?php }
                                } ?>
                            </ul>
                        </div>
                        <div class="col">
                            <div class="h4 pl-2 mt-3" style="color: #354856;">Age: 25-40</div>
                            <ul class="list-group" style="font-size: 0.9rem; font-weight: 400; line-height: 1.5; color: #354856;">
                                <?php foreach ($top3s as $top3) {
                                    if ($top3['age_group'] == 'Age: 25-40') { ?>
                                        <li class="list-group-item d-flex justify-content-between"><?php echo ucfirst($top3['prname']) . ', ' . $top3['brand'] ?><span class="badge"><?php echo $top3['total'] ?></span></li>
                                <?php }
                                } ?>
                            </ul>
                        </div>
                        <div class="col">
                            <div class="h4 pl-2 mt-3" style="color: #354856;">Age: 41-64</div>
                            <ul class="list-group" style="font-size: 0.9rem; font-weight: 400; line-height: 1.5; color: #354856;">
                                <?php foreach ($top3s as $top3) {
                                    if ($top3['age_group'] == 'Age: 41-64') { ?>
                                        <li class="list-group-item d-flex justify-content-between"><?php echo ucfirst($top3['prname']) . ', ' . $top3['brand'] ?><span class="badge"><?php echo $top3['total'] ?></span></li>
                                <?php }
                                } ?>
                            </ul>
                        </div>
                        <div class="col">
                            <div class="h4 pl-2 mt-3" style="color: #354856;">Age: 65+</div>
                            <ul class="list-group" style="font-size: 0.9rem; font-weight: 400; line-height: 1.5; color: #354856;">
                                <?php foreach ($top3s as $top3) {
                                    if ($top3['age_group'] == 'Age: 65+') { ?>
                                        <li class="list-group-item d-flex justify-content-between"><?php echo ucfirst($top3['prname']) . ', ' . $top3['brand'] ?><span class="badge"><?php echo $top3['total'] ?></span></li>
                                <?php }
                                } ?>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
            <div class="carousel-item align-items-center" style="width: 80%; height: 517.8px; background-color: #e3e6e8;">
                <div class="h4" style="color: #354856;">Card Points per Age Group</div>
                <canvas id="myChart4" class="chart" style="height: 517.8px; margin-left: -4%"></canvas>
                <script>
                    let myChart4 = document.getElementById('myChart4').getContext('2d');
                    let massPopChart4 = new Chart(myChart4, {
                        type: 'horizontalBar', // bar, horizontalBar, pie, line, doughnut, radar, polarArea
                        data: {
                            labels: ["Age: 15-24", "Age: 25-40", "Age: 41-64", "Age: 65+"],
                            datasets: [{
                                    label: "Points Redeemed (%)",
                                    data: [<?php echo $redp; ?>],
                                    backgroundColor: "rgb(4, 93, 86)",
                                    borderWidth: 1,
                                    borderColor: '#e3e6e8',
                                    hoverBorderWidth: 3,
                                    hoverBorderColor: '#e3e6e8'
                                },
                                {
                                    label: "Current Points (%)",
                                    data: [<?php echo $curp; ?>],
                                    backgroundColor: "rgb(255, 104, 89)",
                                    borderWidth: 1,
                                    borderColor: '#e3e6e8',
                                    hoverBorderWidth: 3,
                                    hoverBorderColor: '#e3e6e8'
                                }
                            ]
                        },
                        options: {
                            scales: {
                                xAxes: [{
                                    ticks: {
                                        min: 0,
                                        max: 100
                                    },
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Percentage (%)'
                                    }
                                }]
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
            </div>
        </div>
        <a class="carousel-control-prev" style="width: 7%" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" style="width: 7%" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</div>
</body>

</html>