
 <div class="header">
      <span class="titleText">道具清單</span>
      <!-- <a class="titleBtn build_guild" href="?page=buildGuildPage">創建公會</a> -->
      <?php echo $this->status ?>
  </div>
<div class="container">
    <div class="user_info">
        <?php echo $this->info ?>
    </div>
    <div class="fight_btns">
        <a href="?page=fightingPage" class="titleBtn fight_btn">我要打怪</a>
    </div>
    <?php echo $this->html_show_mission ?>
    <?php echo $this->html_show_nowMission ?>
    <div class="show_list">
        <?php echo $this->li_filter ?>
    </div>
</div>