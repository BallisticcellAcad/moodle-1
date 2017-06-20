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
 * Administration settings definitions for the quiz module.
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */


defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    require_once($CFG->dirroot.'/mod/mootyper/lib.php');
    require_once($CFG->dirroot.'/mod/mootyper/locallib.php');

    // Availability settings.
    $settings->add(new admin_setting_heading('mod_lesson/availibility', get_string('availability'), ''));

    // Recent activity setting.
    $name = new lang_string('showrecentactivity', 'mootyper');
    $description = new lang_string('showrecentactivityconfig', 'mootyper');
    $settings->add(new admin_setting_configcheckbox('mod_mootyper/showrecentactivity',
                                                    $name,
                                                    $description,
                                                    0));

    $settings->add(new admin_setting_configcheckbox_with_advanced('mod_mootyper/password',
        get_string('password', 'mootyper'), get_string('configpassword_desc', 'mootyper'),
        array('value' => 0, 'adv' => true)));

    // Options settings.
    $settings->add(new admin_setting_heading('mod_mootyper/options', get_string('options', 'mootyper'), ''));

    // Default typing precision.
    $precs = array();
    for ($i = 0; $i <= 100; $i++) {
        $precs[] = $i;
    }
    $settings->add(new admin_setting_configselect(
    'mod_mootyper/defaultprecision',
        get_string('defaultprecision', 'mootyper'),
        '', 97, $precs)
    );

    // Default continuous typing setting.
        $settings->add(new admin_setting_configcheckbox_with_advanced('mod_mootyper/continuoustype',
        get_string('continuoustype', 'mootyper'), get_string('continuoustype_help', 'mootyper'),
        array('value' => 0, 'adv' => true)));

    // Lesson export settings.
    $settings->add(new admin_setting_heading('mod_mootyper/lesson_export', get_string('lesson_export', 'mootyper'), ''));

    // Lesson export filename setting.
    $name = new lang_string('lesson_export_filename', 'mootyper');
    $description = new lang_string('lesson_export_filenameconfig', 'mootyper');
    $settings->add(new admin_setting_configcheckbox('mod_mootyper/lesson_export_filename',
                                                    $name,
                                                    $description,
                                                    0));

    // Appearance settings.
    $settings->add(new admin_setting_heading('mod_mootyper/appearance', get_string('appearance'), ''));

    // Default keyboard layout.
    $layouts = get_keyboard_layouts_db();
    $settings->add(new admin_setting_configselect(
    'mod_mootyper/defaultlayout',
        get_string('defaultlayout', 'mootyper'),
        '', 1, $layouts)
    );



    // Date format setting.
    $settings->add(new admin_setting_configtext(
        'mod_mootyper/dateformat',
        new lang_string('dateformat', 'mootyper'),
        new lang_string('configdateformat', 'mootyper'),
        'M d, Y G:i', PARAM_TEXT, 15)
    );

    // Passing grade background colour setting.
    $settings->add(new admin_setting_configcolourpicker(
    'mod_mootyper/passbgc',
        get_string('passbgc_title', 'mootyper'),
        get_string('passbgc_descr', 'mootyper'),
        get_string('passbgc_colour', 'mootyper'),
        null)
    );

    // Failing grade background colour setting.
    $settings->add(new admin_setting_configcolourpicker(
    'mod_mootyper/failbgc',
        get_string('failbgc_title', 'mootyper'),
        get_string('failbgc_descr', 'mootyper'),
        get_string('failbgc_colour', 'mootyper'),
        null)
    );

    // Suspicion marks colour setting.
    $settings->add(new admin_setting_configcolourpicker(
    'mod_mootyper/suspicion',
        get_string('suspicion_title', 'mootyper'),
        get_string('suspicion_descr', 'mootyper'),
        get_string('suspicion_colour', 'mootyper'),
        null)
    );

    // Key top colour setting.
    $name = 'mod_mootyper/normalkeytops';
    $title = get_string('normalkeytops_title', 'mootyper');
    $description = get_string('normalkeytops_descr', 'mootyper');
    $default = get_string('normalkeytops_colour', 'mootyper');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Keyboard background colour setting.
    $name = 'mod_mootyper/keyboardbgc';
    $title = get_string('keyboardbgc_title', 'mootyper');
    $description = get_string('keyboardbgc_descr', 'mootyper');
    $default = get_string('keyboardbgc_colour', 'mootyper');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $settings->add($setting);

    // Load css template, change settings, save as styles.css to implement color changes.
    // Css template is needed to guarantee correct settings are easily found and changed.
    $old1 = '[[setting:normalkeytops]]';
    $new1 = get_config('mod_mootyper', 'normalkeytops');
    $old2 = '[[setting:keyboardbgc]]';
    $new2 = get_config('mod_mootyper', 'keyboardbgc');
    $data = file_get_contents($CFG->dirroot.'/mod/mootyper/template.css');
    $data = str_replace($old1, $new1, $data);
    $data = str_replace($old2, $new2, $data);
    file_put_contents($CFG->dirroot.'/mod/mootyper/styles.css', $data);

}
