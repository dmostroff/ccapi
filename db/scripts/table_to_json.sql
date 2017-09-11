SET @schema = 'ccpoints';
SET @table = 'cc_company';

SELECT CONCAT(
  'SELECT CONCAT(TRIM(TRAILING ', QUOTE(','), ' FROM CONCAT(', QUOTE('{'), ',', 
  GROUP_CONCAT(QUOTE('"'), ',', QUOTE(COLUMN_NAME), ',', 
  QUOTE('"'), ',', QUOTE(':'), ',', QUOTE('"'), ',', COLUMN_NAME, ',', 
  QUOTE('"'),',', QUOTE(',')),
  ')), ''}'') FROM ', @table
)
INTO @qry FROM 
  (SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS c
  WHERE TABLE_SCHEMA = @schema AND TABLE_NAME = @table) t;

SELECT @qry;
PREPARE stmt FROM @qry;
EXECUTE stmt;