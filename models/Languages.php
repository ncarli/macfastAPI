<?php

use Phalcon\Mvc\Model;

class Languages extends Model{

    public function getSource(){
        return 'mf_languages';
    }
    
    public function columnMap(){
        return Array(
            'lang_code2' => 'langCode',
            'lang_name'  => 'langName'
        );
    }
}
