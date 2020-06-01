
<div class="container">
      <!-- <a href="?page"><img src="./View/img/back.png" alt=""></a> -->
      <div class="show_list login">
        <form class="setCol" action="?page=checklogin" method="post">
          <label for="account">請輸入帳號</label>
          <br />
          <input class="text" type="text" name="account" id="account" />
          <br />
          <label for="password">請輸入密碼</label>
          <br />
          <input class="text" type="password" name="password" id="password" />
          <br />
          <input class="key" type="hidden" name="keyPage" value="<?php echo $_SESSION['keyPage'];?>">
          <div class="btnControl">
            <input type="submit" value="送出" class="titleBtn form"  />
            <a href="?page=register" class="titleBtn form">註冊</a>
          </div>
        </form>
      </div>
</div>