<?php

use Phalcon\Mvc\Model;

class Pays extends Model{
    
//    protected $codeIso;
//    protected $paysName;
//    protected $prefixTel;
//    protected $dateFormatShort;
//    protected $dateFormatMedium;
//    protected $dateFormatLong;
//    protected $timeOffset;
//    protected $timeFormatShort;
//    protected $timeFormatLong;
//    protected $currencyCode;
//    protected $currencyName;
//    protected $currencySymbol;
//    protected $currencyPlacement;
//    protected $currencyFormat;
    
    public function getSource(){
        return 'mf_pays';
    }
    
    public function columnMap(){
        return Array(
            'pays_code_iso2'            => 'codeIso',
            'pays_name'                 => 'paysName',
            'pays_prefix_tel'           => 'prefixTel',
            'pays_date_format_short'    => 'dateFormatShort',
            'pays_date_format_medium'   => 'dateFormatMedium',
            'pays_date_format_long'     => 'dateFormatLong',
            'pays_time_offset'          => 'timeOffset',
            'pays_time_format_short'    => 'timeFormatShort',
            'pays_time_format_long'     => 'timeFormatLong',
            'pays_currency_code'        => 'currencyCode',
            'pays_currency_name'        => 'currencyName',
            'pays_currency_symbol'      => 'currencySymbol',
            'pays_currency_placement'   => 'currencyPlacement',
            'pays_currency_format'      => 'currencyFormat'
        );
    }
    
    public function validation(){
    
    }
}
