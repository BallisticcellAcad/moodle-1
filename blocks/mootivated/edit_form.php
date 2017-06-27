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
 * Block Mootivated edit form.
 *
 * @package    block_mootivated
 * @copyright  2017 Mootivation Technologies Corp.
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Block Mootivated edit form class.
 *
 * @package    block_mootivated
 * @copyright  2017 Mootivation Technologies Corp.
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_mootivated_edit_form extends block_edit_form {

    /**
     * Form definition.
     *
     * @param moodleform $mform Moodle form.
     * @return void
     */
    protected function specific_definition($mform) {
        $mform->addElement('header', 'confighdr', get_string('configheader', 'block_mootivated'));
        $mform->addElement('text', 'config_title', get_string('configtitle', 'block_mootivated'));
        $mform->setDefault('config_title', get_string('defaulttitle', 'block_mootivated'));
        $mform->setType('config_title', PARAM_TEXT);

        $mform->addElement('selectyesno', 'config_hideavatar', get_string('confighideavatar', 'block_mootivated'));
        $mform->setDefault('config_hideavatar', false);
    }

}
