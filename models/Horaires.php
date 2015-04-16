<?php

use Phalcon\Mvc\Model;

class Horaires extends Model{

    public function getSource(){
        return 'mf_horaires';
    }
}
