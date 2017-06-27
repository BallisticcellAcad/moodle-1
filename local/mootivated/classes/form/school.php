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
 * School form.
 *
 * @package    local_mootivated
 * @copyright  2017 Mootivation Technologies Corp.
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_mootivated\form;
defined('MOODLE_INTERNAL') || die();

use context_system;
use MoodleQuickForm;
use local_mootivated\school as schoolclass;

require_once($CFG->libdir . '/formslib.php');
require_once($CFG->dirroot . '/cohort/lib.php');

MoodleQuickForm::registerElementType('local_mootivated_duration', __DIR__ . '/duration.php', 'local_mootivated_form_duration');

/**
 * Form class.
 *
 * @package    local_mootivated
 * @copyright  2017 Mootivation Technologies Corp.
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class school extends \moodleform {

    public function definition() {
        global $PAGE;
        $mform = $this->_form;
        $school = $this->_customdata['school'];

        $mform->addElement('select', 'cohortid', get_string('cohortid', 'local_mootivated'), $this->get_cohorts());
        $mform->addHelpButton('cohortid', 'cohortid', 'local_mootivated');

        $mform->addElement('text', 'privatekey', get_string('privatekey', 'local_mootivated'));
        $mform->setType('privatekey', PARAM_RAW);
        $mform->addHelpButton('privatekey', 'privatekey', 'local_mootivated');

        $mform->addElement('advcheckbox', 'sendusername', get_string('sendusername', 'local_mootivated'));
        $mform->setType('sendusername', PARAM_BOOL);
        $mform->addHelpButton('sendusername', 'sendusername', 'local_mootivated');

        $methods = [
            schoolclass::METHOD_EVENT => get_string('rewardmethod_event', 'local_mootivated'),
            schoolclass::METHOD_COMPLETION_ELSE_EVENT => get_string('rewardmethod_completionelseevent', 'local_mootivated'),
        ];
        $mform->addElement('select', 'rewardmethod', get_string('rewardmethod', 'local_mootivated'), $methods);
        $mform->addHelpButton('rewardmethod', 'rewardmethod', 'local_mootivated');

        $mform->addElement('text', 'maxactions', get_string('maxactions', 'local_mootivated'));
        $mform->setType('maxactions', PARAM_INT);
        $mform->addHelpButton('maxactions', 'maxactions', 'local_mootivated');

        $mform->addElement('local_mootivated_duration', 'timeframeformaxactions', get_string('timeframeformaxactions',
            'local_mootivated'));
        $mform->setType('timeframeformaxactions', PARAM_INT);
        $mform->addHelpButton('timeframeformaxactions', 'timeframeformaxactions', 'local_mootivated');

        $mform->addElement('local_mootivated_duration', 'timebetweensameactions', get_string('timebetweensameactions',
            'local_mootivated'));
        $mform->setType('timebetweensameactions', PARAM_INT);
        $mform->addHelpButton('timebetweensameactions', 'timebetweensameactions', 'local_mootivated');

        $this->add_action_buttons();

        if ($school->get_id()) {
            $renderer = $PAGE->get_renderer('local_mootivated');
            $mform->addElement('static', '', '', $renderer->delete_school_button($school));
        }
    }

    /**
     * Get the list of cohorts.
     *
     * @return array Keys are cohort IDs, values are their names.
     */
    protected function get_cohorts() {
        $context = context_system::instance();
        $cohorts = cohort_get_cohorts($context->id, 0, 500)['cohorts'];
        $data = [0 => '---'];
        foreach ($cohorts as $id => $cohort) {
            $data[$id] = format_string($cohort->name);
        }
        return $data;
    }

}
