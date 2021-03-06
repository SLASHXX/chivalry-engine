<?php
/*
	File: js//script/outputteam.php
	Created: 4/4/2017 at 7:10PM Eastern Time
	Info: PHP file for outputting info about the selected team
	when registering
	Author: TheMasterGeneral
	Website: https://github.com/MasterGeneral156/chivalry-engine
*/
$menuhide = 1;
if (isset($_SERVER['REQUEST_METHOD']) && is_string($_SERVER['REQUEST_METHOD'])) {
    if (strtoupper($_SERVER['REQUEST_METHOD']) !== 'POST') {
        // Ignore a GET request
        header('HTTP/1.1 400 Bad Request');
        //exit;
    }
}
require_once('../../global_func.php');
if (!is_ajax()) {
    header('HTTP/1.1 400 Bad Request');
    //exit;
}

require_once('../../globals_nonauth.php');
$class = $_POST['team'];
if ($class == 'Warrior') {
    $warning = "Warriors begin with more strength and less guard.";
    $newclass = 'form-control is-valid';
} elseif ($class == 'Rogue') {
    $warning = "Rogues start with more agility and less strength.";
    $newclass = 'form-control is-valid';
} elseif ($class == 'Defender') {
    $warning = "Defenders begin with more guard and less agility.";
    $newclass = 'form-control is-valid';
} else {
    $warning = "Please select a valid class.";
    $newclass = 'form-control is-invalid';
}
?>
    <script>
        var d = document.getElementById("class");
        var div = document.getElementById("teamresult");
        d.className = " <?php echo $newclass; ?>";
        div.innerHTML = " <?php echo $warning; ?>";
    </script>
<?php