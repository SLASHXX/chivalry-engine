<?php
/*
	File:		register.php
	Created: 	4/5/2016 at 12:24AM Eastern Time
	Info: 		The registration form.
	Author:		TheMasterGeneral
	Website: 	https://github.com/MasterGeneral156/chivalry-engine
*/
$menuhide=1;
require('globals_nonauth.php');
?>
  <style type="text/css">
    body {
      background-color: #DADADA;
    }
    body > .grid {
      height: 100%;
    }
    .image {
      margin-top: -100px;
    }
    .column {
      max-width: 450px;
    }
  </style>
  <?php
$IP = $db->escape($_SERVER['REMOTE_ADDR']);
//Check if someone is already registered on this IP.
if ($db->fetch_single($db->query("SELECT COUNT(`userid`) FROM `users` WHERE `lastip` = '{$IP}' OR `loginip` = '{$IP}' OR `registerip` = '{$IP}'")) >= 1) {
    alert('danger', "Uh Oh!", "You can only have one account per IP Address. We're going to stop you from registering for now.", true, 'login.php');
    die($h->endpage());

}
if (!isset($_GET['REF'])) {
    $_GET['REF'] = '';
}
$_GET['REF'] = abs($_GET['REF']);
if ($_GET['REF']) {
    $_GET['REF'] = $_GET['REF'];
}
$username = (isset($_POST['username']) && is_string($_POST['username'])) ? stripslashes($_POST['username']) : '';
if (!empty($username)) {
    //If the registration captcha is enabled.
    if ($set['RegistrationCaptcha'] == 'ON') {
        //If the user got the captcha wrong.
        if (!$_SESSION['captcha'] || !isset($_POST['captcha']) || $_SESSION['captcha'] != $_POST['captcha']) {
            unset($_SESSION['captcha']);
            alert('danger', "Uh Oh!", "You have failed the captcha.");
            die($h->endpage());

        }
        unset($_SESSION['captcha']);
    }
    //If the email is inputted, and valid.
    if (!isset($_POST['email']) || !valid_email(stripslashes($_POST['email']))) {
        alert('danger', "Uh Oh!", "You input an invalid email address.");
        die($h->endpage());

    }
    //If the username is empty
    if (empty($username)) {
        alert('danger', "Uh Oh!", "You input an invalid or empty username.");
        die($h->endpage());

    }
    //If the username is less than 3 characters and more than 20.
    if (((strlen($username) > 20) OR (strlen($username) < 3))) {
        alert('danger', "Uh Oh!", "Your username can only be 3 through 20 characters in length.");
        die($h->endpage());

    }
    //Check Gender
    if (!isset($_POST['gender']) || ($_POST['gender'] != 'Male' && $_POST['gender'] != 'Female')) {
        alert('danger', "Uh Oh!", "You are trying to register as an invalid sex.");
        die($h->endpage());

    }
    //Check class
    if (!isset($_POST['class']) || ($_POST['class'] != 'Warrior' && $_POST['class'] != 'Rogue' && $_POST['class'] != 'Defender')) {
        alert('danger', "Uh Oh!", "You are trying to register as an invalid class.");
        die($h->endpage());

    }
    $e_gender = $db->escape(stripslashes($_POST['gender']));
    $e_class = $db->escape(stripslashes($_POST['class']));
    $e_username = $db->escape($username);
    $e_email = $db->escape(stripslashes($_POST['email']));
    $q = $db->query("SELECT COUNT(`userid`) FROM `users` WHERE `username` = '{$e_username}'");
    $q2 = $db->query("SELECT COUNT(`userid`)  FROM `users` WHERE `email` = '{$e_email}'");
    $u_check = $db->fetch_single($q);
    $e_check = $db->fetch_single($q2);
    $db->free_result($q);
    $db->free_result($q2);
    $base_pw = (isset($_POST['password']) && is_string($_POST['password'])) ? stripslashes($_POST['password']) : '';
    $check_pw = (isset($_POST['cpassword']) && is_string($_POST['cpassword'])) ? stripslashes($_POST['cpassword']) : '';
    //Username is in use.
    if ($u_check > 0) {
        alert('danger', "Uh Oh!", "The username you've chosen is already in use.");
    } //Email is in use
    else if ($e_check > 0) {
        alert('danger', "Uh Oh!", "The email you've chosen is already in use.");
    } //Both passwords aren't entered
    else if (empty($base_pw) || empty($check_pw)) {
        alert('danger', "Uh Oh!", "You must specify a password and confirm it.");
    } //The entered passwords match.
    else if ($base_pw != $check_pw) {
        alert('danger', "Uh Oh!", "Your entered passwords did not match.");
    } else {
        $_POST['ref'] = (isset($_POST['ref']) && is_numeric($_POST['ref'])) ? abs($_POST['ref']) : '';
        $IP = $db->escape($_SERVER['REMOTE_ADDR']);
        //If the registrating user was referred to the game by someone.
        if ($_POST['ref']) {
            $q = $db->query("SELECT `lastip` FROM `users` WHERE `userid` = {$_POST['ref']}");
            //If referring does not exist.
            if ($db->num_rows($q) == 0) {
                $db->free_result($q);
                alert('danger', "Uh Oh!", "The user who referred you does not exist.");
                die($h->endpage());
            }
            $rem_IP = $db->fetch_single($q);
            $db->free_result($q);
            //If referring user has the same IP as the registering one.
            if ($rem_IP == $_SERVER['REMOTE_ADDR']) {
                alert('danger', "Uh Oh!", "You cannot use a referral ID from someone on your IP.");
                die($h->endpage());
            }
        }
        $encpsw = encode_password($base_pw);    //Encode the password.
        $e_encpsw = $db->escape($encpsw);
        $profilepic = "https://www.gravatar.com/avatar/" . md5(strtolower(trim($e_email))) . "?s=250.jpg";
        $CurrentTime = time();
        $db->query("INSERT INTO `users`
					(`username`,`email`,`password`,`level`,`gender`,`class`,
					`lastip`,`registerip`,`registertime`,`loginip`,`display_pic`)
					VALUES ('{$e_username}','{$e_email}','{$e_encpsw}','1','{$e_gender}',
					'{$e_class}','{$IP}','{$IP}','{$CurrentTime}', '127.0.0.1', 
					'{$profilepic}')");
        $i = $db->insert_id();
        $db->query("UPDATE `users` SET `brave`='10',`maxbrave`='10',`hp`='100',
					`maxhp`='100',`maxwill`='100',`will`='100',`energy`='24',
					`maxenergy`='24' WHERE `userid`={$i}");
        if ($e_class == 'Warrior') {
            $db->query(
                "INSERT INTO `userstats`
					 VALUES({$i}, 1100, 1000, 900, 1000, 1000)");
        }
        if ($e_class == 'Rogue') {
            $db->query(
                "INSERT INTO `userstats`
					 VALUES({$i}, 900, 1100, 1000, 1000, 1000)");
        }
        if ($e_class == 'Defender') {
            $db->query(
                "INSERT INTO `userstats`
					 VALUES({$i}, 1000, 900, 1100, 1000, 1000)");
        }
        if ($_POST['ref']) {
            $db->query("UPDATE `users` SET `secondary_currency` = `secondary_currency` + {$set['ReferalKickback']} WHERE `userid` = {$_POST['ref']}");
            notification_add($_POST['ref'], "For referring $username to the game, you have earned {$set['ReferalKickback']} valuable Secondary Currency(s)!");
            $e_rip = $db->escape($rem_IP);
            $db->query("INSERT INTO `referals`
			VALUES (NULL, {$_POST['ref']}, '{$e_rip}', {$i}, '{$IP}',{$CurrentTime})");
        }
        $db->query("INSERT INTO `infirmary`
			(`infirmary_user`, `infirmary_reason`, `infirmary_in`, `infirmary_out`) 
			VALUES ('{$i}', 'N/A', '0', '0');");
        $db->query("INSERT INTO `dungeon`
			(`dungeon_user`, `dungeon_reason`, `dungeon_in`, `dungeon_out`) 
			VALUES ('{$i}', 'N/A', '0', '0');");
        session_regenerate_id();
        $_SESSION['loggedin'] = 1;
        $_SESSION['userid'] = $i;
        $_SESSION['last_login'] = time();
        //Promo code reward
        if (isset($_POST['promo'])) {
            $code = (isset($_POST['promo'])) ? $db->escape(strip_tags(stripslashes($_POST['promo']))) : '';
            if (!empty($code)) {
                $promocodereal = $db->query("SELECT * FROM `promo_codes` WHERE `promo_code` = '{$code}'");
                if ($db->num_rows($promocodereal) > 0) {
                    $pcrr = $db->fetch_row($promocodereal);
                    item_add($i, $pcrr['promo_item'], 1);
                    $db->query("UPDATE `promo_codes` SET `promo_use` = `promo_use` + 1 WHERE `promo_code` = '{$code}'");
                    $api->GameAddNotification($i, "Your promotion code was valid! Check your inventory for your item!");
                } else {
                    $api->GameAddNotification($i, "Your promotion code was invalid.");
                }
            }
        }
        $api->SystemLogsAdd($_SESSION['userid'], 'login', "Successfully logged in.");
        $db->query("UPDATE `users` SET `loginip` = '$IP', `last_login` = '{$CurrentTime}', `laston` = '{$CurrentTime}' WHERE `userid` = {$i}");
        //User registered, lets log them in.
        alert('success', "Success!", "You have successfully signed up to play {$set['WebsiteName']}. Click here to <a href='tutorial.php'>Sign In</a>", false);
        $url=determine_game_urlbase();
        $WelcomeMSGEmail="Welcome to the game, {$e_username}!<br />We hope you enjoy our lovely game and stick around for a while! If you have any questions or concerns, please contact a staff member in-game!<br />Thank you!<br /> -{$set['WebsiteName']}<br /><a href='http://{$url}'>http://{$url}</a>";
        $api->SystemSendEmail($e_email,$WelcomeMSGEmail,"{$set['WebsiteName']} Registration",$set['sending_email']);
        die($h->endpage());
    }
    $h->endpage();
} else {
	?>
	<div class="ui middle aligned center aligned grid">
  <div class="column">
    <h2 class="ui teal image header">
      <img src="img/logo.png" class="image">
      <div class="content">
        Sign up for account
      </div>
    </h2>
    <form class="ui large form" method="post">
      <div class="ui stacked segment">
        <div class="field" id="usernamefield">
          <div class="ui left icon input">
            <i class="user icon"></i>
            <input type="text" name="username" id="username" placeholder="Username" onkeyup="CheckUsername(this.value);" required>
          </div>
		  <div id='usernameresult'></div>
        </div>
		<div class="field" id="emailfield">
          <div class="ui left icon input">
            <i class="envelope icon"></i>
            <input type="email" name="email" id="email" placeholder="E-mail Address" onkeyup="CheckEmail(this.value);" required><br />
          </div>
		  <div id='emailresult'></div>
        </div>
        <div class="field" id="passwordfield">
          <div class="ui left icon input">
            <i class="lock icon"></i>
            <input type="password" name="password" id="password" minxlength="3" maxlength="256" placeholder="Password" onkeyup="CheckPasswords(this.value);PasswordMatch();">
          </div>
		  <div id='passwordresult'></div>
        </div>
		<div class="field" id="cpasswordfield">
          <div class="ui left icon input">
            <i class="check icon"></i>
            <input type="password" name="cpassword" id="cpassword" minxlength="3" maxlength="256" placeholder="Confirm Password" onkeyup="PasswordMatch();" required>
          </div>
		  <div id='cpasswordresult'></div>
        </div>
		<div class="field">
          <div class="ui left icon input">
            <select name='gender' class='ui fluid dropdown' type='dropdown'>
				<option value='Male'>Male</option>
				<option value='Female'>Female</option>
			</select>
          </div>
        </div>
		<div class="field">
          <div class="ui left icon input">
            <select name='class' id='class' class='ui fluid dropdown' onchange='OutputTeam(this)' type='dropdown'>
				<option>Select your class</option>
				<option value='Warrior'>Warrior</option>
				<option value='Rogue'>Rogue</option>
				<option value='Defender'>Defender</option>
			</select>
          </div>
		  <div id='teamresult' class='invalid-feedback'></div>
        </div>
		<div class="field">
          <div class="ui left icon input">
            <i class="id card icon"></i>
            <input type="number" min="0" name="ref" placeholder="Referral ID" title="The User ID of the player who referred you. Can be empty." value='<?php echo $_GET['REF']; ?>'>
          </div>
        </div>
		<div class="field">
          <div class="ui left icon input">
            <i class="qrcode icon"></i>
            <input type="text" name="ref" placeholder="Promo code. Can be empty." title="Promo Code">
          </div>
        </div>
		<i>By clicking Register, you accept you have read the <a href='gamerules2.php'>Game Rules</a>
						and our <a href='privacy.php'>Privacy Policy</a>. You also agree that you wish to opt-in to our
						game newsletter. You may opt-out at anytime by checking your in-game settings.</i>
		<input type="submit" class="ui fluid large teal submit button" value="Register">
      </div>
    </form>

    <div class="ui message">
      Already have account? <a href="loginform.php">Log In</a>
    </div>
  </div>
</div>
<?php
}
$h->endpage();