

<div class="container">
      <div class="show_list login">
        <form class="setCol" action="?page=checklogin" method="post">
          <label for="new_account">申請帳號</label>
          <br />
          <input class="text" type="text" name="new_account" id="new_account" />
          <br />
          <label for="new_password">申請密碼</label>
          <br />
          <input class="text" type="password" name="new_password" id="new_password" />
          <br />
          <label for="name">設定名稱</label>
          <br />
          <input class="text" type="text" name="name" id="name" />
          <br />
          <label for="career">選擇職業</label>
          <br />
          <select name="career" id="career">
            <option value="劍士">劍士</option>
            <option value="刺客">刺客</option>
            <option value="坦克">坦克</option>
            <option value="弓箭手">弓箭手</option>
            <option value="路人">路人</option>
          </select>
          <br />
          <div class="btnControl">
            <a href="?page=login" class="titleBtn form">重新登入</a>
            <input type="submit" value="送出" class="titleBtn form"  />
          </div>
        </form>
      </div>
</div>