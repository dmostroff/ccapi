<?php

class Meta_AngularCreateForm extends Base_dblayer {

    public function invoke($request, $response, $args) {
        $dbc = $this->connect();
        $tablename = $args['table'];
        $res = "########### BEGIN " . date("Y-m-d h:i:s") . " ###############\n";
        // $baseDir = '/var/www/ccapi';
        try {
            $idCols = [];
            $cols = [];
            $insertCols = [];
            $autoIncCols = [];
//            $sql =<<<ESQL
//SELECT GROUP_CONCAT( concat(COLUMN_NAME, ': '
//	, case 
//		when lower(DATA_TYPE) in ('varchar', 'char', 'text') then 'string' 
//		when lower(DATA_TYPE) in ('date', 'datetime', 'timestamp') then 'date' 
//		when lower(DATA_TYPE) in ('bool','boolean') then 'boolean' 
//        else 'number'
//    end
//    , ';') SEPARATOR '\n')
//INTO mysnippet
//FROM INFORMATION_SCHEMA.COLUMNS
//WHERE TABLE_NAME = 'adm_tags'
//ORDER BY ORDINAL_POSITION 
//;ESQL;
            $sql = 'select ccpoints.f_angular_form_create(?, ?) as form';
            $rows = dbconn::exec($dbc, $sql, [$tablename, $args['classname']]);
            foreach ($rows as $r) {
                $res .= $r['form'];
            }
        } catch (Exception $ex) {
            $res .= sprintf("%s %s] %s", $ex->getFile(), $ex->getLine(), $ex->getMessage());
            error_log(sprintf("%s %s] %s", $ex->getFile(), $ex->getLine(), $ex->getMessage()));
            $rc = -1;
            $msg = $ex->getMessage();
            $retStatus = 400;
        }
        // $ret = ['res' => ['rc' => $rc, 'msg' => $msg], 'data' => $data];
        $r = $response->withStatus(200);
        $r = $r->withHeader('Content-type', 'text/plain');
        $r->getBody()->write($res);
        return $r;
    }

}
