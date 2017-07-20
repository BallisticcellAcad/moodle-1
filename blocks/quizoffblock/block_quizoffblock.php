<?php

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/quizoff/config.php');
require_once($CFG->dirroot . '/mod/quizoff/locallib.php');

/**
 * Description of block_quizoffblock
 *
 * @author Sve
 */
class block_quizoffblock extends block_base {

    public function init() {
        $this->title = get_string('title', 'block_quizoffblock');
    }

    public function get_content() {
        global $USER, $DB, $CFG;

        if ($this->content !== null) {
            return $this->content;
        }

        $userId = $USER->id;
//        $k12 = K12_NAME;
//        $enrolK12Query = "SELECT COUNT(*) AS EnrollCount FROM mdl_user_enrolments WHERE userid=$userId AND enrolid IN(
//            SELECT id FROM mdl_enrol where enrol='manual' AND status=0 AND courseid IN(
//                    SELECT id FROM mdl_course WHERE category IN 
//                    (SELECT id FROM mdl_course_categories WHERE name='$k12')
//                    UNION 
//                    SELECT id FROM mdl_course WHERE category IN 
//                    (SELECT id FROM mdl_course_categories WHERE parent=(SELECT id FROM mdl_course_categories WHERE name='$k12'))
//            ) 
//        )";

        //$enrolCount = $DB->get_field_sql($enrolK12Query);

        //$hasK12Enrollment = $enrolCount > 0;

        $urlParam = QUIZOFF_GAME_URL;
        $token = get_token_by_user_id();
        $tokenParam = "?token=" . $token;
        $urlWithToken = str_replace('&', '&amp;', $urlParam . $tokenParam);

        $pageUrl = $CFG->wwwroot . QUIZOFF_STATIC_PAGE_URL . $urlWithToken;

        $htmlText = "<div style='float: left; text-align: right; vertical-align: middle; padding-top: 110px; padding-right: 20px'>" . $this->GetButtonHtml("Играй", $pageUrl);
        $htmlText .= '<br/>';

        $rank = $DB->get_field_sql("SELECT total_user_rank_score FROM user_last WHERE userid=$userId");
        if (empty($rank)) {
            $rank = 0;
        }
        $bgImageUrl = $CFG->wwwroot . '/blocks/quizoffblock/pix/gold-trophey.png';
        $rangCssStyle = "float: left; "
                . "padding-left: 50px; "
                . "width: 250px; "
                . "height: 245px; "
                . "padding-left: 110px; "
                . "padding-top: 205px; "
                . "color: white; "
                . "font-size: x-large; "
                . "background-image: url(\"$bgImageUrl\")";
        $rangHtml = "<div style='$rangCssStyle'><span>$rank</span></div>";
        
//        if ($hasK12Enrollment) {
//            $htmlText .= $this->GetButtonHtml("Играй с учебно съдържание", $pageUrlWithToken);
//        }

        $htmlText .= "</div>" . $rangHtml;

        $this->content = new stdClass;
        $this->content->text = $htmlText;
        $this->content->footer = '';

        return $this->content;
    }

    function GetButtonHtml($buttonText, $buttonUrl) {
        return "<div style='width: 250px; text-align: right;'>"
                . "<a href='$buttonUrl'><button type=\"button\" id=\"btn-save-changes\" class=\"btn btn-primary btn-flat\">$buttonText</button></a>"
                . "</div><br/>";
    }

}
