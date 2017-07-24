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

namespace theme_remui\controller;

defined('MOODLE_INTERNAL') || die();

/**
 * Handles requests regarding all ajax operations.
 *
 * @package   theme_remui
 * @copyright Copyright (c) 2015 Moodlerooms Inc. (http://www.moodlerooms.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class ajax_controller extends controller_abstract
{
    /**
     * Do any security checks needed for the passed action
     *
     * @param string $action
     */
    public function require_capability($action)
    {
        $action = $action;
    }

     /**
      * Send the message to the user.
      *
      * @return json encode array
      */
    public function send_quickmessage_action()
    {
        $contactid = optional_param('contactid', 0, PARAM_INT);
        $message = optional_param('message', '', PARAM_TEXT);
        return json_encode(array(
           'html' => theme_controller::quickmessage($contactid, $message)
        ));
    }

    public function get_add_activity_course_list_action()
    {
        $courseid = required_param('courseid', PARAM_INT);
        return json_encode(theme_controller::get_courses_add_activity($courseid));
        // return json_encode(array('html' => theme_controller::get_courses_for_teacher()));
    }

    public function get_userlist_action()
    {
        global $DB;
        $courseid = optional_param('courseid', 0, PARAM_INT);
        $sqlq = ("

        SELECT u.id, u.firstname, u.lastname

        FROM {course} c
    JOIN {context} ct ON c.id = ct.instanceid
    JOIN {role_assignments} ra ON ra.contextid = ct.id
    JOIN {user} u ON u.id = ra.userid
    JOIN {role} r ON r.id = ra.roleid

    WHERE c.id = ? AND r.id=5

    ");
        $userlist = $DB->get_records_sql($sqlq, array($courseid));

        return json_encode($userlist);
    }

    public function set_contact_action()
    {
        $otheruserid = required_param('otheruserid', PARAM_INT);
        $type = required_param('type', PARAM_ALPHAEXT);
        $value = theme_controller::set_user_contact($otheruserid, $type);

        return json_encode($value);
    }

    public function save_user_profile_settings_action()
    {
        $id = required_param('id', PARAM_INT);
        $fname = required_param('fname', PARAM_ALPHAEXT);
        $lname = required_param('lname', PARAM_ALPHAEXT);
        $emailid = required_param('emailid', PARAM_EMAIL);
        $description = required_param('description', PARAM_TEXT);
        $city = required_param('city', PARAM_INT);
        $cityname = required_param('cityname', PARAM_TEXT);
        $country = required_param('country', PARAM_ALPHAEXT);
        $region = required_param('region', PARAM_INT);
        $regionname = required_param('regionname', PARAM_TEXT);
        $municipality = required_param('municipality', PARAM_INT);
        $municipalityname = required_param('municipalityname', PARAM_TEXT);
        $school = required_param('school', PARAM_INT);
        $schoolname = required_param('schoolname', PARAM_TEXT);
        $class = required_param('stdclass', PARAM_TEXT);
        $gander = required_param('gander', PARAM_TEXT);
        $interests = required_param('interests', PARAM_TAGLIST);
        $birthdatestring = required_param('birthdate', PARAM_TEXT);
        $birthdate = strtotime($birthdatestring);
        $defaultdate = strtotime('1970-01-01');
        $userFields = new \stdClass();
        $userFields->id = $id;
        $userFields->name = $fname . " " .$lname;
        $userFields->profile_field_studentregion = $regionname;
        $userFields->profile_field_studentmunicipality = $municipalityname;
        $userFields->profile_field_studentcity = $cityname;
        $userFields->profile_field_studentschool = $schoolname;
        $userFields->profile_field_studentclass = $class;
        $userFields->profile_field_studentgender = $gander;
        $userFields->regionId = $region;
        $userFields->municipalityId = $municipality;
        $userFields->cityId = $city;
        $userFields->schoolId = $school;
        $userFields->profile_field_studentbirthdate = $birthdate - $defaultdate;
        $userFields->interests = $interests;
        theme_controller::save_user_profile_info($fname, $lname, $emailid, $description, $cityname, $country, $userFields);
        return json_encode($userFields);
    }

    public function get_courses_by_category_action()
    {
        $categoryid = required_param('categoryid', PARAM_INT);
        return json_encode(theme_controller::get_courses_by_category($categoryid));
    }

    public function get_courses_for_quiz_action()
    {
        $courseid = required_param('courseid', PARAM_INT);
        return(json_encode(theme_controller::get_quiz_participation_data($courseid)));
    }
    
    public function get_municipalities_action(){
        global $DB;
        $regionId = required_param('regionId', PARAM_INT);
        $sql = "SELECT * FROM Municipalities WHERE regionId = " . $regionId;
        $municipalitiesDb = $DB->get_records_sql($sql);

        return json_encode($municipalitiesDb);
    }
    
    public function get_cities_action(){
        global $DB;
        $municipalityId = required_param('municipalityId', PARAM_INT);
        $sql = "SELECT * FROM Cities WHERE municipalityId = " . $municipalityId;
        $sql .= " AND ID IN (SELECT DISTINCT cityId FROM schools)";
        $cities = $DB->get_records_sql($sql);

        return json_encode($cities);
    }
    
    public function get_schools_action(){
        global $DB;
        $cityId = required_param('cityId', PARAM_INT);
        $sql = "SELECT * FROM schools WHERE cityId = " . $cityId;
        $schools = $DB->get_records_sql($sql);

        return json_encode($schools);
    }
}
