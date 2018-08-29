<?php
/*
	File:		login.php
	Created: 	4/5/2016 at 12:17AM Eastern Time
	Info: 		The main page when not logged in.
	Author:		TheMasterGeneral
	Website: 	https://github.com/MasterGeneral156/chivalry-engine
*/
if ((!file_exists('./installer.lock')) && (file_exists('installer.php'))) {
    header("Location: installer.php");
    die();
}
require("globals_nonauth.php");
$currentpage = $_SERVER['REQUEST_URI'];
$cpage = strip_tags(stripslashes($currentpage));
$domain = determine_game_urlbase();
?>
<div class="row">
    <div class="column">
      <div class="ui message">
        <h1 class="ui header"><?php echo $set['WebsiteName']; ?></h1>
        <p><?php echo $set['Website_Description']; ?></p>
        <a class="ui blue button" href="register.php">Register &raquo;</a>
      </div>
    </div>
  </div>
  <br />
<?php
$AnnouncementQuery = $db->query("SELECT `ann_text`,`ann_time` FROM `announcements` ORDER BY `ann_time` desc LIMIT 1");
$ANN = $db->fetch_row($AnnouncementQuery);
echo "<div class='ui link cards centered'>
	<div class='ui card'>
		<div class='content'>
			<div class='header'>
				Latest Announcement
			</div>
		</div>
		<div class='content'>
			{$ANN['ann_text']}
		</div>
	</div>
	<div class='ui card'>
		<div class='content'>
			<div class='header'>
				Top 10 Players
			</div>
		</div>
		<div class='content'>";
$Rank = 0;
$RankPlayerQuery =
    $db->query("SELECT u.`userid`, `level`, `username`,
                `strength`, `agility`, `guard`, `labor`, `IQ`
                FROM `users` AS `u`
                INNER JOIN `userstats` AS `us`
                 ON `u`.`userid` = `us`.`userid`
                WHERE `u`.`user_level` != 'Admin' AND `u`.`user_level` != 'NPC'
                ORDER BY (`strength` + `agility` + `guard` + `labor` + `IQ`)
                DESC, `u`.`userid` ASC
                LIMIT 10");
while ($pdata = $db->fetch_row($RankPlayerQuery)) {
    $Rank = $Rank + 1;
    echo "{$Rank}) {$pdata['username']} [{$pdata['userid']}] (Level {$pdata['level']})<br />";
}
echo "</div>
</div>
<div class='ui card'>
		<div class='content'>
			<div class='header'>
				Top 10 Guilds
			</div>
		</div>
		<div class='content'>";
$GRank = 0;
$RankGuildQuery = $db->query("SELECT `guild_name`,`guild_level` FROM `guild` ORDER BY `guild_level` desc LIMIT 10");
echo "
            <div class='card-body'>";
while ($gdata = $db->fetch_row($RankGuildQuery)) {
    $GRank = $GRank + 1;
    echo "{$GRank}) {$gdata['guild_name']} (Level {$gdata['guild_level']})<br />";
}
echo "</div>
        </div>
    </div>";
$h->endpage();