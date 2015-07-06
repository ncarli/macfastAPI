<?php

use Phalcon\Db\Adapter\Pdo\Mysql as MysqlAdapter;
use Phalcon\Mvc\Micro;
use Phalcon\DI\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Http\Response;

/*
 * $loader is necessary for load corectly the models
 */

$loader = new Loader;

$loader->registerDirs(array(
    __DIR__ . '/models/'
))->register();

$di = new FactoryDefault;

/**
 * Set up the database service
 */
$di->set('db', function(){
    return new MysqlAdapter(array(
        "host"     => "nick.dev",
        "username" => "root",
        "password" => "root",
        "dbname"   => "macfast"
    ));
});

/*
 * Don't forget to add $di before instanciation of the Micro class
 */

$app = new Micro($di);

/**
 * CUSTOM QUERY
 */

$app->get('/api/customquery/{param}', function($param) use($app) {

    $phql = $param;
    $query = $app->modelsManager->executeQuery($phql, array(
        'id' => $id
    ));

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

/**
 * COUNT
 */

$app->get('/api/count/user', function() use($app) {
    $total = $app->modelsManager->createBuilder()
                ->columns('COUNT(userId) as totalUser')
                ->from('User')
                ->getQuery()
                ->execute();
        
    $data = 0;
    foreach($total as $t)
        $data = $t->totalUser;
    
    echo json_encode($data);
});

$app->get('/api/count/ticket', function() use($app) {
    $total = $app->modelsManager->createBuilder()
                ->columns('COUNT(pticId) as totalTicket')
                ->from('PrizeTicket')
                ->where('pticWinningUser IS NULL')
                ->getQuery()
                ->execute();
        
    $data = 0;
    foreach($total as $t)
        $data = $t->totalTicket;
    
    echo json_encode($data);
});

$app->get('/api/count/winningTicket', function() use($app) {
    $total = $app->modelsManager->createBuilder()
                ->columns('COUNT(pticId) as totalWinningTicket')
                ->from('PrizeTicket')
                ->where('pticWinningUser IS NOT NULL')
                ->getQuery()
                ->execute();
        
    $data = 0;
    foreach($total as $t)
        $data = $t->totalWinningTicket;
    
    echo json_encode($data);
});

$app->get('/api/count/restaurant', function() use($app) {
    $total = $app->modelsManager->createBuilder()
                ->columns('COUNT(restId) as totalRest')
                ->from('Restaurant')
                ->getQuery()
                ->execute();
        
    $data = 0;
    foreach($total as $t)
        $data = $t->totalRest;
    
    echo json_encode($data);
});

$app->get('/api/count/maxLocalite', function() use($app) {
    $total = $app->modelsManager->createBuilder()
                ->columns(array('count(User.userCity) as NbVille', 'City.cityName'))
                ->from('User')
                ->join('City', 'User.userCity = City.cityId')
                ->groupBy(Array('User.userCity'))
                ->orderBy('NbVille DESC')
                ->limit(1)
                ->getQuery()
                ->execute();
        
    $data = Array();
    foreach($total as $t)
        $data[] = Array(
            'cityName'  => $t->cityName,
            'nbVille'   => $t->NbVille);
    
    echo json_encode($data);
});

/**
 * AVG
 */

$app->get('/api/avg/user', function() use($app) {
    $total = $app->modelsManager->createBuilder()
                ->columns('AVG(DATEDIFF(NOW(), userDateBirth)) as AvgAge')
                ->from('User')
                ->getQuery()
                ->execute();
        
    $data = null;
    foreach($total as $t)
        $data = $t->AvgAge;
    
    echo json_encode($data);
});


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

/**
 * PRIZE TYPE
 */

$app->get('/api/prize', function() use($app) {
    $prize = $app->modelsManager->createBuilder()
                ->columns('ptypTitle, ptypRunQuantity')
                ->from('PrizeType')
                ->getQuery()
                ->execute();
    
    $data = Array();
    foreach($prize as $p) {
        $data[] = Array(
            'title'     => $p->ptypTitle,
            'quantity'  => $p->ptypRunQuantity
        );
    }
    
    echo json_encode($data);
});

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

$app->get('/api/ticket/{limit}/{start}', function($limit, $start) use($app) {
    $ticket = $app->modelsManager->createBuilder()
                ->columns(array('prizeTicket.*', 'prizeType.*'))
                ->from('prizeTicket')
                ->join('prizeType', 'prizeTicket.pticPrizeType = prizeType.ptypId')
                ->orderBy('prizeTicket.pticId')
                ->limit($limit, $start)
                ->getQuery()
                ->execute();
        
    $data = Array();
    foreach($ticket as $t) {
        $data[] = Array(
            'pticId'                        => $t->prizeTicket->pticId,
            'pticTicketCode1'               => $t->prizeTicket->pticTicketCode1,
            'pticWinningUser'               => $t->prizeTicket->pticWinningUser,
            'pticCollectionRestaurant'      => $t->prizeTicket->pticCollectionRestaurant,
            'pticDateVerified'              => $t->prizeTicket->pticDateVerified,
            'pticDateCollected'             => $t->prizeTicket->pticDateCollected,
            'ptypTitle'                     => $t->prizeType->ptypTitle
        );
    }
    echo json_encode($data);
});

//////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * USERS
 */

$app->get('/api/user/{limit}/{start}', function($limit, $start) use($app) {
    $user = $app->modelsManager->createBuilder()
                ->columns(array('User.*', 'Pays.*', 'Languages.*', 'City.*', 'Restaurant.*'))
                ->from('User')
                ->join('Pays',          'Pays.paysCodeIso   = User.userCountry')
                ->join('Languages',     'Languages.langCode = User.userPreferredLanguage')
                ->join('City',          'City.cityId        = User.userCity')
                ->join('Restaurant',    'Restaurant.restId  = User.userPreferredRestaurant')
                ->orderBy('User.userId')
                ->limit($limit, $start)
                ->getQuery()
                ->execute();
        
    $data = Array();
    foreach($user as $u) {
        $data[] = Array(
            'userId'                        => $u->user->userId,
            'userLogin'                     => $u->user->userLogin,
            'userName'                      => $u->user->userName,
            'userFirstName'                 => $u->user->userFirstName,
            'userEmail'                     => $u->user->userEmail,
            'userTelFixed'                  => $u->user->userTelFixed,
            'userTelMob'                    => $u->user->userTelMob,
            'userGender'                    => $u->user->userGender,
            'userDateJoined'                => $u->user->userDateJoined,
            'userAccountActive'             => $u->user->userAccountActive,
            'userLoginLocked'               => $u->user->userLoginLocked,
            'paysName'                      => $u->pays->paysName,
            'langName'                      => $u->languages->langName,
            'cityName'                      => $u->city->cityName,
            'cityCp'                        => $u->city->cityCp,
            'restName'                      => $u->restaurant->restName
        );
    }
    echo json_encode($data);
});

$app->get('/api/user/filter/{champs}/{operateur}/{value}', function($champs, $operateur, $value) use($app) {

    $clause = null;
    switch ($operateur){
        case 'egal':
            $clause = $champs." = '".$value."'";
            break;
        case 'like':
            $clause = $champs." LIKE '%".$value."%'";
            break;
        case 'different':
            $clause = $champs." <> ".$value;
            break;
    }
    
    $user = $app->modelsManager->createBuilder()
                ->columns(array('User.*', 'Pays.*', 'Languages.*', 'City.*', 'Restaurant.*'))
                ->from('User')
                ->join('Pays',          'Pays.paysCodeIso   = User.userCountry')
                ->join('Languages',     'Languages.langCode = User.userPreferredLanguage')
                ->join('City',          'City.cityId        = User.userCity')
                ->join('Restaurant',    'Restaurant.restId  = User.userPreferredRestaurant')
                ->orderBy('User.userId')
                ->where($clause)
                ->getQuery()
                ->execute();

    //Create a response
    $response = new Response;

    if ($user == false) {
        $response->setJsonContent(array('status' => 'NOT-FOUND'));
    } else {        
        $data = Array();
        foreach($user as $u) {
            $data[] = Array(
                'userId'                        => $u->user->userId,
                'userLogin'                     => $u->user->userLogin,
                'userName'                      => $u->user->userName,
                'userFirstName'                 => $u->user->userFirstName,
                'userEmail'                     => $u->user->userEmail,
                'userTelFixed'                  => $u->user->userTelFixed,
                'userTelMob'                    => $u->user->userTelMob,
                'userGender'                    => $u->user->userGender,
                'userDateJoined'                => $u->user->userDateJoined,
                'userAccountActive'             => $u->user->userAccountActive,
                'userLoginLocked'               => $u->user->userLoginLocked,
                'paysName'                      => $u->pays->paysName,
                'langName'                      => $u->languages->langName,
                'cityName'                      => $u->city->cityName,
                'cityCp'                        => $u->city->cityCp,
                'restName'                      => $u->restaurant->restName
            );
        }
        
        $response->setJsonContent($data);
    }
    
    return $response;
});

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
