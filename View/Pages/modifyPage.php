
  <div class="container">
        <a href="?page=userPage"><img src="./View/img/back.png" alt=""></a>
        <div class="show_list login">
          <form class="setCol" action="?page=modify" method="post">
            <label for="password">修改密碼</label>
            <br />
            <input class="text" type="password" name="password" id="password" placeholder="要改密碼在點我" />
            <br />
            <label for="checkPassword">確認密碼</label>
            <br />
            <input class="text" type="password" name="checkPassword" id="checkPassword" placeholder="要改密碼在點我" />
            <br />
            <label for="name">修改名稱</label>
            <br />       
            <input class="text" type="text" value="<?php echo $_SESSION['userName'] ?>" name="name" id="name" />        
            <br />
            <div class="spacing">
              <input type="hidden" id="csToken" name="CSR" value="<?php echo $_SESSION['token'] ?>"/>
            </div>
            <div class="btnControl">
              <input type="submit" value="送出" class="titleBtn form"  />
            </div>
          </form>
        </div>
  </div>
