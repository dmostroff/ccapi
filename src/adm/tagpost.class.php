<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of adm_tagPost
 *
 * @author DANO
 */
class Adm_TagPost extends Adm_TagsBase {

    public function run($dbc, $args) {
        $data = $this->helper_->post($dbc, $args, $this->posted_);
        $data = $this->helper_->get($dbc, $this->posted_['prefix'], $this->posted_['tag']);
        return $data;
    }

}
