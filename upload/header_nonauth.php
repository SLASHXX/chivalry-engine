<?php

/*
	File:		header_nonauth.php
	Created: 	4/5/2016 at 12:05AM Eastern Time
	Info: 		Class file to load the template when outside of the game.
	Author:		TheMasterGeneral
	Website: 	https://github.com/MasterGeneral156/chivalry-engine
*/

class headers
{
    function startheaders()
    {
        global $set, $h, $db, $menuhide;
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
			<center>
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
			<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.3.3/dist/semantic.min.css">
			<link rel="stylesheet" href="css/game.css">
			<link rel="shortcut icon" href="" type="image/x-icon"/>
			<meta name="author" content="<?php echo $set['WebsiteOwner']; ?>">
			<?php echo "<title>{$set['WebsiteName']}</title>"; ?>
        </head>
        <body>
        <?php
        if (!isset($menuhide)) 
		{
            $csrf = request_csrf_html('login');
			?>
			<!-- Following Menu -->
			<div class="ui inverted menu fixed">
			  <div class="ui container">
				<a class="item" href="index.php"><?php echo $set['WebsiteName']; ?></a>
				<a class="item" href="gamerules2.php">Rules</a>
				<div class="right menu">
					<a class="item" href="loginform.php">Log in</a>
					<a class="item" href="register.php">Sign Up</a>
				</div>
			  </div>
			</div>
			<div class="ui container">
			<noscript>
				<?php alert('info', "Information!", "Please enable Javascript.", false); ?>
			</noscript>
			<?php
			$IP = $db->escape($_SERVER['REMOTE_ADDR']);
			$ipq = $db->query("SELECT `ip_id` FROM `ipban` WHERE `ip_ip` = '{$IP}'");
			if ($db->num_rows($ipq) > 0) 
			{
				alert('danger', "Uh Oh!", "You are currently IP Banned. Sorry about that.", false);
				die($h->endpage());
			}
		}
	}

    function endpage()
    {
        global $db, $ir, $set;
        $query_extra = '';
    if (isset($_GET['mysqldebug']) && $ir['user_level'] == 'Admin')
    {
        ?>
        <pre class='pre-scrollable'> <?php var_dump($db->queries) ?> </pre> <?php
    }
    ?>
        </div>
        </div>
        <!-- /.row -->

        </div>
        <!-- /.container -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/semantic-ui@2.3.3/dist/semantic.min.js"></script>
		<script src="js/register.js"></script>
        </body>
        <footer>
            <p>
                <br/>
                <?php
                echo "<hr />
					Time is now " . date('F j, Y') . " " . date('g:i:s a') . "<br />
					Powered by <a href='https://github.com/MasterGeneral156/chivalry-engine'>codes</a> by TheMasterGeneral.
					{$set['WebsiteName']} &copy; " . date("Y") . " {$set['WebsiteOwner']}.";?>
            </p>
        </footer>
        </html>
    <?php
    }
}