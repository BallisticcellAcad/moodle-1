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
 * Settings.
 *
 * @package    format_etask
 * @copyright  2017 Martin Drlik <martin.drlik@email.cz>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$settings->add(
    new admin_setting_configcheckbox(
        'format_etask/private_view',
        get_string('privateviewlabel', 'format_etask'),
        get_string('privateviewinfo', 'format_etask'),
        0
    )
);

$settings->add(
    new admin_setting_configcheckbox(
        'format_etask/show_progress_bars',
        get_string('showprogressbarslabel', 'format_etask'),
        get_string('showprogressbarsinfo', 'format_etask'),
        1
    )
);

$settings->add(
    new admin_setting_configtext(
        'format_etask/students_per_page',
        get_string('studentsperpagelabel', 'format_etask'),
        get_string('studentsperpageinfo', 'format_etask'),
        10
    )
);

$settings->add(
    new admin_setting_configtextarea(
        'format_etask/registered_due_date_modules',
        get_string('registeredduedatemoduleslabel', 'format_etask'),
        get_string('registeredduedatemodulesinfo', 'format_etask'),
        'assign:duedate, lesson:deadline, lucson:deadline, quiz:timeclose, scorm:timeclose, workshop:submissionend'
    )
);
