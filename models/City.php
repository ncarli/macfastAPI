<?php

use Phalcon\Mvc\Model;

class City extends Model{

    public function getSource(){
        return 'mf_city';
    }
    
    public function columnMap(){
        return Array(
            'city_id'         => 'cityId',
            'city_cp'         => 'cityCp',
            'city_name'       => 'cityName',
            'city_coordX'     => 'cityCoordX',
            'city_coordY'     => 'cityCoordY',
            'city_country'    => 'cityCountry'
        );
    }
}
