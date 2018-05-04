<?php
require_once("$CFG->libdir/filelib.php");
require_once("$CFG->libdir/resourcelib.php");
require_once($CFG->dirroot.'/mod/gamelearn/config.php');
require_once($CFG->dirroot.'/mod/quizoff/config.php');

function gamelearn_get_final_display_type($gamelearn) {
    global $CFG;

    if ($gamelearn->display != RESOURCELIB_DISPLAY_AUTO) {
        return $gamelearn->display;
    }

    // detect links to local moodle pages
    if (strpos($gamelearn->externalurl, $CFG->wwwroot) === 0) {
        if (strpos($gamelearn->externalurl, 'file.php') === false and strpos($gamelearn->externalurl, '.php') !== false ) {
            // most probably our moodle page with navigation
            return RESOURCELIB_DISPLAY_OPEN;
        }
    }

    static $download = array('application/zip', 'application/x-tar', 'application/g-zip',     // binary formats
                             'application/pdf', 'text/html');  // these are known to cause trouble for external links, sorry
    static $embed    = array('image/gif', 'image/jpeg', 'image/png', 'image/svg+xml',         // images
                             'application/x-shockwave-flash', 'video/x-flv', 'video/x-ms-wm', // video formats
                             'video/quicktime', 'video/mpeg', 'video/mp4',
                             'audio/mp3', 'audio/x-realaudio-plugin', 'x-realaudio-plugin',   // audio formats,
                            );

    $mimetype = resourcelib_guess_url_mimetype($gamelearn->externalurl);

    if (in_array($mimetype, $download)) {
        return RESOURCELIB_DISPLAY_DOWNLOAD;
    }
    if (in_array($mimetype, $embed)) {
        return RESOURCELIB_DISPLAY_EMBED;
    }

    // let the browser deal with it somehow
    return RESOURCELIB_DISPLAY_OPEN;
}

function gamelearn_get_full_url($gamelearn, $cm, $course, $config=null) {
    global $COURSE;
    
    // make sure there are no encoded entities, it is ok to do this twice
    $fullurl = html_entity_decode($gamelearn->externalurl, ENT_QUOTES, 'UTF-8');

    if (preg_match('/^(\/|https?:|ftp:)/i', $fullurl) or preg_match('|^/|', $fullurl)) {
        // encode extra chars in URLs - this does not make it always valid, but it helps with some UTF-8 problems
        $allowed = "a-zA-Z0-9".preg_quote(';/?:@=&$_.+!*(),-#%', '/');
        $fullurl = preg_replace_callback("/[^$allowed]/", 'url_filter_callback', $fullurl);
    } else {
        // encode special chars only
        $fullurl = str_replace('"', '%22', $fullurl);
        $fullurl = str_replace('\'', '%27', $fullurl);
        $fullurl = str_replace(' ', '%20', $fullurl);
        $fullurl = str_replace('<', '%3C', $fullurl);
        $fullurl = str_replace('>', '%3E', $fullurl);
    }
        
    $courseId = $COURSE->id;
    $currentCourseSection = $cm->section;
    $categoryId = $COURSE->category;
    $token = get_token_by_user_id();
    
    $fullurl = $fullurl . "?token=$token&class_lesson=$categoryId&subject_lesson=$courseId&lesson=$currentCourseSection";
    
    // encode all & to &amp; entity
    $fullurl = str_replace('&', '&amp;', $fullurl);

    return $fullurl;
}

function get_token_by_user_id() {
    global $USER;
    
    $cache = cache::make_from_params(cache_store::MODE_SESSION, 'mod_gamelearn', 'json');
    $cachedToken = $cache->get('token');
    
    if(!empty($cachedToken)) {
        return $cachedToken;
    }

    $user_id = $USER->id;    
    $post_data = array('user_id' => $user_id);
    $context = stream_context_create(array(
        'http' => array(
            'method' => 'POST',
            'header' => "Content-Type: application/json\r\n",
            'content' => json_encode($post_data)
        )
    ));
    
    $response = file_get_contents(JSON_SERVICES_GET_TOKEN_BY_USER_UD_URL, FALSE, $context);
    
    if ($response === FALSE || $response == 0) {
        return '0';
    }
    
    $cache->set('token', $response);
    
    return $response;
}

