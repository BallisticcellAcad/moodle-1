<?php

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot.'/course/moodleform_mod.php');
require_once($CFG->dirroot.'/mod/quizoff/lib.php');
require_once($CFG->dirroot.'/mod/quizoff/locallib.php');

class mod_quizoff_mod_form extends moodleform_mod {
    function definition() {
        global $CFG, $DB, $OUTPUT, $COURSE, $USER;
 
        $mform = & $this->_form;
        $config = get_config('quizoff');
        
        $mform->addElement('header', 'general', get_string('general', 'form'));
        $mform->addElement('text', 'name', get_string('name'), array('size'=>'48'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addElement('url', 'externalurl', get_string('externalurl', 'quizoff'), array('size'=>'60'), array('usefilepicker'=>true));
        $mform->setType('externalurl', PARAM_RAW_TRIMMED);
        $mform->addRule('externalurl', null, 'required', null, 'client');
        $mform->setDefault('externalurl', QUIZOFF_GAME_URL);
        $this->standard_grading_coursemodule_elements();
        $this->standard_coursemodule_elements();
        $this->add_action_buttons();
    }
    
    function validation($data, $files) {
        $errors = parent::validation($data, $files);

        // Validating Entered url, we are looking for obvious problems only,
        // teachers are responsible for testing if it actually works.

        // This is not a security validation!! Teachers are allowed to enter "javascript:alert(666)" for example.

        // NOTE: do not try to explain the difference between URL and URI, people would be only confused...

        if (!empty($data['externalurl'])) {
            $url = $data['externalurl'];
            if (preg_match('|^/|', $url)) {
                // links relative to server root are ok - no validation necessary

            } else if (preg_match('|^[a-z]+://|i', $url) or preg_match('|^https?:|i', $url) or preg_match('|^ftp:|i', $url)) {
                // normal URL
                if (!quizoff_url_appears_valid_url($url)) {
                    $errors['externalurl'] = get_string('invalidurl', 'quizoff');
                }

            } else if (preg_match('|^[a-z]+:|i', $url)) {
                // general URI such as teamspeak, mailto, etc. - it may or may not work in all browsers,
                // we do not validate these at all, sorry

            } else {
                // invalid URI, we try to fix it by adding 'http://' prefix,
                // relative links are NOT allowed because we display the link on different pages!
                if (!quizoff_url_appears_valid_url('http://'.$url)) {
                    $errors['externalurl'] = get_string('invalidurl', 'quizoff');
                }
            }
        }
        return $errors;
    }
}