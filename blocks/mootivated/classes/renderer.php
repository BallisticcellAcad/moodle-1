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
 * Block Mootivated renderer.
 *
 * @package    block_mootivated
 * @copyright  2017 Mootivation Technologies Corp.
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

use block_mootivated\manager;

/**
 * Block Mootivated renderer class.
 *
 * Note: We CANNOT use namespaces here.
 *
 * @package    block_mootivated
 * @copyright  2017 Mootivation Technologies Corp.
 * @author     Frédéric Massart <fred@branchup.tech>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_mootivated_renderer extends plugin_renderer_base {

    /**
     * Render a user's avatar.
     *
     * @param manager $manager The manager.
     * @return string
     */
    public function avatar(manager $manager) {
        return $this->user_picture($manager->get_user(), [
            'size' => 512,
            'link' => false,
            'class' => 'mootivated-avatar',
            'alttext' => false
        ]);
    }

    /**
     * Full page avatar.
     *
     * The page which displays the avatar in full size.
     *
     * @param manager $manager The manager
     * @return string
     */
    public function fullpage_avatar(manager $manager) {
        $o = '';
        $o .= $this->avatar($manager);
        return $o;
    }

    /**
     * Return the block's content.
     *
     * @param manager $manager The manager.
     * @param bool $hideavatar Whether to hide the avatar.
     * @return string
     */
    public function main_block_content(manager $manager, $hideavatar) {
        $o = '';
        if (!$hideavatar) {
            $o .= html_writer::start_div();
            $o .= html_writer::link(new moodle_url('/blocks/mootivated/myavatar.php'), $this->avatar($manager));
            $o .= html_writer::end_div();
        }
        $o .= html_writer::start_div();
        $o .= $this->wallet($manager);
        $o .= html_writer::end_div();
        return $o;
    }

    /**
     * Return the block's content for managers.
     *
     * @param manager $manager The manager.
     * @param bool $hideavatar Whether to hide the avatar.
     * @return string
     */
    public function main_block_content_for_managers(manager $manager, $hideavatar) {
        return $this->main_block_content($manager, $hideavatar);
    }

    /**
     * Override pix_url to auto-handle deprecation.
     *
     * It's just simpler than having to deal with differences between
     * Moodle < 3.3, and Moodle >= 3.3.
     *
     * @param string $image The file.
     * @param string $component The component.
     * @return string
     */
    public function pix_url($image, $component = 'moodle') {
        if (method_exists($this, 'image_url')) {
            return $this->image_url($image, $component);
        }
        return parent::pix_url($image, $component);
    }

    /**
     * Return the content of the user's wallet.
     *
     * @param manager $manager The manager.
     * @return string
     */
    public function wallet(manager $manager) {
        $coins = $manager->get_coins_for_session();
        $icon = $this->pix_url('coins', 'block_mootivated');
        $o = '';
        $o .= html_writer::start_div('mootivated-wallet');
        $o .= html_writer::start_div('pic');
        $o .= html_writer::empty_tag('img', ['src' => $icon, 'alt' => get_string('coin', 'block_mootivated')]);
        $o .= html_writer::end_div();
        $o .= html_writer::start_div('coins');
        $o .= html_writer::start_div('amount');
        $o .= get_string('ncoins', 'block_mootivated', $coins);
        $o .= html_writer::end_div();
        $o .= html_writer::start_div('legend');
        $o .= get_string('earnedthissession', 'block_mootivated');
        $o .= html_writer::end_div();
        $o .= html_writer::end_div();
        $o .= html_writer::end_div();
        return $o;
    }

}
