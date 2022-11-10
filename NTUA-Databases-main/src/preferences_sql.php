<?php
$conn = mysqli_connect('192.168.99.100', 'root', 'root', 'supermarketdb');

if (!$conn) {
    echo 'Bad connection:' . mysqli_connect_error();
}

/* Show the Stores in the collapse */
$sql = 'SELECT * FROM store ORDER BY city ASC';

$result = mysqli_query($conn, $sql);

$stores = mysqli_fetch_all($result, MYSQLI_ASSOC);

$storefix = false;
if (isset($_GET['submit'])) {
    /*Creating part of the query that chooses stores */
    foreach ($stores as $stor) {
        if ($_GET['storch' . '' . $stor['storeid']]) {
            if ($storefix == false) {
                $first = true;
                $storefix = true;
                $stores_sel = '(';
            }
            $stores_sel = $stores_sel . $stor['storeid'] . ', ';
        };
    }

    $stores_sel = substr($stores_sel, 0, -2);
    $stores_sel = $stores_sel . ')';
}

/*Most famous pairs */
if ($storefix == false) {
    $sql1 = 'SELECT COUNT(*) AS count, t1.productid, t1.name AS name1, t1.brand AS brand1, t2.productid, t2.name AS name2, t2.brand AS brand2
         FROM famous_pairs t1, famous_pairs t2
         WHERE t1.productid > t2.productid
         AND t1.purid = t2.purid
         GROUP BY t1.productid, t2.productid ORDER BY count DESC LIMIT 10;';
} else {
    $sql1 = 'SELECT COUNT(*) AS count, t1.productid, t1.name AS name1, t1.brand AS brand1, t2.productid, t2.name AS name2, t2.brand AS brand2
         FROM famous_pairs t1, famous_pairs t2
         WHERE t1.productid > t2.productid
         AND t1.purid = t2.purid
         AND t2.storeid IN ' . $stores_sel .
        ' GROUP BY t1.productid, t2.productid ORDER BY count DESC LIMIT 10;';
}
$result1 = mysqli_query($conn, $sql1);
$famous_pairs = mysqli_fetch_all($result1, MYSQLI_ASSOC);

/*Most Famous positions */
if ($storefix == false) {
    $sql2 = 'SELECT COUNT(*) AS count, offers.productid, offers.storeid, offers.corridor, offers.shelve
         FROM purch_prod, offers, purchase
         WHERE purchase.purid = purch_prod.purid 
         AND purchase.storeid = offers.storeid 
         AND purch_prod.productid = offers.productid 
         GROUP BY offers.productid, offers.storeid
         ORDER BY count DESC
         LIMIT 10;';
} else {
    $sql2 = 'SELECT COUNT(*) AS count, offers.productid, offers.storeid, offers.corridor, offers.shelve
         FROM purch_prod, offers, purchase
         WHERE purchase.purid = purch_prod.purid 
         AND purchase.storeid = offers.storeid 
         AND purch_prod.productid = offers.productid 
         AND offers.storeid IN ' . $stores_sel .
        ' GROUP BY offers.productid, offers.storeid
         ORDER BY count DESC
         LIMIT 10;';
}
$result2 = mysqli_query($conn, $sql2);
$famous_pos = mysqli_fetch_all($result2, MYSQLI_ASSOC);

/*isLabel Trust per Category */
if ($storefix == false) {
    $sql3 = 'SELECT COUNT(*) AS count, islabel, name FROM isLabel_pref  
             GROUP BY name, islabel;';
} else {
    $sql3 = 'SELECT COUNT(*) AS count, islabel, name FROM isLabel_pref  
         WHERE storeid IN ' . $stores_sel .
        ' GROUP BY name, islabel;';
}

$result3 = mysqli_query($conn, $sql3);
$check = 0;
$no = 0;
$yes = 0;

while ($row = mysqli_fetch_array($result3, MYSQLI_ASSOC)) {
    if ($check == 0) {
        $nolabel[$no] = (int) $row['count'];
        $no++;
        $check = 1;
        $labels1 .= '"' . ucwords($row['name']) . '", ';
    } else {
        $yeslabel[$yes] = (int) $row['count'];
        $yes++;
        $check = 0;
    }
}
for ($i = 0; $i < $no; $i++) {
    $div = $nolabel[$i] + $yeslabel[$i];
    $nolabel[$i] /= $div;
    $yeslabel[$i] /= $div;
}

