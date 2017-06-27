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
 * This file contains main class for the course format Topic
 *
 * @since Moodle 2.0
 * @package format_etask
 * @copyright 2016 Martin Drlik <martin.drlik@email.cz>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

/**
 * Additional lib class for the eTask topics course format
 *
 * @package format_etask
 * @copyright 2016 Martin Drlik <martin.drlik@email.cz>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class FormatEtaskLib
{

    /**
     *
     * @var string
     */
    const STATUS_SUBMITTED = 'submitted';

    /**
     * Return mod items.
     *
     * @param stdClass $course
     * @return array
     */
    public function get_mod_items($course) {
        $modinfo = get_fast_modinfo($course);
        $moditems = [];
        foreach ($modinfo->cms as $cm) {
            $moditems[$cm->modname][$cm->instance] = $cm->id;
        }

        return $moditems;
    }

    /**
     * Array of scale values.
     *
     * @param int $scaleid
     * @return array
     */
    public function get_scale($scaleid) {
        global $DB;

        $scale = $DB->get_field('scale', 'scale', [
            'id' => $scaleid
        ], IGNORE_MISSING);

        return make_menu_from_list($scale);
    }

    /**
     * Return due date of grade item.
     *
     * @param grade_item $gradeitem
     * @return string
     */
    public function get_due_date($gradeitem) {
        global $DB;
        $timestamp = '';
        $gradedatefields = $this->get_grade_date_fields();

        if (isset($gradedatefields[$gradeitem->itemmodule])) {
            $timestamp = $DB->get_field($gradeitem->itemmodule, $gradedatefields[$gradeitem->itemmodule], [
                'id' => $gradeitem->iteminstance
            ], IGNORE_MISSING);
        }

        return !empty($timestamp) ? userdate($timestamp) : '';
    }

    /**
     * Set gradepass value for grade item.
     *
     * @param context $context
     * @param int $gradeitemid
     * @return string
     */
    public function update_grade_pass($context, $gradeitemid) {
        global $DB;

        $messagedata = [];

        if (data_submitted() && confirm_sesskey() && has_capability('moodle/grade:edit', $context)) {
            $gradepassvalue = required_param('gradePass' . $gradeitemid, PARAM_INT);

            $gradeitemobj = new grade_item();
            $gradeitem = $gradeitemobj->fetch([
                'id' => $gradeitemid
            ]);
            $gradeitem->id = $gradeitemid;
            $gradeitem->gradepass = $gradepassvalue;

            if (!empty($gradeitem->scaleid)) {
                $scale = $this->get_scale($gradeitem->scaleid);
                $gradepass = isset($scale[$gradepassvalue]) ? $scale[$gradepassvalue] : '-';
            } else {
                $gradepass = $gradepassvalue;
            }

            $res = $DB->update_record('grade_items', $gradeitem);

            if ($res !== false) {
                $messagedata = [
                    'message' => get_string('gradesavingsuccess', 'format_etask', [
                        'itemName' => $gradeitem->itemname,
                        'gradePass' => $gradepass
                    ]),
                    'success' => true
                ];
            } else {
                $messagedata = [
                    'message' => get_string('gradesavingerror', 'format_etask', $gradeitem->itemname),
                    'success' => false
                ];
            }
        }

        // After update make redirect.
        return $messagedata;
    }

    /**
     * Return grade stasus.
     *
     * @param grade_item $gradeitem
     * @param float $grade
     * @param int $userid
     * @return string
     */
    public function get_grade_item_status($gradeitem, $grade, $userid) {
        global $DB;

        $submissionstatus = '';
        if ($gradeitem->itemmodule == 'assign') {
            $submissionstatus = $DB->get_field('assign_submission', 'status', [
                'assignment' => $gradeitem->iteminstance,
                'userid' => $userid
            ], IGNORE_MISSING);
        }

        $gradepass = (int) $gradeitem->gradepass;
        if (empty($grade) && $submissionstatus == self::STATUS_SUBMITTED) {
            $status = 'submitted';
        } else if ((empty($grade) && $submissionstatus != self::STATUS_SUBMITTED) || empty($gradepass)) {
            $status = 'none';
        } else if ($grade >= $gradepass) {
            $status = 'passed';
        } else if ($grade < $gradepass) {
            $status = 'failed';
        }

        return $status;
    }

    /**
     * Student role identifier.
     *
     * @return int
     */
    public function get_student_role_id() {
        global $DB;

        $studentroleid = $DB->get_field('role', 'id', [
            'archetype' => 'student'
        ], IGNORE_MISSING);

        return $studentroleid;
    }

    /**
     * Course groups.
     *
     * @param int $courseid
     * @return int
     */
    public function get_course_groups($courseid) {
        $coursegroupsobjects = groups_get_all_groups($courseid);
        $coursegroups = [];
        foreach ($coursegroupsobjects as $coursegroup) {
            $coursegroups[$coursegroup->id] = $coursegroup->name;
        }
        return $coursegroups;
    }

    /**
     * Is user allowed in grade table view.
     *
     * @param context_course $context
     * @param stdClass $course
     * @param stdClass $user
     * @param int $selectedgroup
     * @param array $loggedinusergroups
     * @return bool
     */
    public function is_allowed_user($context, $course, $user, $selectedgroup, $loggedinusergroups) {
        $isalloweduser = false;
        // Default state of allowed user group (no groups mode).
        $allowedusergroup = true;
        // Get enroled user groups membership.
        $usergroups = current(groups_get_user_groups($course->id, $user->id));
        if (!empty($usergroups)) {
            // Filter users by filter or show students from logged in user group.
            if (has_capability('moodle/course:update', $context)) {
                // Check if user is in allowed group.
                if (in_array($selectedgroup, $usergroups) === false) {
                    $allowedusergroup = false;
                }
            } else {
                // Check if user is in allowed group.
                foreach ($usergroups as $usergroup) {
                    if (in_array($usergroup, $loggedinusergroups) === false) {
                        $allowedusergroup = false;
                    }
                }
            }
        }

        // Get user roles.
        $userroles = get_user_roles($context, $user->id);
        $studentroleid = $this->get_student_role_id();
        $allowedstudentrole = false;
        // Allow only student role in the eTask grading.
        foreach ($userroles as $userrole) {
            if ($userrole->roleid === $studentroleid) {
                $allowedstudentrole = true;
            }
        }

        if ($allowedusergroup === true && $allowedstudentrole === true) {
            $isalloweduser = true;
        }
        return $isalloweduser;
    }

    /**
     * Get grade date fields array from config text.
     *
     * @return array
     */
    private function get_grade_date_fields() {
        $gradedatefields = [];
        $config = get_config('format_etask', 'registered_due_date_modules');
        $items = explode(',', $config);
        foreach ($items as $item) {
            if (!empty($item)) {
                list($module, $duedate) = explode(':', $item);
                $gradedatefields[trim($module)] = trim($duedate);
            }
        }
        return $gradedatefields;
    }
}
