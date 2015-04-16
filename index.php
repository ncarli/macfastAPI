<?php

use Phalcon\Db\Adapter\Pdo\Mysql as MysqlAdapter;
use Phalcon\Mvc\Micro;
use Phalcon\DI\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Http\Response;

/*
 * Le $loader est nécessaire pour charger correctement le models (Robots en l'occurence)
 */

$loader = new Loader;

$loader->registerDirs(array(
    __DIR__ . '/models/'
))->register();

$di = new FactoryDefault;

//Set up the database service
$di->set('db', function(){
    return new MysqlAdapter(array(
        "host"     => "nick.dev",
        "username" => "root",
        "password" => "root",
        "dbname"   => "macfast"
    ));
});

/*
 * Ne pas oublier d'ajouter $di lors de l'instanciation de la classe Micro
 */

$app = new Micro($di);

/**
 * PAYS
 */

// get all pays
$app->get('/api/pays', function() use($app) {
    $pays = $app->modelsManager->createBuilder()
                ->from('pays')
                ->orderBy('pays.paysName')
                ->getQuery()
                ->execute();
    
    $data = Array();
    foreach($pays as $p) {
        $data[] = Array(
            'codeIso'  => $p->codeIso,
            'paysName' => $p->paysName
        );
    }
    echo json_encode($data);
});

// Searches for pays with $codeiso
$app->get('/api/pays/search/{codeiso}', function($codeiso) use ($app) {
    $pays = $app->modelsManager->createBuilder()
                ->from('pays')
                ->where('pays.codeIso = "'.$codeiso.'"')
                ->getQuery()
                ->execute();

    $data = Array();
    foreach($pays as $p){
        $data[] = array(
            'code_iso' => $p->codeIso,
            'name' => $p->paysName,
        );
    }
    echo json_encode($data);
});

/**
 * RESTAURANT
 */

// get all pays
$app->get('/api/restaurant', function() use($app) {
    $pays = $app->modelsManager->createBuilder()
                ->columns(array('restaurant.*', 'city.*'))
                ->from('restaurant')
                ->join('city', 'restaurant.restCity = city.cityId')
                ->orderBy('restaurant.restName')
                ->getQuery()
                ->execute();
        
    $data = Array();
    foreach($pays as $p) {
        $data[] = Array(
            'restId'            => $p->restaurant->restId,
            'restName'          => $p->restaurant->restName,
            'restAdr1'          => $p->restaurant->restAdr1,
            'restAdr2'          => $p->restaurant->restAdr2,
            'restAdr3'          => $p->restaurant->restAdr3,
            'restCity'          => $p->restaurant->restCity,
            'restTel'           => $p->restaurant->restTel,
            'restCoordX'        => $p->restaurant->restCoordX,
            'restCoordY'        => $p->restaurant->restCoordY,
            'restCountryCode'   => $p->restaurant->restCountryCode,
            'cityCp'            => $p->city->cityCp,
            'cityName'          => $p->city->cityName
        );
    }
    echo json_encode($data);
});

// get for restaurant with city cp or city name
$app->get('/api/restaurant/search/{param}', function($param) use ($app) {

    $pays = $app->modelsManager->createBuilder()
                ->columns(array('restaurant.*', 'city.*'))
                ->from('restaurant')
                ->join('city', 'restaurant.restCity = city.cityId')
                ->where('city.cityCp ="'.$param.'" OR city.cityName like "%'.$param.'%"')
                ->orderBy('restaurant.restName')
                ->getQuery()
                ->execute();
        
    $data = Array();
    foreach($pays as $p){
        $data[] = Array(
            'restId'            => $p->restaurant->restId,
            'restName'          => $p->restaurant->restName,
            'restAdr1'          => $p->restaurant->restAdr1,
            'restAdr2'          => $p->restaurant->restAdr2,
            'restAdr3'          => $p->restaurant->restAdr3,
            'restCity'          => $p->restaurant->restCity,
            'restTel'           => $p->restaurant->restTel,
            'restCoordX'        => $p->restaurant->restCoordX,
            'restCoordY'        => $p->restaurant->restCoordY,
            'restCountryCode'   => $p->restaurant->restCountryCode,
            'cityCp'            => $p->city->cityCp,
            'cityName'          => $p->city->cityName
        );
    }
    echo json_encode($data);

});

// get distance






/**
 * USERS
 */

