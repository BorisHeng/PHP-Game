<?php 
namespace Boris\Model;



class contactDB
{
    public $server = "localhost";      # MySQL/MariaDB 伺服器
    public $dbuser = "GameProject";      # 使用者帳號
    public $dbpassword = "RJwXdOQzDRDb2DPm";  # 使用者密碼
    public $dbname = "GameProject";      # 資料庫名稱
    public $connection;

    public function __construct($server='',$dbuser='',$dbpassword='',$dbname='')
    {
        if($server!='' && $dbuser!='' && $dbpassword!='' && $dbname!=''){
            $this->server = $server;
            $this->dbuser = $dbuser;
            $this->dbpassword = $dbpassword;
            $this->dbname = $dbname;
        }
        
        $this->connection = new \mysqli($this->server, $this->dbuser, $this->dbpassword, $this->dbname);
        # 檢查連線是否成功
        if ($this->connection->connect_error) {
            die("連線失敗：" . $this->connection->connect_error);
        }
    }

    public function __destruct()
    {
        # 關閉 MySQL/MariaDB 連線
        $this->connection->close();
    }

    // 建立資料表
    function createMysql($command){
        # MySQL/MariaDB 指令
        $sqlQuery = $command;
        
        # 執行 MySQL/MariaDB 指令
        if ($this->connection->query($sqlQuery) === TRUE) {
            echo "成功建立資料表。";
        } else {
            echo "執行失敗：" . $this->connection->error;
        }
    }

    // 新增資料表
    function insertMysql($table,$select,$value){
        # MySQL/MariaDB 指令

        $CheckArray = $this->check_array($select,$value);
        if($CheckArray == false){
            return false;
        }
        if($CheckArray == 'array'){
            $value = '("'.implode('","',$value).'")';
        }
        if($CheckArray == 'arrayarray'){
            foreach($value as $k => $v){
                $value[$k] = '("'.implode('","',$v).'")';
            }
            $value = implode(",",$value);
        }
        // 防止攻擊 

        $sqlQuery = "INSERT INTO ".$table." (".implode(",",$select).") VALUES ".$value;   
        // var_dump($sqlQuery);

        # 執行 MySQL/MariaDB 指令
        if ($this->connection->query($sqlQuery) === TRUE) {
        # 若有 AUTO_INCREMENT 的 ID 欄位，可直接取得此筆資料的 ID
        $last_id = $this->connection->insert_id;
        // echo "成功新增資料，新資料 ID：" . $last_id;
        } else {
        echo "執行失敗：" . $this->connection->error;
        }
    }

    // 查詢資料
    function selectMysql($table,$where = 0,$select = '*'){
        if($where === 0){
            $sqlQuery = "SELECT ".$select." FROM ".$table;
        }
        else{
            $where = array_keys($where)[0]." = '".$where[array_keys($where)[0]]."'";
            $sqlQuery = "SELECT ".$select." FROM ".$table." WHERE ".$where;
        }

        if ($result = $this->connection->query($sqlQuery)) {
            $dbdata = [];
            while ($row = $result->fetch_assoc()) {
                array_push($dbdata,$row);
            }
            // var_dump($dbdata);
            # 釋放資源
            $result->close();
            return $dbdata;
        } else {
            echo "執行失敗：" . $this->connection->error;
        }

    }
    // 合併資料
    function selectLeftMysql($table,$left,$on,$select = '*',$where = 0){
        $selected ='';
        $selectRam = $select;
        // print_r($select);
        // print_r($CheckArray);
        if(count($select) > 1){
            foreach($select as $key => $select){
                // print_r($key);
                if(is_array($selectRam[$key])){
                    foreach($selectRam[$key] as $select){
                        $selected .= "`".array_keys($selectRam)[0]."`.".$select.",";
                    }
                }
                elseif(!is_array($key)){
                    $selected .= "`".$key."`.".$select.",";
                }
            }
            $selected = substr($selected,0,-1);
        }else{
            $selected .= "`".$key."`.".$select;
        }
        // echo $selected;

        if($where === 0){
            $sqlQuery = "SELECT ".$selected." FROM ".$table." LEFT JOIN ".$left." ON `".$table."`.".$on."=`".$left."`.".$on;
        }
        else{
            $where = array_keys($where)[0]." = '".$where[array_keys($where)[0]]."'";
            $sqlQuery = "SELECT ".$selected." FROM ".$table." LEFT JOIN ".$left." ON `".$table."`.".$on."=`".$left."`.".$on." WHERE `".$table."`.".$where;
        }
        // echo $sqlQuery;
        

        if ($result = $this->connection->query($sqlQuery)) {
            $dbdata = [];
            while ($row = $result->fetch_assoc()) {
                array_push($dbdata,$row);
            }
            // var_dump($dbdata);
            # 釋放資源
            $result->close();
            return $dbdata;
        } else {
            echo "執行失敗：" . $this->connection->error;
        }

    }

