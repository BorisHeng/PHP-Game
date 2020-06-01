<?php
require_once './Control/Control.php';
use Controller\Control as Control;

$show = new Control;

if(isset($_GET['ajax'])){
    switch($_GET['ajax']){
        case 'fighting':
            $show -> fighting();
        break;
        case 'mission':
            $show -> mission();
        break;
    }
    return;
}

if(isset($_GET['page'])){
    require_once './View/header.php';
    switch ($_GET['page'])
    { 
        // 實際頁面的路由
        case 'register':
            $show->defAttack();
            $show -> showPage('register');
        break; 
        case 'userPage':
            $show -> showPage('userPage');
        break; 
        case 'adminPage':
            $show -> showPage('adminPage');
        break; 
        case 'modifyPage':
            $show->defAttack();
            $show -> showPage('modifyPage');
        break; 
        case 'sendPage':
            $show->defAttack();
            $show -> showPage('sendPage');
        break; 
        case 'fightingPage':
            $show -> showPage('fightingPage');
        break; 
        // 功能型的路由
        case 'checklogin':
            $show -> checkLogin();
        break; 
        case 'sendRewards':
            $show -> sendRewards();
        break; 
        case 'sendPerson':
            $show -> sendPerson();
        break; 
        case 'modify':
            $show -> modify();
        break; 
        case 'mission':
            $show -> mission();
        break; 
        case 'finishMission':
            $show -> finishMission();
            $show -> fighting();
        break; 
        case 'deleteItem':
            $show -> deleteItem();
        break; 
        case 'runaway':
            $show -> runaway();
        break; 
        // 其他條件
        default:
            if(is_numeric($_GET['page'])){
                $show -> showPage($_SESSION['nowPage'],$_GET['page']);
            }
            else{
                session_destroy();
                $show -> showPage();
            }
            
    }
    require_once './View/footer.php';
  }
else{
    session_destroy();
    require_once './View/header.php';
    $show -> showPage();
    require_once './View/footer.php';
  }

