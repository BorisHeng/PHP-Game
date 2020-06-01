<?php
namespace Models;
require_once './Model/config/configInClass.php';
use Boris\Model\contactDB as contactDB;

class Model extends contactDB{
    public function select($table,$where = 0){
        if($where == 0){
            return $this->selectMysql($table);
        }
        else{
            return $this->selectMysql($table,$where);
        }
    }

    public function selectLeft($where = 0){
        $table = 'data';
        $left = 'user_account';
        $on = 'account';
        $select = [
            'data' => ['id','account','stage','createtime'],
            'user_account' => 'name'
        ];
        return $this->selectLeftMysql($table,$left,$on,$select,$where);
    }

    public function selectTwice($where = 0){
        $table = 'data';
        $left = 'user_account';
        $left2 = 'stage';
        $on = 'account';
        $on2 = 'stage';
        $on3 = 'name';
        $select = [
            'data' => ['id','account','stage','createtime'],
            'stage' => ['atk','def','hp','mp','career'],
            'user_account' => ['name','mission']
        ];
        return $this->selectLeftTwiceMysql($table,$left,$on,$left2,$on2,$on3,$select,$where);
    }


    public function insert($table,$data){
        if($table == 'user_account'){
            $this->insertMysql($table,['account','password','name','career'],$data);
        }
        elseif($table == 'data'){
            $this->insertMysql($table,['account','stage'],$data);
        }
    }

    public function delete($table,$where){
        if($table == 'data'){
            $this->deleteMysql($table,$where);
        }
    }

    public function modify($set,$where,$table = 'data'){
        $this->updateMysql($table,$set,$where);
    }
}