// Add a new user
$app->post('/api/add/user', function() use ($app) {

    $user = $app->request->getJsonRawBody();

    $phql = "INSERT INTO User VALUES (:userId:, :userLogin:, :userMdp:, :userName:, :userFirstName:, :userEmail:, "
                                    . ":userTelFixed:, :userTelMob:, :userCountry:, :userTitre:, :userDateBirth:,"
                                    . ":userGender:, :userStatus:, :userDateJoined:, :userAccountActive:, :userAccountLocked:,"
                                    . ":userPreferredRestaurant:, :userPreferredLanguage:, :userOkayMarketingInt:, :userOkayMarketingExt:,"
                                    . ":userAddress1:, :userAddress2:, :userAddress3:, :userCity:)";

    $status = $app->modelsManager->executeQuery($phql, array(
        'userId'                    => NULL,
        'userLogin'                 => $user->userLogin,
        'userMdp'                   => $user->userMdp,
        'userName'                  => $user->userName,
        'userFirstName'             => $user->userFirstName,
        'userEmail'                 => $user->userEmail,
        'userTelFixed'              => $user->userTelFixed,
        'userTelMob'                => $user->userTelMob,
        'userCountry'               => $user->userCountry,
        'userTitre'                 => $user->userTitre,
        'userDateBirth'             => $user->userDateBirth,
        'userGender'                => $user->userGender,
        'userStatus'                => $user->userStatus,
        'userDateJoined'            => $user->userDateJoined,
        'userAccountActive'         => $user->userAccountActive,
        'userAccountLocked'         => $user->userAccountLocked,
        'userPreferredRestaurant'   => $user->userPreferredRestaurant,
        'userPreferredLanguage'     => $user->userPreferredLanguage,
        'userOkayMarketingInt'      => $user->userOkayMarketingInt,
        'userOkayMarketingExt'      => $user->userOkayMarketingExt,
        'userAddress1'              => $user->userAddress1,
        'userAddress2'              => $user->userAddress2,
        'userAddress3'              => $user->userAddress3,
        'userCity'                  => $user->userCity
    ));

    //Create a response
    $response = new Response;

    //Check if the insertion was successful
    if ($status->success() == true) {

        $response->setStatusCode(201, "Created");

        $user->id = $status->getModel()->userId;

        $response->setJsonContent(array('status' => 'OK', 'data' => $user));

    } else {

        //Change the HTTP status
        $response->setStatusCode(409, "Conflict");

        //Send errors to the client
        $errors = array();
        foreach ($status->getMessages() as $message) {
            $errors[] = $message->getMessage();
        }

        $response->setJsonContent(array('status' => 'ERROR', 'messages' => $errors));
    }
    return $response;
});


//Updates user based on primary key
$app->put('/api/update/user/{id:[0-9]+}', function($id) use($app) {

    $user = $app->request->getJsonRawBody();

    $phql = "UPDATE User SET userMdp                    = :userMdp:, "
                            . "userName                 = :userName:, "
                            . "userFirstName            = :userFirstName:, "
                            . "userEmail                = :userEmail:,"
                            . "userTelFixed             = :userTelFixed:, "
                            . "userTelMob               = :userTelMob:, "
                            . "userCountry              = :userCountry:, "
                            . "userTitre                = :userTitre:,"
                            . "userGender               = :userGender:, "
                            . "userStatus               = :userStatus:, "
                            . "userAccountActive        = :userAccountActive:, "
                            . "userLoginLocked          = :userLoginLocked:,"
                            . "userPreferredRestaurant  = :userPreferredRestaurant:, "
                            . "userPreferredLanguage    = :userPreferredLanguage:,"
                            . "userAddress1             = :userAddress1:, "
                            . "userAddress2             = :userAddress2:, "
                            . "userAddress3             = :userAddress3:, "
                            . "userCity                 = :userCity:"
                            . " WHERE userId = :id:";
    
    $status = $app->modelsManager->executeQuery($phql, array(
        'userId'                    => $id,
        'userMdp'                   => $user->userMdp,
        'userName'                  => $user->userName,
        'userFirstName'             => $user->userFirstName,
        'userEmail'                 => $user->userEmail,
        'userTelFixed'              => $user->userTelFixed,
        'userTelMob'                => $user->userTelMob,
        'userCountry'               => $user->userCountry,
        'userTitre'                 => $user->userTitre,
        'userGender'                => $user->userGender,
        'userStatus'                => $user->userStatus,
        'userAccountActive'         => $user->userAccountActive,
        'userLoginLocked'           => $user->userLoginLocked,
        'userPreferredRestaurant'   => $user->userPreferredRestaurant,
        'userPreferredLanguage'     => $user->userPreferredLanguage,
        'userAddress1'              => $user->userAddress1,
        'userAddress2'              => $user->userAddress2,
        'userAddress3'              => $user->userAddress3,
        'userCity'                  => $user->userCity
    ));

    //Create a response
    $response = new Response;

    //Check if the insertion was successful
    if ($status->success() == true) {
        $response->setJsonContent(array('status' => 'OK', 'data' => $user));
    } else {

        //Change the HTTP status
        $response->setStatusCode(409, "Conflict");

        $errors = array();
        foreach ($status->getMessages() as $message) {
            $errors[] = $message->getMessage();
        }

        $response->setJsonContent(array('status' => 'ERROR', 'messages' => $errors));
    }

    return $response;
});

