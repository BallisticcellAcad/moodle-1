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
 * This file defines the Russian(V3) keyboard layout.
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
<div id="keyboard" class="keyboardback">Russian(V3) Keyboard Layout<br>
    <section>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 12px !important; line-height: 15px'>
            <div id="jkeyё" class="normal">Ё</div>
            <div id="jkey1" class="normal">!<br>1</div>
            <div id="jkey2" class="normal">"<br>2</div>
            <div id="jkey3" class="normal">№<br>3</div>
            <div id="jkey4" class="normal">;<br>4</div>
            <div id="jkey5" class="normal">%<br>5</div>
            <div id="jkey6" class="normal">:<br>6</div>
            <div id="jkey7" class="normal">?<br>7</div>
            <div id="jkey8" class="normal">*<br>8</div>
            <div id="jkey9" class="normal">(<br>9</div>
            <div id="jkey0" class="normal">)<br>0</div>
            <div id="jkeyminus" class="normal">_<br>-</div>
            <div id="jkeyequals" class="normal">+<br>=</div>
            <div id="jkeybackspace" class="normal" style="width: 95px;">Backspace</div>
        </div>

    <div style="float: left;">
        <div class="mtrow" style='float: left; margin-left:5px;'>
            <div id="jkeytab" class="normal" style="width: 60px;">Tab</div>
            <div id="jkeyй" class="normal">Й</div>
            <div id="jkeyц" class="normal">Ц</div>
            <div id="jkeyу" class="normal">У</div>
            <div id="jkeyк" class="normal">К</div>
            <div id="jkeyе" class="normal">Е</div>
            <div id="jkeyн" class="normal">Н</div>
            <div id="jkeyг" class="normal">Г</div>
            <div id="jkeyш" class="normal">Ш</div>
            <div id="jkeyщ" class="normal">Щ</div>
            <div id="jkeyз" class="normal">З</div>
            <div id="jkeyх" class="normal">Х</div>
            <div id="jkeyъ" class="normal" style="border-right-style: solid;">Ъ</div>
        </div>
        <span id="jkeyenter" class="normal" style="width: 50px; margin-right:5px; float: right; height: 85px;">Enter</span>
        <div class="mtrow" style='float: left; margin-left:5px;'>
            <div id="jkeycaps" class="normal" style="width: 80px;">C.Lock</div>
            <div id="jkeyф" class="finger4">Ф</div>
            <div id="jkeyы" class="finger3">Ы</div>
            <div id="jkeyв" class="finger2">В</div>
            <div id="jkeyа" class="finger1">А</div>
            <div id="jkeyп" class="normal">П</div>
            <div id="jkeyр" class="normal">Р</div>
            <div id="jkeyо" class="finger1">О</div>
            <div id="jkeyл" class="finger2">Л</div>
            <div id="jkeyд" class="finger3">Д</div>
            <div id="jkeyж" class="finger4">Ж</div>
            <div id="jkeyэ" class="normal">Э</div>
            <div id="jkeybackslash" class="normal" style='font-size: 12px !important; line-height: 15px'>/<br>\</div>
        </div>
    </div>
        <div class="mtrow" style='float: left; margin-left:5px;'>
            <div id="jkeyshiftl" class="normal" style="width: 70px;">Shift</div>
            <div id="jkeyslash" class="normal" style='font-size: 12px !important; line-height: 15px'>|<br>\</div>
            <div id="jkeyя" class="normal">Я</div>
            <div id="jkeyч" class="normal">Ч</div>
            <div id="jkeyс" class="normal">С</div>
            <div id="jkeyм" class="normal">М</div>
            <div id="jkeyи" class="normal">И</div>
            <div id="jkeyт" class="normal">Т</div>
            <div id="jkeyь" class="normal">Ь</div>
            <div id="jkeyб" class="normal">Б</div>
            <div id="jkeyю" class="normal">Ю</div>
            <div id="jkeyperiod" class="normal" style='font-size: 12px !important; line-height: 15px'>,<br>.</div>
            <div id="jkeyshiftd" class="normal" style="width: 105px;">Shift</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px;'>
            <div id="jkeyctrll" class="normal" style="width: 50px;">Ctrl</div>
            <div id="jempty" class="normal" style="width: 50px;">Meta</div>
            <div id="jkeyalt" class="normal" style="width: 50px;">Alt</div>
            <div id="jkeyspace" class="normal" style="width: 265px;">Пробел</div>
            <div id="jkeyaltgr" class="normal" style="width: 50px;">Alt gr</div>
            <div id="jempty" class="normal" style="width: 50px;">Meta</div>
            <div id="jempty" class="normal" style="width: 50px;">Menu</div>
            <div id="jkeyctrlr" class="normal" style="width: 50px; border-right-style: solid;">Ctrl</div>
        <div>
    </section>
</div>
</div>
