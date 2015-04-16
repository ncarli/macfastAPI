<?php

use Phalcon\Mvc\Model;

class PrizeTicket extends Model{

    public function getSource(){
        return 'mf_prize_ticket';
    }
    
    public function columnMap(){
        return Array(
            'ptic_id'                       => 'pticId',
            'ptic_issue_id'                 => 'pticIssueId',
            'ptic_prize_type'               => 'pticPrizeType',
            'ptic_lang_code'                => 'pticLangCode',
            'ptic_ticket_code1'             => 'pticTicketCode1',
            'ptic_winning_user'             => 'pticWinningUser',
            'ptic_collection_restaurant'    => 'pticCollectionRestaurant',
            'ptic_date_verified'            => 'pticDateVerified',
            'ptic_date_collected'           => 'pticDateCollected'
        );
    }
}
