--DROP FUNCTION f_angular_form_create;
DELIMITER $$
CREATE FUNCTION f_angular_form_create( a_tablename text, component text, className text) RETURNS text
  DETERMINISTIC
BEGIN
  DECLARE mytext text;
  DECLARE mysnippet text;
  DECLARE mysnippet1 text;
  DECLARE ucTable text;
  DECLARE mycolumn text;
  DECLARE mydatatype text;
  DECLARE objName text;
  DECLARE myFormName text;
  DECLARE myServiceName text;
  DECLARE formControlText text;
  

DECLARE done int;
DECLARE cursor_i CURSOR FOR 
	SELECT COLUMN_NAME, DATA_TYPE
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_NAME = a_tablename 
    ORDER BY ORDINAL_POSITION
    ;
DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

SET ucTable = CONCAT(UCASE(LEFT(LOWER(a_tablename), 1)), SUBSTRING(LOWER(a_tablename), 2));
SET objName = CONCAT(LOWER(LEFT(className, 1)), SUBSTRING(className, 2));
SET myFormName = CONCAT( objName,'Form');
SET myServiceName = CONCAT( objName, 'Service');

SET mysnippet = '';


OPEN cursor_i;
lp1: LOOP
	FETCH cursor_i INTO mycolumn, mydatatype;
    IF done THEN
		LEAVE lp1;
	END IF;
	SET formControlText = CONCAT(
						'\n\t<div fxLayout="row">',
						'\n\t\t<md-form-field class="form-group" fxFlex fxFlexAlign="end center">',
						'\n\t\t\t<input mdInput type="text" formControlName="', mycolumn, '" placeholder="', mycolumn, '">',
						'\n\t\t\t<md-error *ngIf="', myFormName, '.hasError(\'required\')">', mycolumn, ' is <strong>required</strong>',
						'\n\t\t\t</md-error>',
						'\n\t\t</md-form-field>',
						'\n\t</div>');

    SET mysnippet = CONCAT( mysnippet, REPLACE( formControlText, 'COULUMN_NAME', mycolumn));
END LOOP;
CLOSE cursor_i;
    
