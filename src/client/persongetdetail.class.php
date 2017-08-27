<?php
class Client_PersonGetDetail extends Base_dblayer {
    protected $clientAddressHelper_ = null;

    public function __construct() {
        $this->helper_ = new Client_PersonHelper();
        $this->clientAddressHelper_ = new Client_AddressHelper();
        $this->clientCcHelper_ = new Client_CcHelper();
    }

    public function run($args) {
        $dbc = $this->connect();
        $rows = $this->helper_->get($dbc, $args);
        foreach( $rows as $r) {
            $data = $r;
            $data['address'] = $this->clientAddressHelper_->getByFk($dbc, [$args[$this->helper_->idcol_]]);
            $data['cc'] = $this->clientCcHelper_->getByFk($dbc, [$args[$this->helper_->idcol_]]);
            $rows;
        }
        return $data;
    }

}
?>
