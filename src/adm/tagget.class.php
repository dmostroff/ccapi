<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tags
 *
 * @author DANO
 */
class Adm_TagGet extends Adm_TagsBase {

    public function run($dbc, $args) {
        $data = $this->helper_->getTag($dbc, $args['prefix'], $args['tag']);
        return $data;
    }

}
