<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License fo r more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Version information
 *
 * @package    mod_evoting
 * @copyright  2016 Cyberlearn
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once ("../../config.php");

$idPoll = required_param('id', PARAM_INT);
$lang = required_param('lang', PARAM_TEXT);
?>

<!DOCTYPE html>
<html style="height:100%" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-Voting</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="styles.css">
    <script src="./js/jquery-3.1.0.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/html5shiv.min.js"></script>
    <script src="./js/respond.min.js"></script>
	<script src="./js/client_poll.js"></script>

<?php
echo "<body id=\"clientVote\" style=\"height:100%\" class=\"path-mod-evoting\">\n";
echo "	<div id=\"clientOptions\">		\n";
echo "	</div>	\n";
echo "		<span id=\"spanIdPoll\" style=\"display:none\">$idPoll</span>\n";
echo "		<div class='toastBg' style='display:none'></div>\n";
echo "		<div id='myToast' class='toast' style='display:none'><span></span></div>\n";
echo "		<div id='preloadImg' style='display:none'></div>\n";
echo "		<input id=\"lang\" type=\"hidden\" value=\"$lang\">\n";
echo '		<input id="sesskey" type="hidden" value="' . sesskey()  . '">';
echo "</body>\n";
?>


