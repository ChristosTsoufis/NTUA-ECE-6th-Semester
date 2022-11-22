<?php
ob_start();
$conn = mysqli_connect('192.168.99.100', 'root', 'root', 'supermarketdb');

if (!$conn) {
    echo 'Bad connection:' . mysqli_connect_error();
}

if (isset($_GET['cardid'])) {
    $cardid = $_GET['cardid'];
}

// check POST for DELETION
if (isset($_GET['delete'])) {

    $cardid_to_delete = mysqli_real_escape_string($conn, $_GET['cardid_to_delete']);

    $sqldel = "DELETE FROM customer WHERE cardid = $cardid_to_delete";
    echo $sqldel;
    if (mysqli_query($conn, $sqldel)) {
        // success
        header_remove();
        header("Location: customers.php");
        exit;
    } else {
        // failure
        echo 'query error: ' . mysqli_error($conn);
    }
}
ob_flush();
?>
<?php
/* Customer Info */
$sql = 'SELECT *, YEAR(CURRENT_TIMESTAMP) - DATE_FORMAT(customer.date_of_birth, "%Y") as age FROM customer WHERE cardid=' . $cardid;
$res = mysqli_query($conn, $sql);
$cust = mysqli_fetch_array($res, MYSQLI_ASSOC);

if ($cust['number_of_kids'] > 0) {
    if ($cust['number_of_kids'] == 1) {
        $status = ucfirst($cust['relationship_status']) . ' with 1 kid';
    } else {
        $status = ucfirst($cust['relationship_status']) . ' with ' . $cust['number_of_kids'] . ' children';
    }
} else {
    $status = ucfirst($cust['relationship_status']);
}

/* Customer Phone Numbers */
$sqlphones = 'SELECT * FROM phone_number WHERE cardid=' . $cardid;
$phoneres = mysqli_query($conn, $sqlphones);
$phones = mysqli_fetch_all($phoneres, MYSQLI_ASSOC);

/* Find top 10 products */
$sql1 = 'SELECT SUM(purch_prod.amount) as total, product.name, product.brand FROM customer, purchase, purch_prod, product
         WHERE customer.cardid = purchase.cardid AND purchase.purid = purch_prod.purid AND purch_prod.productid = product.productid AND customer.cardid=' .
    $cardid .
    ' GROUP BY product.name, product.brand
        ORDER BY total DESC
        LIMIT 10';
$res1 = mysqli_query($conn, $sql1);
$top_prod = mysqli_fetch_all($res1, MYSQLI_ASSOC);

/*Find Stores that Customer Visits */
$sqlcount = 'SELECT COUNT(DISTINCT(purchase.storeid)) FROM customer, purchase
             WHERE customer.cardid = purchase.cardid AND customer.cardid = ' . $cardid;
$rescount = mysqli_query($conn, $sqlcount);
$count = mysqli_fetch_array($rescount);
$count = (int) $count['COUNT(DISTINCT(purchase.storeid))'];

$sql2 = 'SELECT DISTINCT(store.storeid), store.street_name, store.street_number, store.city FROM customer, purchase, store
         WHERE customer.cardid = purchase.cardid AND purchase.storeid = store.storeid AND customer.cardid = ' . $cardid;

$res2 = mysqli_query($conn, $sql2);
$stores = mysqli_fetch_all($res2, MYSQLI_ASSOC);


