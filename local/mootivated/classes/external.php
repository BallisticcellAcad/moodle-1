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
 * External API.
 *
 * @package   local_mootivated
 * @copyright 2016 Mootivation Technologies Corp.
 * @author    Mootivation Technologies Corp.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_mootivated;
defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');
require_once($CFG->libdir . '/gdlib.php');
require_once($CFG->dirroot . '/cohort/lib.php');

use context_system;
use context_user;
use external_api;
use external_function_parameters;
use external_single_structure;
use external_value;
use moodle_exception;
use local_mootivated\helper;

/**
 * External API class.
 *
 * @package   local_mootivated
 * @copyright 2016 Mootivation Technologies Corp.
 * @author    Mootivation Technologies Corp.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class external extends external_api {

    /**
     * External function parameters.
     *
     * @return external_function_parameters
     */
    public static function get_setup_parameters() {
        return new external_function_parameters([]);
    }

    /**
     * External function.
     *
     * @return array
     */
    public static function get_setup() {
        global $USER;

        $params = self::validate_parameters(self::get_setup_parameters(), []);

        $context = context_system::instance();
        self::validate_context($context);

        $schoolresolver = helper::get_school_resolver();
        $inaschool = $schoolresolver->get_by_member($USER->id) !== null;

        $canlogin = helper::can_login($USER);
        $isvisible = $canlogin && $inaschool;

        return [
            'can_login' => $canlogin,
            'can_redeem_store_items' => helper::can_redeem_store_items($USER),
            'is_visible' => $isvisible,
            'title' => get_string('motrainsidebartitle', 'local_mootivated')
        ];
    }

    /**
     * External function return definition.
     *
     * @return external_single_structure
     */
    public static function get_setup_returns() {
        return new external_single_structure([
            'can_login' => new external_value(PARAM_BOOL, 'Can login'),
            'can_redeem_store_items' => new external_value(PARAM_BOOL, 'Can redeem store items'),
            'is_visible' => new external_value(PARAM_BOOL, 'Is it visible'),
            'title' => new external_value(PARAM_RAW, 'The title'),
        ]);
    }

    /**
     * External function parameters.
     *
     * @return external_function_parameters
     */
    public static function login_parameters() {
        return new external_function_parameters([
            'token' => new external_value(PARAM_RAW, 'Token', VALUE_DEFAULT, ''),
            'language_code' => new external_value(PARAM_RAW, 'Language code', VALUE_DEFAULT, '')
        ]);
    }

    /**
     * Login.
     *
     * @param string $token The token.
     * @param string $langcode The language code.
     * @return array
     */
    public static function login($token, $langcode) {
        global $USER;

        $params = self::validate_parameters(self::login_parameters(), [
            'token' => $token, 'language_code' => $langcode]);

        $token = $params['token'];
        $langcode = $params['language_code'];

        $context = context_user::instance($USER->id);
        self::validate_context($context);

        $schoolresolver = helper::get_school_resolver();
        $school = $schoolresolver->get_by_member($USER->id);
        if (!$school) {
            throw new moodle_exception('User does not belong to any school.');
        } else if (!$school->is_setup()) {
            throw new moodle_exception('School not configured.');
        } else if (!helper::can_login($USER)) {
            throw new moodle_exception('Login not permitted for user.');
        }

        // If we didn't get a token, and the user is allowed to login, generate a Mootivated token.
        // This situation arises when the user attempts to login from the Moodle Mobile app.
        if (empty($token)) {
            $token = helper::get_mootivated_token();
        }

        $result = $school->login($USER, $token, $langcode);

        return [
            'server_ip' => $school->get_host(),
            'can_redeem_store_items' => helper::can_redeem_store_items($USER),
            'result' => $result
        ];
    }

    /**
     * External function return definition.
     *
     * @return external_single_structure
     */
    public static function login_returns() {
        return new external_single_structure([
            'server_ip' => new external_value(PARAM_RAW, 'Host'),
            'can_redeem_store_items' => new external_value(PARAM_BOOL, 'Can redeem store items'),
            'result' => new external_value(PARAM_RAW, 'Login result'),
        ]);
    }

    /**
     * External function parameters.
     *
     * @return external_function_parameters
     */
    public static function upload_avatar_parameters() {
        return new external_function_parameters([
            'image' => new external_value(PARAM_RAW, 'Image')
        ]);
    }

    /**
     * Upload avatar.
     *
     * @param string $image Image data.
     * @return array
     */
    public static function upload_avatar($image) {
        global $CFG, $DB, $USER;

        $params = self::validate_parameters(self::upload_avatar_parameters(), ['image' => $image]);
        $image = $params['image'];

        $context = context_user::instance($USER->id);
        self::validate_context($context);

        if ($CFG->disableuserimages) {
            throw new moodle_exception('disableuserimages', 'local_mootivated');
        } else if (!has_capability('moodle/user:editownprofile', $context)) {
            throw new moodle_exception('permissioneditownprofile', 'local_mootivated');
        }

        if (stristr($image, 'base64,')) {
            // Convert webrtc.
            $image = explode('base64,', $image);
            $image = end($image);
        }

        // Decode.
        $image = base64_decode($image);
        if (empty($image)) {
            throw new moodle_exception('failed', 'local_mootivated');
        }

        $dir = make_temp_directory('local_mootivated/avatars/');
        $tempfile = $dir . '/' . $USER->id . '_' . uniqid() . '_' . time();
        file_put_contents($tempfile, $image);
        chmod($tempfile, 0666);

        $newpicture = (int) process_new_icon($context, 'user', 'icon', 0, $tempfile);
        unlink($tempfile);

        if ($newpicture != $USER->picture) {
            $DB->set_field('user', 'picture', $newpicture, array('id' => $USER->id));
        } else {
            throw new moodle_exception('failed', 'local_mootivated');
        }

        return ['status' => true];
    }

    /**
     * External function return definition.
     *
     * @return external_single_structure
     */
    public static function upload_avatar_returns() {
        return new external_single_structure([
            'status' => new external_value(PARAM_BOOL, 'Success')
        ]);
    }

}
