<?php
require_once '../Model/config/configInClass.php';
use Boris\Model\contactDB as contactDB;

function AutoSendRewards($pass){
    if(!($pass == 'af5g4a564g1651321gz1dfz56')){
        // echo 'No';
        return;
    }
    $model = new contactDB;
    $stage = $model->selectMysql('stage');
    $users = $model->selectMysql('user_account');
    
    foreach($users as $key => $user){
        if($user['admin'] == '1'){

        }else{
            $data = [
                'account' => $user['account'],
                'stage' => $stage[array_rand($stage)]['name']
            ];
            $model->insertMysql('data',['account','stage'],$data);
        }
    }
    // echo 'Yes';

}

AutoSendRewards('af5g4a564g1651321gz1dfz56');




// $show->AutoSendRewards();