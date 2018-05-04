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
 * This file defines the Portuguese(PortugalV4)keyboard layout.
 *
 * @package    mod_mootyper
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
<div id="keyboard" class="keyboardback">Teclado Português (Portugal - V4)<br>
    <section>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeybackslash" class="normal" style='text-align:left;'>|<br>\</div>
            <div id="jkey1" class="normal" style='text-align:left;'>!<br>1</div>
            <div id="jkey2" class="normal" style='text-align:left;'>"<br>2
                <span style="color:blue">&nbsp;&nbsp;@</span></div>
            <div id="jkey3" class="normal" style='text-align:left;'>#<br>3
                <span style="color:blue">&nbsp; &nbsp;£</span></div>
            <div id="jkey4" class="normal" style='text-align:left;'>$<br>4
                <span style="color:blue">&nbsp; &nbsp;§</span></div>
            <div id="jkey5" class="normal" style='text-align:left;'>%<br>5
                <span style="color:blue">&nbsp; &nbsp;€</span></div>
            <div id="jkey6" class="normal" style='text-align:left;'>& <br>6</div>
            <div id="jkey7" class="normal" style='text-align:left;'>/<br>7
                <span style="color:blue">&nbsp;&nbsp;&nbsp;{</span></div>
            <div id="jkey8" class="normal" style='text-align:left;'>(<br>8
                <span style="color:blue">&nbsp;&nbsp;&nbsp;[</span></div>
            <div id="jkey9" class="normal" style='text-align:left;'>)<br>9
                <span style="color:blue">&nbsp;&nbsp;&nbsp;]</span></div>
            <div id="jkey0" class="normal" style='text-align:left;'>=<br>0
                <span style="color:blue">&nbsp;&nbsp;&nbsp;}</span></div>
            <div id="jkeyapostrophe" class="normal" style='text-align:left;'>?<br>'</div>
            <div id="jkeyquote" class="normal" style='text-align:left;'>»<br>«</div>
            <div id="jkeybackspace" class="normal" style="width: 95px;">Backspace</div>
    </div>
    <div style="float: left;">
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeytab" class="normal" style="width: 60px;">Tab</div>
            <div id="jkeyq" class="normal">Q</div>
            <div id="jkeyw" class="normal">W</div>
            <div id="jkeye" class="normal">E<span style="color:blue">&nbsp;&nbsp;€</span></div>
            <div id="jkeyr" class="normal">R</div>
            <div id="jkeyt" class="normal">T</div>
            <div id="jkeyy" class="normal">Y</div>
            <div id="jkeyu" class="normal">U</div>
            <div id="jkeyi" class="normal">I</div>
            <div id="jkeyo" class="normal">O</div>
            <div id="jkeyp" class="normal">P</div>
            <div id="jkeyplus" class="normal" style='text-align: left;'>*<br>+
                <span style="color:red">&nbsp;&nbsp;&nbsp;¨</span></div>
            <div id="jkeyacuteaccent" class="normal" style='text-align:left; color:red;'>`<br>´
                <span style="color:blue">&nbsp; &nbsp;]</span></div>
        </div>
        <span id="jkeyenter" class="normal" style="width: 50px; margin-right:5px; float: right; height: 85px;">Enter</span>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeycaps" class="normal" style="width: 80px; font-size: 12px !important;">Caps Lock</div>
            <div id="jkeya" class="finger4">A</div>
            <div id="jkeys" class="finger3">S</div>
            <div id="jkeyd" class="finger2">D</div>
            <div id="jkeyf" class="finger1">F</div>
            <div id="jkeyg" class="normal">G</div>
            <div id="jkeyh" class="normal">H</div>
            <div id="jkeyj" class="finger1">J</div>
            <div id="jkeyk" class="finger2">K</div>
            <div id="jkeyl" class="finger3">L</div>
            <div id="jkeyç" class="finger4">Ç</div>
            <div id="jkeyº" class="normal" style='text-align:left;'>ª<br>º</div>
            <div id="jkeytilde" class="normal" style='text-align:left; color:red;'>^<br>~</div>
        </div>
    </div>

        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeyshiftl" class="normal" style="width: 70px;">Shift</div>
            <div id="jkeylessthan" class="normal" style='text-align:left;'>&gt;<br>&lt;</div>
            <div id="jkeyz" class="normal">Z</div>
            <div id="jkeyx" class="normal">X</div>
            <div id="jkeyc" class="normal">C</div>
            <div id="jkeyv" class="normal">V</div>
            <div id="jkeyb" class="normal">B</div>
            <div id="jkeyn" class="normal">N</div>
            <div id="jkeym" class="normal">M</div>
            <div id="jkeycomma" class="normal" style='text-align:left;'>;<br>,</div>
            <div id="jkeyperiod" class="normal" style='text-align:left;'>:<br>.</div>
            <div id="jkeyminus" class="normal" style='text-align:left;'>_<br>-</div>
            <div id="jkeyshiftd" class="normal" style="width: 105px;">Shift</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px;'>
            <div id="jkeyctrll" class="normal" style="width: 60px;">Ctrl</div>
            <div id="jkeywin" class="normal" style="width: 50px;">Win</div>
            <div id="jkeyalt" class="normal" style="width: 50px;">Alt</div>
            <div id="jkeyspace" class="normal" style="width: 245px;">Space</div>
            <div id="jkeyaltgr" class="normal" style="width: 50px;">Alt Gr</div>
            <div id="jkeywin" class="normal" style="width: 50px;">Win</div>
            <div id="jkeyfn" class="normal" style="width: 50px;">Menu</div>
            <div id="jkeyctrlr" class="normal" style="width: 60px;">Ctrl</div>
        </div>
        </section>
</div>
</div>
