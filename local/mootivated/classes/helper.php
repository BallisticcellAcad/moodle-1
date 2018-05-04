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
 * @package   local_mootivated
 * @copyright 2016 Mootivation Technologies Corp.
 * @author    Mootivation Technologies Corp.
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_mootivated;
defined('MOODLE_INTERNAL') || die();

// Conditionally include completion lib.
if (!empty($CFG->enablecompletion)) {
    require_once($CFG->libdir . '/completionlib.php');
}

use completion_info;
use context_course;
use context_system;
use context_user;
use course_modinfo;
use moodle_exception;
use stdClass;

/**
 * Mootivated helper class.
 *
 * @package    local_mootivated
 * @copyright  2016 Mootivation Technologies
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class helper {

    /** Role name. */
    const ROLE_SHORTNAME = 'mootivateduser';

    /** @var ischool_resolver Used to resolve a school. */
    protected static $schoolrevolver = null;

    /**
     * Whether automatically assigning the Mootivated User role is allowed.
     *
     * @return bool
     */
    public static function allow_automatic_role_assignment() {
        $autoassign = get_config('local_mootivated', 'disableautoroleassign');
        return empty($autoassign);
    }

    /**
     * Can the user login with the server.
     *
     * @param stdClass $user The user.
     * @return bool
     */
    public static function can_login(stdClass $user) {
        return has_capability('local/mootivated:login', context_system::instance(), $user);
    }

    /**
     * Can redeem store items.
     *
     * @param stdClass $user The user.
     * @return bool.
     */
    public static function can_redeem_store_items(stdClass $user) {
        $cap = 'local/mootivated:redeem_store_items';
        $sysctx = context_system::instance();
        $userctx = context_user::instance($user->id);

        // This checks whether the capability is given at user or system context. For legacy
        // reason we check the user context, but it should not be possible to assign it there.
        if (has_capability($cap, $userctx, $user)) {
            return true;
        }

        // Now we need to check if the user has the capability in any course. Yes, it looks
        // terribly inefficient, but I suggest you look at various functions in enrollib...
        $courses = enrol_get_all_users_courses($user->id, true, 'id');
        foreach ($courses as $course) {
            if (has_capability($cap, context_course::instance($course->id), $user)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Create the mootivated role.
     *
     * The role is required in order to allow user to create WS token for the Mootivated service.
     * Such service grants access to the few external functions needed for the system to work.
     *
     * Additionally, this role also contain the capability to determine whether they can login or
     * not. It's unlikely that this would be turned off, but it gives flexibility to the admin. For
     * instance if users have the capability to create tokens and use rest, but shouldn't be able
     * to login to Mootivated.
     *
     * @return void
     */
    public static function create_mootivated_role() {
        global $DB;

        $contextid = context_system::instance()->id;
        $roleid = create_role(get_string('mootivatedrole', 'local_mootivated'), static::ROLE_SHORTNAME,
            get_string('mootivatedroledesc', 'local_mootivated'));

        set_role_contextlevels($roleid, [CONTEXT_SYSTEM]);
        assign_capability('webservice/rest:use', CAP_ALLOW, $roleid, $contextid, true);
        assign_capability('moodle/webservice:createtoken', CAP_ALLOW, $roleid, $contextid, true);
        assign_capability('local/mootivated:login', CAP_ALLOW, $roleid, $contextid, true);

        $managerroleid = $DB->get_field('role', 'id', ['shortname' => 'manager'], IGNORE_MISSING);
        if ($managerroleid) {
            if (function_exists('core_role_set_override_allowed')) {
                core_role_set_override_allowed($managerroleid, $roleid);
            } else {
                allow_override($managerroleid, $roleid);
            }

            if (function_exists('core_role_set_assign_allowed')) {
                core_role_set_assign_allowed($managerroleid, $roleid);
            } else {
                allow_assign($managerroleid, $roleid);
            }

            if (function_exists('core_role_set_switch_allowed')) {
                core_role_set_switch_allowed($managerroleid, $roleid);
            } else {
                allow_switch($managerroleid, $roleid);
            }
        }
    }

    /**
     * Return whether the mootivated role exists.
     *
     * @return bool
     */
    public static function mootivated_role_exists() {
        global $DB;
        return $DB->record_exists('role', array('shortname' => static::ROLE_SHORTNAME));
    }

    /**
     * Get the mootivated role.
     *
     * @return stdClass
     */
    public static function get_mootivated_role() {
        global $DB;
        return $DB->get_record('role', ['shortname' => static::ROLE_SHORTNAME], '*', MUST_EXIST);
    }

    /**
     * Last sync time.
     *
     * @return int
     */
    public static function mootivated_role_last_synced() {
        return (int) get_config('local_mootivated', 'lastrolesync');
    }

    /**
     * Did we ever sync the role?
     *
     * @return bool
     */
    public static function mootivated_role_was_ever_synced() {
        return (bool) get_config('local_mootivated', 'lastrolesync');
    }

    /**
     * Is syncing scheduled?
     *
     * @return bool
     */
    public static function adhoc_role_sync_scheduled() {
        // Value 1 means running or scheduled, 0 means neither.
        return (bool) get_config('local_mootivated', 'adhocrolesync');
    }

    /**
     * Schedule the adhoc role sync.
     *
     * @return void
     */
    public static function schedule_mootivated_role_sync() {
        set_config('adhocrolesync', 1, 'local_mootivated');
        $task = new task\adhoc_role_sync();
        $task->set_component('local_mootivated');
        \core\task\manager::queue_adhoc_task($task);
    }

    /**
     * Return whether webservices are enabled.
     *
     * @return bool
     */
    public static function webservices_enabled() {
        global $CFG;
        return !empty($CFG->enablewebservices);
    }

    /**
     * Enable webservices.
     *
     * @return void
     */
    public static function enable_webservices() {
        set_config('enablewebservices', 1);
    }

    /**
     * Return whether REST is enabled.
     *
     * @return bool
     */
    public static function rest_enabled() {
        global $CFG;
        $protocols = !empty($CFG->webserviceprotocols) ? explode(',', $CFG->webserviceprotocols) : [];
        return in_array('rest', $protocols);
    }

    /**
     * Enable the REST protocol.
     *
     * @return void
     */
    public static function enable_rest() {
        global $CFG;
        $protocols = !empty($CFG->webserviceprotocols) ? explode(',', $CFG->webserviceprotocols) : [];
        $protocols[] = 'rest';
        $protocols = array_unique($protocols);
        set_config('webserviceprotocols', implode(',', $protocols));
    }

    /**
     * Get a Mootivated token for the current user.

     * @return string
     */
    public static function get_mootivated_token() {
        global $DB;

        $service = $DB->get_record('external_services', ['shortname' => 'local_mootivated', 'enabled' => 1], '*', MUST_EXIST);
        if (!function_exists('external_generate_token_for_current_user')) {
            throw new moodle_exception('cannotcreatetoken', 'webservice', '', $service->shortname);
        }

        $token = external_generate_token_for_current_user($service);
        external_log_token_request($token);

        return $token->token;
    }

    /**
     * Quick set-up.
     *
     * Enables webservices, rest and creates the mootivated role.
     *
     * @return void
     */
    public static function quick_setup() {
        if (!static::webservices_enabled()) {
            static::enable_webservices();
        }
        if (!static::rest_enabled()) {
            static::enable_rest();
        }
        if (!static::mootivated_role_exists()) {
            static::create_mootivated_role();
        }
        if (!static::mootivated_role_was_ever_synced()) {
            static::schedule_mootivated_role_sync();
        }
    }

    /**
     * Delete old log entries.
     *
     * @param int $epoch Delete everything before that timestamp.
     * @return void
     */
    public static function delete_logs_older_than($epoch) {
        global $DB;
        $DB->delete_records_select('local_mootivated_log', 'timecreated < :timecreated', ['timecreated' => $epoch]);
    }

    /**
     * Observe the events, and dispatch them if necessary.
     *
     * @param \core\event\base $event The event.
     * @return void
     */
    public static function observer(\core\event\base $event) {
        global $CFG;

        static $allowedcontexts = array(CONTEXT_COURSE, CONTEXT_MODULE);

        if ($event->component === 'local_mootivated') {
            // Skip own events.
            return;
        } else if ($event->anonymous) {
            // Skip all the events marked as anonymous.
            return;
        } else if (!in_array($event->contextlevel, $allowedcontexts)) {
            // Ignore events that are not in the right context.
            return;
        } else if (!$event->get_context()) {
            // Sometimes the context does not exist, not sure when...
            return;
        }

        if ($event->edulevel !== \core\event\base::LEVEL_PARTICIPATING
                && !($event instanceof \core\event\course_completed)) {

            // Ignore events that are not participating, or course completion.
            return;
        }

        // Check target.
        $userid = static::get_event_target_user($event);

        // Skip non-logged in users and guests.
        if (!$userid || isguestuser($userid) || is_siteadmin($userid)) {
            return;
        }

        try {
            if (!has_capability('local/mootivated:earncoins', $event->get_context())) {
                return;
            }
        } catch (moodle_exception $e) {
            return;
        }

        // Keep the event, and proceed.
        static::handle_event($event);
    }

    /**
     * Handle an event.
     *
     * @param \core\event\base $event The event.
     * @return void
     */
    protected static function handle_event(\core\event\base $event) {
        global $CFG;

        // Don't use completion_info::is_enabled_for_site() because we only include the library when completion is enabled.
        $completionenabled = !empty($CFG->enablecompletion);

        // Early catch the course completed event.
        if ($completionenabled && $event instanceof \core\event\course_completed) {
            static::handle_course_completed_event($event);
            return;
        }

        // Just making sure we're not letting unexpected events through.
        if ($event->edulevel !== \core\event\base::LEVEL_PARTICIPATING) {
            return;
        }

        // We also skip all non-module events as we current are only being conditional on activities.
        if ($completionenabled && $event->contextlevel == CONTEXT_MODULE) {
            $userid = static::get_event_target_user($event);

            // Check their school.
            $school = self::get_school_resolver()->get_by_member($userid);
            if (!$school || !$school->is_setup()) {
                // No school, no chocolate.
                return;
            }

            // When the reward method is completion, then event, check if completion is enabled in module.
            if ($school->is_reward_method_completion_else_event()) {

                $courseinfo = course_modinfo::instance($event->courseid);
                $cminfo = $courseinfo->get_cm($event->get_context()->instanceid);
                $completioninfo = new completion_info($courseinfo->get_course());

                if ($completioninfo->is_enabled($cminfo)) {
                    static::reward_for_completion($event);
                    return;
                }
            }
        }

        static::reward_for_event($event);
    }

    /**
     * Handle course completed event.
     *
     * @param \core\event\course_completed $event The event.
     * @return void
     */
    protected static function handle_course_completed_event(\core\event\course_completed $event) {
        $userid = static::get_event_target_user($event);

        // Check their school.
        $school = self::get_school_resolver()->get_by_member($userid);
        if (!$school || !$school->is_setup()) {
            // No school, no chocolate.
            return;
        }

        if (!$school->is_course_completion_reward_enabled()) {
            // Sorry mate, no pocket money for you.
            return;
        }

        if ($school->was_user_rewarded_for_completion($userid, $event->courseid, 0)) {
            // The course completion state must have been reset. If we do not ignore this
            // then we will have issue when logging the event due to unique indexes.
            return;
        }

        // Ok, here you can have some coins.
        $school->capture_event($userid, $event, (int) $school->get_course_completion_reward());
        $school->log_user_was_rewarded_for_completion($userid, $event->courseid, 0, COMPLETION_COMPLETE);
    }

    /**
     * Reward a user for completion.
     *
     * @param \core\event\base $event The event.
     * @return void
     */
    protected static function reward_for_completion(\core\event\base $event) {
        // We only care about one event at this point.
        if ($event instanceof \core\event\course_module_completion_updated) {
            $data = $event->get_record_snapshot('course_modules_completion', $event->objectid);
            if ($data->completionstate == COMPLETION_COMPLETE
                    || $data->completionstate == COMPLETION_COMPLETE_PASS) {

                $userid = static::get_event_target_user($event);
                $courseid = $event->courseid;
                $cmid = $event->get_context()->instanceid;

                $school = self::get_school_resolver()->get_by_member($userid);
                if ($school->was_user_rewarded_for_completion($userid, $courseid, $cmid)) {
                    return;
                }

                $modinfo = course_modinfo::instance($courseid);
                $cminfo = $modinfo->get_cm($cmid);
                $calculator = $school->get_completion_points_calculator_by_mod();
                $coins = (int) $calculator->get_for_module($cminfo->modname);

                $school->capture_event($userid, $event, $coins);
                $school->log_user_was_rewarded_for_completion($userid, $courseid, $cmid, $data->completionstate);
            }
        }
    }

    /**
     * Reward a user by event.
     *
     * @param \core\event\base $event The event.
     * @return void
     */
    protected static function reward_for_event(\core\event\base $event) {
        $coins = 0;
        $userid = static::get_event_target_user($event);

        static $ignored = [
            '\\core\\event\\competency_user_competency_review_request_cancelled' => true,
            '\\core\\event\\courses_searched' => true,
            '\\core\\event\\course_viewed' => true,
            '\\mod_glossary\\event\\entry_disapproved' => true,
            '\\mod_lesson\\event\\lesson_restarted' => true,
            '\\mod_lesson\\event\\lesson_resumed' => true,
            '\\mod_quiz\\event\\attempt_abandoned' => true,
            '\\mod_quiz\\event\\attempt_becameoverdue' => true,

            // Redudant events.
            '\\mod_book\\event\\course_module_viewed' => true,
            '\\mod_forum\\event\\discussion_subscription_created' => true,
            '\\mod_forum\\event\\subscription_created' => true,
        ];

        if ($event->crud === 'd') {
            $coins = 0;

        } else if (array_key_exists($event->eventname, $ignored)) {
            $coins = 0;

        } else if (strpos($event->eventname, 'assessable_submitted') !== false
                || strpos($event->eventname, 'assessable_uploaded') !== false) {
            // Loose redundancy check.
            $coins = 0;

        } else if ($event->crud === 'c') {
            $coins = 3;

        } else if ($event->crud === 'r') {
            $coins = 1;

        } else if ($event->crud === 'u') {
            $coins = 1;
        }

        if ($coins > 0) {
            static::add_coins_for_event($event->userid, $coins, $event);
        }
    }

    /**
     * Add coins for an event.
     *
     * @param int $userid The user ID.
     * @param int $coins The number of coins.
     * @param \core\event\base $event The event.
     */
    private static function add_coins_for_event($userid, $coins, \core\event\base $event) {
        $school = self::get_school_resolver()->get_by_member($userid);
        if (!$school) {
            // The user is not part of any school.
            return;
        }

        if (!$school->is_setup()) {
            // The school is not yet set-up.
            return;
        }

        if ($school->has_exceeded_threshold($userid, $event)) {
            // The user has exceeded the threshold, no coins for them!
            return;
        }

        $school->capture_event($userid, $event, $coins);
    }

    /**
     * Get the target of an event.
     *
     * @param \core\base\event $event The event.
     * @return int The user ID.
     */
    protected static function get_event_target_user(\core\event\base $event) {
        $userid = $event->userid;
        if ($event instanceof \core\event\course_completed || $event instanceof \core\event\course_module_completion_updated) {
            $userid = $event->relateduserid;
        }
        return $userid;
    }

    /**
     * Find the global school.
     *
     * It is always the first school we find, in case the site switched from and to
     * using sections. Also, this ensures that the global school is kept even after
     * using sections has been turned on.
     *
     * @return stdClass|null
     */
    public static function get_global_school() {
        global $DB;
        $candidates = $DB->get_records('local_mootivated_school', [], 'id ASC', 'id');
        if (!empty($candidates)) {
            $candidate = reset($candidates);
            return new \local_mootivated\global_school($candidate->id);
        }
        return new \local_mootivated\global_school(0);
    }

    /**
     * Get the school resolver.
     *
     * @return ischool_resolver
     */
    public static function get_school_resolver() {
        if (!self::$schoolrevolver) {
            if (!self::uses_sections()) {
                $resolver = new global_school_resolver();
            } else {
                $resolver = new school_resolver();
            }
            self::$schoolrevolver = $resolver;
        }
        return self::$schoolrevolver;
    }

    /**
     * Set the school resolver.
     *
     * @param ischool_resolver $resolver The resolver.
     */
    public static function set_school_resolver(ischool_resolver $resolver) {
        self::$schoolrevolver = $resolver;
    }

    /**
     * Whether we're using sections.
     *
     * @return bool
     */
    public static function uses_sections() {
        $usesections = get_config('local_mootivated', 'usesections');
        return !empty($usesections);
    }

}