    // 合併資料*2
    function selectLeftTwiceMysql($table,$left,$on,$left2,$on2,$on3,$select = '*',$where = 0){
        $selected ='';
        $selectRam = $select;
        $num = 0;
        // print_r($select);
        // print_r($CheckArray);
        if(count($select) > 1){
            foreach($select as $key => $select){
                // print_r($key);
                if(is_array($selectRam[$key])){
                    foreach($selectRam[$key] as $select){
                        $selected .= "`".array_keys($selectRam)[$num]."`.".$select.",";
                    }
                }
                elseif(!is_array($key)){
                    $selected .= "`".$key."`.".$select.",";
                }
                $num = $num + 1;
            }
            $selected = substr($selected,0,-1);
        }else{
            $selected .= "`".$key."`.".$select;
        }
        // echo $selected;

        if($where === 0){
            $sqlQuery = "SELECT ".$selected." FROM ".$table." LEFT JOIN ".$left." ON `".$table."`.".$on."=`".$left."`.".$on." LEFT JOIN ".$left2." ON `".$table."`.".$on2."=`".$left2."`.".$on3." ORDER BY id";
        }
        else{
            $where = array_keys($where)[0]." = '".$where[array_keys($where)[0]]."'";
            $sqlQuery = "SELECT ".$selected." FROM ".$table." LEFT JOIN ".$left." ON `".$table."`.".$on."=`".$left."`.".$on." LEFT JOIN ".$left2." ON `".$table."`.".$on2."=`".$left2."`.".$on3." WHERE `".$table."`.".$where." ORDER BY id";
        }

        // echo $sqlQuery;
        

        if ($result = $this->connection->query($sqlQuery)) {
            $dbdata = [];
            while ($row = $result->fetch_assoc()) {
                array_push($dbdata,$row);
            }
            // var_dump($dbdata);
            # 釋放資源
            $result->close();
            return $dbdata;
        } else {
            echo "執行失敗：" . $this->connection->error;
        }

    }

    // 更新資料
    function updateMysql($table,$sets,$where){
        # MySQL/MariaDB 指令
        $setting = '';

        if(count($sets) > 1){
            foreach($sets as $key => $set){
                $setting .= $key." = '".$set."',";
            }
            $setting = substr($setting,0,-1);
        }else{
            $setting = array_keys($sets)[0].' = "'.$sets[array_keys($sets)[0]].'"';
        }

        $where = array_keys($where)[0]." = '".$where[array_keys($where)[0]]."'";

        $sqlQuery = "UPDATE ".$table." SET ".$setting." WHERE ".$where;
        # 執行 MySQL/MariaDB 指令
        // print_r($sqlQuery);
        if ($this->connection->query($sqlQuery) === TRUE) {
        echo "成功更新資料。";
        } else {
        echo "執行失敗：" . $this->connection->error;
        }
    }

    // 刪除資料
    function deleteMysql($table,$where){
        # MySQL/MariaDB 指令
        $where = array_keys($where)[0]." = '".$where[array_keys($where)[0]]."'";
        $sqlQuery = "DELETE FROM ".$table." WHERE ".$where;
        // return $sqlQuery;
        # 執行 MySQL/MariaDB 指令
        if ($this->connection->query($sqlQuery) === TRUE) {
        echo "成功刪除資料。";
        } else {
        echo "執行失敗：" . $this->connection->error;
        }
    }

    // 陣列判斷
    function check_array($select,$value){
        if(!is_array($value)){
            return false;
        }
        if (array_key_exists($select[0], $value)) {
            return 'array';
        }
        
        if (isset($value[0]) && array_key_exists($select[0], $value[0])) {
            return 'arrayarray';
        }
        
        return false;

    }

}




// 必須先 new 一個contactDB()
// $testContact = new contactDB();

// $table = 'data';
// $left = 'user_account';
// $left2 = 'stage';
// $on = 'account';
// $on2 = 'stage';
// $on3 = 'name';
// $select = [
//     'data' => ['id','account','stage'],
//     'stage' => ['atk','def','hp','mp'],
//     'user_account' => 'name'
// ];
// $where = ['account' => 'aa1234'];
// $select = ['account','password'];
// $selectAll = '*';

// $value = ['account' => 'boris123','password' => 'boris123'];
// $set = ['title' => 9990,'content' => 9990];

// 建立資料表   
// $testContact->createMysql("CREATE TABLE test999 (
//     id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
//     name VARCHAR(50),
//     age INT UNSIGNED)");
// 新增資料表 insertMysql($table,$select,$value)
// $testContact->insertMysql($table,$select,$value);

// 更新資料表 updateMysql($table,$set,$where)   
// $testContact->updateMysql($table,$set,$where);

// 合併資料表 selectLeftMysql($table,$left,$on,$select = '*',$where = 0)
// print_r($testContact->selectLeftMysql($table,$left,$on,$select,$where));

        // SELECT `data`.id,`data`.account,`data`.stage,`user_account`.name,`stage`.atk,`stage`.def,`stage`.hp,`stage`.mp 
        // FROM `data`
        // LEFT JOIN `user_account`
        // ON data.account = user_account.account
        // LEFT JOIN `stage`
        // ON data.stage = stage.name
// 合併3張資料表 selectLeftTwiceMysql($table,$left,$on,$left2,$on2,$on3,$select = '*',$where = 0)
// $testContact->selectLeftTwiceMysql($table,$left,$on,$left2,$on2,$on3,$select,$where);


// 刪除資料表 deleteMysql($table,$where)
// $testContact->deleteMysql($table,$where);

// // // 查詢資料表 selectMysql($select,$table,$where)
// $test = $testContact->selectMysql($selectAll,$table);
// var_dump($test);

?>