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
 * Layout - Frontpage.
 *
 * @copyright Copyright (c) 2026 WisdmLabs
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/* Default globals */
global $CFG, $PAGE, $USER, $SITE, $COURSE;
$hasrightsideblocks = $PAGE->blocks->region_has_content('side-post', $OUTPUT);

// Slider variables
$slideinterval =  \theme_remui\toolbox::get_setting('slideinterval'); 
$sliderautoplay = \theme_remui\toolbox::get_setting('sliderautoplay');
if (!empty($sliderautoplay) && $sliderautoplay == '1') {
    $sliderautoplay = 'wdm_Carousel'; // Adding class for slider to autoplay
} else {
    $sliderautoplay = ''; // slide not to autoplay
}
$sliderdata = \theme_remui\controller\theme_controller::slider_data();


// butttons and their links for all the sections
$enablesectionbutton =  \theme_remui\toolbox::get_setting('enablesectionbutton');
if ($enablesectionbutton) {
    $sectionbuttontext1 = \theme_remui\toolbox::get_setting('sectionbuttontext1');
    $sectionbuttonlink1 =  \theme_remui\toolbox::get_setting('sectionbuttonlink1');

    $sectionbuttontext2 =  \theme_remui\toolbox::get_setting('sectionbuttontext2');
    $sectionbuttonlink2 =  \theme_remui\toolbox::get_setting('sectionbuttonlink2');

    $sectionbuttontext3 =  \theme_remui\toolbox::get_setting('sectionbuttontext3');
    $sectionbuttonlink3 =  \theme_remui\toolbox::get_setting('sectionbuttonlink3');

    $sectionbuttontext4 =  \theme_remui\toolbox::get_setting('sectionbuttontext4');
    $sectionbuttonlink4 =  \theme_remui\toolbox::get_setting('sectionbuttonlink4');
}

// Variable for body section 1
$frontpageblocksection1 =  \theme_remui\toolbox::get_setting('frontpageblocksection1');
$frontpageblockdescriptionsection1 = \theme_remui\toolbox::get_setting('frontpageblockdescriptionsection1');
$frontpageblockiconsection1 =  \theme_remui\toolbox::get_setting( 'frontpageblockiconsection1');
// Variable for body section 2
$frontpageblocksection2 =  \theme_remui\toolbox::get_setting('frontpageblocksection2');
$frontpageblockdescriptionsection2 =  \theme_remui\toolbox::get_setting('frontpageblockdescriptionsection2');
$frontpageblockiconsection2 =  \theme_remui\toolbox::get_setting('frontpageblockiconsection2');
// Variables for body section 3
$frontpageblocksection3 =  \theme_remui\toolbox::get_setting('frontpageblocksection3');
$frontpageblockdescriptionsection3 =  \theme_remui\toolbox::get_setting('frontpageblockdescriptionsection3');
$frontpageblockiconsection3 =  \theme_remui\toolbox::get_setting('frontpageblockiconsection3');
// Variables for body section 4
$frontpageblocksection4 =  \theme_remui\toolbox::get_setting('frontpageblocksection4');
$frontpageblockdescriptionsection4 =  \theme_remui\toolbox::get_setting('frontpageblockdescriptionsection4');
$frontpageblockiconsection4 =  \theme_remui\toolbox::get_setting('frontpageblockiconsection4');

// Front page about us variables.
$frontpageaboutusheading =  \theme_remui\toolbox::get_setting('frontpageaboutusheading');
$frontpageaboutusimage = \theme_remui\toolbox::setting_file_url('frontpageaboutusimage', 'frontpageaboutusimage');
$frontpageaboutustext =  \theme_remui\toolbox::get_setting('frontpageaboutustext');

// @param int $start how many blog should be skipped.
// If specified 0 no recent blog will be skipped.
// @param int $blogcount number of blog posts to be return.
$recentblogs = \theme_remui\controller\theme_controller::get_recent_blog(0, 4);

$PAGE->set_popup_notification_allowed(false);
echo $OUTPUT->doctype();
?>

