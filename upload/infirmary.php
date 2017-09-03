<?php
/*
	File:		infirmary.php
	Created: 	4/5/2016 at 12:11AM Eastern Time
	Info: 		Lists the players currently in the infirmary, and allows
				them to heal those players out using secondary currency.
	Author:		TheMasterGeneral
	Website: 	https://github.com/MasterGeneral156/chivalry-engine
*/
require("globals.php");
if (!isset($_GET['action']))
{
    $_GET['action'] = '';
}
switch ($_GET['action'])
{
case 'heal':
    heal();
    break;
default:
    home();
    break;
}
function home()
{
	global $db,$api;
	$CurrentTime=time();
	$PlayerCount=$db->fetch_single($db->query("SELECT COUNT(`infirmary_user`) FROM `infirmary` WHERE `infirmary_out` > {$CurrentTime}"));
	echo "<h3>The Infirmary</h3><hr />
	<small>There's currently " . number_format($PlayerCount) . " users in the infirmary.</small>
	<hr />
	<table class='table table-hover table-bordered'>
		<thead>
			<tr>
				<th>
					User
				</th>
				<th>
					Reason
				</th>
				<th class='hidden-xs'>
					Check-In
				</th>
				<th>
					Check-out
				</th>
				<th>
					Actions
				</th>
			</tr>
		</thead>
		<tbody>";
	$query = $db->query("SELECT * FROM `infirmary` WHERE `infirmary_out` > {$CurrentTime} ORDER BY `infirmary_out` DESC");
	while ($Infirmary=$db->fetch_row($query))
	{
		echo "
			<tr>
				<td>
					<a href='profile.php?user={$Infirmary['infirmary_user']}'>
						{$api->SystemUserIDtoName($Infirmary['infirmary_user'])}
					</a> [{$Infirmary['infirmary_user']}]
				</td>
				<td>
					{$Infirmary['infirmary_reason']}
				</td>
				<td class='hidden-xs'>
					" . DateTime_Parse($Infirmary['infirmary_in']) . "
				</td>
				<td>
					" . TimeUntil_Parse($Infirmary['infirmary_out']) . "
				</td>
				<td>
					[<a href='?action=heal&user={$Infirmary['infirmary_user']}'>Heal User</a>]
				</td>
			</tr>";
	}
	echo "</tbody></table>";
}
function heal()
{
	global $db,$api,$h,$userid,$ir;
	if (isset($_GET['user']))
	{
		$_GET['user'] = (isset($_GET['user']) && is_numeric($_GET['user'])) ? abs($_GET['user']) : 0;
		if (empty($_GET['user']) || $_GET['user'] == 0)
		{
			alert('danger',"Uh Oh!","You are attempting to heal an invalid or non-existent user.",true,'infirmary.php');
			die($h->endpage());
		}
		if ($api->UserStatus($_GET['user'],'infirmary') == false)
		{
			alert('danger',"Uh Oh!","You are attempting to heal out a player who's not even in the infirmary.",true,'infirmary.php');
			die($h->endpage());
		}
        if (isset($_GET['times']))
        {
            $_GET['times'] = (isset($_GET['times']) && is_numeric($_GET['times'])) ? abs($_GET['times']) : 0;
            if (empty($_GET['times']))
            {
                alert('danger',"Uh Oh!","You are attempting to heal an invalid or non-existent user.",true,'infirmary.php');
                die($h->endpage());
            }
            $cost=25*$_GET['times'];
            $time=30*$_GET['times'];
            if ($ir['secondary_currency'] < $cost)
            {
                alert('danger',"Uh Oh!","You do not have enough Secondary Currency to heal {$_GET['times']} sets.",true,'infirmary.php');
                die($h->endpage());
            }
            else
            {
                $api->UserStatusSet($_GET['user'],'infirmary',$time*-1,'Not read');
                $api->UserTakeCurrency($userid,'secondary',$cost);
                $api->GameAddNotification($_GET['user'],"<a href='profile.php?user={$userid}'>{$ir['username']}</a> has healed you {$_GET['times']} times.");
                $api->SystemLogsAdd($userid,'heal',"Healed {$api->SystemUserIDtoName($_GET['user'])} {$_GET['times']} times.");
                alert('success',"Success!","You have healed {$api->SystemUserIDtoName($_GET['user'])} {$_GET['times']}
                times, costing you {$cost} Secondary Currency.",true,'index.php');
            }
        }
        else
        {
            echo "How many times do you wish to heal {$api->SystemUserIDtoName($_GET['user'])}?<br />
            1 Set = 30 minutes<br />
            1 Set = 25 Secondary Currency<br />
            <form>
                <input type='hidden' name='user' value='{$_GET['user']}'>
                <input type='hidden' name='action' value='heal'>
                <input type='number' required='1' name='times' class='form-control'>
                <input type='submit' class='btn btn-primary' value='Heal'>
            </form>";
        }
	}
	else
	{
		alert('danger',"Uh Oh!","Please specify a user you wish to heal out.",true,'infirmary.php');
	}
}
$h->endpage();