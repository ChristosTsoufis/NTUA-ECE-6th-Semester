<?php include 'purchase_sql.php' ?>
<?php include 'templates/header.php'; ?>
<div class="container-fluid">
    <div class="row my-3 mx-2" style="color:#354856;">
        <div class="h2">Purchases</div>
    </div>
    <div class="row">
        <form class="col-3 justify-content-start" action="/purchases.php" method="GET" id="purform">
            <div id="accordion" style="background-color:#e3e6e8;">
                <div class="card my-2 border-0">
                    <div class="card-header" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="background-color:#c8cfd2;">
                        <h5 class="mb-0" style="color:#354856;">
                            Store
                        </h5>
                    </div>
                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="card-body p-0 overflow-auto" id="scrolli">
                            <ul class="list-group px-0" style="max-height: 300px;">
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
                <div class="card my-2 border-0">
                    <div class="card-header" id="headingTwo" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" style="background-color:#c8cfd2;">
                        <h5 class="mb-0" style="color:#354856;">
                            Date
                        </h5>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">
                            <?php include 'datepicker.php' ?>
                        </div>
                    </div>
                </div>
                <div class="card my-2 border-0">
                    <div class="card-header" id="headingThree" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree" style="background-color:#c8cfd2;">
                        <h5 class="mb-0" style="color:#354856;">
                            Total Amount
                        </h5>
                    </div>
                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <input type="text" class="form-control" name="minamount" placeholder="Min. Amount" value="<?php if (isset($_GET['minamount'])) echo $_GET['minamount']; ?>">
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control" name="maxamount" placeholder="Max. Amount" value="<?php if (isset($_GET['maxamount'])) echo $_GET['maxamount']; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card my-2 border-0">
                    <div class="card-header" id="headingFour" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour" style="background-color:#c8cfd2;">
                        <h5 class="mb-0" style="color:#354856;">
                            Total Cost
                        </h5>
                    </div>
                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <input type="text" class="form-control" value="<?php if (isset($_GET['mincost'])) echo $_GET['mincost']; ?>" name="mincost" placeholder="Min. Cost">
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control" value="<?php if (isset($_GET['maxcost'])) echo $_GET['maxcost']; ?>" name="maxcost" placeholder="Max. Cost">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card my-2 border-0">
                    <div class="card-header" id="headingFive" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive" style="background-color:#c8cfd2;">
                        <h5 class="mb-0" style="color:#354856;">
                            Payment Method
                        </h5>
                    </div>
                    <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
                        <div class="card-body ml-2">
                            <div class="row container-fluid">
                                <div class="custom-control custom-radio custom-control-inline col">
                                    <input type="radio" id="customRadioInline1" name="paymentradio" class="custom-control-input" value="0" <?php if (isset($_GET['paymentradio']) && $_GET['paymentradio'] === '0') echo "checked='checked'"; ?>>
                                    <label class="custom-control-label" for="customRadioInline1">Cash</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline col">
                                    <input type="radio" id="customRadioInline2" name="paymentradio" class="custom-control-input" value="1" <?php if (isset($_GET['paymentradio']) && $_GET['paymentradio'] === '1') echo "checked='checked'"; ?>>
                                    <label class="custom-control-label" for="customRadioInline2">Credit Card</label>
                                </div>
                            </div>
                            <div class="w-100 my-1"></div>
                            <div class="row container-fluid">
                                <div class="custom-control custom-radio custom-control-inline col">
                                    <input type="radio" id="customRadioInline3" name="paymentradio" class="custom-control-input" value="2" <?php if (isset($_GET['paymentradio']) && $_GET['paymentradio'] === '2') echo "checked='checked'"; ?>>
                                    <label class="custom-control-label" for="customRadioInline3">Both</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card my-2 border-0">
                    <div class="card-header" id="headingSix" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix" style="background-color:#c8cfd2;">
                        <h5 class="mb-0" style="color:#354856;">
                            Categories
                        </h5>
                    </div>
                    <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion">
                        <div class="card-body p-0 overflow-auto" id="scrolli">
                            <ul class="list-group px-0" style="max-height: 300px;">
                                <?php foreach ($cats as $cat) { ?>
                                    <li class="list-group-item rounded-0" style="border-bottom:1px dashed #354856;">
                                        <div class="custom-control custom-checkbox" style="padding-left: 1.75rem">
                                            <input class="custom-control-input mr-2" type="checkbox" value="1" <?php if (isset($_GET['catch' . '' . $cat['catid']])) echo "checked='checked'"; ?> id="catch<?php echo $cat['catid'] ?>" name="catch<?php echo $cat['catid'] ?>">
                                            <label class="custom-control-label" for="catch<?php echo $cat['catid'] ?>">
                                                <?php echo $cat['name']  ?>
                                            </label>
                                        </div>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <button type="submit" name="submit" value="submit" class="btn btn-block" style="float: right; color:#e3e6e8; background-color:#354856;">Submit</button>
            </div>
        </form>
        <div class="col-9" id="purtable">
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
                <button class="tablinks" onclick="opentab(event, 'det')">Detail</button>
            </div>
            <div class="tabcontent" id="gen">
                <?php if ($catfix == false) { ?>
                    <table class="table">
                        <thead>
                            <tr style="text-align: center;">
                                <th scope="col">Total</th>
                                <th scope="col">Date</th>
                                <th scope="col">Payment Method</th>
                                <th scope="col">Customer Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($purs as $pur) { ?>
                                <tr style="text-align: center;" data-href="<?php echo 'purchase_profile.php?purid=' . $pur['purid'] ?>">
                                    <td><?php echo $pur['total'] ?> €</td>
                                    <td><?php echo $pur['date'] ?></td>
                                    <td><?php echo $pur['payment_method'] ?></td>
                                    <td><?php echo $pur['first_name'] . ' ' . $pur['last_name'] ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } else { ?>
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
                            <?php foreach ($purs as $pur) { ?>
                                <tr style="text-align: center;" data-href="<?php echo 'purchase_profile.php?purid=' . $pur['purid'] ?>">
                                    <th scope="row"><?php echo $pur['prname'] ?></th>
                                    <td><?php echo $pur['brand'] ?></td>
                                    <td><?php echo $pur['amount'] ?></td>
                                    <td><?php echo $pur['cost'] * $pur['amount'] ?> €</td>
                                    <td><?php echo $pur['name'] ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
            </div>

            <div class="tabcontent" style="display: none;" id="det">
                <?php include 'purchase_charts.php'; ?>
            </div>
        </div>
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

</body>

</html>