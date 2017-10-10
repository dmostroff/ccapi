DROP PROCEDURE sp_company_cards_insert;
DELIMITER $$
CREATE PROCEDURE sp_company_cards_insert()
  DETERMINISTIC
BEGIN
DECLARE my_company_id integer;

DELETE FROM `ccpoints`.`cc_company`;
ALTER TABLE `ccpoints`.`cc_company` AUTO_INCREMENT = 1;

DELETE FROM `ccpoints`.`cc_cards`;
ALTER TABLE `ccpoints`.`cc_cards` AUTO_INCREMENT = 1;

INSERT INTO `ccpoints`.`cc_company`(`cc_name`,`url`,`address_1`,`address_2`,`city`,`state`,`zip`,`phone`, `phone_2`)
VALUES
('Chase', 'chase.com', 'Broadway and Chambers', '270 Broadway', 'New York', 'NY', '10007', '2123490990', null)
, ('American Express', 'americanexpress.com', 'P.O. Box 650448', null, 'Dallas', 'TX', '751650448', '8009540559', '8014494029')
, ('Barclay', null, null, null, null, null, null, null, null)
, ('Citi Bank', null, null, null, null, null, null, null, null)
, ('BOFA', null, null, null, null, null, null, null, null)
, ('Synchrony', null, null, null, null, null, null, null, null)
, ('Capital One', null, null, null, null, null, null, null, null)
;

SELECT cc_company_id
INTO my_company_id
FROM cc_company
WHERE cc_name = 'American Express'
;

INSERT INTO `ccpoints`.`cc_cards`(`cc_company_id`,`card_name`)
VALUES (my_company_id, 'MR Plat P')
, (my_company_id, 'MR Gold P')
, (my_company_id, 'Delta Gold P')
, (my_company_id, 'Delta Plat P')
, (my_company_id, 'Delta Reserve P')
, (my_company_id, 'SPG P')

, (my_company_id, 'MR Plat B')
, (my_company_id, 'MR Gold B')
, (my_company_id, 'Delta Gold B')
, (my_company_id, 'Delta Plat B')
, (my_company_id, 'Delta Reserve B')
, (my_company_id, 'SPG B')
;

SELECT cc_company_id
INTO my_company_id
FROM cc_company
WHERE cc_name = 'Chase'
;

INSERT INTO `ccpoints`.`cc_cards`(`cc_company_id`,`card_name`)
VALUES(my_company_id, 'Ink ')
, (my_company_id, 'SAP')
, (my_company_id, 'RES')
, (my_company_id, 'SW Premier P')
, (my_company_id, 'SW Plus P')
, (my_company_id, 'SW B')
, (my_company_id, 'United P')
, (my_company_id, 'United B')
, (my_company_id, 'BA P')
, (my_company_id, 'Marriott')
, (my_company_id, 'Hyatt')
;

SELECT cc_company_id
INTO my_company_id
FROM cc_company
WHERE cc_name = 'Barclay'
;

INSERT INTO `ccpoints`.`cc_cards`(`cc_company_id`,`card_name`)
VALUES(my_company_id, 'BCA')
, (my_company_id, 'MAM')
, (my_company_id, 'BC AA')
;

SELECT cc_company_id
INTO my_company_id
FROM cc_company
WHERE cc_name = 'Citi Bank'
;

INSERT INTO `ccpoints`.`cc_cards`(`cc_company_id`,`card_name`)
VALUES(my_company_id, 'Citi Thank you')
, (my_company_id, 'Citi Prestige')
, (my_company_id, 'Citi AA')
;

SELECT cc_company_id
INTO my_company_id
FROM cc_company
WHERE cc_name = 'BOFA'
;

INSERT INTO `ccpoints`.`cc_cards`(`cc_company_id`,`card_name`)
VALUES(my_company_id, 'BOFA Cash')
, (my_company_id, 'BOFA Rewards')
;

SELECT cc_company_id
INTO my_company_id
FROM cc_company
WHERE cc_name = 'Synchrony'
;

INSERT INTO `ccpoints`.`cc_cards`(`cc_company_id`,`card_name`)
VALUES(my_company_id, 'Cathay Pacific')
;


SELECT cc_company_id
INTO my_company_id
FROM cc_company
WHERE cc_name = 'Capital One'
;

INSERT INTO `ccpoints`.`cc_cards`(`cc_company_id`,`card_name`)
VALUES(my_company_id, 'Cap1 Venture')
;
END;
$$
DELIMITER ;
