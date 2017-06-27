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
 * Renderer for outputting the eTask topics course format.
 *
 * @package format_etask
 * @copyright 2012 Dan Poltawski
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since Moodle 2.3
 */


defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot.'/course/format/renderer.php');

/**
 * Basic renderer for eTask topics format.
 *
 * @copyright 2017 Martin Drlik <martin.drlik@email.cz>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class format_etask_renderer extends format_section_renderer_base {

    /**
     *
     * @var FormatEtaskLib
     */
    private $etasklib;

    /**
     *
     * @var int
     */
    private $studentsperpage;

    /**
     * Constructor method, calls the parent constructor.
     *
     * @param moodle_page $page
     * @param string $target one of rendering target constants
     */
    public function __construct(moodle_page $page, $target) {
        parent::__construct($page, $target);

        // Since format_etask_renderer::section_edit_controls() only displays the 'Set current section' control
        // when editing mode is on we need to be sure that the link 'Turn editing mode on' is available for a user
        // who does not have any other managing capability.
        $page->set_other_editing_capability('moodle/course:setcurrentsection');
    }

    /**
     * Html representaiton of user picture and name linked to user profile.
     *
     * @param core_user $user
     * @return string
     */
    private function render_user_head($user) {
        $userpicture = $this->output->user_picture($user, [
            'size' => 35,
            'link' => true,
            'popup' => true
        ]);
        $url = new moodle_url('/user/view.php', [
            'id' => $user->id,
            'course' => $this->page->course->id
        ]);

        return $userpicture . ' ' . html_writer::link($url, $user->firstname . ' ' . $user->lastname);
    }

    /**
     * Html representation of grade item head.
     *
     * @param grade_item $gradeitem
     * @param integer $itemnum
     * @param integer $studentscount
     * @param array $progressbardata
     * @return string
     */
    private function render_grade_item_head($gradeitem, $itemnum, $studentscount, array $progressbardata) {
        $sesskey = sesskey();
        $sectionreturn = optional_param('sr', 0, PARAM_INT);

        $itemtitleshort = strtoupper(substr($gradeitem->itemmodule, 0, 1)) . $itemnum;
        $gradesettings = $this->render_grade_settings($gradeitem, $this->page->context);

        // Calculate progress bar data count if allowed in cfg.
        $showprogressbarscfg = (bool) get_config('format_etask', 'show_progress_bars');
        if ($showprogressbarscfg === true) {
            // Init porgress bars data.
            $progressbardatainit = [
                'passed' => 0,
                'submitted' => 0,
                'failed' => 0
            ];

            $progressbardatacount = array_merge($progressbardatainit, array_count_values($progressbardata));
            $progresspassed = round(100 * ($progressbardatacount['passed'] / $studentscount));
            $progresssubmitted = round(100 * (
                array_sum([
                    $progressbardatacount['submitted'],
                    $progressbardatacount['passed'], $progressbardatacount['failed']
                ]) / $studentscount));
        }

        // Prepare module icon.
        $ico = html_writer::img($this->output->pix_url('icon', $gradeitem->itemmodule), '', [
            'class' => 'itemIco'
        ]);

        // Prepare due date if available.
        $duedate = $this->etasklib->get_due_date($gradeitem);
        $duedatetag = '';
        if (!empty($duedate)) {
            $duedatetag = html_writer::tag('tr',
                html_writer::tag('td', '<strong>' . get_string('duedate', 'assign') . ':&nbsp;</strong>') .
                html_writer::tag('td', $duedate));
        }

        // Prepare grade to pass if available.
        $gradepass = round($gradeitem->gradepass, 0);
        if (!empty($gradeitem->scaleid) && !empty($gradepass)) {
            $scale = $this->etasklib->get_scale($gradeitem->scaleid);
            $gradepass = $scale[$gradepass];
        }

        $gradepasstag = '';
        if (!empty($gradepass)) {
            $gradepasstag = html_writer::tag('tr',
                html_writer::tag('td', '<strong>' . get_string('gradepass', 'grades') . ':&nbsp;</strong>') .
                html_writer::tag('td', html_writer::span($gradepass, 'label label-success')));
        }

        // Prepare progress bars if allowed.
        $progressbars = '';
        $passedplaceholder = !empty($progresspassed) ? html_writer::tag(
            'div',
            '&nbsp;', [
                'class' => 'label label-success',
                'style' => 'width: ' . $progresspassed . '%; height: 18px; padding: 0;'
            ]
        ) : '&nbsp;';
        $submittedplaceholder = !empty($progresssubmitted) ? html_writer::tag(
            'div',
            '&nbsp;', [
                'class' => 'label label-warning',
                'style' => 'width: ' . $progresssubmitted . '%; height: 18px; padding: 0;'
            ]
        ) : '&nbsp;';
        if ($showprogressbarscfg === true) {
            $progressbars = html_writer::tag('tr',
                html_writer::tag('td', '<strong>' . get_string('passed', 'format_etask') . ':&nbsp;</strong>') .
                html_writer::tag('td',
                    html_writer::tag(
                        'div',
                        $passedplaceholder, [
                            'class' => 'label label-default',
                            'style' => 'width: 100%; height: 18px; background-color: #fffbde; padding: 0;'
                        ]
                    ),
                    ['style' => 'width: 100%; padding-right: 20px;']
                )
            ) . html_writer::tag('tr',
                html_writer::tag('td', '<strong>' . get_string('submitted', 'format_etask') . ':&nbsp;</strong>') .
                html_writer::tag('td',
                    html_writer::tag(
                        'div',
                        $submittedplaceholder, [
                            'class' => 'label label-default',
                            'style' => 'width: 100%; height: 18px; background-color: #fffbde; padding: 0;'
                        ]
                    ),
                    ['style' => 'width: 100%; padding-right: 20px;']
                )
            );
        }

        // Prepare activity tooltip.
        $tooltiptitle = html_writer::tag('div',
            '<h5>' . get_string('pluginname', $gradeitem->itemmodule) . ': ' . $gradeitem->itemname . '</h5>
            ' . html_writer::tag('table', $duedatetag . '
            ' . $gradepasstag . '
            ' . $progressbars), ['class' => 'gradeItemTooltip']);

        $moditems = $this->etasklib->get_mod_items($this->page->course);

        // Prepare activity short link.
        $itemtitleshortlink = '';
        if (has_capability('moodle/course:update', $this->page->context)) {
            $itemtitleshortlink = html_writer::link(new moodle_url('/course/mod.php', [
                'sesskey' => $sesskey,
                'sr' => $sectionreturn,
                'update' => $moditems[$gradeitem->itemmodule][$gradeitem->iteminstance]
            ]), $ico . ' ' . $itemtitleshort, [
                'class' => 'gradeItemHeadTooltip',
                'title' => $tooltiptitle
            ]);
        } else {
            $itemtitleshortlink = html_writer::link(new moodle_url('/mod/' . $gradeitem->itemmodule . '/view.php', [
                'id' => $moditems[$gradeitem->itemmodule][$gradeitem->iteminstance]
            ]), $ico . ' ' . $itemtitleshort, [
                'class' => 'gradeItemHeadTooltip',
                'title' => $tooltiptitle
            ]);
        }

        // Prepare grade item head.
        $ret = html_writer::tag('div', $itemtitleshortlink . $gradesettings, [
            'class' => 'gradeItemContainer'
        ]);

        return $ret;
    }

    /**
     * Html representation of grade settings.
     *
     * @param grade_item $gradeitem
     * @param context_course $context
     * @return string
     */
    private function render_grade_settings($gradeitem, $context) {
        $gradesettings = '';

        if ($this->page->user_is_editing() && has_capability('moodle/course:update', $context)) {
            $ico = html_writer::img($this->output->pix_url('t/edit', 'core'), '', [
                'class' => 'iconsmall gradeItemDialog pointer',
                'id' => 'editGradeItem' . $gradeitem->id
            ]);

            $gradesettings = $ico . html_writer::div($this->render_grade_settings_form($gradeitem), 'gradeSettings', [
                'id' => 'gradeSettings-editGradeItem' . $gradeitem->id,
                'style' => 'display:none;'
            ]);
        }

        return $gradesettings;
    }

    /**
     * Create grade settings form.
     *
     * @param grade_item $gradeitem
     * @return string
     */
    private function render_grade_settings_form($gradeitem) {
        $action = new moodle_url('/course/view.php', [
            'id' => $this->page->course->id,
            'gradeItemId' => $gradeitem->id
        ]);

        if (!empty($gradeitem->scaleid)) {
            $scale = $this->etasklib->get_scale($gradeitem->scaleid);
        } else {
            $grademax = round($gradeitem->grademax, 0);

            for ($i = $grademax; $i >= 1; --$i) {
                $scale[$i] = $i;
            }
        }

        $formtitle = html_writer::div(get_string('pluginname', $gradeitem->itemmodule) . ': ' . $gradeitem->itemname, 'hd');
        $form = new GradeSettingsForm($action->out(false), [
            'gradeItem' => $gradeitem,
            'scale' => $scale
        ]);
        return $formtitle . html_writer::tag('div', $form->render(), [
            'class' => 'bd'
        ]);
    }

    /**
     * Create grade table form.
     *
     * @param array $groups
     * @param int $selectedgroup
     * @param int $studentscount
     * @return string
     */
    private function render_grade_table_footer(array $groups, $selectedgroup, $studentscount) {
        global $SESSION;
        $page = isset($SESSION->eTask['page']) ? $SESSION->eTask['page'] : 0;
        $action = new moodle_url('/course/view.php', [
            'id' => $this->page->course->id
        ]);
        $formrender = '';
        if (!empty($groups) && has_capability('moodle/course:update', $this->page->context)) {
            $form = new GradeTableForm($action->out(false), [
                'groups' => $groups,
                'selectedGroup' => $selectedgroup
            ]);

            $formrender = $form->render();
        }

        return html_writer::start_tag('table', ['class' => 'gradeTableFooter']) .
                html_writer::div(html_writer::tag('tr',
                    html_writer::tag('td', $formrender, ['class' => 'gradeTableForm']) .
                    html_writer::tag(
                        'td',
                        html_writer::div(
                            $this->paging_bar(
                                $studentscount,
                                $page,
                                $this->studentsperpage,
                                $action)
                        ), ['style' => 'text-align: center;']) .
                    html_writer::tag('td', html_writer::div(
                html_writer::tag(
                    'strong',
                    get_string('legend', 'format_etask') . ': ') . html_writer::tag(
                        'span',
                        get_string('submitted', 'format_etask'), [
                            'class' => 'submitted'
                        ]
                    ) . html_writer::tag('span', get_string('passed', 'format_etask'), [
                        'class' => 'passed'
                    ]) . html_writer::tag('span', get_string('failed', 'format_etask'), [
                        'class' => 'failed'
                    ]), 'legend')))) .
                html_writer::end_tag('table');
    }

    /**
     * Html representation of grade item body.
     *
     * @param grade_grade $usersgrades
     * @param grade_item $gradeitem
     * @param core_user $user
     * @return array
     */
    private function render_item_body($usersgrades, $gradeitem, $user) {
        $sectionreturn = optional_param('sr', 0, PARAM_INT);

        $finalgrade = (int) $usersgrades[$gradeitem->id][$user->id]->finalgrade;
        if (empty($usersgrades[$gradeitem->id][$user->id]->rawscaleid) && !empty($finalgrade)) {
            $gradevalue = $finalgrade;
        } else if (!empty($usersgrades[$gradeitem->id][$user->id]->rawscaleid) && !empty($finalgrade)) {
            $scale = $this->etasklib->get_scale($gradeitem->scaleid);
            $gradevalue = $scale[$finalgrade];
        } else {
            $gradevalue = '-';
        }

        if (has_capability('moodle/course:update', $this->page->context)) {
            $gradelinkparams = [
                'courseid' => $this->page->course->id,
                'id' => $usersgrades[$gradeitem->id][$user->id]->id,
                'gpr_type' => 'report',
                'gpr_plugin' => 'grader',
                'gpr_courseid' => $this->page->course->id,
                'sr' => $sectionreturn
            ];

            if (empty($usersgrades[$gradeitem->id][$user->id]->id)) {
                $gradelinkparams['userid'] = $user->id;
                $gradelinkparams['itemid'] = $gradeitem->id;
            }

            $gradelink = html_writer::link(new moodle_url('/grade/edit/tree/grade.php', $gradelinkparams), $gradevalue, [
                'class' => 'gradeItemBody',
                'title' => $user->firstname . ' ' . $user->lastname . ': ' . $gradeitem->itemname
            ]);
        } else {
            $gradelink = $gradevalue;
        }

        return [
            'text' => $gradelink,
            'status' => $this->etasklib->get_grade_item_status($gradeitem, $finalgrade, $user->id)
        ];
    }

    /**
     * Render flash message.
     *
     * @param array $messagedata
     */
    public function render_message($messagedata) {
        $closebutton = '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>';
        $message = $closebutton . $messagedata['message'];
        if ($messagedata['success'] === true) {
            echo html_writer::div($message, 'alert alert-success', ['data-dismiss' => 'alert']);
        } else {
            echo html_writer::div($message, 'alert alert-error', ['data-dismiss' => 'alert']);
        }
    }

    /**
     * Render grade table.
     *
     * @param context_course $context
     * @param stdClass $course
     * @param FormatEtaskLib $etasklib
     * @return string
     */
    public function render_grade_table($context, $course, $etasklib) {
        echo '
            <style type="text/css" media="screen" title="Graphic layout" scoped>
            <!--
                @import "../lib/jquery/ui-1.11.4/jquery-ui.min.css";
                @import "../course/format/etask/format_etask.css";
            -->
            </style>';

        global $USER;
        global $SESSION;
        $this->etasklib = $etasklib;
        $this->studentsperpage = get_config('format_etask', 'students_per_page');

        $users = get_enrolled_users($context);
        // Get logged in user groups membership.
        $loggedinusergroups = current($USER->groupmember);
        // Get all course groups and selected group to the group filter form.
        $allcoursegroups = $this->etasklib->get_course_groups($course->id);
        $selectedgroup = !empty($SESSION->eTask['filtergroup']) ? $SESSION->eTask['filtergroup'] : key($allcoursegroups);
        // In the grading table show only users with role 'student'.
        $students = [];
        foreach ($users as $user) {
            $isalloweduser = $this->etasklib->is_allowed_user($context, $course, $user, $selectedgroup, $loggedinusergroups);
            if ($isalloweduser === true) {
                $students[$user->id] = $user;
            }
        }
        // Students count for pagination.
        $studentscount = count($students);
        $gradeitems = [];
        $usersgrades = [];
        if (!empty($students)) {
            $gradeitems = grade_item::fetch_all(['courseid' => $course->id, 'itemtype' => 'mod', 'hidden' => 0]);
            if ($gradeitems === false) {
                $gradeitems = [];
            }
            krsort($gradeitems);

            foreach ($gradeitems as $gradeitem) {
                $usersgrades[$gradeitem->id] = grade_grade::fetch_users_grades($gradeitem, array_keys($students), true);
            }
        }
        $this->page->requires->js(new moodle_url('/course/format/etask/format_etask.js'));

        $editableclass = '';
        if (has_capability('moodle/course:update', $this->page->context)) {
            $editableclass = 'headerSettings';
        }
        $privateviewcfg = (bool) get_config('format_etask', 'private_view');
        $privateview = false;
        $privateviewuserid = 0;
        // If private view is active, students can view only own grades.
        if ($privateviewcfg === true && !has_capability('moodle/course:update', $context)) {
            $privateview = true;
            $privateviewuserid = $USER->id;
            $studentscount = 1;
        }

        $data = [];
        $progressbardata = [];
        foreach ($students as $user) {
            $bodycells = [];
            if ($privateview === false || ($privateview === true && $user->id === $privateviewuserid)) {
                $cell = new html_table_cell();
                $cell->text = $this->render_user_head($user);
                $cell->attributes = array(
                    'class' => 'userHeader'
                );
                $bodycells[] = $cell;
            }

            foreach ($gradeitems as $gradeitem) {
                $grade = $this->render_item_body($usersgrades, $gradeitem, $user);
                $progressbardata[$gradeitem->id][] = $grade['status'];
                if ($privateview === false || ($privateview === true && $user->id === $privateviewuserid)) {
                    $cell = new html_table_cell();
                    $cell->text = $grade['text'];
                    $cell->attributes = [
                        'class' => 'gradeItemGrade center ' . $grade['status'],
                        'title' => $user->firstname . ' ' . $user->lastname . ': ' . $gradeitem->itemname
                    ];
                    $bodycells[] = $cell;
                }
            }

            if ($privateview === false || ($privateview === true && $user->id === $privateviewuserid)) {
                $row = new html_table_row($bodycells);
                $data[] = $row;
            }
        }
        // Table head.
        $headcells = ['']; // First cell of the head is empty.
        $gradeitemsnum = [];
        // Grade items num.
        foreach ($gradeitems as $gradeitem) {
            if (!isset($gradeitemsnum[$gradeitem->itemmodule])) {
                $gradeitemsnum[$gradeitem->itemmodule] = 0;
            }
            $gradeitemsnum[$gradeitem->itemmodule]++;
        }
        // Render table cells.
        foreach ($gradeitems as $gradeitem) {
            $cell = new html_table_cell();
            $cell->text = $this->render_grade_item_head(
                $gradeitem,
                $gradeitemsnum[$gradeitem->itemmodule],
                count($students),
                $progressbardata[$gradeitem->id]);
            $cell->attributes = array(
                'class' => 'gradeItemHeader center ' . $editableclass
            );
            $headcells[] = $cell;
            $gradeitemsnum[$gradeitem->itemmodule]--;
        }

        // Slice of students by paging after geting progresbar data.
        $SESSION->eTask['page'] = $studentscount <= $SESSION->eTask['page'] * $this->studentsperpage ? 0 : $SESSION->eTask['page'];
        $data = array_slice($data, $SESSION->eTask['page'] * $this->studentsperpage, $this->studentsperpage, $preserve_keys = true);

        // Html table.
        $gradebook = new html_table();
        $gradebook->attributes = [
            'class' => 'gradeTable table-hover table-striped table-condensed',
            'table-layout' => 'fixed'
        ];
        $gradebook->head = $headcells;
        $gradebook->data = $data;

        // Grade table footer: groups filter and legend.
        $gradebookfooter = $this->render_grade_table_footer($allcoursegroups, $selectedgroup, $studentscount);

        echo html_writer::div(
                html_writer::table($gradebook),
                'block eTaskGradeTable table-responsive'
            ) . $gradebookfooter;
    }

    /**
     * Generate the starting container html for a list of sections
     *
     * @return string HTML to output.
     */
    protected function start_section_list() {
        return html_writer::start_tag('ul', array(
            'class' => 'topics'
        ));
    }

    /**
     * Generate the closing container html for a list of sections
     *
     * @return string HTML to output.
     */
    protected function end_section_list() {
        return html_writer::end_tag('ul');
    }

    /**
     * Generate the title for this section page
     *
     * @return string the page title
     */
    protected function page_title() {
        return get_string('topicoutline');
    }

    /**
     * Generate the section title, wraps it in a link to the section page if page is to be displayed on a separate page
     *
     * @param stdClass $section The course_section entry from DB
     * @param stdClass $course The course entry from DB
     * @return string HTML to output.
     */
    public function section_title($section, $course) {
        return $this->render(course_get_format($course)->inplace_editable_render_section_name($section));
    }

    /**
     * Generate the section title to be displayed on the section page, without a link
     *
     * @param stdClass $section The course_section entry from DB
     * @param stdClass $course The course entry from DB
     * @return string HTML to output.
     */
    public function section_title_without_link($section, $course) {
        return $this->render(course_get_format($course)->inplace_editable_render_section_name($section, false));
    }

    /**
     * Generate the edit control items of a section
     *
     * @param stdClass $course The course entry from DB
     * @param stdClass $section The course_section entry from DB
     * @param bool $onsectionpage true if being printed on a section page
     * @return array of edit control items
     */
    protected function section_edit_control_items($course, $section, $onsectionpage = false) {
        global $PAGE;

        if (!$PAGE->user_is_editing()) {
            return array();
        }

        $coursecontext = context_course::instance($course->id);

        if ($onsectionpage) {
            $url = course_get_url($course, $section->section);
        } else {
            $url = course_get_url($course);
        }
        $url->param('sesskey', sesskey());

        $isstealth = $section->section > $course->numsections;
        $controls = array();
        if (!$isstealth && $section->section && has_capability('moodle/course:setcurrentsection', $coursecontext)) {
            if ($course->marker == $section->section) { // Show the "light globe" on/off.
                $url->param('marker', 0);
                $markedthistopic = get_string('markedthistopic');
                $highlightoff = get_string('highlightoff');
                $controls['highlight'] = array(
                    'url' => $url,
                    "icon" => 'i/marked',
                    'name' => $highlightoff,
                    'pixattr' => array(
                        'class' => '',
                        'alt' => $markedthistopic
                    ),
                    'attr' => array(
                        'class' => 'editing_highlight',
                        'title' => $markedthistopic
                    )
                );
            } else {
                $url->param('marker', $section->section);
                $markthistopic = get_string('markthistopic');
                $highlight = get_string('highlight');
                $controls['highlight'] = array(
                    'url' => $url,
                    "icon" => 'i/marker',
                    'name' => $highlight,
                    'pixattr' => array(
                        'class' => '',
                        'alt' => $markthistopic
                    ),
                    'attr' => array(
                        'class' => 'editing_highlight',
                        'title' => $markthistopic
                    )
                );
            }
        }

        $parentcontrols = parent::section_edit_control_items($course, $section, $onsectionpage);

        // If the edit key exists, we are going to insert our controls after it.
        if (array_key_exists("edit", $parentcontrols)) {
            $merged = array();
            // We can't use splice because we are using associative arrays.
            // Step through the array and merge the arrays.
            foreach ($parentcontrols as $key => $action) {
                $merged[$key] = $action;
                if ($key == "edit") {
                    // If we have come to the edit key, merge these controls here.
                    $merged = array_merge($merged, $controls);
                }
            }

            return $merged;
        } else {
            return array_merge($controls, $parentcontrols);
        }
    }
}
