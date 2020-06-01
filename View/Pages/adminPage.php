
  <div class="header">
      <span class="titleText">管理者介面</span>
      <form action="?page=sendRewards" method="post">
        <input type="hidden" name="pass" value="afg4654gx1cv61zx491fv" />
        <input type="submit" value="發水果囉" class="titleBtn center"  />
      </form>
      <?php echo $this->status ?>
  </div>
    <div class="container">
      <div class="show_list">
        <?php echo $this->li_filter ?>
      </div>
    </div>

    </body>
</html>