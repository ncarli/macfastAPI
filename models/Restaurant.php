<?php

use Phalcon\Mvc\Model;

class Restaurant extends Model{

    public function getSource(){
        return 'mf_restaurant';
    }
    
    public function columnMap(){
        return Array(
            'rest_id'           => 'restId',
            'rest_name'         => 'restName',
            'rest_address1'     => 'restAdr1',
            'rest_address2'     => 'restAdr2',
            'rest_address3'     => 'restAdr3',
            'rest_city'         => 'restCity',
            'rest_tel'          => 'restTel',
            'rest_coordX'       => 'restCoordX',
            'rest_coordY'       => 'restCoordY',
            'rest_country_code' => 'restCountryCode'
        );
    }
}
