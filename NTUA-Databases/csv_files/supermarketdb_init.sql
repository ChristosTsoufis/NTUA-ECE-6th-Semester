--
-- Βάση δεδομένων: `supermarketdb`
--

DROP DATABASE IF EXISTS `supermarketdb`;
CREATE DATABASE `supermarketdb` DEFAULT CHARACTER SET utf8;
USE `supermarketdb`;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `catid` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL UNIQUE,
  primary key(`catid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE `customer` (
  `cardid` bigint(16) NOT NULL,
  `card_exp_date` date NOT NULL,
  `current_points` decimal(5,0) NOT NULL,
  `points_redeemed` decimal(11,0) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `street_name` varchar(30) NOT NULL,
  `street_number` decimal(3,0) NOT NULL,
  `apt_floor` decimal(2,0) NOT NULL,
  `city` varchar(30) NOT NULL,
  `zip` decimal(5,0) NOT NULL,
  `relationship_status` varchar(8) NOT NULL,
  `number_of_kids` tinyint(2) NOT NULL,
  `date_of_birth` date NOT NULL,
  primary key(`cardid`),
  check(`current_points` >= 0),
  check(`points_redeemed` >= 0),
  check(`street_number` > 0),
  check(`zip` > 0),
  check(`number_of_kids` >= 0),
  check(`relationship_status` in ('married', 'widowed', 'free', 'divorced'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `productid` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `islabel` tinyint(1) NOT NULL,
  `brand` varchar(30) NOT NULL,
  `catid` int(2) NOT NULL,
  primary key(`productid`),
  unique key prod_info (`name`,`brand`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `store`
--

DROP TABLE IF EXISTS `store`;
CREATE TABLE `store` (
  `storeid` int(4) NOT NULL AUTO_INCREMENT,
  `street_name` varchar(30) NOT NULL,
  `street_number` decimal(3,0) NOT NULL,
  `city` varchar(30) NOT NULL,
  `zip` decimal(5,0) NOT NULL,
  `sq_meters` decimal(6,1) NOT NULL,
  primary key(`storeid`),
  check(`street_number` > 0),
  check(`zip` > 0),
  check(`sq_meters` >= 0),
  unique key uniq_store (`street_name`,`street_number`,`city`,`zip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `has`
--

DROP TABLE IF EXISTS `has`;
CREATE TABLE `has` (
  `catid` int(2) NOT NULL,
  `storeid` int(4) NOT NULL,
  primary key (`catid`,`storeid`),
  foreign key(`storeid`) references `store`(`storeid`) on delete cascade,
  foreign key(`catid`) references `category`(`catid`) on delete cascade
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `offers`
--

DROP TABLE IF EXISTS `offers`;
CREATE TABLE `offers` (
  `storeid` int(4) NOT NULL,
  `productid` int(8) NOT NULL,
  `current_price` decimal(6,2) NOT NULL,
  `quantity` int(3) NOT NULL,
  `corridor` char(1) NOT NULL,
  `shelve` int(1) NOT NULL,
  primary key(`storeid`,`productid`),
  foreign key(`storeid`) references `store`(`storeid`) on delete cascade,
  foreign key(`productid`) references `product`(`productid`) on delete cascade,
  check(`current_price` >= 0),
  check(`quantity` >= 0),
  check (`corridor` in ('A', 'B', 'C', 'D', 'E', 'F')), 
  check (`shelve` in (1, 2, 3))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `opening_hours`
--

DROP TABLE IF EXISTS `opening_hours`;
CREATE TABLE `opening_hours` (
  `storeid` int(4) NOT NULL,
  `day` varchar(9) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  primary key(`day`,`storeid`),
  foreign key(`storeid`) references `store`(`storeid`) on delete cascade,
  check(`day` in ('Monday', 'Tuesday', 'Wednesday', 'Thursday' ,'Friday', 'Saturday', 'Sunday' ))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `phone_number`
--

DROP TABLE IF EXISTS `phone_number`;
CREATE TABLE `phone_number` (
  `cardid` bigint(16) NOT NULL,
  `phone` bigint(10) NOT NULL,
  primary key(`cardid`,`phone`),
  foreign key(`cardid`) references `customer`(`cardid`) on delete cascade,
  check(`phone` >= 100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `pricehistory`
--

DROP TABLE IF EXISTS `pricehistory`;
CREATE TABLE `pricehistory` (
  `storeid` int(4) NOT NULL,
  `productid` int(8) NOT NULL,
  `date` date NOT NULL,
  `issales` tinyint(1) NOT NULL,
  `newprice` decimal(6,2) NOT NULL,
  primary key (`storeid`,`productid`,`date`),
  foreign key(`storeid`) references `store`(`storeid`) on delete cascade,
  foreign key(`productid`) references `product`(`productid`) on delete cascade,
  check(`newprice` >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `purchase`
--

DROP TABLE IF EXISTS `purchase`;
CREATE TABLE `purchase` (
  `purid` bigint(16) NOT NULL AUTO_INCREMENT,
  `total` decimal(6,2) NOT NULL,
  `payment_method` varchar(11) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `storeid` int(4),
  `cardid` bigint(16),
  primary key (`purid`),
  foreign key(`storeid`) references `store`(`storeid`) on delete set null,
  foreign key(`cardid`) references `customer`(`cardid`) on delete set null,
  unique key uniq_trans (`date`,`time`,`storeid`,`cardid`),
  check (`payment_method` in ("cash", "credit card")),
  check(`total` > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Δομή πίνακα για τον πίνακα `purch_prod`
--

DROP TABLE IF EXISTS `purch_prod`;
CREATE TABLE `purch_prod` (
  `purid` bigint(16) NOT NULL,
  `productid` int(8) NOT NULL,
  `cost` decimal(6,2) NOT NULL,
  `amount` decimal(3,0) NOT NULL,
  primary key (`purid`,`productid`),
  foreign key (`purid`) references `purchase`(`purid`),
  foreign key (`productid`) references `product`(`productid`) on delete cascade,
  check(`cost` > 0),
  check(`amount` > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Δομή για προβολή `famous_pairs`
-- Most famous product pairs , Top 10 -- Create View containing storeid
--

DROP VIEW IF EXISTS famous_pairs;
CREATE VIEW famous_pairs AS
SELECT purch_prod.productid, product.name, product.brand, purchase.storeid, purchase.purid
FROM purchase, purch_prod, product
WHERE purchase.purid = purch_prod.purid 
AND purch_prod.productid = product.productid
ORDER BY purchase.purid;

-- --------------------------------------------------------

--
-- Δομή για προβολή `islabel_pref`
-- isLabel % per store per category
--

DROP VIEW IF EXISTS isLabel_pref;
CREATE VIEW isLabel_pref AS
SELECT product.islabel, category.name, purchase.storeid
FROM purchase, purch_prod, product, category
WHERE purchase.purid = purch_prod.purid
AND purch_prod.productid = product.productid
AND product.catid = category.catid;

-- --------------------------------------------------------

--
-- Δομή για προβολή `pur_categ`
-- View for Purchase - Category
--

DROP VIEW IF EXISTS pur_categ;
CREATE VIEW pur_categ AS
SELECT purchase.purid, total, payment_method, date, time, storeid, product.catid,
	   cost, amount, product.name AS prname, brand, category.name AS name
FROM purchase, purch_prod, product, category
WHERE purchase.purid = purch_prod.purid 
AND purch_prod.productid = product.productid
AND product.catid = category.catid
ORDER BY purid;

-- --------------------------------------------------------

--
-- Δομή για προβολή `pur_cust`
-- View for Purchases - Customer
--

DROP VIEW IF EXISTS pur_cust;
CREATE VIEW pur_cust AS
SELECT purid, total, payment_method, date, time, card_exp_date, first_name,
	   last_name, street_name, street_number, city, YEAR(CURRENT_TIMESTAMP) - DATE_FORMAT(customer.date_of_birth, "%Y") as age,
       relationship_status, number_of_kids, storeid
FROM purchase
INNER JOIN customer
ON purchase.cardid=customer.cardid
ORDER BY purid;

-- --------------------------------------------------------

--
-- Συναρτήσεις
-- Find first and last day of week
--

DELIMITER $$
CREATE FUNCTION FIRST_DAY_OF_WEEK(day DATE)
RETURNS DATE DETERMINISTIC
BEGIN
  RETURN SUBDATE(day, WEEKDAY(day));
END$$

CREATE FUNCTION LAST_DAY_OF_WEEK(day DATE)
RETURNS DATE DETERMINISTIC
BEGIN
  RETURN SUBDATE(day, WEEKDAY(day)-6);
END$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Ευρετήρια για πίνακα `pricehistory`
--

CREATE INDEX product
ON `pricehistory` (`productid`);

-- --------------------------------------------------------

--
-- Ευρετήρια για πίνακα `customer`
--

CREATE INDEX hometown
ON `customer` (`city`);

CREATE INDEX fullname
ON `customer` (`first_name`,`last_name`);

-- --------------------------------------------------------

--
-- Ευρετήρια για πίνακα `purchase`
--

CREATE INDEX total_cost_method
ON `purchase` (`total`, `payment_method`);