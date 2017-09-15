--DROP FUNCTION f_angular_service_create;
DELIMITER $$
CREATE FUNCTION f_angular_service_create( a_tablename text, className text) RETURNS text
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
SET myServiceName = CONCAT( objName, 'Service');

SET formControlText = CONCAT('<div fxFlex class="form-group">'
                    '\n\t<div fxLayout="row">'
                	'\n\t\t<md-form-field class="form-group" fxFlex fxFlexAlign="end center">'
                	'\n\t\t\t<input mdInput type="text" formControlName="', 'COLUMN_NAME', '" placeholder="', 'COLUMN_NAME', '">',
                    '\n\t\t\t<md-error *ngIf="', myFormName, '.hasError(\'required\')">', 'COLUMN_NAME', ' is <strong>required</strong>',
                	'\n\t\t\t</md-error>',
                	'\n\t\t</md-form-field>',
                    '\n\t</div>');

SET mysnippet = '';


OPEN cursor_i;
lp1: LOOP
	FETCH cursor_i INTO mycolumn, mydatatype;
    IF done THEN
		LEAVE lp1;
	END IF;
    SET mysnippet = CONCAT( mysnippet, REPLACE( formControlText, 'COULUMN_NAME', mycolumn));
END LOOP;
CLOSE cursor_i;
    