<html <?php echo $OUTPUT->htmlattributes(); ?>>
<head>
  <title><?php echo $OUTPUT->page_title(); ?></title>
  <link rel="shortcut icon" href="<?php echo $OUTPUT->favicon() ?>"/>
  <meta http-equiv='X-UA-Compatible' content='IE=edge,chrome=1'>
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
	    <!-- Main content -->
	    <section class="content">

		    <div class="slider-wrapper">
		    <?php
				// Checking of sliderdata contains value or not
				if (!empty($sliderdata) && is_array($sliderdata)) {
				    if ($sliderdata['isslider'] == 2) {
				?>
				        <div id="wdm_Carousel" class="carousel slide  <?php echo $sliderautoplay; ?>"   data-interval="<?php echo $slideinterval; ?>">
				              <!-- Indicators -->
				              <ol class="carousel-indicators">
								<?php  foreach ($sliderdata['slides'] as $slides => $values) { ?>
								                         <li data-target="#wdm_Carousel" data-slide-to="<?php echo $values['img_count'];?>"></li>
								<?php } ?>
				              </ol>
				          <!-- Wrapper for slides -->
				            <div class="carousel-inner" role="listbox">
				                <?php foreach ($sliderdata['slides'] as $slides => $values) {?>
				                    <div class="item <?php if ($values["active"] == 2) { echo 'active';} ?>" > <!--active -->
				                        <div class="fill" style="background-image:url('<?php echo $values['img'];?>');"></div>
				                       	<div class="carousel-caption">
				                            <?php echo $values['img_txt'];?>
				                            <?php if (!empty($values['btn_link']) || !empty($values['btn_txt'])) {?>
				                            <p>
				                                <a class="slider-btn btn btn-lg btn-primary btn-flat btn-responsive" href="<?php echo $values['btn_link'] ?>" role="button">
				                                    <?php echo $values['btn_txt'] ?>
				                                </a>
				                            </p>
				                            <?php }?>
				                        </div>
				                    </div>
				                <?php } ?>
				          			<!-- Left and right controls -->
				                    <a class="left carousel-control" href="#wdm_Carousel" role="button" data-slide="prev">
				                      <span class="fa fa-chevron-left" aria-hidden="true"></span>
				                      <span class="sr-only">Previous</span>
				                    </a>
				                    <a class="right carousel-control" href="#wdm_Carousel" role="button" data-slide="next">
				                      <span class="fa fa-chevron-right" aria-hidden="true"></span>
				                      <span class="sr-only">Next</span>
				                    </a>
				            </div>
				        </div>
				<?php
				    } else {
				?>
				<?php if ($sliderdata['isvideo'] == 2) { ?>
				    <div class="row wdm_static_video">
				       <div class="col-xs-12">
				            <iframe width="100%" height="500" src="<?php echo $sliderdata['video']; ?>" frameborder="0" allowfullscreen></iframe>
				      </div>
				    </div>
				<?php } else {
				?>
				        <div class="wdm_static_image text-center" style="background-image: url(<?php echo $sliderdata['staticimage']; ?>); background-size: cover; background-position: center;">
				            <a href="<?php echo $sliderdata['addlink']; ?>">
					          	<h2 class="wdm_static_image_text">
						        	<span class="pad">
										<?php echo $sliderdata['addtxt']; ?>
						        	</span>
						        </h2>
					        </a>
				        </div>
				<?php }
				    }
				}// End of checking of sliderdata contains value or not
				?>
		  	</div>

		  	<!-- general sections -->
		    <div class="row wdm_generalbox">
				<?php if (!empty($frontpageblocksection1) && !empty($frontpageblockdescriptionsection1)) {
				?>
			      <div class="iconbox span3 col-lg-3 col-md-6 col-sm-6 col-xs-12">
					<?php if(!empty($frontpageblockiconsection1)) { ?>
						<div class="iconcircle">
						<i class="fa fa-<?php echo $frontpageblockiconsection1; ?>"></i>
						</div>
					<?php } ?>
			          
					<div class="iconbox-content text-center">
						<h4><?php echo $frontpageblocksection1; ?></h4>
						<div class="description"><?php echo $frontpageblockdescriptionsection1; ?></div>
					</div>

					<?php if (!empty($sectionbuttontext1) && !empty($sectionbuttonlink1)) { ?>
						<a class="btn btn-primary btn-flat" href="<?php echo $sectionbuttonlink1;?>" target="_blank"><?php echo $sectionbuttontext1; ?></a>
					<?php } ?>
			      </div>
				<?php } if (!empty($frontpageblocksection2) && !empty($frontpageblockdescriptionsection2)) {
				?>
			      <div class="iconbox span3 col-lg-3 col-md-6 col-sm-6 col-xs-12">
					<?php if(!empty($frontpageblockiconsection2)) { ?>
						<div class="iconcircle">
						<i class="fa fa-<?php echo $frontpageblockiconsection2; ?>"></i>
						</div>
					<?php } ?>
			          
					<div class="iconbox-content">
						<h4><?php echo $frontpageblocksection2; ?></h4>
						<div class="description"><?php echo $frontpageblockdescriptionsection2; ?></div>
					</div>

					<?php if (!empty($sectionbuttontext2) && !empty($sectionbuttonlink2)) { ?>
						<a class="btn btn-primary btn-flat" href="<?php echo $sectionbuttonlink2;?>" target="_blank"><?php echo $sectionbuttontext2; ?></a>
					<?php } ?>
			      </div>
				<?php }
				if (!empty($frontpageblocksection3) && !empty($frontpageblockdescriptionsection3)) {
				?>
			      <div class="iconbox span3 col-lg-3 col-md-6 col-sm-6 col-xs-12">
					
					<?php if(!empty($frontpageblockiconsection3)) { ?>
						<div class="iconcircle">
						<i class="fa fa-<?php echo $frontpageblockiconsection3; ?>"></i>
						</div>
					<?php } ?>

					<div class="iconbox-content">
						<h4><?php echo $frontpageblocksection3; ?></h4>
						<div class="description"><?php echo $frontpageblockdescriptionsection3; ?></div>
					</div>

					<?php if (!empty($sectionbuttontext3) && !empty($sectionbuttonlink3)) { ?>
						<a class="btn btn-primary btn-flat" href="<?php echo $sectionbuttonlink3;?>" target="_blank"><?php echo $sectionbuttontext3; ?></a>
					<?php } ?>
			      </div>
				<?php }
				if (!empty($frontpageblockiconsection4) && !empty($frontpageblocksection4) && !empty($frontpageblockdescriptionsection4)) {
				?>
			      <div class="iconbox span3 col-lg-3 col-md-6 col-sm-6 col-xs-12">
					<?php if(!empty($frontpageblockiconsection4)) { ?>
						<div class="iconcircle">
						<i class="fa fa-<?php echo $frontpageblockiconsection4; ?>"></i>
						</div>
					<?php } ?>

					<div class="iconbox-content">
						<h4><?php echo $frontpageblocksection4; ?></h4>
						<div class="description"><?php echo $frontpageblockdescriptionsection4; ?></div>
					</div>

					<?php if (!empty($sectionbuttontext4) && !empty($sectionbuttonlink4)) { ?>
						<a class="btn btn-primary btn-flat" href="<?php echo $sectionbuttonlink4;?>" target="_blank"><?php echo $sectionbuttontext4; ?></a>
					<?php } ?>
			      </div>
				<?php } ?>
		    </div> <!-- general sections end -->

		    <div id="region-main" class="default-section">
		        <?php echo $OUTPUT->main_content(); ?>
		    </div>

	    	<!-- about us section -->
		    <?php if ( \theme_remui\toolbox::get_setting('enablefrontpageaboutus') === "1" && !empty($frontpageaboutusheading) && !empty($frontpageaboutustext)) { ?>
		        <div class="row about-us text-center pad-20">
			        <?php if ($frontpageaboutusimage == null) { ?>
			          	<div class="col-md-12 about-us-text">
			            	<h2 class="section-heading no-margin"><?php echo $frontpageaboutusheading; ?></h2>
			            	<div class="margin-t-5 text-muted"><?php echo  $frontpageaboutustext; ?></div>
			          	</div>
			        <?php } else { ?>
			        	<div class="col-md-7 about-us-text">
				            <h2 class="section-heading no-margin"><?php echo $frontpageaboutusheading; ?></h2>
				            <div class="margin-t-5 text-muted"><?php echo  $frontpageaboutustext; ?></div>
				        </div>
				        <div class="col-md-4 col-md-offset-1 about-us-img">
				            <img src='<?php echo $frontpageaboutusimage; ?>' class='img-responsive margin-auto' alt='About US'>
				        </div>
			        <?php } ?>
		        </div>
		    <?php } ?>

		    <!-- frontpage recent blog -->
		    <?php if (isloggedin() && !empty($CFG->enableblogs) && is_array($recentblogs) && !empty($recentblogs)) { ?>
		      <div class="row blog">
		        <!-- Carousel -->
		        <h2 class="text-center">Recent blogs</h2> <br />
		            <?php foreach ($recentblogs as $key => $recentblog) {
		                $link = $CFG->wwwroot.'/blog/index.php?entryid='.$recentblog['id'];
		            ?>
		            <div class="col-md-3 col-sm-4 recent-item">
		                    <div class ="wdm-recent-item-blog text-center"style="background-image: url(<?php echo $recentblog['imagesrc']; ?>);
		                        background-size: cover;
		                        background-position: center; height:200px">
		                        <div class="wdm-recent-item-blog-info">
		                              <h3><a href="<?php echo $link; ?>" ><?php echo get_string('viewblog', 'theme_remui'); ?></a></h3>
		                        </div>
		                    </div>
		                <div class="recent-caption margin-auto pad-20">
		                    <h4><a href="<?php echo $link; ?>"><?php echo $recentblog['subject']; ?></a></h4>
		                    <p class="text-muted"><?php echo $recentblog['summary'];?></p>
		                </div>
		            </div>
		        <?php } ?>
		      </div>
		    <?php } ?>
	  	</section><!-- /.content -->
	</div><!-- /.content-wrapper -->

<?php
	// Include post sidebar
	if($hasrightsideblocks || $PAGE->user_is_editing())
    	require_once(\theme_remui\controller\theme_controller::get_partial_element('post-aside'));
	
	// Include footer
	require_once(\theme_remui\controller\theme_controller::get_partial_element('footer'));

	echo $OUTPUT->standard_end_of_body_html();
?>

</div> <!-- end main page wrapper -->
</body>
</html>