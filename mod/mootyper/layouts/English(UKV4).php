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
 * This file defines the English(UKV4)keyboard layout.
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
<div id="keyboard" class="keyboardback">English(UKV4) Keyboard Layout<br>
    <section>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeybackquote" class="normal" style='text-align:left;'>¬<br>`
                <span style="color:blue">&nbsp;&nbsp;&nbsp;¦</span></div>
            <div id="jkey1" class="normal" style='text-align:left;'><b>!<br>1</b></div>
            <div id="jkey2" class="normal" style='text-align:left;'><b>"<br>2</b></div>
            <div id="jkey3" class="normal" style='text-align:left;'><b>£<br>3</b></div>
            <div id="jkey4" class="normal" style='text-align:left;'><b>$<br>4<span style="color:blue">&nbsp;&nbsp;&nbsp;€</b></span></div>
            <div id="jkey5" class="normal" style='text-align:left;'><b>%<br>5</b></div>
            <div id="jkey6" class="normal" style='text-align:left;'><b>^<br>6</b></div>
            <div id="jkey7" class="normal" style='text-align:left;'><b>&<br>7</b></div>
            <div id="jkey8" class="normal" style='text-align:left;'><b>*<br>8</b></div>
            <div id="jkey9" class="normal" style='text-align:left;'><b>(<br>9</b></div>
            <div id="jkey0" class="normal" style='text-align:left;'><b>)<br>0</b></div>
            <div id="jkeyminus" class="normal" style='text-align:left;'><b>_<br>-</b></div>
            <div id="jkeyequals" class="normal" style='text-align:left;'><b>+<br>=</b></div>
            <div id="jkeybackspace" class="normal" style="width: 95px;">Backspace</div>
        </div>
        <div style="float: left;">
            <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
                <div id="jkeytab" class="normal" style="width: 60px;">Tab</div>
                <div id="jkeyq" class="normal" style='text-align:left;'>Q</div>
                <div id="jkeyw" class="normal" style='text-align:left;'>W</div>
                <div id="jkeye" class="normal" style='text-align:left;'>E<span style="color:blue">&nbsp;&nbsp;&nbsp;É</span></div>
                <div id="jkeyr" class="normal" style='text-align:left;'>R</div>
                <div id="jkeyt" class="normal" style='text-align:left;'>T</div>
                <div id="jkeyy" class="normal" style='text-align:left;'>Y</div>
                <div id="jkeyu" class="normal" style='text-align:left;'>U
                    <span style="color:blue">&nbsp;&nbsp;&nbsp;Ú</span></div>
                <div id="jkeyi" class="normal" style='text-align:left;'>I
                    <span style="color:blue">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Í</span></div>
                <div id="jkeyo" class="normal" style='text-align:left;'>O
                    <span style="color:blue">&nbsp;&nbsp;&nbsp;Ó</span></div>
                <div id="jkeyp" class="normal" style='text-align:left;'>P</div>
                <div id="jkeybracketl" class="normal" style='text-align:left;'><b>{<br>[</b></div>
                <div id="jkeybracketr" class="normal" style='text-align:left;'><b>}<br>]</b></div>
            </div>
            <span id="jkeyenter" class="normal" style="width: 50px; margin-right:5px; float: right; height: 85px;">Enter</span>
            <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
                <div id="jkeycaps" class="normal" style="width: 80px;  font-size: 12px !important;">Caps Lock</div>
                <div id="jkeya" class="finger4" style='text-align:left;'>A
                    <span style="color:blue">&nbsp;&nbsp;&nbsp;Á</span></div>
                <div id="jkeys" class="finger3" style='text-align:left;'>S</div>
                <div id="jkeyd" class="finger2" style='text-align:left;'>D</div>
                <div id="jkeyf" class="finger1" style='text-align:left;'>F</div>
                <div id="jkeyg" class="normal" style='text-align:left;'>G</div>
                <div id="jkeyh" class="normal" style='text-align:left;'>H</div>
                <div id="jkeyj" class="finger1" style='text-align:left;'>J</div>
                <div id="jkeyk" class="finger2" style='text-align:left;'>K</div>
                <div id="jkeyl" class="finger3" style='text-align:left;'>L</div>
                <div id="jkeysemicolon" class="finger4" style='text-align:left;'><b>:<br>;</b></div>
                <div id="jkeycrtica" class="normal" style='text-align:left;'><b>@<br>'</b></div>
                <div id="jkey#" class="normal" style='text-align:left;'>~<br>#</div>
            </div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeyshiftl" class="normal" style="width: 60px;">Shift</div>
            <div id="jkeybackslash" class="normal" style='font-size: 12px !important; line-height: 15px'>
                <b>|<br>\</b></div>
            <div id="jkeyz" class="normal" style='text-align:left;'>Z</div>
            <div id="jkeyx" class="normal" style='text-align:left;'>X</div>
            <div id="jkeyc" class="normal" style='text-align:left;'>C</div>
            <div id="jkeyv" class="normal" style='text-align:left;'>V</div>
            <div id="jkeyb" class="normal" style='text-align:left;'>B</div>
            <div id="jkeyn" class="normal" style='text-align:left;'>N</div>
            <div id="jkeym" class="normal" style='text-align:left;'>M</div>
            <div id="jkeycomma" class="normal" style='text-align:left;'><b>&lt;<br>,</b></div>
            <div id="jkeyperiod" class="normal" style='text-align:left;'><b>&gt;<br>.</b></div>
            <div id="jkeyslash" class="normal" style='text-align:left;'><b>?<br>/</b></div>
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
