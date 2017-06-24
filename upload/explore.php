<?php
/*
	File:		explore.php
	Created: 	4/5/2016 at 12:00AM Eastern Time
	Info: 		Gateway to many things around the game.
	Author:		TheMasterGeneral
	Website: 	https://github.com/MasterGeneral156/chivalry-engine
*/
require("globals.php");
//Anti-refresh RNG.
$tresder = (Random(100, 999));
$time=time();
//Select users in infirmary and dungeon to list later on the page.
$dung_count=$db->fetch_single($db->query("SELECT COUNT(`dungeon_user`) FROM `dungeon` WHERE `dungeon_out` > {$time}"));
$infirm_count=$db->fetch_single($db->query("SELECT COUNT(`infirmary_user`) FROM `infirmary` WHERE `infirmary_out` > {$time}"));
if (empty($dung_count))
{
	$dung_count=0;
}
if (empty($infirm_count))
{
	$infirm_count=0;
}
//Block access if user is in the infirmary.
if ($api->UserStatus($ir['userid'],'infirmary') == true)
{
	alert('danger',$lang["GEN_INFIRM"],$lang['ERRDE_EXPLORE'],false);
	die($h->endpage());
}
//Block access if user is in the dungeon.
if ($api->UserStatus($ir['userid'],'dungeon') == true)
{
	alert('danger',$lang["GEN_DUNG"],$lang['ERRDE_EXPLORE2']);
	die($h->endpage());
}
echo"<h4>{$lang['EXPLORE_INTRO']}</h4></div>
<div class='col-md-4'>
	<ul class='nav nav-pills flex-column'>
		<li class='nav-item'><a class='nav-link' data-toggle='tab' href='#SHOPS'>{$lang['EXPLORE_SHOP']}</a></li>
		<li class='nav-item'><a class='nav-link' data-toggle='tab' href='#FD'>{$lang['EXPLORE_FD']}</a></li>
		<li class='nav-item'><a class='nav-link' data-toggle='tab' href='#HL'>{$lang['EXPLORE_HL']}</a></li>
		<li class='nav-item'><a class='nav-link' data-toggle='tab' href='#ADMIN'>{$lang['EXPLORE_ADMIN']}</a></li>
		<li class='nav-item'><a class='nav-link' data-toggle='tab' href='#GAMES'>{$lang['EXPLORE_GAMES']}</a></li>
		<li class='nav-item'><a class='nav-link' data-toggle='tab' href='#GUILDS'>{$lang['EXPLORE_GUILDS']}</a></li>
		<li class='nav-item'><a class='nav-link' data-toggle='tab' href='#ACT'>{$lang['EXPLORE_ACT']}</a></li>
		<li class='nav-item'><a class='nav-link' data-toggle='tab' href='#PINTER'>{$lang['EXPLORE_PINTER']}</a></li>
	</ul>
