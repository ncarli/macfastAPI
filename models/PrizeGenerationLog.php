<?php

use Phalcon\Mvc\Model;

class PrizeGenerationLog extends Model{

    public function getSource(){
        return 'mf_prize_generation_log';
    }
}
