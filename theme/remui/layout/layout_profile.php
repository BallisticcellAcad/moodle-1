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
 * Layout - User Profile
 *
 * @package   theme_remui
 * @copyright Copyright (c) 2016 WisdmLabs
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once('../config.php');
require_once($CFG->libdir . '/gdlib.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot . '/theme/remui/layout/partials/avatar.php');
require_once($CFG->dirroot . '/user/profile/lib.php');
require_once($CFG->dirroot . '/user/lib.php');
/* Default globals */
global $CFG, $PAGE, $USER, $SITE, $COURSE, $DB;
$hasrightsideblocks = $PAGE->blocks->region_has_content('side-post', $OUTPUT);

// Get current user info.
if (isloggedin() && !isguestuser()) {
// $userfullname = fullname($USER);
    $userid = optional_param('id', 0, PARAM_INT);
    $redirect = optional_param('redirect', null, PARAM_INT);

// Get other user's object from page url
    $otheruser = $DB->get_record('user', array('id' => $userid));
    $otheruserData = $DB->get_record_sql('SELECT * FROM user_last WHERE userId = ?', array($userid));
    $otheruserFields = $DB->get_records('user_info_data', array('userid' => $userid));
    $userfullname = fullname($otheruser);
    $interests = false;

    $pageurl = $PAGE->url;
    $contact = $DB->get_record('message_contacts', array('userid' => $USER->id, 'contactid' => $otheruser->id));
    $userprofileurl = new moodle_url('/user/profile.php', array('id' => $USER->id));
    $userdashboardurl = new moodle_url('/my');
    $userlogouturl = new moodle_url('/login/logout.php', array('sesskey' => sesskey(), 'alt' => 'logout'));
    $PAGE->set_url('/user/profile.php', array('id' => $userid));

// get user blog count
    if (!empty($CFG->enableblogs)) {
        include_once($CFG->dirroot . '/blog/locallib.php');
        $blogobj = new blog_listing();
        if ($sqlarray = $blogobj->get_entry_fetch_sql(false, 'created DESC')) {
            $sqlarray['sql'] = "SELECT p.*, u.firstnamephonetic,u.lastnamephonetic,u.middlename,u.alternatename,
            u.firstname,u.lastname, u.email FROM {post} p, {user} u WHERE u.deleted = 0 AND p.userid = u.id AND
            (p.module = 'blog' OR p.module = 'blog_external') AND (p.userid = ?  OR p.publishstate = 'site' )
            AND u.id = ? ORDER BY created DESC";
            $sqlarray['params'] = array($USER->id, $otheruser->id);
            $blogobj->entries = $DB->get_records_sql($sqlarray['sql'], $sqlarray['params']);
            $userblogcount = count($blogobj->entries);
            $userbloglink = new moodle_url('/blog/index.php?userid=' . $otheruser->id);
        }
    }
// get user posts count
    include_once($CFG->dirroot . '/mod/forum/lib.php');
    $courses = forum_get_courses_user_posted_in($otheruser);
    $userpostcount = forum_get_posts_by_user($otheruser, $courses)->totalcount;
    $userposts = forum_get_posts_by_user($otheruser, $courses);
    $userpostlink = new moodle_url('/mod/forum/user.php?id=' . $otheruser->id);

// get user discussions count
    $courses = forum_get_courses_user_posted_in($otheruser, 1);
    $userdiscussioncount = forum_get_posts_by_user($otheruser, $courses, 0, 1)->totalcount;
    $userdiscussionlink = new moodle_url('/mod/forum/user.php?id=' . $otheruser->id . '&mode=discussions');
}

$PAGE->set_popup_notification_allowed(false);
echo $OUTPUT->doctype();
?>

<html <?php echo $OUTPUT->htmlattributes(); ?>>
    <head>
        <title><?php echo $OUTPUT->page_title(); ?></title>
        <link rel="shortcut icon" href="<?php echo $OUTPUT->favicon() ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimal-ui">
        <?php echo $OUTPUT->standard_head_html(); ?>
    </head>

    <body <?php echo $OUTPUT->body_attributes(); ?>>
        <div class="wrapper"> <!-- main page wrapper -->

            <?php
            echo $OUTPUT->standard_top_of_body_html();

            // Include header navigation
            require_once(\theme_remui\controller\theme_controller::get_partial_element('header'));

            // Include main sidebar.
            require_once(\theme_remui\controller\theme_controller::get_partial_element('pre-aside'));
            ?>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">

                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <div class="heading"><?php echo $OUTPUT->page_heading(); ?></div>

                    <div class="action-buttons">
                        <?php echo $OUTPUT->page_heading_button(); ?>
                        <?php echo $OUTPUT->course_header(); ?>
                    </div>
                </section>

                <section class="content-breadcrumb">
                    <ol class="breadcrumb">
                        <?php echo $OUTPUT->navbar(); ?>
                    </ol>
                </section><!-- /.content-header -->

                <!-- Main content -->
                <section class="content">
                    <?php
                    $script = null;
                    $text = true;
                    $icon = false;
                    $userpicture = \theme_remui\controller\theme_controller::get_user_image_link($userid, 100);
                    ?>
                    <div class="row">
                        <div class="col-md-3">
                            <?php
                            $userid = optional_param('id', 0, PARAM_INT);
                            if (!$userid) {
                                $userid = $USER->id;
                            }

                            // get other user's object from page url
                            $otheruser = $DB->get_record('user', array('id' => $userid));
                            $userfullname = fullname($otheruser);
                            ?>

                            <!-- Widget: user widget style 1 -->
                            <div class="box box-widget widget-user-2">
                                <!-- Add the bg color to the header using any of the bg-* classes -->
                                <div class="widget-user-header bg-primary">
                                    <div class="widget-user-image">
                                        <img class="img-circle" src="<?php echo $userpicture; ?>" alt="User Avatar">
                                    </div>
                                    <!-- /.widget-user-image -->
                                    <div class="text-center">
                                        <h3 class="widget-user-username"><?php echo $userfullname; ?></h3>
                                    </div>
                                </div>
                                <div class="box-footer no-padding">
                                    <ul class="nav nav-stacked">
                                        <?php
                                        if (!empty($CFG->enableblogs)) {
                                            ?>
                                            <li><a href="<?php echo $userbloglink; ?>"><?php echo get_string('blogentries', 'theme_remui');
                                            ?>
                                                    <span class="pull-right badge bg-blue"><?php echo $userblogcount; ?></span></a></li>
                                            <?php
                                        }
                                        ?>
                                        <li><a href="<?php echo $userdiscussionlink; ?>"><?php echo get_string('discussions', 'theme_remui'); ?>
                                                <span class="pull-right badge bg-aqua"><?php echo $userdiscussioncount; ?></span></a></li>
                                        <li><a href="<?php echo $userpostlink; ?>"><?php echo get_string('discussionreplies', 'theme_remui'); ?>
                                                <span class="pull-right badge bg-green"><?php echo $userpostcount; ?></span></a></li>
                                        <?php
// if logged user is not viewing his/her own profile
                                        if ($otheruser->id != $USER->id) {
                                            if ($contact && $contact->blocked == 0) {
                                                ?>
                                                <a href="" onclick="return false;" data-action="remove" data-id="<?php echo $otheruser->id; ?>"
                                                   class="btn btn-warning btn-block btn-flat"><b><?php echo get_string('removefromcontacts', 'theme_remui'); ?></b>
                                                    <i class="fa fa-refresh fa-spin" style="display:none"></i>
                                                </a>
                                                <a href="" onclick="return false;" data-action="block" data-id="<?php echo $otheruser->id; ?>"
                                                   class="btn btn-danger btn-block btn-flat"><b><?php echo get_string('block', 'theme_remui'); ?></b>
                                                    <i class="fa fa-refresh fa-spin" style="display:none"></i>
                                                </a>
                                                <?php
                                            } else if (!$contact) {
                                                ?>
                                                <a href="" onclick="return false;" data-action="add" data-id="<?php echo $otheruser->id; ?>"
                                                   class="btn btn-primary btn-block btn-flat"><b><?php echo get_string('addtocontacts', 'theme_remui'); ?></b>
                                                    <i class="fa fa-refresh fa-spin" style="display:none"></i>
                                                </a>
                                                <a href="" onclick="return false;" data-action="block" data-id="<?php echo $otheruser->id; ?>"
                                                   class="btn btn-danger btn-block btn-flat"><b><?php echo get_string('block', 'theme_remui'); ?></b>
                                                    <i class="fa fa-refresh fa-spin" style="display:none"></i>
                                                </a>
                                                <?php
                                            } else {
                                                ?>
                                                <a href="" onclick="return false;" data-action="add" data-id="<?php echo $otheruser->id; ?>"
                                                   class="btn btn-primary btn-block btn-flat"><b><?php echo get_string('addtocontacts', 'theme_remui'); ?></b>
                                                    <i class="fa fa-refresh fa-spin" style="display:none"></i>
                                                </a>
                                                <a href="" onclick="return false;" data-action="unblock" data-id="<?php echo $otheruser->id; ?>"
                                                   class="btn btn-success btn-block btn-flat"><b><?php echo get_string('removeblock', 'theme_remui'); ?></b>
                                                    <i class="fa fa-refresh fa-spin" style="display:none"></i>
                                                </a>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </ul>
                                </div>
                                <div class="alert alert-info" id="add-contacts-error" style="display:none;">
                                </div>
                            </div>
                            <!-- /.widget-user -->

                            <!-- About Me Box -->
                            <div class="box about-me">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo get_string('aboutme', 'theme_remui'); ?></h3>
                                </div><!-- /.box-header -->
                                <div class="box-body about-me-body">
                                    <?php
                                    if ($CFG->version / 1000000 < 2016) {
                                        $interests = tag_get_tags('user', $otheruser->id);
                                    } else {
                                        $interests = core_tag_tag::get_item_tags('core', 'user', $otheruser->id);
                                    }
                                    if ($interests) {
                                        ?>
                                        <div id="user-interests" class="user-about-me">
                                            <hr>
                                            <strong><i class="fa fa-pencil margin-r-5"></i> <?php echo get_string('interests'); ?></strong>
                                            <?php
                                            if ($CFG->version / 1000000 < 2016) {
                                                $interests = tag_get_tags('user', $otheruser->id);
                                            } else {
                                                $interests = core_tag_tag::get_item_tags('core', 'user', $otheruser->id);
                                            }
                                            ?>
                                            <?php
                                            // Used for setting label class for different colors
                                            $colorarray = array(
                                                0 => 'danger',
                                                1 => 'success',
                                                2 => 'info',
                                                3 => 'warning',
                                                4 => 'primary'
                                            );
                                            ?>
                                            <p>
                                                <?php
                                                foreach ($interests as $interest) {
                                                    ?>
                                                    <a href= "<?php echo new moodle_url('/tag/index.php?tag=' . $interest->rawname); ?>">
                                                        <span class="label label-<?php echo $colorarray[$interest->ordering % 5]; ?>"><?php echo $interest->rawname; ?></span></a>
                                                    <?php
                                                }
                                                ?>
                                            </p>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if ($otheruser->institution) {
                                        ?>
                                        <div id="user-institution" class="user-about-me">
                                            <hr>
                                            <strong><i class="fa fa-book margin-r-5"></i> <?php echo get_string('institution'); ?></strong>
                                            <p class="text-muted">
                                                <?php echo $otheruser->institution; ?>
                                            </p>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    if ($otheruser->city) {
                                        ?>
                                        <div id="user-location" class="user-about-me">
                                            <hr>
                                            <strong><i class="fa fa-map-marker margin-r-5"></i> <?php echo get_string('location'); ?></strong>
                                            <p class="text-muted">
                                                <?php
                                                echo $otheruser->city;
                                                if ($otheruser->country) {
                                                    echo ", " . get_string($otheruser->country, 'countries');
                                                }
                                                ?>
                                            </p>
                                        </div>
                                        <?php
                                    } else if ($otheruser->country) {
                                        ?>
                                        <div id="user-location" class="user-about-me">
                                            <hr>
                                            <strong><i class="fa fa-map-marker margin-r-5"></i> <?php echo get_string('location'); ?></strong>
                                            <p class="text-muted">
                                                <?php echo get_string($otheruser->country, 'countries'); ?>
                                            </p>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                    <?php
                                    $userdescription = \theme_remui\controller\theme_controller::get_user_description($otheruser);
                                    if ($userdescription != '') {
                                        ?>
                                        <div id="user-description" class="user-about-me">
                                            <hr>
                                            <strong><i class="fa fa-file-text-o margin-r-5"></i> <?php echo get_string('description'); ?></strong>
                                            <p class="text-muted"><?php echo $userdescription; ?></p>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div><!-- /.box-body -->
                            </div><!-- /.box -->
                        </div><!-- /.col -->
                        <div class="col-md-9">
                            <?php
                            $activeCourses = 'active';
                            $activeProfile = '';
                            $displayRequierdFields = 'none';
                            if (user_not_fully_set_up($otheruser)) {
                                $activeCourses = '';
                                $activeProfile = 'active';
                                $displayRequierdFields = '';
                            } else if ($redirect === 0) {
                                $activeCourses = '';
                                $activeProfile = 'active';
                            }
                            ?>
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">
                                    <!-- <li ><a href="#activity" data-toggle="tab">Activity</a></li> -->
                                    <?php if (is_siteadmin() && $otheruser->id != $USER->id) { ?>
                                        <li class="<?php echo $activeCourses; ?>"><a href="#common_courses" data-toggle="tab"><?php echo get_string('user') . ' ' . get_string('courses'); ?></a></li>
                                    <?php } else if ($otheruser->id != $USER->id) { ?>
                                        <li class="<?php echo $activeCourses; ?>"><a href="#common_courses" data-toggle="tab"><?php echo get_string('courses'); ?></a></li>
                                    <?php } else { ?>
                                        <li class="<?php echo $activeCourses; ?>"><a href="#courses" data-toggle="tab"><?php echo get_string('courses'); ?></a></li>
                                    <?php } ?>
                                    <li><a href="#badges" data-toggle="tab"><?php echo get_string('badges'); ?></a></li>
                                    <?php if (is_siteadmin() || $otheruser->id == $USER->id) { ?>
                                        <li class="<?php echo $activeProfile; ?>"><a href="#editprofile" data-toggle="tab"><?php echo get_string('editmyprofile'); ?></a></li>
                                    <?php } ?>
                                    <?php if (is_siteadmin() || $otheruser->id == $USER->id) { ?>
                                        <li><a href="#editavatar" data-toggle="tab"><?php echo get_string('editavatar', 'theme_remui'); ?></a></li>
                                    <?php } ?>
                                    <?php if (is_siteadmin()) { ?>
                                        <li class = "pull-right"><a href="#advancedusersettings" data-toggle="tab"><i class="fa fa-gear fa-lg"></i></a></li>
                                    <?php } ?>
                                </ul>
                                <div class="tab-content">
                                    <?php
                                    if (is_siteadmin() || $USER->id === $otheruser->id) {
                                        // Disable fields that are locked by auth plugins.
                                        $fields = get_user_fieldnames();
                                        $authplugin = get_auth_plugin($USER->auth);
                                        $customfields = $authplugin->get_custom_user_profile_fields();
                                        $fields = array_merge($fields, $customfields);
                                        $firstnamedisabled = '';
                                        $lastnamedisabled = '';
                                        $emaildisabled = '';
                                        $citydisabled = '';
                                        $countrydisabled = '';
                                        $descriptiondisabled = '';

                                        foreach ($fields as $field) {
                                            $configvariable = 'field_lock_' . $field;
                                            if (isset($authplugin->config->{$configvariable})) {
                                                if ($authplugin->config->{$configvariable} === 'locked') {
                                                    if ($configvariable == 'field_lock_firstname') {
                                                        $firstnamedisabled = 'disabled';
                                                    }
                                                    if ($configvariable == 'field_lock_lastname') {
                                                        $lastnamedisabled = 'disabled';
                                                    }
                                                    if ($configvariable == 'field_lock_email') {
                                                        $emaildisabled = 'disabled';
                                                    }
                                                    if ($configvariable == 'field_lock_city') {
                                                        $citydisabled = 'disabled';
                                                    }
                                                    if ($configvariable == 'field_lock_country') {
                                                        $countrydisabled = 'disabled';
                                                    }
                                                    if ($configvariable == 'field_lock_description') {
                                                        $descriptiondisabled = 'disabled';
                                                    }
                                                } else if ($authplugin->config->{$configvariable} === 'unlockedifempty') {
                                                    echo "unlocked";
                                                }
                                            }
                                        }
                                        ?>
                                        <div class="<?php echo $activeProfile; ?> tab-pane" id="editprofile">
                                            <div class="alert alert-danger" style="display:<?php echo $displayRequierdFields; ?>;" id="required-fields">
                                                Моля попълнете всички задължителни полета, за да продължите!                                                
                                            </div>
                                            <script src="http://code.jquery.com/jquery-3.2.1.slim.min.js"
                                                    integrity="sha256-k2WSCIexGzOj3Euiig+TlR8gA0EmPjuc79OEeY5L45g="
                                                    crossorigin="anonymous">
                                            </script>
                                            <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.min.js" type="text/javascript"></script>
                                            <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js" type="text/javascript"></script>
                                            <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="Stylesheet" type="text/css" />
                                            <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
                                            <script>
                                                $(window).bind("load",function(){
                                                    if (!Modernizr.inputtypes.date) {
                                                        console.log("NO DATE");
                                                        $('#inputBirthdate').datepicker({
                                                            dateFormat: 'yy-mm-dd'
                                                        });
                                                    }
                                                    
                                                    if(location.href.indexOf('showprofile=1') !== -1) {
                                                        setTimeout(function(){
                                                            $('.nav-tabs').find("[href='#editprofile']")[0].click()},
                                                            100);
                                                }});
                                            </script>
                                            <form class="form-horizontal">
                                                <input type="hidden" value="<?php echo $otheruser->id; ?>" id="inputId">
                                                <div class="form-group">
                                                    <label for="inputfName" class="col-sm-2 control-label"><?php echo get_string('firstname'); ?><span class="text-red">*</span></label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" <?php echo $firstnamedisabled; ?> id="inputfName"
                                                               placeholder="<?php echo get_string('firstname'); ?>"
                                                               value="<?php echo $otheruser->firstname; ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputlName" class="col-sm-2 control-label"><?php echo get_string('lastname'); ?><span class="text-red">*</span></label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" <?php echo $lastnamedisabled; ?> id="inputlName" placeholder="<?php echo get_string('lastname'); ?>" value="<?php echo $otheruser->lastname; ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputEmail" class="col-sm-2 control-label"><?php echo get_string('email'); ?><span class="text-red">*</span></label>
                                                    <div class="col-sm-10">
                                                        <input type="email" readonly="true" class="form-control" <?php echo $emaildisabled; ?> id="inputEmail" placeholder="<?php echo get_string('email'); ?>" value="<?php echo $otheruser->email; ?>" style="max-width:300px">
                                                    </div>
                                                </div>
                                                <!--                      <div class="form-group">
                                                                        <label for="inputCity" class="col-sm-2 control-label"><?php echo get_string('citytown', 'theme_remui'); ?></label>
                                                                        <div class="col-sm-10">
                                                                          <input type="text" class="form-control" <?php echo $citydisabled; ?> id="inputCity" placeholder="City/Town" value="<?php echo $otheruser->city; ?>">
                                                                        </div>
                                                                      </div>-->
                                                <div class="form-group">
                                                    <label for="inputCountry" class="col-sm-2 control-label"><?php echo get_string('country'); ?></label>
                                                    <div class="col-sm-10">
                                                        <?php
                                                        $choices = get_string_manager()->get_list_of_countries();
                                                        ?>
                                                        <select class="form-control" <?php echo $countrydisabled; ?> id="select-country" disabled>
                                                            <option><?php echo get_string('selectcountry', 'theme_remui'); ?></option>
                                                            <?php
                                                            foreach ($choices as $key => $choice) {
                                                                if ($key === $otheruser->country || (!$otheruser->country && $key === "BG")) {
                                                                    echo "<option selected value=" . $key . ">" . $choice . "</option>";
                                                                } else {
                                                                    echo "<option value=" . $key . ">" . $choice . "</option>";
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputRegion" class="col-sm-2 control-label"><?php echo get_string('region', 'theme_remui'); ?></label>
                                                    <div class="col-sm-10">
                                                        <?php
                                                        $sql = "SELECT * FROM Regions";
                                                        $regionsDb = $DB->get_recordset_sql($sql, NULL);
                                                        ?>
                                                        <select class="form-control" id="select-region">
                                                            <option><?php echo get_string('selectregion', 'theme_remui'); ?></option>
                                                            <?php
                                                            
                                                            foreach ($regionsDb as $regionDb) {
                                                                if ($otheruserData && $regionDb->id === $otheruserData->regionid) {
                                                                    echo "<option selected value=" . $regionDb->id . ">" . $regionDb->name . "</option>";
                                                                } else {
                                                                    echo "<option value=" . $regionDb->id . ">" . $regionDb->name . "</option>";
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputMunicipality" class="col-sm-2 control-label"><?php echo get_string('municipality', 'theme_remui'); ?></label>
                                                    <div class="col-sm-10">
                                                        <?php
                                                        $municipalityDb = array();
                                                        if ($otheruserData && $otheruserData->regionid > 0) {
                                                            $sql = "SELECT * FROM Municipalities WHERE regionId = " . $otheruserData->regionid;
                                                            $municipalityDb = $DB->get_recordset_sql($sql, NULL);
                                                        }
                                                        ?>
                                                        <select class="form-control" id="select-municipality">
                                                            <option><?php echo get_string('selectmunicipality', 'theme_remui'); ?></option>
                                                            <?php
                                                            foreach ($municipalityDb as $munDb) {
                                                                if ($otheruserData && $munDb->id === $otheruserData->municipalityid) {
                                                                    echo "<option selected value=" . $munDb->id . ">" . $munDb->name . "</option>";
                                                                } else {
                                                                    echo "<option value=" . $munDb->id . ">" . $munDb->name . "</option>";
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputCity" class="col-sm-2 control-label"><?php echo get_string('city'); ?></label>
                                                    <div class="col-sm-10">
                                                        <?php
                                                        $citiesDb = array();
                                                        if ($otheruserData && $otheruserData->municipalityid > 0) {

                                                            $sql = "SELECT * FROM Cities WHERE municipalityId = " . $otheruserData->municipalityid;
							    $sql .= " AND ID IN (SELECT DISTINCT cityId FROM schools)";
                                                            $citiesDb = $DB->get_recordset_sql($sql, NULL);
                                                        }
                                                        ?>
                                                        <select class="form-control" id="select-city">
                                                            <option><?php echo get_string('selectcity', 'theme_remui'); ?></option>
                                                            <?php
                                                            foreach ($citiesDb as $cityDb) {
                                                                if ($otheruserData && $cityDb->id === $otheruserData->cityid) {
                                                                    echo "<option selected value=" . $cityDb->id . ">" . $cityDb->name . "</option>";
                                                                } else {
                                                                    echo "<option value=" . $cityDb->id . ">" . $cityDb->name . "</option>";
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputSchool" class="col-sm-2 control-label"><?php echo get_string('school', 'theme_remui'); ?></label>
                                                    <div class="col-sm-10">
                                                        <?php
                                                        $schoolsDb = array();
                                                        if ($otheruserData && $otheruserData->cityid > 0) {

                                                            $sql = "SELECT * FROM schools WHERE cityId = " . $otheruserData->cityid;
                                                            $schoolsDb = $DB->get_recordset_sql($sql, NULL);
                                                        }
                                                        ?>
                                                        <select class="form-control" id="select-school">
                                                            <option><?php echo get_string('selectschool', 'theme_remui'); ?></option>
                                                            <?php
                                                            foreach ($schoolsDb as $schoolDb) {
                                                                if ($otheruserData && $schoolDb->id === $otheruserData->schoolid) {
                                                                    echo "<option selected value=" . $schoolDb->id . ">" . $schoolDb->name . "</option>";
                                                                } else {
                                                                    echo "<option value=" . $schoolDb->id . ">" . $schoolDb->name . "</option>";
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                        <div class="text-warning">
                                                            Ако вашето училище не е намерено, моля, пишете на <a href="mailto:info@academico.bg">info@academico.bg</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputClass" class="col-sm-2 control-label"><?php echo get_string('class', 'theme_remui'); ?></label>
                                                    <div class="col-sm-10">
                                                        <?php
                                                        $classField = $DB->get_record('user_info_field', array('shortname' => 'studentclass'));
                                                        $classes = explode(PHP_EOL, $classField->param1);
                                                        $userClass = '';
                                                        if ($otheruserFields) {
                                                            foreach ($otheruserFields as $field) {
                                                                if ($field->fieldid == $classField->id) {
                                                                    $userClass = $field->data;
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        <select class="form-control" id="select-class">                                                            
                                                            <?php
                                                            foreach ($classes as $class) {
                                                                if ($userClass && $class === $userClass) {
                                                                    echo "<option selected value=" . $class . ">" . $class . "</option>";
                                                                } else {
                                                                    echo "<option value=" . $class . ">" . $class . "</option>";
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputBirthdate" class="col-sm-2 control-label"><?php echo get_string('birthdate', 'theme_remui'); ?><span class="text-red">*</span></label>
                                                    <div class="col-sm-10">
                                                        <?php
                                                        $birthdateField = $DB->get_record('user_info_field', array('shortname' => 'studentbirthdate'));
                                                        $userBirthdateSec = '';
                                                        $userBirthdate = '';
                                                        if ($otheruserFields) {
                                                            foreach ($otheruserFields as $field) {
                                                                if ($field->fieldid == $birthdateField->id) {
                                                                    $userBirthdateSec = $field->data;
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                        if ($userBirthdateSec) {
                                                            $date = new DateTime('1970-01-02');
                                                            $date->modify($userBirthdateSec . ' sec');
                                                            $userBirthdate = $date->format('Y-m-d');
                                                        }
                                                        ?>
                                                        <input type="date" class="form-control" id="inputBirthdate" style="max-width:300px" value="<?php echo $userBirthdate; ?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputGander" class="col-sm-2 control-label"><?php echo get_string('gander', 'theme_remui'); ?></label>
                                                    <div class="col-sm-10">
                                                        <?php
                                                        $ganderField = $DB->get_record('user_info_field', array('shortname' => 'studentgender'));
                                                        $ganders = explode(PHP_EOL, $ganderField->param1);
                                                        $userGander = '';
                                                        if ($otheruserFields) {
                                                            foreach ($otheruserFields as $field) {
                                                                if ($field->fieldid == $ganderField->id) {
                                                                    $userGander = $field->data;
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                        ?>
                                                        <select class="form-control" id="select-gander">                                                            
                                                            <?php
                                                            foreach ($ganders as $gander) {
                                                                if ($userGander && $gander === $userGander) {
                                                                    echo "<option selected value=" . $gander . ">" . $gander . "</option>";
                                                                } else {
                                                                    echo "<option value=" . $gander . ">" . $gander . "</option>";
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div id="fitem_id_interests" class="fitem fitem_fautocomplete">
                                                        <label for="form_autocomplete_input"  class="col-sm-2 control-label"><?php echo get_string('interestslist'); ?></label>
                                                        <div class="felement fautocomplete col-sm-10">
                                                            <input type="hidden" name="interests" value="_qf__force_multiselect_submission">
    <!--                                                            <select multiple="multiple" name="interests[]" id="id_interests" aria-hidden="true" style="display: none;">
                                                            </select>-->
                                                            <div class="form-autocomplete-selection form-autocomplete-multiple" id="form_autocomplete_selection" role="list" aria-atomic="true" tabindex="0" aria-multiselectable="true">
                                                                <span class="accesshide">Selected items:</span>
                                                                <?php
                                                                if ($interests) {
                                                                    foreach ($interests as $interest) {
                                                                        ?>
                                                                        <span role="listitem" data-value="<?php echo $interest->rawname; ?>" aria-selected="true" class="label label-info">
                                                                            <span aria-hidden="true">× </span><?php echo $interest->rawname; ?>
                                                                        </span>
                                                                        <?php
                                                                    }
                                                                } else {
                                                                    ?>
                                                                    <span id="noselection"><?php echo get_string('noselection', 'theme_remui'); ?></span>
                                                                    <?php
                                                                }
                                                                ?>

                                                            </div>
                                                            <input type="text" id="form_autocomplete_input" placeholder="<?php echo get_string('entertags', 'theme_remui'); ?>" role="textbox" aria-owns="form_autocomplete_selection" style="width:100%">
                                                            <ul class="form-autocomplete-suggestions" id="form_autocomplete_suggestions" role="listbox" aria-hidden="true" style="display: none;">
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputDescription" class="col-sm-2 control-label"><?php echo get_string('userdescription'); ?></label>
                                                    <div class="col-sm-10">
                                                        <textarea class="form-control" <?php echo $descriptiondisabled; ?> id="inputDescription" placeholder="<?php echo get_string('userdescription'); ?>"><?php echo strip_tags($otheruser->description); ?></textarea>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label"></label>
                                                    <div class="col-sm-10">                                                       
                                                        <a href="<?php echo $CFG->wwwroot . "/login/change_password.php?id=" . $otheruser->id; ?>"> <?php echo get_string('changepassword'); ?></a>
                                                    </div>
                                                </div>    
                                                <div class="form-group">
                                                    <div class="col-sm-offset-2 col-sm-10">
                                                        <button type="button" id="btn-save-changes" class="btn btn-primary btn-flat"><?php echo get_string('savechanges'); ?></button>
                                                    </div>
                                                </div>
                                            </form>
                                            <div class="alert alert-danger" id="error-message" style="display:none">
                                            </div>
                                        </div><!-- /.tab-pane -->
                                        <div class="tab-pane" id="editavatar">
                                            <?php
                                            $draftitemid = 0;
                                            $editoroptions = array(
                                                'maxfiles' => 0,
                                                'maxbytes' => 0,
                                                'trusttext' => false,
                                                'forcehttps' => false,
                                                'context' => $coursecontext
                                            );
                                            $filemanagercontext = $editoroptions['context'];
                                            $filemanageroptions = array('maxbytes' => $CFG->maxbytes,
                                                'subdirs' => 0,
                                                'maxfiles' => 1,
                                                'accepted_types' => 'web_image');
                                            file_prepare_draft_area($draftitemid, $filemanagercontext->id, 'user', 'newicon', 0, $filemanageroptions);
                                            $otheruser->imagefile = $draftitemid;
                                            // Create form.
                                            $url = $CFG->wwwroot . "/user/profile.php?id=" . $userid;
                                            $userform = new user_avatar_form($url, array(
                                                'editoroptions' => $editoroptions,
                                                'filemanageroptions' => $filemanageroptions,
                                                'user' => $otheruser));

                                            if ($usernew = $userform->get_data()) {
                                                if (empty($USER->newadminuser)) {
                                                    useredit_update_picture($usernew, $userform, $filemanageroptions);
                                                }
                                            }
                                            $userform->display();
                                            ?>
                                        </div>
                                    <?php } ?>
                                    <div class="tab-pane" id="advancedusersettings">
                                        <?php
                                        echo $OUTPUT->course_content_header();
                                        echo $OUTPUT->main_content();
                                        echo $OUTPUT->course_content_footer();
                                        ?>
                                    </div><!-- /.tab-pane -->
                                    <?php if ($otheruser->id != $USER->id) { ?>
                                        <!-- /.common_courses-pane -->
                                        <div class="active tab-pane" id="common_courses">
                                            <div class="row">
                                                <?php
                                                if (is_siteadmin()) {
                                                    $courses = enrol_get_all_users_courses($otheruser->id, $onlyactive = false, $fields = null, $sort = 'visible DESC,startdate ASC');
                                                } else {
                                                    $courses = enrol_get_shared_courses($USER->id, $otheruser->id, $preloadcontexts = false, $checkexistsonly = false);
                                                    // Get the detailed of currently enrolled course for both(loggend in and viewing) user.
                                                }
                                                if (!empty($courses)) {
                                                    $countcourse = 0;
                                                    $coursearray = array();
                                                    foreach ($courses as $key => $coursevalue) {
                                                        // $date = $coursevalue->startdate;
                                                        array_push($coursearray, $coursevalue->id);
                                                    }
                                                    $courseinfo = \theme_remui\controller\theme_controller::courseinfo($coursearray, $otheruser->id);
                                                    foreach ($courseinfo as $course) {
                                                        $countcourse++;
                                                        $courselink = $CFG->wwwroot . "/course/view.php?id=" . $course->courseid;
                                                        $gradelink = new moodle_url('/grade/report/overview/index.php', array('id' => $course->courseid, 'userid' => $otheruser->id));

                                                        // $date = $coursevalue->startdate;
                                                        // if ($date > strtotime("now") && $date < strtotime("+1 week")) { // get the course for only one week.
                                                        ?>
                                                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                                            <div class="box box-plain box-body text-center user-profile-common-courses">
                                                                <div class="box-header ">
                                                                    <h4 class="box-title"><a href="<?php echo $courselink; ?>" ><?php echo $course->coursename; ?></a> </h4>
                                                                    <?php
                                                                    if (is_siteadmin()) {
                                                                        if (isset($course->progress)) {
                                                                            echo $course->progress->progresshtml;
                                                                        }

                                                                        if (!empty($course->str_long_grade) && trim($course->str_long_grade) != '-') {
                                                                            echo '<br /> ' . get_string('grade') . ' :' . $course->str_long_grade . '  <a href="' . $gradelink . '">&nbsp; <i class="fa fa-arrow-right" aria-hidden="true"></i></a>';
                                                                        }
                                                                        $sitenoteslink = new moodle_url('/notes/index.php', array('course' => $course->courseid, 'user' => $otheruser->id));
                                                                        ?>
                                                                        <div class="view-notes" align="center"> 
                                                                            <a href= "<?php echo $sitenoteslink ?>">
                                                                                <span class="label text-blue"><?php echo get_string('viewnotes', 'theme_remui'); ?></span></a>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                        if ($countcourse == 0) {
                                                            echo '<div class="clearfix visible-xs-block"></div>';
                                                        } else if ($countcourse == 1) {
                                                            echo '<div class="clearfix visible-sm-block"></div>';
                                                        } else if ($countcourse == 2) {
                                                            echo '<div class="clearfix visible-md-block"></div>';
                                                        } else if ($countcourse == 3) {
                                                            echo '<div class="clearfix visible-lg-block"></div>';
                                                            $countcourse = 0;
                                                        }
                                                    }
                                                } else {
                                                    if (!is_siteadmin()) {
                                                        echo "<div class='alert alert-default' ><center>" . get_string('nocommoncourses', 'theme_remui') . "</center></div>";
                                                    } else {
                                                        echo "<div class='alert alert-default' ><center>" . get_string('usernotenrolledanycourse', 'theme_remui', $userfullname) . "</center></div>";
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    <?php } else {
                                        ?>
                                        <div class="<?php echo $activeCourses; ?> tab-pane" id="courses">
                                            <div class="row">
                                                <?php
                                                $courses = enrol_get_all_users_courses($otheruser->id, $onlyactive = false, $fields = null, $sort = 'visible DESC,startdate ASC');
                                                // Get the detailed of currently enrolled course currently viewing user.
                                                if (!empty($courses)) {
                                                    $countcourse = 0;
                                                    $coursearray = array();
                                                    foreach ($courses as $key => $coursevalue) {
                                                        // $date = $coursevalue->startdate;
                                                        array_push($coursearray, $coursevalue->id);
                                                    }
                                                    $courseinfo = \theme_remui\controller\theme_controller::courseinfo($coursearray);
                                                    foreach ($courseinfo as $course) {
                                                        $countcourse++;
                                                        $courselink = $CFG->wwwroot . "/course/view.php?id=" . $course->courseid;
                                                        $gradelink = new moodle_url('/grade/report/overview/index.php', array('id' => $course->courseid, 'userid' => $otheruser->id));
                                                        ?>

                                                        <div id='course1'class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                                            <div class="box box-plain box-body text-center user-profile-courses">
                                                                <div class="box-header">
                                                                    <h4 class="box-title"><a href="<?php echo $courselink; ?>" ><?php echo $course->coursename; ?></a>
                                                                </div>
                                                                <?php
                                                                if (isset($course->progress)) {
                                                                    echo $course->progress->progresshtml;
                                                                }
                                                                if (!empty($course->str_long_grade) && trim($course->str_long_grade) != '-') {
                                                                    echo '<br />' . get_string('grade') . ' :' . $course->str_long_grade . '  <a href="' . $gradelink . '">&nbsp; <i class="fa fa-arrow-right" aria-hidden="true"></i></a>';
                                                                }
                                                                ?>
                                                                </h4>
                                                            </div>
                                                        </div>
                                                        <?php
                                                        if ($countcourse == 0) {
                                                            echo '<div class="clearfix visible-xs-block"></div>';
                                                        } else if ($countcourse == 1) {
                                                            echo '<div class="clearfix visible-sm-block"></div>';
                                                        } else if ($countcourse == 2) {
                                                            echo '<div class="clearfix visible-md-block"></div>';
                                                        } else if ($countcourse == 3) {
                                                            echo '<div class="clearfix visible-lg-block"></div>';
                                                            $countcourse = 0;
                                                        }
                                                    }
                                                } else {
                                                    echo "<p class='text-center'>" . get_string('notenrolledanycourse', 'theme_remui') . "</p>";
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <div class="tab-pane" id="badges">
                                        <div class="row">
                                            <?php
// Local badges.
                                            require_once($CFG->dirroot . '/badges/renderer.php');
                                            $badges = badges_get_user_badges($otheruser->id, 0, null, null, null, true);
                                            if ($badges) {
                                                $renderer = new core_badges_renderer($PAGE, '');
                                                $title = get_string('localbadgesp', 'badges', format_string($SITE->fullname));

                                                foreach ($badges as $badge) {
                                                    $context = ($badge->type == BADGE_TYPE_SITE) ? context_system::instance() : context_course::instance($badge->courseid);
                                                    $imageurl = moodle_url::make_pluginfile_url($context->id, 'badges', 'badgeimage', $badge->id, '/', 'f1', false);
                                                    $bname = $badge->name;
                                                    $badgelink = new moodle_url('/badges/badge.php?hash=' . $badge->uniquehash);
                                                    // echo "<img src='" . $imageurl . "'/>";
                                                    // echo $bname;
                                                    ?>
                                                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                                                        <div class="box box-plain box-body text-center user-profile-badges">
                                                            <div class="box-header nopadding">
                                                                <img class="img-rounded" src="<?php echo $imageurl; ?>" alt="Your Badge">
                                                            </div>
                                                            <a href="<?php echo $badgelink; ?>"><h4><?php echo $bname; ?></h4></a>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                            } else {
                                                if ($USER->id === $otheruser->id) {
                                                    echo "<center>" . get_string('nobadgesyetcurrent', 'theme_remui') . "</center>";
                                                } else {
                                                    echo "<center>" . get_string('nobadgesyetother', 'theme_remui') . "</center>";
                                                }
                                            }
                                            ?>

                                        </div>

                                    </div>
                                </div><!-- /.tab-content -->
                            </div><!-- /.nav-tabs-custom -->
                        </div><!-- /.col -->
                    </div><!-- /.row -->

                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->

            <?php
            // Include post sidebar
            if ($hasrightsideblocks || $PAGE->user_is_editing())
                require_once(\theme_remui\controller\theme_controller::get_partial_element('post-aside'));

            // Include footer
            require_once(\theme_remui\controller\theme_controller::get_partial_element('footer'));

            echo $OUTPUT->standard_end_of_body_html();
            $params = array();
            $this->page->requires->js_call_amd('theme_remui/profile', 'initialise', $params);
            $this->page->requires->strings_for_js(array('addtocontacts', 'removefromcontacts', 'block', 'removeblock',
                'actioncouldnotbeperformed', 'enterfirstname', 'enterlastname', 'enteremailid', 'enterproperemailid', 'enterbirthdate',
                'selectcountry', 'selectmunicipality', 'selectregion', 'selectcity', 'selectschool', 'detailssavedsuccessfully',
                'location', 'description', 'noselection'), 'theme_remui');
            ?>
        </div> <!-- ./wrapper -->
    </body>
</html>

<?php

function useredit_update_picture(stdClass $usernew, moodleform $userform, $filemanageroptions = array()) {
    global $CFG, $DB;
    require_once("$CFG->libdir/gdlib.php");

    $context = context_user::instance($usernew->id, MUST_EXIST);
    $user = $DB->get_record('user', array('id' => $usernew->id), 'id, picture', MUST_EXIST);

    $newpicture = $user->picture;
    // Get file_storage to process files.
    $fs = get_file_storage();
    if (!empty($usernew->deletepicture)) {
        // The user has chosen to delete the selected users picture.
        $fs->delete_area_files($context->id, 'user', 'icon'); // Drop all images in area.
        $newpicture = 0;
    } else {
        // Save newly uploaded file, this will avoid context mismatch for newly created users.
        file_save_draft_area_files($usernew->imagefile, $context->id, 'user', 'newicon', 0, $filemanageroptions);
        if (($iconfiles = $fs->get_area_files($context->id, 'user', 'newicon')) && count($iconfiles) == 2) {
            // Get file which was uploaded in draft area.
            foreach ($iconfiles as $file) {
                if (!$file->is_directory()) {
                    break;
                }
            }
            // Copy file to temporary location and the send it for processing icon.
            if ($iconfile = $file->copy_content_to_temp()) {
                // There is a new image that has been uploaded.
                // Process the new image and set the user to make use of it.
                // NOTE: Uploaded images always take over Gravatar.
                $newpicture = (int) process_new_icon($context, 'user', 'icon', 0, $iconfile);
                // Delete temporary file.
                @unlink($iconfile);
                // Remove uploaded file.
                $fs->delete_area_files($context->id, 'user', 'newicon');
            } else {
                // Something went wrong while creating temp file.
                // Remove uploaded file.
                $fs->delete_area_files($context->id, 'user', 'newicon');
                return false;
            }
        }
    }

    if ($newpicture != $user->picture) {
        $DB->set_field('user', 'picture', $newpicture, array('id' => $user->id));
        return true;
    } else {
        return false;
    }
}
?>