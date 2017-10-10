DROP SCHEMA IF EXISTS `ccpoints`;
/*
select concat('DROP TABLE ', TABLE_NAME,';')
FROM INFORMATION_SCHEMA.TABLES
WHERE TABLE_SCHEMA = 'ccpoints'
;
DROP TABLE adm_settings;
DROP TABLE adm_tags;
DROP TABLE cc_action;
DROP TABLE cc_bal_transfer_info;
DROP TABLE cc_cards;
DROP TABLE cc_company;
DROP TABLE cc_transaction;
DROP TABLE client_address;
DROP TABLE client_business;
DROP TABLE client_cc;
DROP TABLE client_cc_history;
DROP TABLE client_financials;
DROP TABLE client_person;
*/

CREATE DATABASE IF NOT EXISTS `ccpoints`
DEFAULT CHARACTER SET utf8
;

USE `ccpoints`;

\! echo 'adm_users'
CREATE TABLE IF NOT EXISTS `adm_users` (
	user_id bigint AUTO_INCREMENT PRIMARY KEY
	, login varchar(32) NOT NULL
	, pwd varchar(255)
	, user_name varchar(255)
	, email varchar(255)
	, phone varchar(10)
	, phone_2 varchar(10)
	, phone_cell varchar(10)
	, phone_fax varchar(10)
    , UNIQUE INDEX (login)
	, recorded_on TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
 
\! echo 'adm_settings'
CREATE TABLE IF NOT EXISTS `adm_settings` (
	prefix varchar(16) NOT NULL
	, keyname varchar(32) NOT NULL
	, keyvalue text
    , PRIMARY KEY(prefix, keyname)
);

\! echo 'adm_tags'
CREATE TABLE IF NOT EXISTS `adm_tags` (
	prefix varchar(16) NOT NULL
	, tag varchar(32) NOT NULL
	, description text
    , PRIMARY KEY(prefix, tag)
);

\! echo 'cc_company'
DROP TABLE IF EXISTS `cc_company`;
CREATE TABLE IF NOT EXISTS `cc_company` (
	cc_company_id bigint AUTO_INCREMENT PRIMARY KEY
	, cc_name varchar(255) NOT NULL
	, url text
	, contact text
	, address_1 varchar(255)
	, address_2 varchar(255)
	, city varchar(255)
	, state char(2)
    , zip varchar(10)
	, country varchar(255) default 'USA'
	, phone varchar(10)
	, phone_2 varchar(10)
	, phone_cell varchar(10)
	, phone_fax varchar(10)
	, recorded_on TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
	, UNIQUE INDEX (cc_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

\! echo 'cc_cards'
DROP TABLE IF EXISTS `cc_cards`;

CREATE TABLE IF NOT EXISTS `cc_cards` (
    cc_card_id bigint AUTO_INCREMENT PRIMARY KEY
    , cc_company_id bigint NOT NULL
    , card_name varchar(255)
    , version text
    , annual_fee decimal(15,2)
    , first_year_free boolean
    , recorded_on TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    , UNIQUE KEY `idx_card_name`(`card_name`)
    , CONSTRAINT `fk_cc_cards_company` FOREIGN KEY (`cc_company_id`) REFERENCES `cc_company`(`cc_company_id`) ON DELETE CASCADE
--    , CONSTRAINT `FK_CartBilling_Cart` FOREIGN KEY (`cartId`) REFERENCES `cart_main` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

\! echo 'client_person'
CREATE TABLE IF NOT EXISTS `client_person`
(
    client_id bigint AUTO_INCREMENT PRIMARY KEY
    , last_name varchar(255) NOT NULL
    , first_name varchar(255) NOT NULL
    , middle_name varchar(255)
    , dob date
    , gender char(1)
    , ssn char(9)
    , mmn varchar(255)
    , email varchar(255)
    , pwd blob
    , phone varchar(10)
    , phone_2 varchar(10)
    , phone_cell varchar(10)
    , phone_fax varchar(10)
    , phone_official varchar(10)
    , recorded_on TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    , UNIQUE INDEX (last_name, first_name, middle_name)
);

\! echo 'client_address'
CREATE TABLE IF NOT EXISTS `client_address` (
    address_id bigint AUTO_INCREMENT PRIMARY KEY
    , client_id bigint NOT NULL
    , address_type varchar(32) NOT NULL DEFAULT 'primary'
    , address_1 varchar(255)
    , address_2 varchar(255)
    , city varchar(255) NOT NULL
    , state char(2) NOT NULL
    , zip varchar(10)
    , country varchar(255) default 'USA'
    , valid_from date
    , valid_to date
    , recorded_on TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    , UNIQUE INDEX (`client_id`, `address_type`)
    , CONSTRAINT `fk_cliadd_per` FOREIGN KEY (`client_id`) REFERENCES `client_person`(`client_id`) ON DELETE CASCADE
);

\! echo 'client_financials'
CREATE TABLE IF NOT EXISTS `client_financials` 
( 
    financial_id bigint AUTO_INCREMENT PRIMARY KEY
    , client_id bigint NOT NULL
    , annual_income decimal( 15,2)
    , credit_line decimal( 15,2)
    , valid_from date
    , valid_to date
    , recorded_on TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    , CONSTRAINT `fk_clifin_per` FOREIGN KEY(`client_id`) REFERENCES `client_person`(`client_id`) ON DELETE CASCADE
);

\! echo 'client_business'
CREATE TABLE IF NOT EXISTS `client_business`
(
    `pbiz_id` bigint AUTO_INCREMENT PRIMARY KEY
    , `client_id` bigint NOT NULL
    , `business_name` varchar(255) NOT NULL
    , `address_id` bigint
    , `revenue` double
    , `num_of_years` integer
    , `num_of_employees` int
    , `valid_from` date
    , `valid_to` date
    , `recorded_on` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    , UNIQUE INDEX (client_id, business_name)
    , CONSTRAINT `fk_clibusiness_person` FOREIGN KEY (`client_id` ) REFERENCES client_person(`client_id`) ON DELETE CASCADE
)
;

\! echo 'client_account'
CREATE TABLE `client_accounts` (
  `account_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) DEFAULT NULL,
  `cc_card_id` bigint(20) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `account` varchar(32) NOT NULL,
  `account_info` varchar(255) NOT NULL, -- card #, expdate, cvv/cvv2
  `cc_login` text,
  `cc_password` varchar(255) DEFAULT NULL,
  `cc_status` varchar(32) DEFAULT NULL,
  `annual_fee` decimal(15,5) DEFAULT NULL,
  `credit_limit` decimal(15,5) DEFAULT NULL,
  `addtional_card` tinyint(1) DEFAULT NULL,
  `recorded_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`account_id`),
  UNIQUE KEY `account` (`account`,`name`),
  KEY `fk_client_accounts_person` (`client_id`),
  CONSTRAINT `fk_client_accounts_person` FOREIGN KEY (`client_id`) REFERENCES `client_person` (`client_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_client_accounts_card` FOREIGN KEY (`cc_card_id`) REFERENCES `cc_cards` (`cc_card_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

\! echo 'cc_transaction'
CREATE TABLE IF NOT EXISTS cc_transaction (
    cctrans_id bigint AUTO_INCREMENT PRIMARY KEY
    , account_id bigint
    , transaction_date datetime NOT NULL
    , transaction_type varchar(32) NOT NULL -- pay/charge
    , transaction_status varchar(32)
    , cedit decimal(15,2)
    , debit decimal(15,2)
    , recorded_on TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    , UNIQUE INDEX (transaction_date, transaction_type)
    , CONSTRAINT `fk_cctrans_account` FOREIGN KEY (`account_id` ) REFERENCES `client_account`(`account_id`) ON DELETE CASCADE
);

\! echo 'cc_action'
CREATE TABLE IF NOT EXISTS cc_action (
ccaction_id bigint AUTO_INCREMENT PRIMARY KEY
, account_id bigint
, ccaction text
, action_type varchar(32)
, action_status varchar(32)
, due_date date
, details text
, recorded_on TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
, CONSTRAINT `fk_ccact_cli` FOREIGN KEY (`account_id`) REFERENCES `client_account`(`account_id`)  ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS client_cchistory (
cchist_id bigint AUTO_INCREMENT PRIMARY KEY
, account_id bigint
, ccevent text
, ccevent_amt decimal(15,2)
, details text
, recorded_on TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
, CONSTRAINT `fk_cchist_account` FOREIGN KEY (`account_id`) REFERENCES `client_account`(`account_id`)  ON DELETE CASCADE
);

\! echo 'cc_bal_transfer_info'
CREATE TABLE IF NOT EXISTS cc_baltransferinfo (
bal_id bigint AUTO_INCREMENT PRIMARY KEY
, client_id bigint
, account_id bigint
, due_date date
, total decimal(15,2)
, credit_line decimal( 15,2)
, recorded_on TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
, CONSTRAINT `fk_ccbal_cli` FOREIGN KEY (`client_id`) REFERENCES client_person(client_id) ON DELETE CASCADE
, CONSTRAINT `fk_ccbal_account` FOREIGN KEY (`account_id`) REFERENCES `client_account`(`account_id`)  ON DELETE CASCADE
);
-- 
/* data */

insert into adm_tags(prefix, tag, description)
values
( 'PERSTATUS', 'NEWCUSTOMER', 'New Customer')
, ( 'PERSTATUS', 'BUILDING CREDIT', 'Building Credit')
, ( 'PERSTATUS', 'ZEROTOFIVE', 'Zero To Five')
, ('PERSTATUS', 'MORETHANFILE', 'More than Five')
, ('CCSTATUS', 'ZEROBALANCE', 'Zero Balance')
, ('TRANSTYPE', 'PAYMENT', 'Payment')
, ('TRANSTYPE', 'CHARGE', 'Charge')
, ('TRANSTYPE', 'AF', 'annual fee')
, ('TRANSTYPE', 'CREDIT', 'credit')
, ('TRANSTYPE', 'RETURNOFAF', 'Return of AF')
, ('TRANSTYPE', 'RETURN', 'Return')
, ('CARDSTATUS', 'APPLIED', 'Applied')
, ('CARDSTATUS', 'APPROVED', 'Approved')
, ('CARDSTATUS', 'DECLINED', 'Declined')
, ('CARDSTATUS', 'ACTIVEPOINTS', 'Active, Earning Points')
, ('CARDSTATUS', 'LOANOFFER', 'Waiting for Loan Offer')
, ('CARDSTATUS', 'LOANBALANCE', 'Balance for Loan')
, ('CARDSTATUS', 'PENDING', 'Pending for Points')
, ('CARDSTATUS', 'CLOSED', 'Closed')
;

insert into adm_settings( prefix, keyname, keyvalue)
values( 'SYS', 'dbver', '0.01')
;

insert into adm_users (
	login
	, pwd
	, user_name
	, email
	, phone
	, phone_2
	, phone_cell
	, phone_fax
)
values
( 'dano', PASSWORD('chesed'), 'Daniel Ostroff', 'dostroff@gmail.com', '025336219', null, '0548463872', '025790204')
, ('rto', PASSWORD('doobie'), 'Raphael Ostroff', 'ostroff2015@gmail.com', '7326643993', null, '', null)
;

CREATE USER IF NOT EXISTS 'ccadmin'@'localhost'
    IDENTIFIED BY 'doobie'
    PASSWORD EXPIRE NEVER
    ;

GRANT ALL ON `ccpoints`.* TO 'ccadmin'@'localhost'
;

CREATE USER IF NOT EXISTS 'dano'@'localhost'
    IDENTIFIED BY 'chesed'
    PASSWORD EXPIRE NEVER
    ;

GRANT ALL ON `ccpoints`

GRANT ALL ON `ccpoints`.* TO 'ccadmin'@'%'
;

/*
CREATE USER 'ccadmin'@'localhost'
    IDENTIFIED BY 'doobie'
    ;

GRANT ALL ON `ccpoints`.* TO 'ccadmin'@'localhost';

CREATE USER 'dano'@'localhost'
    IDENTIFIED BY 'chesed'
    ;

GRANT ALL ON `ccpoints`.* TO 'dano'@'localhost'
;
*/