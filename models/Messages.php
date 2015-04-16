<?php

use Phalcon\Mvc\Model;

class Messages extends Model{

    public function getSource(){
        return 'mf_messages';
    }
}
