DROP FUNCTION f_angular_form_create;
DELIMITER $$
CREATE FUNCTION f_angular_form_create( a_tablename text, className text) RETURNS text
  DETERMINISTIC
BEGIN
  DECLARE mytext text;
  DECLARE mysnippet text;
  DECLARE mysnippet1 text;
  DECLARE ucTable text;
  DECLARE component text;
  DECLARE mycolumn text;
  DECLARE mydatatype text;
  DECLARE myTitle text;
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

SET component = concat(className, 'Component');
SET ucTable = concat(UCASE(LEFT(LOWER(a_tablename), 1)), SUBSTRING(LOWER(a_tablename), 2));
SET objName = concat(LOWER(LEFT(className, 1)), SUBSTRING(className, 2));
SET myFormName = concat( objName,'Form');
SET myServiceName = concat( objName, 'Service');
SET myTitle = SUBSTRING_INDEX(className, '_', -1);
SET myTitle = concat(UCASE(LEFT(myTitle, 1)), SUBSTRING(LOWER(myTitle), 2));

SET mysnippet = '';


OPEN cursor_i;
lp1: LOOP
	FETCH cursor_i INTO mycolumn, mydatatype;
    IF done THEN
		LEAVE lp1;
	END IF;
	SET formControlText = CONCAT(
		'\n\t<div fxLayout="row" fxLayoutGap="15px">'
		'\n\t\t<md-form-field class="form-group" fxFlex fxFlexAlign="start center">',
		'\n\t\t\t<input mdInput type="text" formControlName="', mycolumn, '" placeholder="', mycolumn, '">',
		'\n\t\t\t<md-error *ngIf="', myFormName, '.hasError(\'required\')">', mycolumn, ' is <strong>required</strong>',
		'\n\t\t\t</md-error>',
		'\n\t\t</md-form-field>'
        '\n\t</div>'
		);

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
	SET mytext := concat(mytext, '<div *ngIf="', myServiceName, '.', objName, '.name" fxLayout="row">');
	SET mytext := concat(mytext, '\n\t<h2 class="md-title" fxFlex="50%">{{', myServiceName, '.', objName, '.name}}</h2>');
	SET mytext := concat(mytext, '\n\t<div fxFlex></div>');
	SET mytext := concat(mytext, '\n\t<div fxFlex="5%"  fxFlexAlign="end" class="id">{{', myServiceName, '.', objName, '.id}}</div>');
	SET mytext := concat(mytext, '\n\t<div fxFlex="16%" fxFlexAlign="end" class="recorded-on">{{', myServiceName, '.', objName, '.recorded_on}}</div>');
	SET mytext := concat(mytext, '\n</div>');
	SET mytext := concat(mytext, '\n');
	SET mytext := concat(mytext, '<form [formGroup]="', myFormName, '" (ngSubmit)="onSubmit()" class="form-class" fxLayout="column" fxLayoutAlign="center center" novalidate>');
	SET mytext := concat(mytext, '\n\t', mysnippet);
  	SET mytext := concat(mytext, '\n\t<div fxLayout="row" fxLayoutAlign="end end">');
	SET mytext := concat(mytext, '\n\t\t<button fxFlex="18%" md-raised-button type="submit" (click)="onSubmit()" class="submitButton">Submit</button>');
  	SET mytext := concat(mytext, '\n\t</div>');
	SET mytext := concat(mytext, '\n</form>');
	SET mytext := concat(mytext, '\n\n\t  <button md-raised-button type="submit" (click)="onSubmit()" class="loginButton">Login</button>');
	SET mytext := concat(mytext, '\n\t\t<p>Form value: {{ '', ', myFormName, '.value | json }}</p>);');
	SET mytext := concat(mytext, '\n');

	SET mytext := concat(mytext, '\nimport { Component, Input, OnChanges } from "@angular/core";');
	SET mytext := concat(mytext, '\nimport { FormArray, FormBuilder} from "@angular/forms";');
	SET mytext := concat(mytext, '\nimport {FormGroup, FormControl, Validators} from "@angular/forms";');
	SET mytext := concat(mytext, '\nimport { Routes, RouterModule, Router, ActivatedRoute } from "@angular/router";');
    SET mytext := concat(mytext, '\n');
	SET mytext := concat(mytext, '\nimport { ', objName, ' } from ''./', a_tablename, ''';');
	SET mytext := concat(mytext, '\nimport { ', myServiceName, ' } from ''./', a_tablename, '.service'';');
	SET mytext := concat(mytext, '\n');
	SET mytext := concat(mytext, '\n');
	SET mytext := concat(mytext, '\n@Component({');
	SET mytext := concat(mytext, '\n  selector: app-', a_tablename, ''',');
	SET mytext := concat(mytext, '\n  templateUrl: ''./', a_tablename, '.component.html'',''');
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
	SET mytext := concat(mytext, '\n\t, private route: ActivatedRoute');
	SET mytext := concat(mytext, '\n\t) {');
	SET mytext := concat(mytext, '\n\tthis.', myFormName, 'Control = new FormControl('', [Validators.required]);');
	SET mytext := concat(mytext, '\n\tthis.', objName, ' = ', myServiceName, '.', objName, ';');
	SET mytext := concat(mytext, '\n\tthis.createForm();');
	SET mytext := concat(mytext, '\n\t}');
	SET mytext := concat(mytext, '\n');
	SET mytext := concat(mytext, '\ncreateForm() {');
	SET mytext := concat(mytext, '\n\tthis.', myFormName, ' = this.fb.group({');
	SET mytext := concat(mytext, '\n\t\t');
	  
	SELECT GROUP_CONCAT( concat(COLUMN_NAME, ': this.', objName, '.', COLUMN_NAME) SEPARATOR '\n\t\t, ')
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
	SET mytext := concat(mytext, '\n\tsetValues() {');
	SET mytext := concat(mytext, '\n\t\tthis.', myFormName, '.setValue({');
	SELECT GROUP_CONCAT( concat(COLUMN_NAME, ': this.', myServiceName, '.', objName, '.', COLUMN_NAME) SEPARATOR '\n\t\t, ')
INTO mysnippet
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = a_tablename AND COLUMN_NAME != 'recorded_on'
ORDER BY ORDINAL_POSITION 
;
	SET mytext := concat(mytext, mysnippet);
	SET mytext := concat(mytext, '\n\t});');    
	SET mytext := concat(mytext, '\n');    
	SET mytext := concat(mytext, '\n\tonLoad( id) {');
   	SET mytext := concat(mytext, '\n\t\tthis.', myServiceName, '.get', classname, '(id);');
   	SET mytext := concat(mytext, '\n\t\tthis.', myServiceName, '.bDone.subscribe(isDone => { if(isDone) { this.setValues(); }});');
    SET mytext := concat(mytext, '\n\t}');
    SET mytext := concat(mytext, '\n');
    SET mytext := concat(mytext, '\n\tonSubmit() {');
    SET mytext := concat(mytext, '\n\t\tconsole.log(this.', myFormName, '.value);');
    SET mytext := concat(mytext, '\n\t\tthis.', myServiceName, '.post', classname, ', (this.', myFormName, '.value);');
    SET mytext := concat(mytext, '\n\t\tthis.', myServiceName, '.bDone.subscribe(isDone => { if(isDone) { this.setValues(); }});');
    SET mytext := concat(mytext, '\n\t}');
    SET mytext := concat(mytext, '\n');
	SET mytext := concat(mytext, '\nrevert() { this.ngOnChanges(); }');
	SET mytext := concat(mytext, '\n}');


RETURN (mytext);

END;
$$
DELIMITER ;
GRANT ALL ON `ccpoints`.* TO 'ccadmin'@'localhost';
