<?php

use Phalcon\Mvc\Model;

class Pays extends Model{
    
    public function getSource(){
        return 'mf_pays';
    }
    
    public function columnMap(){
        return Array(
            'pays_code_iso2'            => 'paysCodeIso',
            'pays_name'                 => 'paysName',
            'pays_prefix_tel'           => 'paysPrefixTel',
            'pays_date_format_short'    => 'paysDateFormatShort',
            'pays_date_format_medium'   => 'paysDateFormatMedium',
            'pays_date_format_long'     => 'paysDateFormatLong',
            'pays_time_offset'          => 'paysTimeOffset',
            'pays_time_format_short'    => 'paysTimeFormatShort',
            'pays_time_format_long'     => 'paysTimeFormatLong',
            'pays_currency_code'        => 'paysCurrencyCode',
            'pays_currency_name'        => 'paysCurrencyName',
            'pays_currency_symbol'      => 'paysCurrencySymbol',
            'pays_currency_placement'   => 'paysCurrencyPlacement',
            'pays_currency_format'      => 'paysCurrencyFormat'
        );
    }
    
    public function validation(){
    
    }
}