for ($i = 0; $i < $no; $i++) {
    $nolabelarr .= number_format($nolabel[$i] * 100, 2, '.', ' ') . ', ';
    $yeslabelarr .= number_format($yeslabel[$i] * 100, 2, '.', ' ') . ', ';
}

$yeslabelarr = substr($yeslabelarr, 0, -2);
$nolabelarr = substr($nolabelarr, 0, -2);
$labels1 = substr($labels1, 0, -2);

/*Hours most money spend per store */
if ($storefix == false) {
    $sql4 = 'SELECT TIME_FORMAT(purchase.time, "%H") AS hourspan, SUM(total) AS profit
         FROM purchase
         GROUP BY hourspan;';
} else {
    $sql4 = 'SELECT TIME_FORMAT(purchase.time, "%H") AS hourspan, SUM(total) AS profit
         FROM purchase
         WHERE storeid IN ' . $stores_sel .
        ' GROUP BY hourspan;';
}

$result4 = mysqli_query($conn, $sql4);

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

while ($row = mysqli_fetch_array($result4, MYSQLI_ASSOC)) {
    $hour = substr($row['hourspan'], 0, 2);
    /* Updating total money spend */
    $arr[(int) $hour][1] += (float) $row['profit'];
}
$i = 0;
while ($i < 24) {
    if ($arr[$i][1] == 0) {
        $i++;
        continue;
    }
    $profit = number_format($arr[$i][1], 2, '.', '');
    $time .= '"' . $arr[$i][0] . ":00-" . $arr[$i][0] . ':59", ';
    $prof .=  $profit . ", ";
    $i++;
}
$time = substr($time, 0, -2);
$prof = substr($prof, 0, -2);

/*Age Groups per Hour per store
Age 15-24
Age 25-40
Age 41-64
Age 65+ */
if ($storefix == false) {
    $sql5 = 'SELECT COUNT(*) as count, TIME_FORMAT(purchase.time, "%H") AS hourspan,
         (CASE
	        WHEN YEAR(CURRENT_TIMESTAMP) - DATE_FORMAT(customer.date_of_birth, "%Y") <= 24 THEN "Age: 15-24"
            WHEN YEAR(CURRENT_TIMESTAMP) - DATE_FORMAT(customer.date_of_birth, "%Y") <= 40 THEN "Age: 25-40"
            WHEN YEAR(CURRENT_TIMESTAMP) - DATE_FORMAT(customer.date_of_birth, "%Y") <= 64 THEN "Age: 41-64"
            ELSE "Age: 65+"
         END) AS age_group
    FROM purchase, customer
    WHERE purchase.cardid = customer.cardid
    GROUP BY hourspan, age_group;';
} else {
    $sql5 = 'SELECT COUNT(*) as count, TIME_FORMAT(purchase.time, "%H") AS hourspan,
         (CASE
	        WHEN YEAR(CURRENT_TIMESTAMP) - DATE_FORMAT(customer.date_of_birth, "%Y") <= 24 THEN "Age: 15-24"
            WHEN YEAR(CURRENT_TIMESTAMP) - DATE_FORMAT(customer.date_of_birth, "%Y") <= 40 THEN "Age: 25-40"
            WHEN YEAR(CURRENT_TIMESTAMP) - DATE_FORMAT(customer.date_of_birth, "%Y") <= 64 THEN "Age: 41-64"
            ELSE "Age: 65+"
         END) AS age_group
    FROM purchase, customer
    WHERE purchase.cardid = customer.cardid and purchase.storeid IN ' . $stores_sel .
        ' GROUP BY hourspan, age_group;';
}
$result5 = mysqli_query($conn, $sql5);

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