function gamelearn_print_header($gamelearn, $cm, $course) {
    global $PAGE, $OUTPUT;

    $PAGE->set_title($course->shortname.': '.$gamelearn->name);
    $PAGE->set_heading($course->fullname);
    $PAGE->set_activity_record($gamelearn);
    echo $OUTPUT->header();
}

function gamelearn_print_heading($gamelearn, $cm, $course, $notused = false) {
    global $OUTPUT;
    echo $OUTPUT->heading(format_string($gamelearn->name), 2);
}

function gamelearn_display_frame($gamelearn, $cm, $course) {
    global $PAGE, $OUTPUT, $CFG;

    $frame = optional_param('frameset', 'main', PARAM_ALPHA);

    if ($frame === 'top') {
        $PAGE->set_pagelayout('frametop');
        gamelearn_print_header($gamelearn, $cm, $course);
        gamelearn_print_heading($gamelearn, $cm, $course);
        echo $OUTPUT->footer();
        die;

    } else {
        $config = get_config('gamelearn');
        $context = context_module::instance($cm->id);
        $exteurl = gamelearn_get_full_url($gamelearn, $cm, $course, $config);
        $navurl = "$CFG->wwwroot/mod/gamelearn/view.php?id=$cm->id&amp;frameset=top";
        $coursecontext = context_course::instance($course->id);
        $courseshortname = format_string($course->shortname, true, array('context' => $coursecontext));
        $title = strip_tags($courseshortname.': '.format_string($gamelearn->name));
        $framesize = $config->framesize;
        $modulename = s(get_string('modulename','gamelearn'));
        $contentframetitle = s(format_string($gamelearn->name));
        $dir = get_string('thisdirection', 'langconfig');

        $extframe = <<<EOF
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">
<html dir="$dir">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title>$title</title>
  </head>
  <frameset rows="$framesize,*">
    <frame src="$navurl" title="$modulename"/>
    <frame src="$exteurl" title="$contentframetitle"/>
  </frameset>
</html>
EOF;

        @header('Content-Type: text/html; charset=utf-8');
        echo $extframe;
        die;
    }
}

function gamelearn_url_appears_valid_url($url) {
    if (preg_match('/^(\/|https?:|ftp:)/i', $url)) {
        // note: this is not exact validation, we look for severely malformed URLs only
        return (bool)preg_match('/^[a-z]+:\/\/([^:@\s]+:[^@\s]+@)?[a-z0-9_\.\-]+(:[0-9]+)?(\/[^#]*)?(#.*)?$/i', $url);
    } else {
        return (bool)preg_match('/^[a-z]+:\/\/...*$/i', $url);
    }
}

function gamelearn_fix_submitted_url($url) {
    // note: empty urls are prevented in form validation
    $url = trim($url);

    // remove encoded entities - we want the raw URI here
    $url = html_entity_decode($url, ENT_QUOTES, 'UTF-8');

    if (!preg_match('|^[a-z]+:|i', $url) and !preg_match('|^/|', $url)) {
        // invalid URI, try to fix it by making it normal URL,
        // please note relative urls are not allowed, /xx/yy links are ok
        $url = 'http://'.$url;
    }

    return $url;
}

function gamelearn_display_embed($gamelearn, $cm, $course) {
    global $CFG, $PAGE, $OUTPUT;

    $fullurl  = gamelearn_get_full_url($gamelearn, $cm, $course);
    $title    = $gamelearn->name;

    $link = html_writer::tag('a', $fullurl, array('href'=>str_replace('&amp;', '&', $fullurl)));
    $clicktoopen = get_string('clicktoopen', 'url', $link);
    $moodleurl = new moodle_url($fullurl);

    $mediarenderer = $PAGE->get_renderer('core', 'media');
    $embedoptions = array(
        core_media::OPTION_TRUSTED => true,
        core_media::OPTION_BLOCK => true
    );

    if ($mediarenderer->can_embed_url($moodleurl, $embedoptions)) {
        // Media (audio/video) file.
        $code = $mediarenderer->embed_url($moodleurl, $title, 0, 0, $embedoptions);

    } else {
        // anything else - just try object tag enlarged as much as possible
        $code = resourcelib_embed_general($fullurl, $title, $clicktoopen, $mimetype);
    }

    gamelearn_print_header($gamelearn, $cm, $course);
    gamelearn_print_heading($gamelearn, $cm, $course);

    echo $code;
    echo $OUTPUT->footer();
    die;
}