SET mytext = CONCAT (mytext, '\nimport { Injectable } from ''@angular/core'';');
SET mytext = CONCAT (mytext, '\nimport {HttpClient, HttpErrorResponse, HttpResponse } from ''@angular/common/http'';');
SET mytext = CONCAT (mytext, '\nimport {BehaviorSubject} from ''rxjs/BehaviorSubject'';');
SET mytext = CONCAT (mytext, '\nimport {Subject} from ''rxjs/Subject'';');
SET mytext = CONCAT (mytext, '\nimport {Observable} from ''rxjs/Observable'';');
SET mytext = CONCAT (mytext, '\nimport {', className, '} from ''./cc-', className, ''';');
SET mytext = CONCAT (mytext, '\nimport ''rxjs/add/operator/map'';');
SET mytext = CONCAT (mytext, '\n');
SET mytext = CONCAT (mytext, '\nimport {CcapiResult} from ''./../ccapiresult'';');
SET mytext = CONCAT (mytext, '\n');
SET mytext = CONCAT (mytext, '\n@Injectable()');
SET mytext = CONCAT (mytext, '\nexport class ', serviceName, ' {');
SET mytext = CONCAT (mytext, '\n');
SET mytext = CONCAT (mytext, '\n  apiUrl:string;');
SET mytext = CONCAT (mytext, '\n  ', className, 'ListCount: Number;');
SET mytext = CONCAT (mytext, '\n  ', className, 'List:', className, '[];');
SET mytext = CONCAT (mytext, '\n');
SET mytext = CONCAT (mytext, '\n  public ', className, 'sListChange:BehaviorSubject<', className, '[]> = new BehaviorSubject<', className, '[]>([]);');
SET mytext = CONCAT (mytext, '\n  public bDone: Subject<boolean> = new Subject();');
SET mytext = CONCAT (mytext, '\n');
SET mytext = CONCAT (mytext, '\n  ', className, ':', className, ';');
SET mytext = CONCAT (mytext, '\n  public ', className, 'Subject:BehaviorSubject<', className, '> = new BehaviorSubject<', className, '>(new ', className, '());');
SET mytext = CONCAT (mytext, '\n');
SET mytext = CONCAT (mytext, '\n  constructor(private http:HttpClient) {');
SET mytext = CONCAT (mytext, '\n    this.apiUrl = ''http://ccapi.com/client/', className, ''';');
SET mytext = CONCAT (mytext, '\n    this.', objName, ' = new ', className, '();');
SET mytext = CONCAT (mytext, '\n    this.', objName, 'List = <', className, '[]>[];');
SET mytext = CONCAT (mytext, '\n    this.bDone = new Subject<boolean>();');
SET mytext = CONCAT (mytext, '\n  }');
SET mytext = CONCAT (mytext, '\n');
SET mytext = CONCAT (mytext, '\n  ngOnInit() {');
SET mytext = CONCAT (mytext, '\n    console.log( "', className, ' service init");');
SET mytext = CONCAT (mytext, '\n  }');
SET mytext = CONCAT (mytext, '\n  ngOnDestroy() {');
SET mytext = CONCAT (mytext, '\n    console.log( "', className, ' service destroy");');
SET mytext = CONCAT (mytext, '\n  }');
SET mytext = CONCAT (mytext, '\n');
SET mytext = CONCAT (mytext, '\n  public loadList( ) {');
SET mytext = CONCAT (mytext, '\n    this.bDone.next(false);');
SET mytext = CONCAT (mytext, '\n    return this.http.get<CcapiResult>(this.apiUrl)');
SET mytext = CONCAT (mytext, '\n      .subscribe(');
SET mytext = CONCAT (mytext, '\n        resdata => {');
SET mytext = CONCAT (mytext, '\n          this.', objName, 'List = resdata.data;');
SET mytext = CONCAT (mytext, '\n          this.', objName, 'ListCount = this.', objName, 'List.length;');
SET mytext = CONCAT (mytext, '\n          console.log( [this.', className, 'List, this.', objName, 'List.length] );');
SET mytext = CONCAT (mytext, '\n          this.bDone.next(true);');
SET mytext = CONCAT (mytext, '\n        }');
SET mytext = CONCAT (mytext, '\n        , err => {');
SET mytext = CONCAT (mytext, '\n          console.log(err);');
SET mytext = CONCAT (mytext, '\n        }');
SET mytext = CONCAT (mytext, '\n      );');
SET mytext = CONCAT (mytext, '\n  }');
SET mytext = CONCAT (mytext, '\n');
SET mytext = CONCAT (mytext, '\n  public load( ) {');
SET mytext = CONCAT (mytext, '\n    this.bDone.next(false);');
SET mytext = CONCAT (mytext, '\n    return this.http.get<CcapiResult>(this.apiUrl)');
SET mytext = CONCAT (mytext, '\n      .subscribe(');
SET mytext = CONCAT (mytext, '\n        resdata => {');
SET mytext = CONCAT (mytext, '\n          this.', objName, ' = resdata.data;');
SET mytext = CONCAT (mytext, '\n          console.log( this.', className, ');');
SET mytext = CONCAT (mytext, '\n          this.bDone.next(true);');
SET mytext = CONCAT (mytext, '\n        }');
SET mytext = CONCAT (mytext, '\n        , err => {');
SET mytext = CONCAT (mytext, '\n          console.log(err);');
SET mytext = CONCAT (mytext, '\n        }');
SET mytext = CONCAT (mytext, '\n      );');
SET mytext = CONCAT (mytext, '\n  }');
SET mytext = CONCAT (mytext, '\n');
SET mytext = CONCAT (mytext, '\n  public post( ) {');
SET mytext = CONCAT (mytext, '\n    this.bDone.next(false);');
SET mytext = CONCAT (mytext, '\n    return this.http.post<CcapiResult>(this.apiUrl, this.', objName, ')');
SET mytext = CONCAT (mytext, '\n      .subscribe(');
SET mytext = CONCAT (mytext, '\n        resdata => {');
SET mytext = CONCAT (mytext, '\n          this.', objName, ' = resdata.data;');
SET mytext = CONCAT (mytext, '\n          console.log( this.', className, ');');
SET mytext = CONCAT (mytext, '\n          this.bDone.next(true);');
SET mytext = CONCAT (mytext, '\n        }');
SET mytext = CONCAT (mytext, '\n        , err => {');
SET mytext = CONCAT (mytext, '\n          console.log(err);');
SET mytext = CONCAT (mytext, '\n        }');
SET mytext = CONCAT (mytext, '\n      );');
SET mytext = CONCAT (mytext, '\n  }');
SET mytext = CONCAT (mytext, '\n');
SET mytext = CONCAT (mytext, '\n}');
SET mytext = CONCAT(mytext, '\n');

RETURN (mytext);

END;
$$
DELIMITER ;

