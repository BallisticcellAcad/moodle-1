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
 * The main mootyper configuration form
 *
 * It uses the standard core Moodle formslib. For more info about them, please
 * visit: http://docs.moodle.org/en/Development:lib/formslib.php
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');
require_once($CFG->dirroot.'/mod/mootyper/locallib.php');
/**
 * Module instance settings form.
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later.
 */
class mod_mootyper_mod_form extends moodleform_mod {

    /**
     * @var $course Protected modifier.
     */
    protected $course = null;

    /**
     * Constructor for the base mootyper class.
     *
     * @param mixed $current
     * @param mixed $section
     * @param int $cm
     * @param mixed $course the current course  if it was already loaded,
     *                      otherwise this class will load one from the context as required.
     */
    public function __construct($current, $section, $cm, $course) {
        $this->course = $course;
        parent::__construct($current, $section, $cm, $course);
    }

    /**
     * Define the MooTyper mod_form.
     */
    public function definition() {
        global $CFG, $COURSE, $DB;
        $mform = $this->_form;

        $mootyperconfig = get_config('mod_mootyper');

        $mform->addElement('header', 'general', get_string('general', 'form'));

        $mform->addElement('text', 'name', get_string('name'), array('size' => '64'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEAN);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('name', 'mootypername', 'mootyper');
        if ($CFG->branch > 28) {
            $this->standard_intro_elements();
        } else {
            $this->add_intro_editor();
        }

        // MooTyper activity setup, Availability settings.
        $mform->addElement('header', 'availabilityhdr', get_string('availability'));

        $mform->addElement('date_time_selector', 'timeopen',
                           get_string('mootyperopentime', 'mootyper'),
                           array('optional' => true, 'step' => 1));
        $mform->addElement('date_time_selector', 'timeclose',
                           get_string('mootyperclosetime', 'mootyper'),
                           array('optional' => true, 'step' => 1));
        // MooTyper activity password setup.
        $mform->addElement('selectyesno', 'usepassword', get_string('usepassword', 'mootyper'));
        $mform->addHelpButton('usepassword', 'usepassword', 'mootyper');
        $mform->setDefault('usepassword', $mootyperconfig->password);
        $mform->setAdvanced('usepassword', $mootyperconfig->password_adv);

        $mform->addElement('passwordunmask', 'password', get_string('password', 'mootyper'));
        $mform->setDefault('password', '');
        $mform->setAdvanced('password', $mootyperconfig->password_adv);
        $mform->setType('password', PARAM_RAW);
        $mform->disabledIf('password', 'usepassword', 'eq', 0);
        $mform->disabledIf('passwordunmask', 'usepassword', 'eq', 0);

        // MooTyper activity setup, Options settings.
        $mform->addElement('header', 'optionhdr', get_string('options', 'mootyper'));

        // Add a dropdown slector for Required precision. 11/25/17.
        $precs = array();
        for ($i = 0; $i <= 100; $i++) {
            $precs[] = $i;
        }
        $mform->addElement('select', 'requiredgoal', get_string('requiredgoal', 'mootyper'), $precs);
        $mform->addHelpButton('requiredgoal', 'requiredgoal', 'mootyper');
        $mform->setDefault('requiredgoal', $mootyperconfig->defaultprecision);

        // Add a dropdown slector for text alignment.
        $aligns = array(get_string('defaulttextalign_left', 'mod_mootyper'),
                      get_string('defaulttextalign_center', 'mod_mootyper'),
                      get_string('defaulttextalign_right', 'mod_mootyper'));
        $mform->addElement('select', 'textalign', get_string('defaulttextalign', 'mootyper'), $aligns);
        $mform->addHelpButton('textalign', 'defaulttextalign', 'mootyper');
        $mform->setDefault('textalign', $mootyperconfig->defaulttextalign);

        // Continuous typing setup.
        $mform->addElement('selectyesno', 'continuoustype', get_string('continuoustype', 'mootyper'));
        $mform->addHelpButton('continuoustype', 'continuoustype', 'mootyper');
        $mform->setDefault('continuoustype', $mootyperconfig->continuoustype);
        $mform->setAdvanced('continuoustype', $mootyperconfig->continuoustype_adv);

        // Count mistyped spaces setup.
        $mform->addElement('selectyesno', 'countmistypedspaces', get_string('countmistypedspaces', 'mootyper'));
        $mform->addHelpButton('countmistypedspaces', 'countmistypedspaces', 'mootyper');
        $mform->setDefault('countmistypedspaces', $mootyperconfig->countmistypedspaces);
        $mform->setAdvanced('countmistypedspaces', $mootyperconfig->countmistypedspaces_adv);

        // Count mistakes setup.
        $mform->addElement('selectyesno', 'countmistakes', get_string('countmistakes', 'mootyper'));
        $mform->addHelpButton('countmistakes', 'countmistakes', 'mootyper');
        $mform->setDefault('countmistakes', $mootyperconfig->countmistakes);
        $mform->setAdvanced('countmistakes', $mootyperconfig->countmistakes_adv);

        // Show keyboard setup.
        $mform->addElement('selectyesno', 'showkeyboard', get_string('showkeyboard', 'mootyper'));
        $mform->addHelpButton('showkeyboard', 'showkeyboard', 'mootyper');
        $mform->setDefault('showkeyboard', $mootyperconfig->showkeyboard);
        $mform->setAdvanced('showkeyboard', $mootyperconfig->showkeyboard_adv);

        // Add a dropdown slector for keyboard layouts. 11/22/17.
        // Use function in localib.php to get layouts.
        $layouts = get_keyboard_layouts_db();
        $mform->addElement('select', 'layout', get_string('layout', 'mootyper'), $layouts);
        $mform->addHelpButton('layout', 'layout', 'mootyper');
        $mform->setDefault('layout', $mootyperconfig->defaultlayout);

        // Add setting for statistics bar background color.
        $attributes = 'size = "20"';
        $mform->setType('statsbgc', PARAM_NOTAGS);
        $mform->addElement('text', 'statsbgc', get_string('statsbgc', 'mootyper'), $attributes);
        $mform->addHelpButton('statsbgc', 'statsbgc', 'mootyper');
        $mform->setDefault('statsbgc', $mootyperconfig->statscolor);

        // Add setting for keytop background color.
        $mform->setType('keytopbgc', PARAM_NOTAGS);
        $mform->addElement('text', 'keytopbgc', get_string('keytopbgc', 'mootyper'), $attributes);
        $mform->addHelpButton('keytopbgc', 'keytopbgc', 'mootyper');
        $mform->setDefault('keytopbgc', $mootyperconfig->normalkeytops);

        // Add setting for keyboard background color.
        $mform->setType('keybdbgc', PARAM_NOTAGS);
        $mform->addElement('text', 'keybdbgc', get_string('keybdbgc', 'mootyper'), $attributes);
        $mform->addHelpButton('keybdbgc', 'keybdbgc', 'mootyper');
        $mform->setDefault('keybdbgc', $mootyperconfig->keyboardbgc);

        // Add setting for cursor color.
        $mform->setType('cursorcolor', PARAM_NOTAGS);
        $mform->addElement('text', 'cursorcolor', get_string('cursorcolor', 'mootyper'), $attributes);
        $mform->addHelpButton('cursorcolor', 'cursorcolor', 'mootyper');
        $mform->setDefault('cursorcolor', $mootyperconfig->cursorcolor);

        // Add setting for texttotype background color.
        $mform->setType('textbgc', PARAM_NOTAGS);
        $mform->addElement('text', 'textbgc', get_string('textbgc', 'mootyper'), $attributes);
        $mform->addHelpButton('textbgc', 'textbgc', 'mootyper');
        $mform->setDefault('textbgc', $mootyperconfig->textbgc);

        // Add setting for mistyped text background color.
        $mform->setType('texterrorcolor', PARAM_NOTAGS);
        $mform->addElement('text', 'texterrorcolor', get_string('texterrorcolor', 'mootyper'), $attributes);
        $mform->addHelpButton('texterrorcolor', 'texterrorcolor', 'mootyper');
        $mform->setDefault('texterrorcolor', $mootyperconfig->texterrorcolor);

        // MooTyper activity, link to Lesson/Categories and exercises.
        $mform->addElement('header', 'mootyperz', get_string('pluginadministration', 'mootyper'));
        $jlnk3 = $CFG->wwwroot . '/mod/mootyper/exercises.php?id='.$COURSE->id;
        $mform->addElement('html', '<a id="jlnk3" href="'.$jlnk3.'">'.get_string('emanage', 'mootyper').'</a>');

        // The rest of the common activity settings.
        $this->standard_coursemodule_elements();
        $this->apply_admin_defaults();
        $this->add_action_buttons();
    }

    /**
     * Enforce validation rules here.
     *
     * @param array $data Post data to validate
     * @param array $files
     * @return array
     **/
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        // Check open and close times are consistent.
        if ($data['timeopen'] != 0 && $data['timeclose'] != 0 &&
                $data['timeclose'] < $data['timeopen']) {
            $errors['timeclose'] = get_string('closebeforeopen', 'mootyper');
        }

        if (!empty($data['usepassword']) && empty($data['password'])) {
            $errors['password'] = get_string('emptypassword', 'mootyper');
        }

        return $errors;
    }
}