//////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * TICKETS
 */

$app->get('/api/ticket', function() use($app) {
    $pays = $app->modelsManager->createBuilder()
                ->columns(array('prizeTicket.*', 'prizeType.*'))
                ->from('prizeTicket')
                ->join('prizeType', 'prizeTicket.pticPrizeType = prizeType.ptypId')
                ->orderBy('prizeTicket.pticId')
                ->getQuery()
                ->execute();
        
    $data = Array();
    foreach($pays as $p) {
        $data[] = Array(
            'pticId'                        => $p->prizeTicket->pticId,
            'pticTicketCode1'               => $p->prizeTicket->pticTicketCode1,
            'pticWinningUser'               => $p->prizeTicket->pticWinningUser,
            'pticCollectionRestaurant'      => $p->prizeTicket->pticCollectionRestaurant,
            'pticDateVerified'              => $p->prizeTicket->pticDateVerified,
            'pticDateCollected'             => $p->prizeTicket->pticDateCollected,
            'ptypTitle'                     => $p->prizeType->ptypTitle
        );
    }
    echo json_encode($data);
});

//////////////////////////////////////////////////////////////////////////////////////////////////////////////

//Retrieves robots based on primary key
$app->get('/api/robots/{id:[0-9]+}', function($id) use ($app) {

    $phql = "SELECT * FROM Robots WHERE id = :id:";
    $robot = $app->modelsManager->executeQuery($phql, array(
        'id' => $id
    ))->getFirst();

    //Create a response
    $response = new Response;

    if ($robot == false) {
        $response->setJsonContent(array('status' => 'NOT-FOUND'));
    } else {
        $response->setJsonContent(array(
            'status' => 'FOUND',
            'data' => array(
                'id' => $robot->id,
                'name' => $robot->name,
                'type' => $robot->type,
                'year' => $robot->year
            )
        ));
    }

    return $response;
});

//Updates robots based on primary key
$app->put('/api/robots/{id:[0-9]+}', function($id) use($app) {

    $robot = $app->request->getJsonRawBody();

    $phql = "UPDATE Robots SET name = :name:, type = :type:, year = :year: WHERE id = :id:";
    $status = $app->modelsManager->executeQuery($phql, array(
        'id' => $id,
        'name' => $robot->name,
        'type' => $robot->type,
        'year' => $robot->year
    ));

    //Create a response
    $response = new Response;

    //Check if the insertion was successful
    if ($status->success() == true) {
        $response->setJsonContent(array('status' => 'OK'));
    } else {

        //Change the HTTP status
        $response->setStatusCode(409, "Conflict");

        $errors = array();
        foreach ($status->getMessages() as $message) {
            $errors[] = $message->getMessage();
        }

        $response->setJsonContent(array('status' => 'ERROR', 'messages' => $errors));
    }

    return $response;
});

//Deletes robots based on primary key
$app->delete('/api/robots/{id:[0-9]+}', function($id) use ($app) {

    $phql = "DELETE FROM Robots WHERE id = :id:";
    $status = $app->modelsManager->executeQuery($phql, array(
        'id' => $id
    ));

    //Create a response
    $response = new Response;

    if ($status->success() == true) {
        $response->setJsonContent(array('status' => 'OK'));
    } else {

        //Change the HTTP status
        $response->setStatusCode(409, "Conflict");

        $errors = array();
        foreach ($status->getMessages() as $message) {
            $errors[] = $message->getMessage();
        }

        $response->setJsonContent(array('status' => 'ERROR', 'messages' => $errors));

    }

    return $response;
});

$app->handle();
