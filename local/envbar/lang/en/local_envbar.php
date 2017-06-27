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
//
/**
 * Strings for component 'local_envbar', language 'en'.
 *
 * @package   local_envbar
 * @author    Grigory Baleevskiy (grigory@catalyst-au.net)
 * @copyright Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['addfields'] = 'Add another environment';
$string['pluginname'] = 'Environment bar';
$string['bgcolour'] = 'Background colour';
$string['cachedef_records'] = 'The environment bar database records';
$string['colourerror'] = 'Invalid HTML color code specified.';
$string['colourplaceholder'] = 'HTML color code. e.g. #fff OR #000000';
$string['configureinprod'] = 'Edit config';
$string['configurehere'] = 'Edit envbar';
$string['dividerselector'] = 'Divider selector';
$string['dividerselector_help'] = 'This is a css class that is used for the menu divider element. If you theme uses different html then you may need to adjust this selector.';
$string['extracss'] = 'Extra CSS';
$string['extracss_help'] = 'This css is add only when the bar is visible and pinned to the top of the page. Your theme may also have fixed headers which may clash so this extra css should resolve the clash and move your main theme header down. If your header is fixed only at certain breakpoints then the media query in this extra css should be identical to the one in your theme.';
$string['header_envbar'] = 'Environment bar';
$string['help'] = '<p><b>WARNING:</b> These settings should generally only be configured once in the production system.</p><p>If you are NOT in the production system, and these values are empty, then ideally go and fill them out in production and then refresh your database back to here.</p>';
$string['menuselector'] = 'Menu selector';
$string['menuselector_help'] = 'This is a css or xpath selector to find the menu ul element for injecting the env swapper menu. If you theme uses different html then you may need to adjust this selector.';
$string['nextrefreshin'] = ' - Reset in {$a}';
$string['notconfigured'] = 'UNKNOWN';
$string['prod'] = 'PROD';
$string['prodwwwroottext'] = 'Production wwwroot';
$string['prodwwwrootplaceholder'] = '$CFG->wwwroot on production';
$string['prodwwwrootautobutton'] = 'Autofill';
$string['refreshedago'] = ' - {$a} old';
$string['refreshednever'] = ' - Never been refreshed';
$string['setenabled'] = 'Enable';
$string['setdeleted'] = 'Delete';
$string['showtext'] = 'Text to show';
$string['showtextplaceholder'] = 'eg: You are on staging environment';
$string['textcolour'] = 'Foreground colour';
$string['urlmatch'] = 'URL Match pattern';
$string['urlmatch_help'] = 'You can use Regular Expressions to match your URL.<br />E.g. http://stage[1,2,3].example.com to match http://stage2.example.com<br />Note that following special characters will be escaped:<br /> / \ - . ? * ^ $';
$string['urlmatchplaceholder'] = 'eg. staging';

