DROP FUNCTION f_angularclass_create;
DELIMITER $$
CREATE FUNCTION f_angularclass_create( a_tablename text) RETURNS text
  DETERMINISTIC
BEGIN
  DECLARE mytext text;
  DECLARE mysnippet text;
  DECLARE ucTable text;

SET ucTable = CONCAT(UCASE(LEFT(LOWER(a_tablename), 1)), SUBSTRING(LOWER(a_tablename), 2));

SELECT GROUP_CONCAT( concat(COLUMN_NAME, ': '
	, case 
		when lower(DATA_TYPE) in ('varchar', 'char', 'text') then 'string' 
		when lower(DATA_TYPE) in ('date', 'datetime', 'timestamp') then 'date' 
		when lower(DATA_TYPE) in ('bool','boolean') then 'boolean' 
        else 'number'
    end
    , ';') SEPARATOR '\n\t')
INTO mysnippet
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = a_tablename
ORDER BY ORDINAL_POSITION 
;

SET mytext := concat('export class ', ucTable, ' {');
SET mytext := concat(mytext, '\n\n\t', mysnippet);
SET mytext := concat(mytext, '\n\n\tconstructor() {');
SET mytext := concat(mytext, '\n\t\tconsole.log( ', a_tablename, ');');
SET mytext := concat(mytext, '\n\t}');
SET mytext := concat(mytext, '\n');
SET mytext := concat(mytext, '\n\tset(new', ucTable, ':', ucTable, ') {');
SET mytext := concat(mytext, '\n\t\tfor( let ii in new', ucTable, ') {');
SET mytext := concat(mytext, '\n\t\t\tthis[ii] = new', ucTable, '[ii];');
SET mytext := concat(mytext, '\n\t\t}');
SET mytext := concat(mytext, '\n\t}');
SET mytext := concat(mytext, '\n}');
SET mytext := concat(mytext, '\n');

RETURN (mytext);

END;
$$
DELIMITER ;

