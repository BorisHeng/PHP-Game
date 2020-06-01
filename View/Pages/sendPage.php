
  <div class="container">
        <a href="?page=userPage"><img src="./View/img/back.png" alt=""></a>
        <div class="show_list login">
          <form class="setCol" action="?page=sendPerson" method="post">
            <?php echo $this->send; ?>
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
