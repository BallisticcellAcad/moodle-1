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
     * Partial - Footer
     * This layout is baed on a moodle site index.php file but has been adapted to show news items in a different
     * way.
     *
     * @package   theme_remui
     * @copyright Copyright (c) 2016 WisdmLabs
     * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
     */
    
    // Social icons
    $facebooklink = \theme_remui\toolbox::get_setting('facebooksetting');
    $twitterlink = \theme_remui\toolbox::get_setting('twittersetting');
    $linkedinlink = \theme_remui\toolbox::get_setting('linkedinsetting');
    $gpluslink = \theme_remui\toolbox::get_setting('gplussetting');
    $youtubelink = \theme_remui\toolbox::get_setting('youtubesetting');
    $instagramlink = \theme_remui\toolbox::get_setting('instagramsetting');
    $pinterestlink = \theme_remui\toolbox::get_setting('pinterestsetting');

    $footercolumn1title = \theme_remui\toolbox::get_setting('footercolumn1title');
    $footercolumn1customhtml = \theme_remui\toolbox::get_setting('footercolumn1customhtml');

    $footercolumn2title = \theme_remui\toolbox::get_setting('footercolumn2title');
    $footercolumn2customhtml = \theme_remui\toolbox::get_setting('footercolumn2customhtml');

    $footercolumn3title = \theme_remui\toolbox::get_setting('footercolumn3title');
    $footercolumn3customhtml = \theme_remui\toolbox::get_setting('footercolumn3customhtml');

    // Footer Bottom-Right Section.
    $footerbottomtext = \theme_remui\toolbox::get_setting('footerbottomtext');
    $footerbottomlink = \theme_remui\toolbox::get_setting('footerbottomlink');
?>

<!-- Main Footer -->
<footer id="moodle-footer" class="main-footer no-border">

	<!-- Create Social Icon Div only if there is value in atleast one of the links -->
   <div class="social-section" id="yui_3_17_2_1_1467373186868_112">
        <ul class="social-icons no-margin" data-animate="tada" id="yui_3_17_2_1_1467373186868_111">
                    <?php if (!empty($facebooklink)) { ?>
                    <li><a href="<?php echo $facebooklink; ?>" class="facebook" alt="facebook"><i class="fa fa-facebook"></i></a></li>
                    <?php }
if (!empty($twitterlink)) { ?>
                <li><a href="<?php echo $twitterlink; ?>" class="twitter" alt="twitter"><i class="fa fa-twitter"></i></a></li>
    <?php                     }
if (!empty($linkedinlink)) {
    ?>
                <li><a href="<?php echo $linkedinlink; ?>" class="linkedin" alt="linkedin"><i class="fa fa-linkedin"></i></a></li>
    <?php                     }
if (!empty($gpluslink)) { ?>
                <li><a href="<?php echo $gpluslink; ?>" class="google-plus" alt="google-plus"><i class="fa fa-google-plus"></i></a></li>
    <?php                     }
if (!empty($youtubelink)) { ?>
                <li><a href="<?php echo $youtubelink; ?>" class="youtube" alt="youtube"><i class="fa fa-youtube"></i></a></li>
    <?php                     }
if (!empty($instagramlink)) { ?>
                <li><a href="<?php echo $instagramlink; ?>" class="instagram" alt="instagram"><i class="fa fa-instagram"></i></a></li>
    <?php                     }
if (!empty($pinterestlink)) { ?>
                <li><a href="<?php echo $pinterestlink; ?>" class="pinterest" alt="pinterest"><i class="fa fa-pinterest"></i></a></li>
    <?php                     } ?>
        </ul>
    </div>

    <!-- custom footer section -->
    <div class="row footer-columns">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 text-xs-center">
            <?php
				if ($footercolumn1title) {
				    echo "<h4>" . $footercolumn1title . "</h4>";
				}
	            //echo '<p>'.$footercolumn1customhtml.'</p>';
                echo $footercolumn1customhtml;
            ?>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 text-xs-center">
            <?php
				if ($footercolumn2title) {
				    echo "<h4>" . $footercolumn2title . "</h4>";
				}
	            echo $footercolumn2customhtml;
            ?>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 text-xs-center">
            <?php
				if ($footercolumn3title) {
				    echo "<h4>" . $footercolumn3title . "</h4>";
				}
	            echo $footercolumn3customhtml;
            ?>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 text-xs-center">
            <h4><?php echo get_string('quicklinks', 'theme_remui'); ?></h4>
            <?php
                echo $OUTPUT->page_doc_link();
                echo $OUTPUT->login_info();

                if (isloggedin()) {
				    echo $OUTPUT->course_footer();
				    echo $OUTPUT->standard_footer_html();
				}
            ?>
        </div>
    </div>
    <!-- end custom footer section -->

    <div class="row footer-last">
        <?php  if (\theme_remui\toolbox::get_setting('poweredbyedwiser') === "1") { ?>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-left text-xs-center">
                <a href="https://edwiser.org/remui/" rel="nofollow" target="_blank" >Powered by Edwiser RemUI</a>
            </div>
        <?php } ?>

        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right text-xs-center">
            <?php echo "<a href='" . $footerbottomlink . "'>" . $footerbottomtext . "</a>"; ?>
        </div>
    </div>
</footer>
<a href="#top" class="remui-back-to-top" ><i class="fa fa-angle-up fa-lg"></i></a>