/*Creating associative arrays */
while ($row = mysqli_fetch_array($result5, MYSQLI_ASSOC)) {
    $arrt[(int) $row['hourspan']][1] = 1;
    if ($row['age_group'] == 'Age: 15-24') {
        $labelg1[$row['hourspan']] = (int) $row['count'];
    } elseif ($row['age_group'] == 'Age: 25-40') {
        $labelg2[$row['hourspan']] = (int) $row['count'];
    } elseif ($row['age_group'] == 'Age: 41-64') {
        $labelg3[$row['hourspan']] = (int) $row['count'];
    } else {
        $labelg4[$row['hourspan']] = (int) $row['count'];
    }
}

$i = 0;
while ($i < 24) {
    /* All age group not visited */
    if ($arrt[$i][1] == 0) {
        $i++;
        continue;
    }
    $labelall .=  '"' . $arrt[$i][0] . ":00-" . $arrt[$i][0] . ':59", ';
    $div = ($labelg1[$arrt[$i][0]] + $labelg2[$arrt[$i][0]] + $labelg3[$arrt[$i][0]] + $labelg4[$arrt[$i][0]]);
    if ($labelg1[$arrt[$i][0]] > 0) {
        $g1 .= number_format(($labelg1[$arrt[$i][0]] / $div * 100), 2, '.', '') . ', ';
    } else {
        $g1 .= '0, ';
    }
    if ($labelg2[$arrt[$i][0]] > 0) {
        $g2 .= number_format(($labelg2[$arrt[$i][0]] / $div * 100), 2, '.', '') . ', ';
    } else {
        $g2 .= '0, ';
    }
    if ($labelg3[$arrt[$i][0]] > 0) {
        $g3 .= number_format(($labelg3[$arrt[$i][0]] / $div * 100), 2, '.', '') . ', ';
    } else {
        $g3 .= '0, ';
    }
    if ($labelg4[$arrt[$i][0]] > 0) {
        $g4 .= number_format(($labelg4[$arrt[$i][0]] / $div * 100), 2, '.', '') . ', ';
    } else {
        $g4 .= '0, ';
    }
    $i++;
}

$g1 = substr($g1, 0, -2);
$g2 = substr($g2, 0, -2);
$g3 = substr($g3, 0, -2);
$g4 = substr($g4, 0, -2);
$labelall = substr($labelall, 0, -2);

