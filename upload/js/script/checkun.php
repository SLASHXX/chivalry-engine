<?php
/*
	File: js//script/checkun.php
	Created: 4/4/2017 at 7:10PM Eastern Time
	Info: PHP file for checking a user's inputted username
	Author: TheMasterGeneral
	Website: https://github.com/MasterGeneral156/chivalry-engine
*/
$menuhide = 1;
if (isset($_SERVER['REQUEST_METHOD']) && is_string($_SERVER['REQUEST_METHOD'])) {
    if (strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST') {
        // Ignore a GET request
        header('HTTP/1.1 400 Bad Request');
        exit;
    }
}
require_once('../../global_func.php');
if (!is_ajax()) {
    header('HTTP/1.1 400 Bad Request');
    exit;
}
require_once('../../globals_nonauth.php');
$username = isset($_POST['username']) ? stripslashes($_POST['username']) : '';
$e_username = $db->escape($username);
$q = $db->query("SELECT COUNT(`userid`) FROM users WHERE username = '{$e_username}'");
if (empty($username)) {
    $newclass = 'field error';
    $warning = "Please enter a username.";

} else if ((strlen($username) < 3)) {
    $newclass = 'field error';
    $warning = "Username must be, at least, 3 characters long.";
} else if ((strlen($username) > 21)) {
    $newclass = 'field error';
    $warning = "Username must be, at most, 20 characters long.";
} else if ($db->fetch_single($q)) {
    $newclass = 'field error';
    $warning = "Username already in use.";
} else {
    $newclass = 'field';
    $warning = "";
}
?>
    <script>
        var d = document.getElementById("usernamefield");
        var div = document.getElementById("usernameresult");
        d.className = " <?php echo $newclass; ?>";
        div.innerHTML = " <?php echo $warning; ?>";
    </script>
<?php
$db->free_result($q);
