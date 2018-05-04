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
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This file defines the English(USV4)keyboard layout.
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
 require_login($course, true, $cm);
?>
<div id="innerKeyboard" style="margin: 0px auto;display: inline-block;
<?php
echo (isset($displaynone) && ($displaynone == true)) ? 'display:none;' : '';
?>
">
<div id="keyboard" class="keyboardback">English(USV4) Keyboard Layout<br>
    <section>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeybackquote" class="normal"><b>~<br>`</b></div>
            <div id="jkey1" class="normal"><b>!<br>1</b></div>
            <div id="jkey2" class="normal"><b>@<br>2</b></div>
            <div id="jkey3" class="normal"><b>#<br>3</b></div>
            <div id="jkey4" class="normal"><b>$<br>4</b></div>
            <div id="jkey5" class="normal"><b>%<br>5</b></div>
            <div id="jkey6" class="normal"><b>^<br>6</b></div>
            <div id="jkey7" class="normal"><b>&<br>7</b></div>
            <div id="jkey8" class="normal"><b>*<br>8</b></div>
            <div id="jkey9" class="normal"><b>(<br>9</b></div>
            <div id="jkey0" class="normal"><b>)<br>0</b></div>
            <div id="jkeyminus" class="normal"><b>_<br>-</b></div>
            <div id="jkeyequals" class="normal"><b>+<br>=</b></div>
            <div id="jkeybackspace" class="normal" style="width: 95px;">Backspace</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeytab" class="normal" style="width: 60px;">Tab</div>
            <div id="jkeyq" class="normal">Q</div>
            <div id="jkeyw" class="normal">W</div>
            <div id="jkeye" class="normal">E</div>
            <div id="jkeyr" class="normal">R</div>
            <div id="jkeyt" class="normal">T</div>
            <div id="jkeyy" class="normal">Y</div>
            <div id="jkeyu" class="normal">U</div>
            <div id="jkeyi" class="normal">I</div>
            <div id="jkeyo" class="normal">O</div>
            <div id="jkeyp" class="normal">P</div>
            <div id="jkeybracketl" class="normal" style='font-size: 15px !important; line-height: 15px'><b>{<br>[</b></div>
            <div id="jkeybracketr" class="normal" style='font-size: 15px !important; line-height: 15px'><b>}<br>]</b></div>
            <div id="jkeybackslash" class="normal" style='width: 75px;font-size: 12px !important; line-height: 15px'>
                <b>|<br>\</b></div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeycaps" class="normal" style="width: 80px;  font-size: 12px !important;">Caps Lock</div>
            <div id="jkeya" class="finger4">A</div>
            <div id="jkeys" class="finger3">S</div>
            <div id="jkeyd" class="finger2">D</div>
            <div id="jkeyf" class="finger1">F</div>
            <div id="jkeyg" class="normal">G</div>
            <div id="jkeyh" class="normal">H</div>
            <div id="jkeyj" class="finger1">J</div>
            <div id="jkeyk" class="finger2">K</div>
            <div id="jkeyl" class="finger3">L</div>
            <div id="jkeysemicolon" class="finger4" style='font-size: 15px !important; line-height: 15px'><b>:<br>;</b></div>
            <div id="jkeycrtica" class="normal" style='font-size: 15px !important; line-height: 15px'><b>"<br>'</b></div>
            <div id="jkeyenter" class="normal" style="width: 95px;">Enter</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeyshiftl" class="normal" style="width: 100px;">Shift</div>
            <div id="jkeyz" class="normal">Z</div>
            <div id="jkeyx" class="normal">X</div>
            <div id="jkeyc" class="normal">C</div>
            <div id="jkeyv" class="normal">V</div>
            <div id="jkeyb" class="normal">B</div>
            <div id="jkeyn" class="normal">N</div>
            <div id="jkeym" class="normal">M</div>
            <div id="jkeycomma" class="normal"><b>&lt;<br>,</b></div>
            <div id="jkeyperiod" class="normal"><b>&gt;<br>.</b></div>
            <div id="jkeyslash" class="normal"><b>?<br>/</b></div>
            <div id="jkeyshiftd" class="normal" style="width: 115px;">Shift</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px;'>
            <div id="jkeyctrll" class="normal" style="width: 60px;">Ctrl</div>
            <div id="jkeyfn" class="normal" style="width: 50px;">Fn</div>
            <div id="jkeyalt" class="normal" style="width: 50px;">Alt</div>
            <div id="jkeyspace" class="normal" style="width: 295px;">Space</div>
            <div id="jkeyaltgr" class="normal" style="width: 50px;">Alt</div>
            <div id="jkeyfn" class="normal" style="width: 50px;">Fn</div>
            <div id="jkeyctrlr" class="normal" style="width: 60px;">Ctrl</div>
        </div>
</section>
</div>
</div>
