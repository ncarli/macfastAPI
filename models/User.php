<?php

use Phalcon\Mvc\Model;

class User extends Model{

    public function getSource(){
        return 'mf_user';
    }
    
    public function columnMap(){
        return Array(
            'user_id'                   => 'userId',
            'user_login'                => 'userLogin',
            'user_mdp'                  => 'userMdp',
            'user_name'                 => 'userName',
            'user_firstname'            => 'userFirstName',
            'user_email'                => 'userEmail',
            'user_tel_fixed'            => 'userTelFixed',
            'user_tel_mob'              => 'userTelMob',
            'user_country'              => 'userCountry',
            'user_titre'                => 'userTitre',
            'user_date_birth'           => 'userDateBirth',
            'user_gender'               => 'userGender',
            'user_status'               => 'userStatus',
            'user_date_joined'          => 'userDateJoined',
            'user_account_active'       => 'userAccountActive',
            'user_login_locked'         => 'userLoginLocked',
            'user_preferred_restaurant' => 'userPreferredRestaurant',
            'user_preferred_language'   => 'userPreferredLanguage',
            'user_okay_marketing_int'   => 'userOkayMarketingInt',
            'user_okay_marketing_ext'   => 'userOkayMarketingExt',
            'user_address1'             => 'userAddress1',
            'user_address2'             => 'userAddress2',
            'user_address3'             => 'userAddress3',
            'user_city'                 => 'userCity'
        );
    }
}