/*
SET mysnippet1 = CONCAT('<div fxFlex class="form-group">'
                    '\n\t<div fxLayout="row">'
                	'\n\t\t<md-form-field class="form-group" fxFlex fxFlexAlign="end center">'
                	'\n\t\t\t<input mdInput type="text" formControlName="', COLUMN_NAME, '" placeholder="', COLUMN_NAME, '">',
                    '\n\t\t\t<md-error *ngIf="_myFormName_.hasError(\'required\')">', COLUMN_NAME, ' is <strong>required</strong>',
                	'\n\t\t\t</md-error>',
                	'\n\t\t</md-form-field>',
                    '\n\t</div>') SEPARATOR '\n')
--	INTO mysnippet
--	FROM INFORMATION_SCHEMA.COLUMNS
--	WHERE TABLE_NAME = a_tablename
--	ORDER BY ORDINAL_POSITION
--	;
*/
	SET mytext := '';
	SET mytext := concat(mytext, '<form [formGroup]="', myFormName, '" (ngSubmit)="onSubmit()" validate fxLayout="column" fxLayoutAlign="center center" novalidate>');
    SET mytext := concat(mytext, '\n<div fxFlex class="form-group">');
	SET mytext := concat(mytext, '\n\t', mysnippet);
    SET mytext := concat(mytext, '\n\t</div>');
	SET mytext := concat(mytext, '\n</form>');
	SET mytext := concat(mytext, '\n\n\t  <button md-raised-button type="submit" (click)="onSubmit()" class="loginButton">Login</button>');
	SET mytext := concat(mytext, '\n\t\t<p>Form value: {{ '', ', myFormName, '.value | json }}</p>);');
	SET mytext := concat(mytext, '\n');

	SET mytext := concat(mytext, '\nimport { Component, Input, OnChanges }       from ''@angular/core'';');
	SET mytext := concat(mytext, '\nimport { FormArray, FormBuilder} from ''@angular/forms'';');
	SET mytext := concat(mytext, '\nimport {FormGroup, FormControl, Validators} from ''@angular/forms'';');
	SET mytext := concat(mytext, '\nimport { ', objName, ' } from ''./../', a_tablename, ''';');
	SET mytext := concat(mytext, '\nimport { ', myServiceName, ' } from ''./../', a_tablename, '.service'';');
	SET mytext := concat(mytext, '\n');
	SET mytext := concat(mytext, '\n');
	SET mytext := concat(mytext, '\nconst PWD_REGEX = /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]{8,}$/;');
	SET mytext := concat(mytext, '\n');
	SET mytext := concat(mytext, '\n@Component({');
	SET mytext := concat(mytext, '\n  selector: app-', a_tablename, ''',');
	SET mytext := concat(mytext, '\n  templateUrl: ./', a_tablename, '.component.html'',''');
	SET mytext := concat(mytext, '\n  styleUrls: [''./', a_tablename, '.component.css''],''');
	SET mytext := concat(mytext, '\n}');
	SET mytext := concat(mytext, '\nexport class ', component, 'Component implements OnChanges {');
	SET mytext := concat(mytext, '\n  @Input()  ', objName, ': ', className, ';');
	SET mytext := concat(mytext, '\n');
	SET mytext := concat(mytext, '\n  ', myFormName, ': FormGroup;');
	SET mytext := concat(mytext, '\n  ', myFormName, 'Control: FormControl;');
	SET mytext := concat(mytext, '\n');
	SET mytext := concat(mytext, '\nconstructor(');
	SET mytext := concat(mytext, '\n\tprivate fb: FormBuilder');
	SET mytext := concat(mytext, '\n\t, private ', myServiceName, ': ', UCASE(LEFT(myServiceName, 1)), SUBSTRING(myServiceName, 2));
	SET mytext := concat(mytext, '\n\t) {');
	SET mytext := concat(mytext, '\n\tthis.', myFormName, 'Control = new FormControl('', [Validators.required, Validators.pattern(PWD_REGEX)]);');
	SET mytext := concat(mytext, '\n\tthis.', objName, ' = ', myServiceName, '.', objName, ';');
	SET mytext := concat(mytext, '\n\tthis.createForm();');
	SET mytext := concat(mytext, '\n\t}');
	SET mytext := concat(mytext, '\n');
	SET mytext := concat(mytext, '\ncreateForm() {');
	SET mytext := concat(mytext, '\n\tthis.', myFormName, ' = this.fb.group({');
	SET mytext := concat(mytext, '\n\t\t');
	  
	SELECT GROUP_CONCAT( concat(COLUMN_NAME, ': '
	, case 
		when lower(DATA_TYPE) in ('varchar', 'char', 'text') then '''''' 
		when lower(DATA_TYPE) in ('date', 'datetime', 'timestamp') then '''''' 
		when lower(DATA_TYPE) in ('bool','boolean') then 'false' 
        else '0'
    end) SEPARATOR '\n\t\t, ')
INTO mysnippet
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = a_tablename AND COLUMN_NAME != 'recorded_on'
ORDER BY ORDINAL_POSITION 
;


	SET mytext := concat(mytext, mysnippet);
	SET mytext := concat(mytext, '\n\t});');
	SET mytext := concat(mytext, '\n\t}');
	SET mytext := concat(mytext, '\n');
	SET mytext := concat(mytext, '\nngOnChanges() {');
	SET mytext := concat(mytext, '\n\tthis.', myFormName, '.reset({');
	SET mytext := concat(mytext, '\n\t});');
	SET mytext := concat(mytext, '\n}');
	SET mytext := concat(mytext, '\n');
	SET mytext := concat(mytext, '\nonSubmit() {');
	SET mytext := concat(mytext, '\nconsole.log(this.', myFormName, '.value);');
	SET mytext := concat(mytext, '\nthis.', myServiceName, '.post(, this.', myFormName, '.value);');
	SET mytext := concat(mytext, '\nthis.', myServiceName, '.loadDone.subscribe(isDone => { if(isDone) {  this.', objName , ' = this.', myServiceName, '.', objName, '; console.log(this.', objName, '); }});');
	SET mytext := concat(mytext, '\n}');
	SET mytext := concat(mytext, '\nrevert() { this.ngOnChanges(); }');
	SET mytext := concat(mytext, '\n}');


RETURN (mytext);

END;
$$
DELIMITER ;

