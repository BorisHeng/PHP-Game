<?php
namespace Controller;
require_once "./Model/Model.php";
use Models\Model as Model;

isset($sdfaa65dfzcvx321zv4z5cv43z1vz3) && $sdfaa65dfzcvx321zv4z5cv43z1vz3 ==='cx1v3z1f5v1z231cvz1vzfv513zxvf2z1'?:exit();

class Control{
    public $token = '';
    // html 可承接任務列表
    private $html_show_mission = '';
    // html 已承接任務列表
    private $html_show_nowMission = '';
    // html 戰鬥區塊
    private $html_fighting = '';
    // html 顯示清單
    private $li_filter = '';
    // html 登出/登入按鈕
    private $status = '';
    // html 顯示能力值
    private $info = '';
    // html 管理員指定發送水果
    private $send = '';
    // 留言允許符號
    private $text = '~!@';
    // ---------------------------顯示頁面---------------------------
    public function showPage($page = 'login',$now = '10'){
        $model = new Model;
        // 儲存現在頁面
        $_SESSION['nowPage'] = $page;
        // 判斷顯示全部或個人資料
        if(isset($_SESSION['userAccount']) && $page == 'adminPage'){
            $data =  $model -> selectTwice();
            // 限制顯示留言數量
            $this -> filter($data,$now,$page);
        }elseif((isset($_SESSION['userAccount']) && $page == 'userPage')){
            $data =  $model -> selectTwice(['account'=>$_SESSION['userAccount']]);
            $this -> filter($data,$now,$page);
            $this -> setInfo($data);
            if($data){
                $this -> showMission($data[0]['mission'],$data);
            }else{
                $this -> showMission();
            }
        }

        // 判斷登入/登出狀態
        $this -> status();
        $this -> send();
        $this -> reset();

        require_once './View/Pages/'.$page.'.php';
        return;
    }
    // ---------------------------指向功能---------------------------
    // 判斷登入/登出/註冊
    public function checkLogin(){
        // 登入
        if(isset($_POST['account']) && isset($_POST['password'])){
            $name = $this->checkUserStatus('getName',$_POST['account']);
            $this->checkUserStatus('login',$_POST['account'],$_POST['password'],$name);
            return;
        }
        // 註冊
        elseif(isset($_POST['new_account']) && isset($_POST['new_password']) && isset($_POST['name']) && isset($_POST['career'])){ 
            $this->checkUserStatus('register',$_POST['new_account'],$_POST['new_password'],$_POST['name'],'','','',$_POST['career']);
            return;
        }
        // 登出
        $this->checkUserStatus('logout');
        return;
    }
    // 修改資料
    public function modify(){
        if(isset($_POST['password']) && isset($_POST['checkPassword']) && isset($_POST['name'])){
            $this->checkUserStatus('modify',$_SESSION['userAccount'],$_POST['password'],$_POST['name'],$_POST['checkPassword'],$_POST['CSR'],$_COOKIE['session_id_ass2']);
            return;
        }
    }
    // 判斷遭遇/戰鬥/逃跑
    public function fighting(){
        $model = new Model;
        $monster = $model->select('monster');

        if(!isset($_SESSION['monsterNowHp'])){
            $_SESSION['fight'] = 'meet';
            $_SESSION['monster'] = $monster[array_rand($monster,1)];

            $this -> showFighting($_SESSION['fight'],$_SESSION['monster']);
            return;
        }

        if(isset($_SESSION['monsterNowHp']) && isset($_SESSION['userNowHp'])){
            $_SESSION['fight'] = 'fighting';
            $this -> showFighting($_SESSION['fight'],$_SESSION['monster']);
            return;
        }
        $_SESSION['fight'] = 'fight';
        $this -> showFighting($_SESSION['fight'],$_SESSION['monster']);

    }
    // ---------------------------一般功能---------------------------
    // 發放水果
    public function sendRewards(){
        if(!isset($_POST['pass']) || !($_POST['pass'] == 'afg4654gx1cv61zx491fv')){
            return;
        }
        $model = new Model;
        $stage = $model->select('stage');
        $users = $model->select('user_account');
        
        foreach($users as $key => $user){
            if($user['admin'] == '1'){

            }else{
                $data = [
                    'account' => $user['account'],
                    'stage' => $stage[array_rand($stage)]['name']
                ];
                $model->insert('data',$data);
            }
        }
        $this->alert('發送成功囉');
    }
    // 指定發水果
    public function sendPerson(){
        $model = new Model;
        if(!isset($_POST['name']) || !isset($_POST['stage']) || !isset($_POST['num'])){
            return;
        }

        if($_POST['CSR']!=$_COOKIE['csrf_token'] || $_COOKIE['session_id_ass2']!=session_id()){
            $this -> alert('這是詐騙網頁，快跑!!');
            return;
        }

        $pattern = "/[0-9]/u";
        preg_match_all($pattern,$_POST['num'],$result);   
        $fullNum = join('',$result[0]);

        if($fullNum != $_POST['num']){
            $this->alert('數量限制為數字，請重新輸入','?page=sendPage');
            return;
        }
        elseif(!($this->checkInput('account',$_POST['name'],'user_account')) || !($this->checkInput('name',$_POST['stage'],'stage'))){
            $this->checkLogin();
            return;
        }
        else{
            $data = [
                'account' => $_POST['name'],
                'stage' => $_POST['stage']
            ];
            for($i=0;$i < $_POST['num'];$i++){
                $model->insert('data',$data);
    
            }
            $this->alert('已成功發送水果','?page=adminPage');
        }
    }
    // 登入/登出/註冊/修改
    private function checkUserStatus($status,$account='',$password='',$name='',$checkPassword='',$user_CSRF='',$user_sessionID='',$career=''){
        $model = new Model;
        $admin = false;

        $data =  $model -> select('career');
        $careerDB = [];
        foreach($data as $value ){
            array_push($careerDB,$value['career_name']);
        };

        $accountDB =  $this->getUser('account');
        $passwordDB =  $this->getUser('password');
        $nameDB =  $this->getUser('name');
        $adminDB =  $this->getUser('admin');

        $accountFilter = $this->wordFilter($account);
        $passwordFilter = $this->wordFilter($password);
        $HASHpasswordFilter = password_hash($passwordFilter, PASSWORD_DEFAULT);
        $nameFilter = $this->strFilter($name);
        $careerFilter = $this->strFilter($career);

        if($status == 'login' && $adminDB[array_search($accountFilter, $accountDB)] == '1'){
            $admin = true;
        }

        switch($status){
            case 'login':
                if(!in_array($accountFilter, $accountDB)  ||  !(password_verify($passwordFilter, $passwordDB[array_search($accountFilter, $accountDB)]))){
                    $this -> alert('帳號密碼輸入錯誤');
                    break;
                }
                $_SESSION['loginCheck'] = 'Checked';
                $_SESSION['userAccount'] = $accountFilter;
                $_SESSION['userName'] = $name;

                // 防止CSRF
                $sessionID = session_id(); //Setting and storing session ID
                        
                if(empty($_SESSION['key']))
                {
                    $_SESSION['key']=md5(uniqid(rand(),true));
                }

                $_SESSION['token'] = hash_hmac('sha256',$sessionID,$_SESSION['key']);//generate CSRF token
                setcookie("session_id_ass2",$sessionID,time()+86400,"/"); //cookie terminates after 1 hour - HTTP only flag     
                setcookie("csrf_token",$_SESSION['token'],time()+86400,"/"); //csrf token cookie


                if($admin){
                    $_SESSION['admin'] = true;
                    $this -> alert('水果大王歡迎回來!!','?page=adminPage');
                    break;
                }
                $this -> alert('登入成功!!','?page=userPage');
                break;
            case 'register':
                if($account == '' || $password == '' || $name == ''){
                    $this -> alert('請輸入欲申請之帳號密碼','?page=register');
                    break;
                }
                elseif($account != $accountFilter || $password != $passwordFilter){
                    $this -> alert('帳號密碼請輸入英文數字6~12位，請重新輸入','?page=register');
                    break;
                }
                elseif($name != $nameFilter){
                    $this -> alert('名稱禁止含有特殊字元，請重新輸入','?page=register');
                    break;
                }
                elseif(in_array($accountFilter, $accountDB)){
                    $this -> alert('已申請過之帳號，請登入','?page=login');
                    break;
                }elseif(!in_array($careerFilter, $careerDB)){
                    $this -> alert('請選擇職業','?page=register');
                    break;
                }

                $data = [
                    'account' => $accountFilter,
                    'password' => $HASHpasswordFilter,
                    'name' => $name,
                    'career' => $careerFilter
                ];
                $model->insert('user_account',$data);
                $this -> alert('申請成功，請登入','?page=login');
                break;
            case 'logout':
                session_destroy();
                $this->status();
                $this -> alert('登出成功!','?page');
                break;
            case 'getName':
                return $nameDB[array_search($accountFilter, $accountDB)];
                break;
            case 'modify':
                if($password != $passwordFilter){
                    $this -> alert('密碼請輸入英文數字6~12位，請重新輸入','?page=modifyPage');
                    break;
                }
                elseif($name != $nameFilter){
                    $this -> alert('名稱禁止含有特殊字元，請重新輸入','?page=modifyPage');
                    break;
                }
                elseif($passwordFilter != $checkPassword){
                    $this -> alert('密碼與確認密碼不符合，請重新輸入','?page=modifyPage');
                    break;
                }

                if($user_CSRF!=$_COOKIE['csrf_token'] || $user_sessionID!=session_id()){
                    $this -> alert('這是詐騙網頁，快跑!!');
                    return;
                }
                
                if($passwordFilter != ''){
                    $data = [
                        'password' => $passwordFilter,
                        'name' => $name
                    ];
                    $model->modify($data,['account' => $_SESSION['userAccount']],'user_account');
                    $this -> alert('修改密碼及名稱成功!!','?page=userPage');
                    break;
                }elseif($name != $_SESSION['userName']){
                    $data = [
                        'name' => $name
                    ];
                    $_SESSION['userName'] = $name;
                    $model->modify($data,['account' => $_SESSION['userAccount']],'user_account');
                    $this -> alert('修改名稱成功!!','?page=userPage');
                    break;
                }else{
                    $this -> alert('甚麼事情都沒有發生!','?page=userPage');
                    break;
                }
                
            
        }
    }
    // 獲取已有帳號密碼名稱
    private function getUser($field){
        $model = new Model;
        $data =  $model -> select('user_account');
        $result = [];
        foreach($data as $value ){
            array_push($result,$value[$field]);
        };
        return $result;
    }
    // 限制單次顯示留言數量及按鈕
    private function filter($data,$nowShow,$user = false){
        // 計算總比數
        $total = count($data);
        // 限制單次顯示數量
        $dataFilter = array_slice($data,$nowShow -10,10);
        // 畫面顯示
        if($user == 'adminPage'){
            $this->showList($data,$total,$nowShow,$user);
            return;
        }
        $this->showList($dataFilter,$total,$nowShow);
        return;

    }
    // 接取任務
    public function mission(){
        if($_POST['CSR']!=$_COOKIE['csrf_token'] || $_COOKIE['session_id_ass2']!=session_id()){
            $this -> alert('這是詐騙網頁，快跑!!');
            return;
        }
        $model = new Model;
        $mission = $model->select('mission'); 
        $user = $model->select('user_account',['account' => $_SESSION['userAccount']]); 

        $nowMission = [];
        foreach($mission as $key => $value){
            if($value['id'] == $_POST['confirm']){
                $nowMission['mission_name'] = $value['mission_name'];
            }
        }

        if($nowMission && is_numeric(strpos($user[0]['mission'],$nowMission['mission_name']))){
            $this->alert('您已經有接取此任務','?page=userPage');
            return;
        }

        if(!$nowMission){
            $this->alert('沒有這個任務呦','?page=userPage');
            return;
        }
        elseif($user[0]['mission'] == ''){
            $model->modify(['mission' => $nowMission['mission_name'].","],['account' => $_SESSION['userAccount']],'user_account');
            $this->alert('任務接取成功','?page=userPage');
        }
        else{
            $model->modify(['mission' => $user[0]['mission'].$nowMission['mission_name'].","],['account' => $_SESSION['userAccount']],'user_account');
            $this->alert('任務接取成功','?page=userPage');
        }

    }
    // 完成任務
    public function finishMission(){
        if($_POST['CSR']!=$_COOKIE['csrf_token'] || $_COOKIE['session_id_ass2']!=session_id()){
            $this -> alert('這是詐騙網頁，快跑!!');
            return;
        }
        $model = new Model;
        $mission = $model->select('mission'); 
        $user = $model->select('data',['account' => $_SESSION['userAccount']]); 
        $user_account = $model->select('user_account',['account' => $_SESSION['userAccount']]); 

        // 確認任務需求
        $mission_aims = [];
        foreach($mission as $key => $value){
            if($value['mission_name'] == $_POST['confirm']){
                $mission_aims = explode(',',$value['need']);
                $difficulty = $value['id'];
            }
        }
        if(!$difficulty){
            $this->alert('沒有這個任務吧，別坑我','?page=userPage');
            return;
        }
        // 確認使用者包包
        $user_stage =[];
        foreach($user as $key => $value){
            if(!isset($user_stage[$value['stage']])){
                $user_stage[$value['stage']] = ['id' => [$value['id']] ,'num' => 1];
            }
            else{
                array_push($user_stage[$value['stage']]['id'],$value['id']);
                $user_stage[$value['stage']]['num'] = $user_stage[$value['stage']]['num'] + 1;
            }
        }
        // 確認任務是否完成
        foreach($mission_aims as $value){
            if(!isset($user_stage[$value])){
                $this->alert('道具尚未收集完成','?page=userPage');
                return;
            }
        }
        // // 移除使用者任務
        $user_mission = str_replace($_POST['confirm'].",","",$user_account[0]['mission']);
        $model->modify(['mission' => $user_mission],['account' => $_SESSION['userAccount']],'user_account');
        // 移除使用者任務物品
        foreach($mission_aims as $value){
            $model->delete('data',['id' => $user_stage[$value]['id'][0]]);
        }
        // 給予獎品
        $reward = $model->select('stage',['type' => '裝備']);
        for($i = 0;$i < $difficulty;$i++){
            $data = [
                'account' => $_SESSION['userAccount'],
                'stage' => $reward[array_rand($reward,1)]['name']
            ];
            $model->insert('data',$data);
        }
        

        $this->alert('任務繳交完成，請在背包中查看獎勵物品','?page=userPage');
    }
    // 刪除指定道具
    public function deleteItem(){
        if($_POST['CSR']!=$_COOKIE['csrf_token'] || $_COOKIE['session_id_ass2']!=session_id()){
            $this -> alert('這是詐騙網頁，快跑!!');
            return;
        }

        $model = new Model;
        $userBag = $model->select('data',['account' => $_SESSION['userAccount']]);
        $userStageId = [];
        $userStageStage = [];
        foreach($userBag as $item){
            array_push($userStageId,$item['id']);
            array_push($userStageStage,$item['stage']);
        }
        // 測試區塊
        // if(!isset($_POST['id']) || !in_array($_POST['id'],$userStageStage)){
        //     $this->alert('你背包裏面沒有這個東西','?page=userPage');
        //     return;
        // }
        // foreach($userStageId as $id){
        //     $model->delete('data',['id' => $id]);
        // }
        // 測試區塊
        // var_dump($_POST['id']);
        if(!isset($_POST['id']) || !in_array($_POST['id'],$userStageId)){
            $this->alert('你背包裏面沒有這個東西','?page=userPage');
            return;
        }
        $model->delete('data',['id' => $_POST['id']]);
        $this->alert('已成功丟棄道具','?page=userPage');
    }
    // 戰鬥的逃跑
    public function runaway(){
        $this->reset();
        $this->alert('你成功逃跑了','?page=fightingPage');
    }
    // 重製戰鬥
    public function reset(){
        unset($_SESSION['fight']);
        unset($_SESSION['monster']);
        unset($_SESSION['monsterNowHp']);
        unset($_SESSION['userNowHp']);
    }
    // ---------------------------輸出Html---------------------------
    // 登入/登出標籤切換
    private function status(){
        if(isset($_SESSION['admin']) && $_SESSION['admin'] == true){
            $this->status = '
            <span class="helloText">'.$_SESSION['userName'].' 您好</span>
            <a class="titleBtn login" href="?page=sendPage">指定送</a>
            <a class="titleBtn" href="?page=checklogin">登出</a>
            ';
        }
        elseif(isset($_SESSION['loginCheck']) && $_SESSION['loginCheck'] == 'Checked'){
            $this->status = '
                <span class="helloText">'.$_SESSION['userName'].' 您好</span>
                <a class="titleBtn login" href="?page=modifyPage">修改</a>
                <a class="titleBtn" href="?page=checklogin">登出</a>
            ';
        }else{
        $this->status ='
            <a class="titleBtn" href="?page=login">登入</a>
        ';
        }
    }
    // 包包顯示
    private function showList($datas,$total,$nowShow,$user = ''){
                if($user == 'adminPage'){
                    $userName = [];
                    if(!$datas){
                        $this->li_filter .= '
                        <li>  
                            <br>
                            <h2>
                            沒有玩家有水果，趕快發阿!
                            </h2>
                        </li>
                        ';
                    }
                    foreach($datas as $data){
                        if(!isset($userName[$data['name']])){
                            // $userName[$data['name']] = [$data['stage']];
                            $userName[$data['name']][$data['stage']] =[
                                'num' => 1,
                                'atk' => $data['atk'],
                                'def' => $data['def'],
                                'hp' => $data['hp'],
                                'mp' => $data['mp']
                            ];
                            $userName[$data['name']]['sum'] =[
                                'atk' => $data['atk'],
                                'def' => $data['def'],
                                'hp' => $data['hp'],
                                'mp' => $data['mp']
                            ];
                        }
                        else{
                            if(isset($userName[$data['name']][$data['stage']]))
                            {
                                $userName[$data['name']][$data['stage']]['num'] = $userName[$data['name']][$data['stage']]['num'] + 1;
                                $userName[$data['name']]['sum']['atk'] = $userName[$data['name']]['sum']['atk'] + $data['atk'];
                                $userName[$data['name']]['sum']['def'] = $userName[$data['name']]['sum']['def'] + $data['def'];
                                $userName[$data['name']]['sum']['hp'] = $userName[$data['name']]['sum']['hp'] + $data['hp'];
                                $userName[$data['name']]['sum']['mp'] = $userName[$data['name']]['sum']['mp'] + $data['mp'];
                            }
                            else{
                                $userName[$data['name']][$data['stage']] =[
                                    'num' => 1,
                                    'atk' => $data['atk'],
                                    'def' => $data['def'],
                                    'hp' => $data['hp'],
                                    'mp' => $data['mp']
                                ];
                                $userName[$data['name']]['sum']['atk'] = $userName[$data['name']]['sum']['atk'] + $data['atk'];
                                $userName[$data['name']]['sum']['def'] = $userName[$data['name']]['sum']['def'] + $data['def'];
                                $userName[$data['name']]['sum']['hp'] = $userName[$data['name']]['sum']['hp'] + $data['hp'];
                                $userName[$data['name']]['sum']['mp'] = $userName[$data['name']]['sum']['mp'] + $data['mp'];
                            }
                        }
                    }

                    foreach($userName as $key => $user){
                        $this->li_filter .= '
                        <li>  
                            <br>
                            <h2>
                            ';
                        foreach($user as $key1 => $user1){
                            if($key1 == 'sum'){}
                            else{
                                $this->li_filter .= $key1.' '.$user1['num'].'個  ';
                            }
                        }
                        $this->li_filter .='
                            </h2>
                            <h3>攻擊力：'.$user["sum"]["atk"].' ,防禦力：'.$user["sum"]["def"].' ,血量：'.$user["sum"]["hp"].' ,魔力：'.$user["sum"]["mp"].'</h3>
                            <span class="title">'.$key.'</span>
                        </li>
                        ';
                    }
                    $total = count($userName);

                    if($total <=10){
                    }
                    elseif($nowShow == 10){
                        $this->li_filter .=  '
                            <a class="changePage" href="?page='.($nowShow+10).'">下一頁</a> 
                        ';
                    }
                    elseif($nowShow < $total){
                        $this->li_filter .=  '
                            <a class="changePage right" href="?page='.($nowShow-10).'">上一頁</a>
                            <a class="changePage" href="?page='.($nowShow+10).'">下一頁</a>
                        ';
                    }
                    else{
                        $this->li_filter .=  '
                            <a class="changePage right" href="?page='.($nowShow-10).'">上一頁</a>
                        ';
                    }   
                    return;
                }

                if(!$datas){
                    $this->li_filter .= '
                    <li>  
                        <br>
                        <h2>
                        您目前還沒有東西喔，趕快去打怪解任務吧!
                        </h2>
                    </li>
                    ';
                }
                // 測試區塊
                // $user_stage =[];
                // foreach($datas as $key => $value){
                //     if(!isset($user_stage[$value['stage']])){
                //         if($value['atk'] != 0){
                //             $user_stage[$value['stage']] = ['id' => [$value['id']] ,'num' => 1,'career' => $value['career'],'atk' => $value['atk'],'def' => $value['def'],'hp' => $value['hp']];
                //         }else{
                //             $user_stage[$value['stage']] = ['id' => [$value['id']] ,'num' => 1,'career' => $value['career']];
                //         }
                //     }
                //     else{
                //         array_push($user_stage[$value['stage']]['id'],$value['id']);
                //         $user_stage[$value['stage']]['num'] = $user_stage[$value['stage']]['num'] + 1;
                //         $user_stage[$value['stage']]['atk'] = $user_stage[$value['stage']]['atk'] + $value['atk'];
                //         $user_stage[$value['stage']]['def'] = $user_stage[$value['stage']]['def'] + $value['def'];
                //         $user_stage[$value['stage']]['hp'] = $user_stage[$value['stage']]['hp'] + $value['hp'];
                //     }
                // }
                // foreach($user_stage as $key => $data){
                //     $this->li_filter .= '
                //     <li>  
                //         <br>
                //         ';
                //     if($data['career'] == '任務道具'){
                //         $this->li_filter .= '
                //             <h2>
                //             '.$key.' '.$data['num'].'顆
                //             </h2>
                //             <h3>任務道具</h3>
                //             ';
                //     }
                //     else{
                //         $this->li_filter .= '
                //         <h2>
                //             '.$key.' +'.($data['num']-1).' 職業限定：'.$data['career'].'
                //         </h2>
                //         <h3>攻擊力增加：'.$data["atk"].' 防禦力增加：'.$data["def"].' 血量增加：'.$data["hp"].'</h3>
                //         ';
                //     }
                //     $this->li_filter .= '
                //         <form action="?page=deleteItem" method="post">
                //             <input type="hidden" name="id" value="'.$key .'" />
                //             <input type="submit" value="丟棄" class="titleBtn text_btn  del_btn"  />
                //             <div class="spacing">
                //                 <input type="hidden" id="csToken" name="CSR" value="'.$_SESSION['token'].'"/>
                //             </div>
                //         </form>
                //     </li>
                //     ';
                // }
                // 測試區塊
                foreach($datas as $data){
                    $this->li_filter .= '
                    <li>  
                        <br>
                        ';
                    if($data['career'] == '任務道具'){
                        $this->li_filter .= '
                            <h2>
                            '.$data["stage"].'
                            </h2>
                            <h3>任務道具</h3>
                            ';
                    }
                    else{
                        $this->li_filter .= '
                        <h2>
                            '.$data["stage"].' 職業限定：'.$data['career'].'
                        </h2>
                        <h3>攻擊力增加：'.$data["atk"].' 防禦力增加：'.$data["def"].' 血量增加：'.$data["hp"].' 魔力增加：'.$data["mp"].'</h3>
                        ';
                    }
                    $this->li_filter .= '<span class="title">'.$data["name"].'</span>
                        <span class="name">'.$data["account"].'</span>
                        <span class="time">'.substr($data["createtime"],0,10).'</span>
                        <form action="?page=deleteItem" method="post">
                            <input type="hidden" name="id" value="'.$data["id"] .'" />
                            <input type="submit" value="刪除" class="titleBtn text_btn  del_btn"  />
                            <div class="spacing">
                                <input type="hidden" id="csToken" name="CSR" value="'.$_SESSION['token'].'"/>
                            </div>
                        </form>
                    </li>
                    ';
                }
                if($total <=10){
                }
                elseif($nowShow == 10){
                    $this->li_filter .=  '
                        <a class="changePage" href="?page='.($nowShow+10).'">下一頁</a> 
                    ';
                }
                elseif($nowShow < $total){
                    $this->li_filter .=  '
                        <a class="changePage right" href="?page='.($nowShow-10).'">上一頁</a>
                        <a class="changePage" href="?page='.($nowShow+10).'">下一頁</a>
                    ';
                }
                else{
                    $this->li_filter .=  '
                        <a class="changePage right" href="?page='.($nowShow-10).'">上一頁</a>
                    ';
                }            
                return;
    }
    // 任務顯示
    private function showMission($nowMission = '',$data = ''){
        $model = new Model;
        $mission = $model->select('mission'); 

        // 顯示可承接任務列表
        $this->html_show_mission .= '
            <div class="mission_table">
            <p class="text_title">可承接任務</p>
        ';
        foreach($mission as $key => $value){
            $this->html_show_mission .= '
                <div class="mission">
                    <span class="user_text">'.$value['mission_name'].'</span>
                    <form action="?page=mission" method="post" >
                        <label for="confirm"></label>
                        <input type="hidden" value="'.$value['id'].'" name="confirm" id="confirm">
                        <div class="spacing">
                            <input type="hidden" id="csToken" name="CSR" value="'.$_SESSION['token'].'"/>
                        </div>
                        <input type="submit" name="getMission" value="接取任務" class="titleBtn form" />
                    </form>
                </div>
            ';
        }
        $this->html_show_mission .= '
            </div>
        ';

        // 顯示已經承接的任務列表
        if($nowMission == ''){
            $user = $model->select('user_account',['account' => $_SESSION['userAccount']]);
            $nowMission = $user[0]['mission'];
        }
        $user_mission = [];
        $user_stage =[];

        foreach(explode(',',$nowMission) as $key => $value){
            foreach($mission as $key1 => $value1){
                if($value1['mission_name'] == $value){
                    $user_mission[$value] = $value1['need'];
                }
            }
        }
        if($data){
            foreach($data as $key => $value){
                if(!isset($user_stage[$value['stage']])){
                    $user_stage[$value['stage']] = 1;
                }
                else{
                    $user_stage[$value['stage']] = $user_stage[$value['stage']] + 1;
                }
            }
        }
        
        $this->html_show_nowMission .= '
            <div class="mission_table user_table">
            <p class="text_title">已承接任務</p>
        ';
        foreach($user_mission as $key => $value){
            $this->html_show_nowMission .='
                <div class="mission">
                    <span class="user_text">'.$key.'</span>
                    <br>
                    <span class="user_text">';
            foreach(explode(',',$value) as $value1){
                $this->html_show_nowMission .= $value1.' ';
                if(array_key_exists($value1,$user_stage)){
                    $this->html_show_nowMission .= $user_stage[$value1];
                }
                else{
                    $this->html_show_nowMission .= '0';
                }
                $this->html_show_nowMission .= '/1 ';
            }
                    
            $this->html_show_nowMission .='</span>
                    <form action="?page=finishMission" method="post">
                        <label for="confirm"></label>
                        <input type="hidden" value="'.$key.'" name="confirm">
                        <div class="spacing">
                            <input type="hidden" id="csToken" name="CSR" value="'.$_SESSION['token'].'"/>
                        </div>
                        <input type="submit" value="提交任務" class="titleBtn form"  />
                    </form>
                </div>
            ';
        }
        $this->html_show_nowMission .= '
            </div>
        ';
    }
    // 能力值顯示
    private function setInfo($user){
        $atk = 100;
        $def = 100;
        $hp = 100;
        $mp = 100;

        $model = new Model;
        $stageDB = $model->select('stage');
        $career = $model->select('user_account',['account'=>$_SESSION['userAccount']]);


        // 取得所有水果
        $stages = [];
        foreach($stageDB as $stage){
            array_push($stages,$stage['name']);
        }
        
        // 查看玩家有哪些水果並做處理
        foreach($user as $user){
            foreach($stageDB as $value){
                if($user['stage'] == $value['name']){
                    if($career[0]['career'] == $value['career']){
                        $atk = $atk + intval(($stageDB[array_search($user['stage'],$stages)]['atk']));
                        $def = $def + intval(($stageDB[array_search($user['stage'],$stages)]['def']));
                        $hp = $hp + intval(($stageDB[array_search($user['stage'],$stages)]['hp']));
                        $mp = $mp + intval(($stageDB[array_search($user['stage'],$stages)]['mp']));
                    }
                }
            }
        }
        // 產出HTML
        if(isset($_SESSION['userAccount'])){
            $this->info .= '
                <span class="user_text">職業 ： '.$career[0]["career"].'</span>
                <span class="user_text">攻擊力 ： '.$atk.'</span>
                <span class="user_text">防禦力 ： '.$def.'</span>
                <span class="user_text">HP ： '.$hp.'</span>
                <span class="user_text">MP ： '.$mp.'</span>
            ';
            $_SESSION['user']['career'] = $career[0]["career"];
            $_SESSION['user']['atk'] = $atk;
            $_SESSION['user']['def'] = $def;
            $_SESSION['user']['hp'] = $hp;

        }

    }
    // 戰鬥區塊 
    private function showFighting($doing = '',$monster = ''){        
        $model = new Model;
        switch($doing){
            case 'meet':
                echo '
                    <div class="fight_btns meet">
                        <a class="titleBtn fight_btn" id="fighting" onclick="fighting()">戰鬥!!!</a>
                        <a href="?page=runaway" class="titleBtn fight_btn">逃跑~~</a>
                    </div> 
                    <p>遭遇 '.$monster['name'].'</p>
                    <p>HP：'.$monster['hp'].'</p>
                    <p>ATK：'.$monster['atk'].' DEF：'.$monster['def'].'</p>
                    <p>掉落物：'.$monster['stage'].'</p>
                    ';
                    $_SESSION['monsterNowHp'] = $monster['hp'];
            break;
            case 'fight':
                $_SESSION['userNowHp'] = $_SESSION['user']['hp'];
                echo '
                    <div class="attak">
                    <div class="user">
                        <p>'.$_SESSION['user']['career'].'</p>
                        <p>HP ： '.$_SESSION['userNowHp'].' / '.$_SESSION['user']['hp'].'</p>
                        <p>ATK ： '.$_SESSION['user']['atk'].'</p>
                        <p>DEF ： '.$_SESSION['user']['def'].'</p>
                    </div>
                    <div class="monster">
                        <p>'.$monster['name'].'</p>
                        <p>HP ： '.$_SESSION['monsterNowHp'].' / '.$monster['hp'].'</p>
                        <p>ATK ： '.$monster['atk'].'</p>
                        <p>DEF ： '.$monster['def'].'</p>
                    </div>
                    <div class="fight_btns meet">
                        <a class="titleBtn fight_btn" onclick="fighting()">打他!!!</a>
                        <a href="?page=runaway" class="titleBtn fight_btn">逃跑~~</a>
                    </div> 
                </div>
                    ';
            break;
            case 'fighting':
                $_SESSION['monsterNowHp'] = (int)$_SESSION['monsterNowHp'] - (((int)$_SESSION['user']['atk'] - (int)$monster['def']) >= 0 ? (int)$_SESSION['user']['atk'] - (int)$monster['def'] : 0);
                if($_SESSION['monsterNowHp'] <= 0){
                    $item = explode(',',$monster['stage']);
                    $take = $item[array_rand($item,1)];
                    $model->insert('data',['account' => $_SESSION['userAccount'],'stage' => $take]);
                    echo '
                    <div class="attak">
                        <div class="user">
                            <p>戰士</p>
                            <p>HP ： '.$_SESSION['userNowHp'].' / '.$_SESSION['user']['hp'].'</p>
                            <p>ATK ： '.$_SESSION['user']['atk'].'</p>
                            <p>DEF ： '.$_SESSION['user']['def'].'</p>
                        </div>
                        <div class="monster">
                            <p>'.$monster['name'].'</p>
                            <p>怪物已死亡</p>
                            <p>您獲得了一個 '.$take.'</p>
                        </div>
                        <div class="fight_btns meet">
                            <a href="?page=userPage" class="titleBtn fight_btn">回背包頁面</a>
                            <a href="?page=fightingPage" class="titleBtn fight_btn">重新找怪</a>
                        </div> 
                    </div>
                        ';
                    $this->reset();
                    return;
                }
                $_SESSION['userNowHp'] = (int)$_SESSION['userNowHp'] - (((int)$monster['atk'] - (int)$_SESSION['user']['def']) >= 0 ? (int)$monster['atk'] - (int)$_SESSION['user']['def'] : 0);
                if($_SESSION['userNowHp'] <= 0){
                    echo '
                    <div class="attak">
                        <div class="user">
                            <p>戰士</p>
                            <p>你不幸的戰敗了，</p>
                            <p>提升能力後在來挑戰吧</p>
                        </div>
                        <div class="monster">
                            <p>'.$monster['name'].'</p>
                            <p>HP ： '.$_SESSION['monsterNowHp'].' / '.$monster['hp'].'</p>
                            <p>ATK ： '.$monster['atk'].'</p>
                            <p>DEF ： '.$monster['def'].'</p>
                        </div>
                        <div class="fight_btns meet">
                            <a href="?page=userPage" class="titleBtn fight_btn">QQ去看看包包</a>
                            <a href="?page=fightingPage" class="titleBtn fight_btn">重新找怪</a>
                        </div> 
                    </div>
                        ';
                    $this->reset();
                    return;
                }

                echo '
                    <div class="attak">
                    <div class="user">
                        <p>戰士</p>
                        <p>HP ： '.$_SESSION['userNowHp'].' / '.$_SESSION['user']['hp'].'</p>
                        <p>ATK ： '.$_SESSION['user']['atk'].'</p>
                        <p>DEF ： '.$_SESSION['user']['def'].'</p>
                    </div>
                    <div class="monster">
                        <p>'.$monster['name'].'</p>
                        <p>HP ： '.$_SESSION['monsterNowHp'].' / '.$monster['hp'].'</p>
                        <p>ATK ： '.$monster['atk'].'</p>
                        <p>DEF ： '.$monster['def'].'</p>
                    </div>
                    <div class="fight_btns meet">
                        <a class="titleBtn fight_btn" onclick="fighting()">打他!!!</a>
                        <a href="?page=runaway" class="titleBtn fight_btn">逃跑~~</a>
                    </div> 
                </div>
                    ';
            break;
        }
    }
    // 指定送表單
    private function send(){
        $model = new Model;
        $stages = $model->select('stage');
        $users = $model->select('user_account');
        
        $this->send .= '<label for="name">選擇玩家</label>
        <br />
        <select class="select" name="name" id="name">';
        foreach($users as $key => $user){
            if($user['admin'] == 1){
            }
            else{
                $this->send .= '<option value="'.$user['account'].'">'.$user['name'].'</option>';
            }
        }
        $this->send .= '</select>
        <br />
        <label for="stage">選擇道具</label>
        <br />
        <select name="stage" id="stage">';
        foreach($stages as $key => $stage){
            $this->send .= '<option value="'.$stage['name'].'">'.$stage['name'].'</option>';
        }
        $this->send .= '</select>
        <br />
        <label for="num">選擇數量</label>
        <br />       
        <input class="text" type="text" name="num" id="num" />';
    }
    // ---------------------------共用功能---------------------------
    // 防止刷新頁面攻擊
    public function defAttack(){
        $ll_nowtime = time();
        //判断session是否存在 如果存在从session取值，如果不存在进行初始化赋值
        if (isset($_SESSION['ll_times']) && isset($_SESSION['ll_lasttime'])){
            $ll_lasttime = $_SESSION['ll_lasttime'];
            $ll_times = $_SESSION['ll_times'] + 1;
            $_SESSION['ll_times'] = $ll_times;
        }else{
            $ll_lasttime = $ll_nowtime;
            $ll_times = 1;
            $_SESSION['ll_times'] = $ll_times;
            $_SESSION['ll_lasttime'] = $ll_lasttime;
        }
        //现在时间-开始登录时间 来进行判断 如果登录频繁 跳转 否则对session进行赋值
        if(($ll_nowtime - $ll_lasttime) < 3){
            if ($ll_times>=5){
                session_destroy();
                // die(0);
                $this->alert('太頻繁刷新頁面，請重新登入','?page');
            }
            }else{
                $ll_times = 0;
                $_SESSION['ll_lasttime'] = $ll_nowtime;
                $_SESSION['ll_times'] = $ll_times;
            }
    }
    // 防禦修改選單value攻擊
    private function checkInput($key,$data,$table){
        $model = new Model;
        $selects = $model -> select($table);

        foreach($selects as $key => $select){
            foreach($select as $key1 => $select1){
                if($select[$key1] == $data){
                    return true;
                }
            }
            
        }
        return false;
    }
    // 字串過濾
    private function strFilter($chars,$except='',$encoding='utf8'){

        $pattern = ($encoding=='utf8') ? "/[\x{4e00}-\x{9fa5}a-zA-Z0-9".$except." ]/u":'/[\x80-\xFF]/';
        preg_match_all($pattern,$chars,$result);   
        $fullStr = join('',$result[0]);
    
        // 反斜線例外處理
        $TryStrpos=strpos($fullStr,"\\");
        if($TryStrpos){
            $fullStr = substr_replace($fullStr, "\\", $TryStrpos+1, 0);
        }
        return $fullStr;
    }
    // 英數過濾
    private function wordFilter($chars,$except='',$encoding='utf8'){

        $pattern = ($encoding=='utf8') ? "/^[a-zA-Z0-9".$except." ]{6,12}$/u":'/[\x80-\xFF]/';
        preg_match_all($pattern,$chars,$result);   
        $fullStr = join('',$result[0]);
    
        // 反斜線例外處理
        $TryStrpos=strpos($fullStr,"\\");
        if($TryStrpos){
            $fullStr = substr_replace($fullStr, "\\", $TryStrpos+1, 0);
        }
        return $fullStr;
    }
    // 產生亂數字串
    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    // 錯誤彈窗跳轉
    private function alert($msg,$where = ''){
        require_once './View/Pages/alert.php';
    }

}