/*Top 3 products by age group */
if ($storefix == false) {
    $sql6 = '(SELECT * FROM ( SELECT  SUM(purch_prod.amount) AS total , name AS prname, product.brand as brand,
    (CASE
        WHEN YEAR(CURRENT_TIMESTAMP) - DATE_FORMAT(customer.date_of_birth, "%Y") <= 24 THEN "Age: 15-24"
        WHEN YEAR(CURRENT_TIMESTAMP) - DATE_FORMAT(customer.date_of_birth, "%Y") <= 40 THEN "Age: 25-40"
        WHEN YEAR(CURRENT_TIMESTAMP) - DATE_FORMAT(customer.date_of_birth, "%Y") <= 64 THEN "Age: 41-64"
        ELSE "Age: 65+"
    END) AS age_group
    FROM purchase, purch_prod, customer, product
    WHERE purchase.cardid = customer.cardid and purchase.purid = purch_prod.purid and purch_prod.productid = product.productid
    group by age_group, prname, brand
    order by age_group, total desc) AS t
    WHERE t.age_group = "Age: 15-24" order by t.total desc limit 3)
    union all
    (SELECT * FROM (SELECT  SUM(purch_prod.amount) AS total , name AS prname, product.brand as brand,
    (CASE
        WHEN YEAR(CURRENT_TIMESTAMP) - DATE_FORMAT(customer.date_of_birth, "%Y") <= 24 THEN "Age: 15-24"
        WHEN YEAR(CURRENT_TIMESTAMP) - DATE_FORMAT(customer.date_of_birth, "%Y") <= 40 THEN "Age: 25-40"
        WHEN YEAR(CURRENT_TIMESTAMP) - DATE_FORMAT(customer.date_of_birth, "%Y") <= 64 THEN "Age: 41-64"
        ELSE "Age: 65+"
    END) AS age_group
    FROM purchase, purch_prod, customer, product
    WHERE purchase.cardid = customer.cardid and purchase.purid = purch_prod.purid and purch_prod.productid = product.productid
    group by age_group, prname, brand
    order by age_group, total desc) AS t WHERE t.age_group = "Age: 25-40" order by t.total desc limit 3)
    union all
    (SELECT * FROM (SELECT  SUM(purch_prod.amount) AS total , name AS prname, product.brand as brand,
    (CASE
        WHEN YEAR(CURRENT_TIMESTAMP) - DATE_FORMAT(customer.date_of_birth, "%Y") <= 24 THEN "Age: 15-24"
        WHEN YEAR(CURRENT_TIMESTAMP) - DATE_FORMAT(customer.date_of_birth, "%Y") <= 40 THEN "Age: 25-40"
        WHEN YEAR(CURRENT_TIMESTAMP) - DATE_FORMAT(customer.date_of_birth, "%Y") <= 64 THEN "Age: 41-64"
        ELSE "Age: 65+"
    END) AS age_group
    FROM purchase, purch_prod, customer, product
    WHERE purchase.cardid = customer.cardid and purchase.purid = purch_prod.purid and purch_prod.productid = product.productid
    group by age_group, prname, brand
    order by age_group, total desc) AS t WHERE t.age_group = "Age: 41-64" order by t.total desc limit 3)
    union all
    (SELECT * FROM (SELECT  SUM(purch_prod.amount) AS total , name AS prname, product.brand as brand,
    (CASE
        WHEN YEAR(CURRENT_TIMESTAMP) - DATE_FORMAT(customer.date_of_birth, "%Y") <= 24 THEN "Age: 15-24"
        WHEN YEAR(CURRENT_TIMESTAMP) - DATE_FORMAT(customer.date_of_birth, "%Y") <= 40 THEN "Age: 25-40"
        WHEN YEAR(CURRENT_TIMESTAMP) - DATE_FORMAT(customer.date_of_birth, "%Y") <= 64 THEN "Age: 41-64"
        ELSE "Age: 65+"
    END) AS age_group
    FROM purchase, purch_prod, customer, product
    WHERE purchase.cardid = customer.cardid and purchase.purid = purch_prod.purid and purch_prod.productid = product.productid
    group by age_group, prname, brand
    order by age_group, total desc) AS t WHERE t.age_group = "Age: 65+" order by t.total desc limit 3)';
}
else {
    $sql6 = '(SELECT * FROM ( SELECT  SUM(purch_prod.amount) AS total , name AS prname, product.brand as brand,
    (CASE
        WHEN YEAR(CURRENT_TIMESTAMP) - DATE_FORMAT(customer.date_of_birth, "%Y") <= 24 THEN "Age: 15-24"
        WHEN YEAR(CURRENT_TIMESTAMP) - DATE_FORMAT(customer.date_of_birth, "%Y") <= 40 THEN "Age: 25-40"
        WHEN YEAR(CURRENT_TIMESTAMP) - DATE_FORMAT(customer.date_of_birth, "%Y") <= 64 THEN "Age: 41-64"
        ELSE "Age: 65+"
    END) AS age_group
    FROM purchase, purch_prod, customer, product
    WHERE purchase.cardid = customer.cardid and purchase.purid = purch_prod.purid and purch_prod.productid = product.productid and purchase.storeid IN ' .
    $stores_sel .
    ' group by age_group, prname, brand
    order by age_group, total desc) AS t
    WHERE t.age_group = "Age: 15-24" order by t.total desc limit 3)
    union all
    (SELECT * FROM (SELECT  SUM(purch_prod.amount) AS total , name AS prname, product.brand as brand,
    (CASE
        WHEN YEAR(CURRENT_TIMESTAMP) - DATE_FORMAT(customer.date_of_birth, "%Y") <= 24 THEN "Age: 15-24"
        WHEN YEAR(CURRENT_TIMESTAMP) - DATE_FORMAT(customer.date_of_birth, "%Y") <= 40 THEN "Age: 25-40"
        WHEN YEAR(CURRENT_TIMESTAMP) - DATE_FORMAT(customer.date_of_birth, "%Y") <= 64 THEN "Age: 41-64"
        ELSE "Age: 65+"
    END) AS age_group
    FROM purchase, purch_prod, customer, product
    WHERE purchase.cardid = customer.cardid and purchase.purid = purch_prod.purid and purch_prod.productid = product.productid and purchase.storeid IN ' .
    $stores_sel .
    ' group by age_group, prname, brand
    order by age_group, total desc) AS t WHERE t.age_group = "Age: 25-40" order by t.total desc limit 3)
    union all
    (SELECT * FROM (SELECT  SUM(purch_prod.amount) AS total , name AS prname, product.brand as brand,
    (CASE
        WHEN YEAR(CURRENT_TIMESTAMP) - DATE_FORMAT(customer.date_of_birth, "%Y") <= 24 THEN "Age: 15-24"
        WHEN YEAR(CURRENT_TIMESTAMP) - DATE_FORMAT(customer.date_of_birth, "%Y") <= 40 THEN "Age: 25-40"
        WHEN YEAR(CURRENT_TIMESTAMP) - DATE_FORMAT(customer.date_of_birth, "%Y") <= 64 THEN "Age: 41-64"
        ELSE "Age: 65+"
    END) AS age_group
    FROM purchase, purch_prod, customer, product
    WHERE purchase.cardid = customer.cardid and purchase.purid = purch_prod.purid and purch_prod.productid = product.productid and purchase.storeid IN ' .
    $stores_sel .
    ' group by age_group, prname, brand
    order by age_group, total desc) AS t WHERE t.age_group = "Age: 41-64" order by t.total desc limit 3)
    union all
    (SELECT * FROM (SELECT  SUM(purch_prod.amount) AS total , name AS prname, product.brand as brand,
    (CASE
        WHEN YEAR(CURRENT_TIMESTAMP) - DATE_FORMAT(customer.date_of_birth, "%Y") <= 24 THEN "Age: 15-24"
        WHEN YEAR(CURRENT_TIMESTAMP) - DATE_FORMAT(customer.date_of_birth, "%Y") <= 40 THEN "Age: 25-40"
        WHEN YEAR(CURRENT_TIMESTAMP) - DATE_FORMAT(customer.date_of_birth, "%Y") <= 64 THEN "Age: 41-64"
        ELSE "Age: 65+"
    END) AS age_group
    FROM purchase, purch_prod, customer, product
    WHERE purchase.cardid = customer.cardid and purchase.purid = purch_prod.purid and purch_prod.productid = product.productid and purchase.storeid IN ' .
    $stores_sel .
    ' group by age_group, prname, brand
    order by age_group, total desc) AS t WHERE t.age_group = "Age: 65+" order by t.total desc limit 3)';
}

