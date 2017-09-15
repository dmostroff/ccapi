DROP PROCEDURE IF EXISTS sp_angularclass_create;
DELIMITER $$
CREATE PROCEDURE sp_angularclass_create( a_tablename text)
  DETERMINISTIC
BEGIN
  DECLARE mytext varchar(1000);
  DECLARE mysnippet varchar(1000);

SET mytext = '';
SELECT concat( 'export class ', TABLE_NAME)
INTO mysnippet
FROM INFORMATION_SCHEMA.TABLES
WHERE TABLE_NAME = a_tablename
;

-- SET mytext = concat(mytext, mysnippet);

SELECT concat( mysnippet, '{\n' 
,GROUP_CONCAT( concat(COLUMN_NAME, ': '
	, case 
		when lower(DATA_TYPE) in ('varchar', 'char', 'text') then 'string' 
		when lower(DATA_TYPE) in ('date', 'datetime', 'timestamp') then 'date' 
		when lower(DATA_TYPE) in ('bool','boolean') then 'boolean' 
        else 'number'
    end
    , ';') SEPARATOR '\n'))
INTO mysnippet
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = a_tablename
ORDER BY ORDINAL_POSITION 
;

SET mytext = concat(mytext, mysnippet);
SET mytext = concat(mytext, '\n\n  constructor() {\n    console.log( ', a_tablename, ');\n  }\n}\n');

 SELECT mytext as myclass;

END;
$$
DELIMITER ;