</div>
<div class='col-md-4'>
	<div class='tab-content'>
		<div id='SHOPS' class='tab-pane'>
			<div class='card card-default'>
				<div class='card-block'>
					<a href='shops.php'>{$lang['EXPLORE_LSHOP']}</a><br />
					<a href='itemmarket.php'>{$lang['EXPLORE_IMARKET']}</a><br />
					<a href='itemauction.php'>{$lang['EXPLORE_IAUCTION']}</a><br />
					<a href='#'>{$lang['EXPLORE_TRADE']}</a><br />
					<a href='secmarket.php'>{$lang['EXPLORE_SCMARKET']}</a><br />	
				</div>
			</div>
		</div>
		<div id='FD' class='tab-pane'>
			<div class='card card-default'>
				<div class='card-block'>
					<a href='bank.php'>{$lang['EXPLORE_BANK']}</a><br />
					<a href='estates.php'>{$lang['EXPLORE_ESTATES']}</a><br />
					<a href='travel.php'>{$lang['EXPLORE_TRAVEL']}</a><br />
					<a href='temple.php'>{$lang['EXPLORE_TEMPLE']}</a><br />
				</div>
			</div>
		</div>
		<div id='HL' class='tab-pane'>
			<div class='card card-default'>
				<div class='card-block'>
					<a href='mine.php'>{$lang['EXPLORE_MINE']}</a><br />
					<a href='smelt.php'>{$lang['EXPLORE_SMELT']}</a><br />
					<a href='#'>{$lang['EXPLORE_WC']}</a><br />
					<a href='#'>{$lang['EXPLORE_FARM']}</a><br />
					<a href='bottent.php'>{$lang['EXPLORE_BOTS']}</a><br />
				</div>
			</div>
		</div>
		<div id='ADMIN' class='tab-pane'>
			<div class='card card-default'>
				<div class='card-block'>
					<a href='users.php'>{$lang['EXPLORE_USERLIST']}</a><br />
					<a href='usersonline.php'>{$lang['UOL_TITLE']}</a><br />
					<a href='staff.php'>{$lang['EXPLORE_STAFFLIST']}</a><br />
					<a href='fedjail.php'>{$lang['EXPLORE_FED']}</a><br />
					<a href='stats.php'>{$lang['EXPLORE_STATS']}</a><br />
					<a href='playerreport.php'>{$lang['EXPLORE_REPORT']}</a><br />
					<a href='announcements.php'>{$lang['EXPLORE_ANNOUNCEMENTS']}</a><br />
				</div>
			</div>
		</div>
		<div id='GAMES' class='tab-pane'>
			<div class='card card-default'>
				<div class='card-block'>
					<a href='russianroulette.php'>{$lang['EXPLORE_RR']}</a><br />
					<a href='hilow.php?tresde={$tresder}'>{$lang['EXPLORE_HILO']}</a><br />
					<a href='roulette.php?tresde={$tresder}'>{$lang['EXPLORE_ROULETTE']}</a><br />
					<a href='slots.php?tresde={$tresder}'>{$lang['EXPLORE_SLOTS']}</a><br />
				</div>
			</div>
		</div>
		<div id='GUILDS' class='tab-pane'>
			<div class='card card-default'>
				<div class='card-block'>";
                    //User is in a guild.
					if ($ir['guild'] > 0)
					{
						echo "<a href='viewguild.php'>{$lang['EXPLORE_YOURGUILD']}</a><br />";
					}
					echo "
					<a href='guilds.php'>{$lang['EXPLORE_GUILDLIST']}</a><br />
					<a href='guilds.php?action=wars'>{$lang['EXPLORE_WARS']}</a><br />
				</div>
			</div>
		</div>
		<div id='ACT' class='tab-pane'>
			<div class='card card-default'>
				<div class='card-block'>
					<a href='dungeon.php'>{$lang['EXPLORE_DUNG']} <span class='badge badge-pill badge-default'>{$dung_count}</span></a><br />
					<a href='infirmary.php'>{$lang['EXPLORE_INFIRM']} <span class='badge badge-pill badge-default'>{$infirm_count}</span></a><br />
					<a href='gym.php'>{$lang['EXPLORE_GYM']}</a><br />
					<a href='#'>{$lang['EXPLORE_JOB']}</a><br />
					<a href='academy.php'>{$lang['EXPLORE_ACADEMY']}</a><br />
					<a href='criminal.php'>{$lang['EXPLORE_CRIMES']}</a><br />
					<a href='tutorial.php'>{$lang['EXPLORE_TUTORIAL']}</a><br />
				</div>
			</div>
		</div>
		<div id='PINTER' class='tab-pane'>
			<div class='card card-default'>
				<div class='card-block'>
					<a href='forums.php'>{$lang['EXPLORE_FORUMS']}</a><br />
					<a href='newspaper.php'>{$lang['EXPLORE_NEWSPAPER']}</a><br />
					<a href='polling.php'>{$lang['POLL_TITLE']}</a><br />
					<a href='halloffame.php'>{$lang['HOF_TITLE']}</a><br />
				</div>
			</div>
		</div>
	</div>
</div>
<div class='col-md-4'>
	<div class='card card-default'>
		<div class='card-header'>
			{$lang['EXPLORE_TOPTEN']}
		</div>
		<div class='card-block'>";
			$Rank=0;
			$RankPlayerQuery = 
			$db->query("SELECT u.`userid`, `level`, `username`,
			`strength`, `agility`, `guard`, `labor`, `IQ`
			FROM `users` AS `u`
			INNER JOIN `userstats` AS `us`
			ON `u`.`userid` = `us`.`userid`
			WHERE `u`.`user_level` != 'Admin' AND `u`.`user_level` != 'NPC'
			ORDER BY (`strength` + `agility` + `guard` + `labor` + `IQ`) 
			DESC, `u`.`userid` ASC LIMIT 10");
            //Shop the top 10 strongest players in the game.
			while ($pdata=$db->fetch_row($RankPlayerQuery))
			{
				$Rank=$Rank+1;
				echo "{$Rank}) <a href='profile.php?user={$pdata['userid']}'>{$pdata['username']}</a> ({$lang['INDEX_LEVEL']} {$pdata['level']})<br />";
			}
			echo 
		"</div>
	</div>
</div>
</div>";
//referral link.
echo "	<div class='row'>
			<div class='col-md-12'>
				{$lang['EXPLORE_REF']}<br />
				<code>http://{$domain}/register.php?REF={$userid}</code>
			</div>
		</div>";
$h->endpage();