/* Show visiting hours of a selected store */
if (isset($_GET['seehours'])) {
    if (isset($_GET['storeid'])) {
        $storeid = $_GET['storeid'];
    } else {
        $res2 = mysqli_query($conn, $sql2);
        $def_store = mysqli_fetch_array($res2, MYSQLI_ASSOC);
        $storeid = $def_store['storeid'];
    }
    $sqlhour = 'SELECT TIME_FORMAT(purchase.time, "%H") AS hourspan, COUNT(purchase.purid) as visits
                FROM purchase, customer
                WHERE customer.cardid = purchase.cardid AND 
                customer.cardid = ' . $_GET['cardid'] . ' AND purchase.storeid =' . $storeid .
        ' GROUP BY hourspan;';

    $reshour = mysqli_query($conn, $sqlhour);

    $arrt = array(
        array("00", 0),
        array("01", 0),
        array("02", 0),
        array("03", 0),
        array("04", 0),
        array("05", 0),
        array("06", 0),
        array("07", 0),
        array("08", 0),
        array("09", 0),
        array("10", 0),
        array("11", 0),
        array("12", 0),
        array("13", 0),
        array("14", 0),
        array("15", 0),
        array("16", 0),
        array("17", 0),
        array("18", 0),
        array("19", 0),
        array("20", 0),
        array("21", 0),
        array("22", 0),
        array("23", 0)
    );

    while ($row = mysqli_fetch_array($reshour, MYSQLI_ASSOC)) {
        $arrt[(int) $row['hourspan']][1] = 1;
        $hours[$row['hourspan']] = (int) $row['visits'];
    }

    /* Find last element */
    $i = 0;
    while ($i < 24) {
        if ($arrt[$i][1] == 1) {
            $last = $arrt[$i][0];
        }
        $i++;
    }
    $i = 0;
    $first == false;
    while ($i < 24) {
        if ($arrt[$i][1] == 0 && $first == false) {
            $i++;
            continue;
        }
        $first = true;
        if ($arrt[$i][1] !== 0) {
            $labels .=  '"' . $arrt[$i][0] . ":00-" . $arrt[$i][0] . ':59", ';
            $visits .= $hours[$arrt[$i][0]] . ', ';
        } elseif ($arrt[$i][0] < $last) {
            $labels .=  '"' . $arrt[$i][0] . ":00-" . $arrt[$i][0] . ':59", ';
            $visits .= '0, ';
        }
        $i++;
    }
    $labels = substr($labels, 0, -2);
    $visits = substr($visits, 0, -2);

    /*Find average per month */
    $sqlmonth = 'SELECT TRUNCATE(AVG(total),2) as average, MONTHNAME(date) as month, year(date) as year FROM purchase, customer
                 WHERE customer.cardid = purchase.cardid AND customer.cardid =' . $cardid .
        ' GROUP BY MONTHNAME(date), year(date)
                 ORDER BY year(date), MONTHNAME(date)';
    $resmonth = mysqli_query($conn, $sqlmonth);
    $avgmonth = mysqli_fetch_all($resmonth, MYSQLI_ASSOC);

    /*Find average per week */
    $sqlweek = 'SELECT TRUNCATE(AVG(total),2) as average, FIRST_DAY_OF_WEEK(date) as start, LAST_DAY_OF_WEEK(date) as end FROM purchase, customer
                WHERE customer.cardid = purchase.cardid AND customer.cardid =' . $cardid .
        ' GROUP BY FIRST_DAY_OF_WEEK(date), LAST_DAY_OF_WEEK(date)
                ORDER BY FIRST_DAY_OF_WEEK(date)';
    $resweek = mysqli_query($conn, $sqlweek);
    $avgweek = mysqli_fetch_all($resweek, MYSQLI_ASSOC);
}

/* All Transactions Total and per category */
$sqlpur = 'SELECT * FROM purchase WHERE cardid=' . $cardid;
$resultpur = mysqli_query($conn, $sqlpur);

$purs = mysqli_fetch_all($resultpur, MYSQLI_ASSOC);

$sqlcat = 'SELECT purchase.purid, purchase.cardid, total, payment_method, date, time, storeid, product.catid,
           cost, amount, product.name AS prname, brand, category.name AS name
           FROM purchase, purch_prod, product, category
           WHERE purchase.purid = purch_prod.purid 
           AND purch_prod.productid = product.productid
           AND product.catid = category.catid
           AND purchase.cardid = ' . $cardid .
    ' ORDER BY name,purid;';

$resultcat = mysqli_query($conn, $sqlcat);

$purscat = mysqli_fetch_all($resultcat, MYSQLI_ASSOC);
?>
<?php include 'templates/header.php'; ?>

