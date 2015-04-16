<?php

use Phalcon\Mvc\Model;

class PrizeType extends Model{

    public function getSource(){
        return 'mf_prize_type';
    }
    
    public function columnMap(){
        return Array(
            'ptyp_id'                       => 'ptypId',
            'ptyp_title'                    => 'ptypTitle',
            'ptyp_title_message_lang_code'  => 'ptypTitleMessageLangCode',
            'ptyp_qr_text'                  => 'ptypQrText',
            'ptyp_run_quantity'             => 'ptypRunQuantity',
            'ptyp_collection_method'        => 'ptypCollectionMethod',
            'ptyp_run_generated'            => 'ptypRunGenerated'
        );
    }
}
