-- DROP FUNCTION f_angular_service_create;
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
  DECLARE serviceName text;
  

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
SET serviceName = CONCAT( objName, 'Service');
SET mytext = '';

SET mytext = concat( mytext, '\nimport { Injectable } from ''@angular/core'';');
SET mytext = concat( mytext, '\nimport {HttpClient, HttpErrorResponse, HttpResponse } from ''@angular/common/http'';');
SET mytext = concat( mytext, '\nimport {BehaviorSubject} from ''rxjs/BehaviorSubject'';');
SET mytext = concat( mytext, '\nimport {Subject} from ''rxjs/Subject'';');
SET mytext = concat( mytext, '\nimport {Observable} from ''rxjs/Observable'';');
SET mytext = concat( mytext, '\nimport {', className, '} from ''./', LOWER(className), ''';');
SET mytext = concat( mytext, '\nimport ''rxjs/add/operator/map'';');
SET mytext = concat( mytext, '\n');
SET mytext = concat( mytext, '\nimport {CcapiResult} from ''./../ccapiresult'';');
SET mytext = concat( mytext, '\n');
SET mytext = concat( mytext, '\n@Injectable()');
SET mytext = concat( mytext, '\nexport class ', serviceName, ' {');
SET mytext = concat( mytext, '\n');
SET mytext = concat( mytext, '\n  apiUrl:string;');
SET mytext = concat( mytext, '\n  ', className, 'ListCount: Number;');
SET mytext = concat( mytext, '\n  ', className, 'List:', className, '[];');
SET mytext = concat( mytext, '\n');
SET mytext = concat( mytext, '\n  public ', className, 'sListChange:BehaviorSubject<', className, '[]> = new BehaviorSubject<', className, '[]>([]);');
SET mytext = concat( mytext, '\n  public bDone: Subject<boolean> = new Subject();');
SET mytext = concat( mytext, '\n');
SET mytext = concat( mytext, '\n  ', className, ':', className, ';');
SET mytext = concat( mytext, '\n  public ', className, 'Subject:BehaviorSubject<', className, '> = new BehaviorSubject<', className, '>(new ', className, '());');
SET mytext = concat( mytext, '\n');
SET mytext = concat( mytext, '\n  constructor(private http:HttpClient) {');
SET mytext = concat( mytext, '\n    this.apiUrl = ''http://ccapi.com/client/', className, ''';');
SET mytext = concat( mytext, '\n    this.', objName, ' = new ', className, '();');
SET mytext = concat( mytext, '\n    this.', objName, 'List = <', className, '[]>[];');
SET mytext = concat( mytext, '\n    this.bDone = new Subject<boolean>();');
SET mytext = concat( mytext, '\n  }');
SET mytext = concat( mytext, '\n');
SET mytext = concat( mytext, '\n  ngOnInit() {');
SET mytext = concat( mytext, '\n    console.log( "', className, ' service init");');
SET mytext = concat( mytext, '\n  }');
SET mytext = concat( mytext, '\n  ngOnDestroy() {');
SET mytext = concat( mytext, '\n    console.log( "', className, ' service destroy");');
SET mytext = concat( mytext, '\n  }');
SET mytext = concat( mytext, '\n');
SET mytext = concat( mytext, '\n  public loadList( ) {');
SET mytext = concat( mytext, '\n    this.bDone.next(false);');
SET mytext = concat( mytext, '\n    return this.http.get<CcapiResult>(this.apiUrl)');
SET mytext = concat( mytext, '\n      .subscribe(');
SET mytext = concat( mytext, '\n        resdata => {');
SET mytext = concat( mytext, '\n          this.', objName, 'List = resdata.data;');
SET mytext = concat( mytext, '\n          this.', objName, 'ListCount = this.', objName, 'List.length;');
SET mytext = concat( mytext, '\n          console.log( [this.', className, 'List, this.', objName, 'List.length] );');
SET mytext = concat( mytext, '\n          this.bDone.next(true);');
SET mytext = concat( mytext, '\n        }');
SET mytext = concat( mytext, '\n        , err => {');
SET mytext = concat( mytext, '\n          console.log(err);');
SET mytext = concat( mytext, '\n        }');
SET mytext = concat( mytext, '\n      );');
SET mytext = concat( mytext, '\n  }');
SET mytext = concat( mytext, '\n');
SET mytext = concat( mytext, '\n  public load( ) {');
SET mytext = concat( mytext, '\n    this.bDone.next(false);');
SET mytext = concat( mytext, '\n    return this.http.get<CcapiResult>(this.apiUrl)');
SET mytext = concat( mytext, '\n      .subscribe(');
SET mytext = concat( mytext, '\n        resdata => {');
SET mytext = concat( mytext, '\n          this.', objName, '.set(resdata.data);');
SET mytext = concat( mytext, '\n          console.log( this.', className, ');');
SET mytext = concat( mytext, '\n          this.bDone.next(true);');
SET mytext = concat( mytext, '\n        }');
SET mytext = concat( mytext, '\n        , err => {');
SET mytext = concat( mytext, '\n          console.log(err);');
SET mytext = concat( mytext, '\n        }');
SET mytext = concat( mytext, '\n      );');
SET mytext = concat( mytext, '\n  }');
SET mytext = concat( mytext, '\n');
SET mytext = concat( mytext, '\n  public post( ) {');
SET mytext = concat( mytext, '\n    this.bDone.next(false);');
SET mytext = concat( mytext, '\n    return this.http.post<CcapiResult>(this.apiUrl, this.', objName, ')');
SET mytext = concat( mytext, '\n      .subscribe(');
SET mytext = concat( mytext, '\n        resdata => {');
SET mytext = concat( mytext, '\n          this.', objName, ' = resdata.data;');
SET mytext = concat( mytext, '\n          console.log( this.', className, ');');
SET mytext = concat( mytext, '\n          this.bDone.next(true);');
SET mytext = concat( mytext, '\n        }');
SET mytext = concat( mytext, '\n        , err => {');
SET mytext = concat( mytext, '\n          console.log(err);');
SET mytext = concat( mytext, '\n        }');
SET mytext = concat( mytext, '\n      );');
SET mytext = concat( mytext, '\n  }');
SET mytext = concat( mytext, '\n');
SET mytext = concat( mytext, '\n}');
SET mytext = concat( mytext, '\n');

RETURN (mytext);

END;
$$
DELIMITER ;