<div class="container-fluid">
    <div class="row my-3 mx-2" style="color:#354856;">
        <div class="h2">Customer Info</div>
    </div>
    <div class="fluid-container mx-5" style="color:#354856;">
        <div class="row">
            <div class="col  my-2">
                <div class="h5">Full Name: <?php echo $cust['first_name'] . ' ' . $cust['last_name']; ?></div>
            </div>
            <div class="col  my-2">
                <div class="h5">Age: <?php echo $cust['age']; ?></div>
            </div>
            <div class="w-100"></div>
            <div class="col  my-2">
                <div class="h5">Address: <?php echo $cust['street_name'] . ' ' . $cust['street_number'] . ', ' . $cust['city'] ?></div>
            </div>
            <div class="col  my-2">
                <div class="h5">Status: <?php echo $status; ?></div>
            </div>
            <div class="w-100"></div>
            <div class="col  my-2">
                <div class="h5">Apartment Floor: <?php echo $cust['apt_floor']; ?></div>
            </div>
            <div class="col  my-2">
                <div class="h5">Card Expiration Date: <?php echo $cust['card_exp_date']; ?></div>
            </div>
            <div class="w-100"></div>
            <div class="col  my-2">
                <div class="h5">ZIP Code: <?php echo $cust['zip']; ?></div>
            </div>
            <div class="col  my-2">
                <div class="h5">Current Points / Redeemed Points : <?php echo $cust['current_points'] . ' / ' . $cust['points_redeemed']; ?></div>
            </div>
            <div class="w-100"></div>
            <?php $i = 1;
            foreach ($phones as $phone) { ?>
                <div class="col  my-2">
                    <div class="h5">Phone Number <?php echo $i; ?>: <?php echo $phone['phone'];
                                                                    $i++; ?></div>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="row my-3 mx-2" style="color:#354856;">
        <div class="h2">Top 10 Products</div>
    </div>
    <ul class="px-2" style="columns: 2; font-size: 0.9rem; font-weight: 400; line-height: 1.5; color: #354856;">
        <?php foreach ($top_prod as $prod) { ?>
            <li class="list-group-item d-flex justify-content-between mx-2"><?php echo ucwords($prod['name']) . ', ' . $prod['brand'] ?><span class="badge"><?php echo $prod['total'] ?></span></li>
        <?php } ?>
    </ul>
    <div class="row my-3 mx-2" style="color:#354856;">
        <div class="h2">Stores Visited (<?php echo $count; ?>)</div>
    </div>
    <style>
        .form-control:focus {
            box-shadow: 0 0 0 3px rgb(247, 172, 21, 0.3);
            border-color: #f7ac15 !important;
            box-shadow: none;
        }
    </style>
    <form action="/customer_profile.php" method="GET">
        <div class="form-row mx-2">
            <div class="form-group col-md-3">
                <label for="inputStore">Pick Store to Display Visiting Hours</label>
                <select id="inputStore" class="form-control" name="storeid">
                    <?php foreach ($stores as $stor) { ?>
                        <option value="<?php echo $stor['storeid'] ?>"> <?php echo $stor['street_name'] . ' ' . $stor['street_number'] . ', ' . $stor['city'] ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="d-flex col-md-3 align-items-end">
                <button type="submit" name="seehours" value="1" class="btn mb-3" style="color:#e3e6e8; background-color:#354856; width: 117.6px; height: 38px;">See Visitng Hours</button>
                <input type="hidden" name="cardid" value="<?php echo $_GET['cardid']; ?>">
            </div>
        </div>
    </form>
    <div class="fluid-container mx-2" style="width: 80%; height: 517.8px; margin-bottom: 7rem;">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
        <canvas id="myChart2" class="chart" style="height: 517.8px; margin-left: -4%;"></canvas>
        <script>
            let myChart2 = document.getElementById('myChart2').getContext('2d');
            let massPopChart2 = new Chart(myChart2, {
                type: 'bar', // bar, horizontalBar, pie, line, doughnut, radar, polarArea
                data: {
                    labels: [<?php echo $labels; ?>],
                    datasets: [{
                        data: [<?php echo $visits; ?>],
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
                                min: 0
                            },
                            scaleLabel: {
                                display: true,
                                labelString: 'Visits'
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
    <div class="row my-3 mx-2" style="color:#354856;">
        <div class="h2">Average Purchases</div>
    </div>
    <div class="fluid-container">
        <div class="row mx-2">
            <div class="col">
                <div class="h4" style="color:#354856;">Per Month</div>
                <ul class="list-group px-0 overflow-auto" style="max-height: 300px;" id="scrolli">
                    <?php foreach ($avgmonth as $month) { ?>
                        <li class="list-group-item rounded-0 d-flex justify-content-between" style="border-bottom:1px dashed #354856;">
                            <div class="h6"><?php echo $month['month'] . ' ' . $month['year'] ?></div>
                            <div class="mr-2"><?php echo $month['average'] . ' €' ?></div>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <div class="col">
                <div class="h4" style="color:#354856;">Per Week</div>
                <ul class="list-group px-0 overflow-auto" style="max-height: 300px;" id="scrolli">
                    <?php foreach ($avgweek as $week) { ?>
                        <li class="list-group-item rounded-0 d-flex justify-content-between" style="border-bottom:1px dashed #354856;">
                            <div class="h6"><?php echo $week['start'] . ' - ' . $week['end'] ?></div>
                            <div class="mr-2"><?php echo $week['average'] . ' €' ?></div>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
    

    <div class="col-12 justify-content-center mt-3" id="purtable">
        <style>
            tr[data-href] {
                cursor: pointer;
            }

            /* Style the tab */
            .tab {
                overflow: hidden;
                /*border: 1px solid #ccc;
                    background-color: #f1f1f1;*/
            }

            /* Style the buttons that are used to open the tab content */
            .tab button {
                background-color: inherit;
                float: left;
                border: none;
                outline: none;
                cursor: pointer;
                padding: 8px 16px 8px 16px;
                margin-left: 0.5rem;
                margin-right: 0.5rem;
                transition: 0.5s;
            }

            /* Change background color of buttons on hover */
            .tab button:hover {
                background-color: #ddd;
            }

            /* Create an active/current tablink class */
            .tab button.active {
                background-color: #f7ac15;
                color: #354856;
            }

            /* Style the tab content */
            .tabcontent {
                display: block;
                padding: 6px 12px;
                /*border: 1px solid #ccc;*/
                border-top: none;
            }
        </style>
        <div class="tab d-flex justify-content-end">
            <button class="tablinks active" onclick="opentab(event, 'gen')">General</button>
            <button class="tablinks" onclick="opentab(event, 'det')">Per Category</button>
        </div>
        <div class="tabcontent" id="gen">
            <table class="table">
                <thead>
                    <tr style="text-align: center;">
                        <th scope="col">Total</th>
                        <th scope="col">Date</th>
                        <th scope="col">Time</th>
                        <th scope="col">Payment Method</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($purs as $pur) { ?>
                        <tr style="text-align: center;" data-href="<?php echo 'purchase_profile.php?purid=' . $pur['purid'] ?>">
                            <td><?php echo $pur['total'] ?> €</td>
                            <td><?php echo $pur['date'] ?></td>
                            <td><?php echo $pur['time'] ?></td>
                            <td><?php echo $pur['payment_method'] ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="tabcontent" style="display: none;" id="det">
            <table class="table">
                <thead>
                    <tr style="text-align: center;">
                        <th scope="col">Product Name</th>
                        <th scope="col">Brand</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Cost</th>
                        <th scope="col">Category</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($purscat as $purc) { ?>
                        <tr style="text-align: center;" data-href="<?php echo 'purchase_profile.php?purid=' . $pur['purid'] ?>">
                            <th scope="row"><?php echo $purc['prname'] ?></th>
                            <td><?php echo $purc['brand'] ?></td>
                            <td><?php echo $purc['amount'] ?></td>
                            <td><?php echo $purc['cost'] * $purc['amount'] ?> €</td>
                            <td><?php echo $purc['name'] ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function opentab(evt, tab) {
            // Declare all variables
            var i, tabcontent, tablinks;

            // Get all elements with class="tabcontent" and hide them
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }

            // Get all elements with class="tablinks" and remove the class "active"
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }

            // Show the current tab, and add an "active" class to the button that opened the tab
            document.getElementById(tab).style.display = "block";
            evt.currentTarget.className += " active";
        }
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const rows = document.querySelectorAll("tr[data-href]");
            rows.forEach(row => {
                row.addEventListener("click", () => {
                    window.location.href = row.dataset.href;
                })
            });
        });
    </script>

    <!-- DELETE FORM -->
    <form class="d-flex container-fluid my-4 mx-auto justify-content-center">
        <form action="customer_profile.php" method="GET">
            <input type="hidden" name="cardid_to_delete" value="<?php echo $cust['cardid']; ?>">
            <button class="btn btn-block" style="width:100px; color:#e3e6e8; background-color:#354856;" type="submit" name="delete" value="1" class="btn brand z-depth-0">Delete</button>
        </form>
        <div class="container w-100 my-5"></div>
</div>