$result6 = mysqli_query($conn, $sql6);
$top3s = mysqli_fetch_all($result6, MYSQLI_ASSOC);

/*Card Points by Age Group */
$sql7 = 'SELECT  SUM(customer.points_redeemed) AS redeemedp , SUM(customer.current_points) AS currentp,
(CASE
	WHEN YEAR(CURRENT_TIMESTAMP) - DATE_FORMAT(customer.date_of_birth, "%Y") <= 24 THEN "Age: 15-24"
    WHEN YEAR(CURRENT_TIMESTAMP) - DATE_FORMAT(customer.date_of_birth, "%Y") <= 40 THEN "Age: 25-40"
    WHEN YEAR(CURRENT_TIMESTAMP) - DATE_FORMAT(customer.date_of_birth, "%Y") <= 64 THEN "Age: 41-64"
    ELSE "Age: 65+"
END) AS age_group
FROM customer
group by age_group;';

$result7 = mysqli_query($conn, $sql7);
while ($row = mysqli_fetch_array($result7, MYSQLI_ASSOC)) {
    $div = (int)$row['redeemedp'] + (int) $row['currentp'];
    $redp .=  number_format(((int)$row['redeemedp'] / $div * 100), 2, '.', '') . ', ';
    $curp .=  number_format(((int)$row['currentp'] / $div * 100), 2, '.', '') . ', ';
}

$redp = substr($redp, 0, -2);
$curp = substr($curp, 0, -2);
