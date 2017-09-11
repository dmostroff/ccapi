-- select TABLE_NAME from information_schema.TABLES where TABLE_SCHEMA = 'ccpoints';
select TABLE_NAME, concat('{', group_concat(concat('"', COLUMN_NAME, '":""')), '}') from information_schema.COLUMNS where TABLE_SCHEMA = 'ccpoints' GROUP BY TABLE_NAME ORDER BY ORDINAL_POSITION;

select * from INFORMATION_SCHEMA.KEY_COLUMN_USAGE where TABLE_NAME = 'client_cc';