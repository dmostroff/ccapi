<?php

class Meta_PostDataCreate extends Base_dblayer {

    public function invoke($request, $response, $args) {
        $dbc = $this->connect();
        $tablename = $args['table'];
        $baseDir = '/home/DANO/projects/ccapi';
        $scriptsDir = $baseDir . '/scripts';
        $curlFile = "{$scriptsDir}/metaPostDataCreate.sh";
        $res = "########### BEGIN " . date("Y-m-d h:i:s") . " ###############\n";
        // $baseDir = '/var/www/ccapi';
        try {
            $idCols = [];
            $cols = [];
            $insertCols = [];
            $autoIncCols = [];
            $rows = dbconn::exec($dbc, "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'ccpoints' ORDER BY TABLE_NAME");
            foreach ($rows as $r) {
                $tablename = $r['TABLE_NAME'];
                $createFile = "{$scriptsDir}/{$tablename}_create.sh";
                $res .= "echo '########### BEGIN " . date("Y-m-d h:i:s") . " ###############'>{$createFile}\n";
                $res .= "curl http://ccapi.com/meta/{$tablename}>>{$createFile}\n";
                $res .= "{$createFile}\n\n";
            }
        } catch (Exception $ex) {
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
