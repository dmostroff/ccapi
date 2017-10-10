DROP FUNCTION f_angular_form_create;
DELIMITER $$
CREATE FUNCTION f_angular_form_create( a_tablename text, className text) RETURNS text
  DETERMINISTIC
BEGIN
  DECLARE mytext text;
  DECLARE mysnippet text;
  DECLARE mydisplay text;
  DECLARE mysnippet1 text;
  DECLARE ucTable text;
  DECLARE idCol text;
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

SELECT COLUMN_NAME 
INTO idCol
FROM information_schema.columns 
WHERE table_name = 'client_address' AND column_key = 'PRI'
;

SET mysnippet = '';
SET mysnippet1 = '';    

OPEN cursor_i;
lp1: LOOP
	FETCH cursor_i INTO mycolumn, mydatatype;
    IF done THEN
		LEAVE lp1;
	END IF;
	SET formControlText = CONCAT(
		'\n\t<div',
        CASE WHEN mycolumn = idCol THEN ' [hidden]="true"' ELSE '' END,
        ' fxLayout="row" fxLayoutAlign="start center" fxLayoutGap="15px">',
		'\n\t\t<md-form-field class="form-group" fxFlex>',
		'\n\t\t\t<input mdInput type="text" formControlName="', mycolumn, '" placeholder="', mycolumn, '">',
		'\n\t\t\t<md-error *ngIf="', myFormName, '.hasError(\'required\')">', mycolumn, ' is <strong>required</strong>',
		'\n\t\t\t</md-error>',
		'\n\t\t</md-form-field>'
        '\n\t</div>'
		);

	SET mydisplay = CONCAT(
		'\n\t\t<div fxLayout="row" fxLayoutAlign="start center" fxLayoutGap="15px">'
		,'\n\t\t\t<div fxFlex'
        , CASE WHEN mycolumn = 'recorded_on' THEN ' fxFlexAlign="end" class="recorded_on"' ELSE '' END
        ,'><label>'
        , concat(UCASE(LEFT(LOWER(mycolumn), 1)), SUBSTRING(LOWER(mycolumn), 2))
        , ':</label>{{', objName, '.', mycolumn, '}}</div>'
        ,'\n\t\t</div>'
		);

    SET mysnippet1 = CONCAT( mysnippet1, mydisplay);
    
	IF mycolumn NOT IN ( 'recorded_on') THEN
		SET mysnippet = CONCAT( mysnippet, REPLACE( formControlText, 'COULUMN_NAME', mycolumn));
	END IF;
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
	SET mytext := concat(mytext, '\n\t<h2 class="md-title" fxFlex="50%">{{title}}</h2>');
	SET mytext := concat(mytext, '\n<div *ngIf="!isEdit && ', objName, '.', idCol, '" fxLayout="column">');
	SET mytext := concat(mytext, '\n\t<div fxFlex>');
	SET mytext := concat(mytext, mysnippet1);
    SET mytext := concat(mytext, '\n\t</div>');
	SET mytext := concat(mytext, '\n</div>');
	SET mytext := concat(mytext, '\n');
	SET mytext := concat(mytext, '<h1 md-dialog-title class="primary-color">{{title}}</h1>');
	SET mytext := concat(mytext, '\n<form [formGroup]="', myFormName, '" (ngSubmit)="onSubmit()" class="form-class" fxLayout="column" fxLayoutAlign="center center" novalidate>');
	SET mytext := concat(mytext, '\n\t<div fxFlex fxFlexFill>');
	SET mytext := concat(mytext, '\n\t<md-dialog-content class="accent-color"">');
	SET mytext := concat(mytext,  mysnippet);
	SET mytext := concat(mytext, '\n\t</md-dialog-content>');
	SET mytext := concat(mytext, '\n\t</div>');
	SET mytext := concat(mytext, '\n\t<div fxFlex fxFlexFill>');
	SET mytext := concat(mytext, '\n\t<md-dialog-actions>');
	SET mytext := concat(mytext, '\n\t\t<div fxLayout="row" fxLayoutAlign="center stretch" fxLayoutGap="20px">');
	SET mytext := concat(mytext, '\n\t\t\t<button fxFlex="18%" md-raised-button type="submit" color="primary" class="submitButton"><md-icon>done</md-icon>Submit</button>');
	SET mytext := concat(mytext, '\n\t\t\t<button fxFlex="18%" md-raised-button (click)="onClickCancel()"><md-icon>cancel</md-icon> Cancel</button>');
	SET mytext := concat(mytext, '\n\t</div>');
	SET mytext := concat(mytext, '\n\t</md-dialog-actions>');
	SET mytext := concat(mytext, '\n\t</div>');
	SET mytext := concat(mytext, '\n<p>Form value: {{', myFormName, '.value | json }}</p>');
	SET mytext := concat(mytext, '\n</form>');
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
-- GRANT ALL ON `ccpoints`.* TO 'ccadmin'@'localhost';
