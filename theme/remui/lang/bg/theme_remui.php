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
 * Strings for component 'theme_remui', language 'en', branch 'MOODLE_3_STABLE'
 *
 * @package   theme_remui
 * @copyright Copyright (c) 2016 WisdmLabs. (http://www.wisdmlabs.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Edwiser RemUI';
$string['region-side-post'] = 'Дясно';
$string['region-side-pre'] = 'Ляво';
$string['fullscreen'] = 'Пълен екран';
$string['closefullscreen'] = 'Затвори пълния екран';
$string['licensesettings'] = 'Настройки на лиценза';
$string['edwiserremuilicenseactivation'] = 'Edwiser RemUI активиране на лиценз';
$string['overview'] = 'Преглед';
$string['choosereadme'] = '
<div class="about-remui-wrapper" align="center">
    <div class="about-remui" style="max-width: 800px;">
        <h1 class="text-center">Добре дошли в Edwiser RemUI</h1><br>
        <h4 class="text-muted">
        Edwiser RemUI е новата революция в Moodle потребителско преживяване. Той е подходящо проектиран да повдигне електронното обучение с персонализирани оформления, 
		опростена навигация, създаване на съдържание и възможност за персонализиране.<br><br>
        Сигурни сме, че ще се насладите на преоформения облик!
        </h4>
        <div class="text-center">
        <img src="' . $CFG->wwwroot . '/theme/remui/pix/screenshot.jpg" alt="Edwiser RemUI screen shot" style="max-width: 100%;"/>
        </div>
        <br><br>
        <div class="text-center">
            <div class="btn-group text-center" role="group" aria-label="...">
              <div class="btn-group" role="group">
                <a href="https://edwiser.org/remui/faq/" target="_blank" class="btn btn-primary">FAQ</a>&nbsp;
              </div>
              <div class="btn-group" role="group">
                <a href="https://edwiser.org/remui/documentation/" target="_blank" class="btn btn-primary">Documentation</a>&nbsp;
              </div>
              <div class="btn-group" role="group">
                <a href="https://edwiser.org/contact-us/" target="_blank" class="btn btn-primary">Support</a>
              </div>
            </div>
        </div>
        <br>
        <h1 class="text-center">Персонализирайте Вашата тема</h1>
        <h4 class="text-muted text-center">
            Разбираме, че не всяка LMS е една и съща. Ние ще работим с вас, за да разберем вашите нужди и да проектираме и разработим решение, което да отговори на вашите цели.
        </h4>
        <br><br>
        <div class="row wdm_generalbox">
            <div class="iconbox span3 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="iconcircle">
                    <i class="fa fa-cogs"></i>
                </div>
                <div class="iconbox-content">
                    <h4>Персонализиране на темата</h4>
                </div>
            </div>
            <div class="iconbox span3 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="iconcircle">
                    <i class="fa fa-edit"></i>
                </div>
                <div class="iconbox-content">
                    <h4>Разработване на функционалности</h4>
                </div>
            </div>
            <br>
            <div class="iconbox span3 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="iconcircle">
                    <i class="fa fa-link"></i>
                </div>
lkj                <div class="iconbox-content">
                    <h4>API Интеграция</h4>
                </div>
            </div>
            <div class="iconbox span3 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="iconcircle">
                    <i class="fa fa-life-ring"></i>
                </div>
                <div class="iconbox-content">
                    <h4>LMS Консултации</h4>
                </div>
            </div>
        </div>
        <div class="text-center">
            <a class="btn btn-primary btn-lg" target="_blank" href="https://edwiser.org/contact-us/">Свържете се с нас</a>&nbsp;&nbsp;
        </div>
    </div>
</div>
<br />';


$string['licensenotactive'] = '<strong>Внимание!</strong> Лицензът не е активиран , моля <strong>активирайте</strong> лиценза в RemUI настройките.';
$string['licensenotactiveadmin'] = '<strong>Внимание!</strong> Лицензът не е активиран , моля <strong>активирайте</strong> лиценза <a href="'.$CFG->wwwroot.'/theme/remui/remui_license.php" >тук</a>.';
$string['activatelicense'] = 'Активирайте лиценза';
$string['deactivatelicense'] = 'Деактивирайте лиценза';
$string['renewlicense'] = 'Подновете лиценза';
$string['active'] = 'Активен';
$string['notactive'] = 'Неактивен';
$string['expired'] = 'Изтекъл';
$string['licensekey'] = 'Лицензен ключ';
$string['licensestatus'] = 'Статус на лиценза';
$string['noresponsereceived'] = 'Няма отговор от сървъра. Моля опитайте отново.';
$string['licensekeydeactivated'] = 'Лицензният ключ е деактивиран.';
$string['siteinactive'] = 'Уебсайтът е неактивен (Натиснете Активиране на лиценз да активирате плъгина).';
$string['entervalidlicensekey'] = 'Моля въведете валиден лицензен ключ.';
$string['licensekeyisdisabled'] = 'Лицензният Ви ключ е Изключен.';
$string['licensekeyhasexpired'] = "Лицензният Ви ключ е Изтекъл. Моля, Подновете го.";
$string['licensekeyactivated'] = "Лицензният Ви ключ е активиран.";
$string['enterlicensekey'] = "Моля въведете лицензен ключ.";

// course
$string['nosummary'] = 'Не е добавено Резюме в тази секция на Курса.';
$string['defaultimg'] = 'Изображение по подразбиране 100 x 100.';
$string['choosecategory'] = 'Изберете категория';
$string['allcategory'] = 'Всички категории';
$string['viewcours'] = 'Преглед на курс';
$string['taught-by'] = 'Преподаван от';
$string['enroluser'] = 'Записване на потребител';
$string['graderreport'] = 'Отчет на оценяващ';
$string['activityeport'] = 'Отчет за дейност';
$string['editcourse'] = 'Редактиране на курс';

// dashboard element -> overview
$string['enabledashboardelements'] = 'Активирайте елементите на таблото';
$string['enabledashboardelementsdesc'] = 'Махнете отметката да изключите Edwiser RemUI персонализиран widget на таблото.';
$string['totaldiskusage'] = 'Общо използване на диска';
$string['activemembers'] = 'Активни членове';
$string['newmembers'] = 'Нови членове';
$string['coursesdiskusage'] = 'Използване на диска от курсове';
$string['activestudents'] = 'Активни студенти';

// Quick meesage
$string['quickmessage'] = 'Бързо съобщение';
$string['entermessage'] = 'Моля въведете някакво съобщение!';
$string['selectcontact'] = 'Моля изберете контакт!';
$string['selectacontact'] = 'Изберете контакт';
$string['sendmessage'] = 'Изпратете съобщение';
$string['yourcontactlisistempty'] = 'Списъкът Ви с контакти е празен!';
$string['viewallmessages'] = 'Преглед на всички съобщения';
$string['messagesent'] = 'Изпратено успешно!';
$string['messagenotsent'] = 'Съобщението не беше изпратено! Уверете се, че сте въвели правилна стойност.';
$string['messagenotsenterror'] = 'Съобщението не беше изпратено! Нещо се обърка.';
$string['sendingmessage'] = 'Съобщението се изпраща...';
$string['sendmoremessage'] = 'Изпратете още съобщения';

// General Seetings.
$string['generalsettings' ] = 'Общи настройки';
$string['navsettings'] = 'Настройки за навигация';
$string['homepagesettings'] = 'Настройки на началната страница';
$string['colorsettings'] = 'Настройки на цветовете';
$string['fontsettings' ] = 'Настройки на шрифтовете';
$string['slidersettings'] = 'Настройки на плъзгачите';
$string['configtitle'] = 'Edwiser RemUI';

// Font settings.
$string['fontselect'] = 'Селектор на тип шрифт';
$string['fontselectdesc'] = 'Изберете от типове Стандартни шрифтове или Google уеб шрифтове. Моял запишете, за да се покажат опциите за Вашия избор.';
$string['fonttypestandard'] = 'Стандартен шрифт';
$string['fonttypegoogle'] = 'Google уеб шрифт';
$string['fontnameheading'] = 'Шрифт на заглавието';
$string['fontnameheadingdesc'] = 'Въведете точното име на шрифта, който ще използвате за заглавия.';
$string['fontnamebody'] = 'Шрифт на текста';
$string['fontnamebodydesc'] = 'Въведете точното име на шрифта, който ще използвате за останалия текст.';

/* Dashboard Settings*/
$string['dashboardsetting'] = 'Настройки на таблото';
$string['themecolor'] = 'Цвят на темата';
$string['themecolordesc'] = 'Каква цветова схема да бъде темата Ви. Това ще промени много компоненти, за да постигне цвета, който желаете в целия Moodle уебсайт';
$string['themetextcolor'] = 'Цвят на текста';
$string['themetextcolordesc'] = 'Задайте цвета на текста.';
$string['layout'] = 'Изберете разпределение';
$string['layoutdesc'] = 'Активирайте разпределението от Фиксирано разпределение (заглавното меню ще бъде залепено на върха) или Разпределение по подразбиране.'; // Кутийно разпределение или
$string['defaultlayout'] = 'По подразбиране';
$string['fixedlayout'] = 'Фиксирано заглавно меню';
$string['defaultboxed'] = 'Кутии';
$string['layoutimage'] = 'Изображение за фон на кутийно разпределение';
$string['layoutimagedesc'] = 'Качете изображението за фон, което да бъде приложено към Кутийното разпределение.';
$string['rightsidebarslide'] = 'Изключване на дясната странична лента';
$string['rightsidebarslidedesc'] = 'Изключване на дясната странична лента по подразбиране.';
$string['leftsidebarslide'] = 'Изключване на лявата странична лента';
$string['leftsidebarslidedesc'] = 'Изключване на лявата странична лента по подразбиране.';
$string['rightsidebarskin'] = 'Изключване на схемата на дясната странична лента';
$string['rightsidebarskindesc'] = 'Променете схемата на дясната странична лента.';

/*color*/
$string['colorscheme'] = 'Изберете Цветова схема';
$string['colorschemedesc'] = 'Може да изберете цветова схема за Вашия уебсайт от следните - Синя, Черна, Лилава, Зелена, Жълта, Синя-светло, Черна-светло, Лилава-светло, Зелена-светло и Жълта-светло. <br /> <b>Светло</b> - дава светъл фон на лявата лента.';
$string['blue'] = 'Синя';
$string['white'] = 'Бяла';
$string['purple'] = 'Лилава';
$string['green'] = 'Зелена';
$string['red'] = 'Червена';
$string['yellow'] = 'Жълта';
$string['bluelight'] = 'Синя Светло';
$string['whitelight'] = 'Бяла Светло';
$string['purplelight'] = 'Лилава Светло';
$string['greenlight'] = 'Зелена Светло';
$string['redlight'] = 'Червена Светло';
$string['yellowlight'] = 'Жълта Светло';
$string['custom'] = 'Персонализирана';
$string['customlight'] = 'Персонализирана Светло';
$string['customskin_color'] = 'Цветова схема';
$string['customskin_color_desc'] = 'Може да изберете персонализиран цвят за вашата тема тук.';

/* Course setting*/
$string['courseperpage'] = 'Курсове на страница';
$string['courseperpagedesc'] = 'Брой курсове, които да се показват на страница в Архивирани курсове.';
$string['enableimgsinglecourse'] = 'Активирайте изображение на страница с единичен курс';
$string['enableimgsinglecoursedesc'] = 'Премахнете отметката, за да деактивирате форматирането на изображението на страница с единичен курс.';
$string['nocoursefound'] = 'Не е намерен Курс';

/*logo*/
$string['logo'] = 'Лого';
$string['logodesc'] = 'Може да добавите логото, което да се показва на заглавната лента. Бележка- Предпочитаната височина е 50 пиксела. Ако искате да персонализирате, може да направите това от персонализираната CSS кутия.';
$string['siteicon'] = 'Икона на уебсайта';
$string['siteicondesc'] = 'Нямате лого? Може да изберете такова от този <a href="http://fortawesome.github.io/Font-Awesome/cheatsheet/" target="_new">списък</a>. <br /> Въведете текста след "fa-".';
$string['logoorsitename'] = 'Изберете формат за логото на уебсайта';
$string['logoorsitenamedesc'] = 'Може да промените логото на заглавната лента на уебсайта според Вашия избор. <br />Възможните избори са: Лого - Само логото ще бъде показвано; <br /> Име на уебсайт - Само името на уебсайта ще бъде показвано; <br /> Икона+име на уебсайт - Икона заедно с името на уебсайта ще бъде показвано.';
$string['onlylogo'] = 'Само лого';
$string['onlysitename'] = 'Само име на уебсайт';
$string['iconsitename'] = 'Икона и име на уебсайт';

/*favicon*/
$string['favicon'] = 'Икона на уебсайта';
$string['favicondesc'] = 'Иконата за Вашия уебсайт. Тук може да вмъкнете иконата за Вашия уебсайт.';
$string['enablehomedesc'] = 'Активиране на начално описание';

/*custom css*/
$string['customcss'] = 'Персонализиран CSS';
$string['customcssdesc'] = 'Може да персонализирате CSS от текстовата кутия горе. Промените ще бъдат отразени на всички страници на уебсайта ви.';

/*google analytics*/
$string['googleanalytics'] = 'Идентификационен номер за проследяване на Google Analytics';
$string['googleanalyticsdesc'] = 'Моля въведете идентификационния номер на Google Analytics, за да бъде включен на уебсайта ви. Форматът на идентификационния номер трябва да е [UA-XXXXX-Y]';

/*theme_remUI_frontpage*/

$string['frontpageimagecontent'] = 'Съдържание на заглавната лента';
$string['frontpageimagecontentdesc'] = 'Тази секция се отнася за горната част на вашата начална страница.';
$string['frontpageimagecontentstyle'] = 'Стил';
$string['frontpageimagecontentstyledesc'] = 'Може да избирате между Статичен и Слайд.';
$string['staticcontent'] = 'Статичен';
$string['slidercontent'] = 'Слайд';
$string['addtext'] = 'Добавете текст';
$string['defaultaddtext'] = 'Образованието е изпитан от времето път към прогреса.';
$string['addtextdesc'] = 'Тук може да добавите текста, който да се показва на началната страница, за предпочитане в HTML.';
$string['uploadimage'] = 'Качете изображение';
$string['uploadimagedesc'] = 'Може да качите изображение като съдържание за слайд';
$string['video'] = 'iframe Вграден код';
$string['videodesc'] = 'Тук може да вмъкнете iframe Вграден код на видеото, което искате да бъде вградено.';
$string['contenttype'] = 'Изберете тип съдържание';
$string['contentdesc'] = 'Може да избирате между изображение или да дадете видео url.';
$string['image'] = 'Изображение';
$string['videourl'] = 'Видео URL';
$string['frontpageimge'] = '';

$string['slidercount'] = 'Брой слайдове';
$string['slidercountdesc'] = '';
$string['one'] = '1';
$string['two'] = '2';
$string['three'] = '3';
$string['four'] = '4';
$string['five'] = '5';
$string['eight'] = '8';
$string['twelve'] = '12';

$string['slideimage'] = 'Качете изображения за Слайдове';
$string['slideimagedesc'] = 'Може да качите изображение като съдържание за този слайд.';
$string['slidertext'] = 'Добавете текст за Слайда';
$string['defaultslidertext'] = '';
$string['slidertextdesc'] = 'Може да вмъкнете текстово съдържание за този слайд. За предпочитане в HTML.';
$string['sliderurl'] = 'Добавете линк към Слайд бутона';
$string['sliderbuttontext'] = 'Добавете Текстов бутон на слайда';
$string['sliderbuttontextdesc'] = 'Може да добавите текст към бутона на този слайд.';
$string['sliderurldesc'] = 'Може да вмъкнете линка към страницата, на която потребителя ще бъде пренасочен, когато натисне бутона.';
$string['slideinterval'] = 'Интервал на слайда';
$string['slideintervaldesc'] = 'Може да настроите времето за преход между слайдовете. Ако има само един слайд, тази настройка няма даима ефект.';
$string['sliderautoplay'] = 'Настройте Автоматично изпълнение на Слайд';
$string['sliderautoplaydesc'] = 'Изберете ‘Да’ ако искате автоматичен преход за вашия слайд.';
$string['true'] = 'Да';
$string['false'] = 'Не';

$string['frontpageblocks'] = 'Съдържание на основната част';
$string['frontpageblocksdesc'] = 'Може да въведете заглавие за основната част на уебсайта ви';

$string['enablesectionbutton'] = 'Активирайте бутони в Секциите';
$string['enablesectionbuttondesc'] = 'Активирайте бутоните в секциите на основната част.';
$string['enablefrontpagecourseimg'] = 'Активирайте Изображения в Курсове на началната страница';
$string['enablefrontpagecourseimgdesc'] = 'Активирайте изображения в наличната секция Курсове на началната страница';

/* General section descriptions */
$string['frontpageblockiconsectiondesc'] = 'Може да избирате икона от този <a href="http://fortawesome.github.io/Font-Awesome/cheatsheet/" target="_new">списък</a>. Въведете текста след "fa-". ';
$string['frontpageblockdescriptionsectiondesc'] = 'Кратко описание на заглавието.';
$string['defaultdescriptionsection'] = 'Холистично използвайте технологиите точно навреме чрез корпоративни сценарии.';
$string['sectionbuttontextdesc'] = 'Въведете текста за бутон в тази Секция.';
$string['sectionbuttonlinkdesc'] = 'Въведете URL линк за тази Секция.';
$string['frontpageblocksectiondesc'] = 'Добавете заглавие за тази Секция.';

/* block section 1 */
$string['frontpageblocksection1'] = 'Заглавие на основната част за 1ва Секция';
$string['frontpageblockdescriptionsection1'] = 'Описание на основната част за 1ва Секция';
$string['frontpageblockiconsection1'] = 'Шрифт-Страхотен икона за 1ва Секция';
$string['sectionbuttontext1'] = 'Текстов бутон за 1ва Секция';
$string['sectionbuttonlink1'] = 'URL линк за 1ва Секция';


/* block section 2 */
$string['frontpageblocksection2'] = 'Заглавие на основната част за 2ра Секция';
$string['frontpageblockdescriptionsection2'] = 'Описание на основната част за 2ра Секция';
$string['frontpageblockiconsection2'] = 'Шрифт-Страхотен икона за 2ра Секция';
$string['sectionbuttontext2'] = 'Текстов бутон за 2ра Секция';
$string['sectionbuttonlink2'] = 'URL линк за 2ра Секция';


/* block section 3 */
$string['frontpageblocksection3'] = 'Заглавие на основната част за 3та Секция';
$string['frontpageblockdescriptionsection3'] = 'Описание на основната част за 3та Секция';
$string['frontpageblockiconsection3'] = 'Шрифт-Страхотен икона за 3та Секция';
$string['sectionbuttontext3'] = 'Текстов бутон за 3та Секция';
$string['sectionbuttonlink3'] = 'URL линк за 3та Секция';


/* block section 4 */
$string['frontpageblocksection4'] = 'Заглавие на основната част за 4та Секция';
$string['frontpageblockdescriptionsection4'] = 'Описание на основната част за 4та Секция';
$string['frontpageblockiconsection4'] = 'Шрифт-Страхотен икона за 4та Секция';
$string['sectionbuttontext4'] = 'Текстов бутон за 4та Секция';
$string['sectionbuttonlink4'] = 'URL линк за 4та Секция';


// Frontpage Aboutus settings
$string['frontpageaboutus'] = 'Начална страница За нас';
$string['frontpageaboutusdesc'] = 'тази секция е за начална страница За нас';

$string['enablefrontpageaboutus'] = 'Активирайте За нас секция';
$string['enablefrontpageaboutusdesc'] = 'Активирайте За нас секцията на началната страница.';
$string['frontpageaboutusheading'] = 'За нас Заглавие';
$string['frontpageaboutusheadingdesc'] = 'Заглавие за заглавния текст по подразбиране за секция';
$string['frontpageaboutustext'] = 'За нас текст';
$string['frontpageaboutustextdesc'] = 'Въведете За нас текст за началната страница.';
$string['frontpageaboutusdefault'] = '<p class="lead">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
              Ut enim ad minim veniam.</p>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do
                  eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.Lorem ipsum dolor sit amet,
                  consectetur adipisicing elit, sed do eiusmod tempor incididunt ut
                  labore et dolore magna aliqua.Lorem ipsum dolor sit amet, consectetur
                  adipisicing elit, sed do eiusmod tempor incididunt
                  ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>';
$string['frontpageaboutusimage'] = 'Начална страница За нас Изображение';
$string['frontpageaboutusimagedesc'] = 'Качете изображение за начална страница За нас секция';

// Social media settings
$string['socialmedia'] = 'Настройки за социални медии';
$string['socialmediadesc'] = 'Въведете линковете за социалните медии за Вашия уебсайт.';
$string['facebooksetting'] = 'Настройки за Facebook';
$string['facebooksettingdesc'] = 'Въведете линк за Facebook страница за сайта ви. Напр. https://www.facebook.com/pagename';
$string['twittersetting'] = 'Настройки за Twitter';
$string['twittersettingdesc'] = 'Въведете линк за Twitter страница за сайта ви. Напр. https://www.twitter.com/pagename';
$string['linkedinsetting'] = 'Настройки за LinkedIn';
$string['linkedinsettingdesc'] = 'Въведете линк за LinkedIn страница за сайта ви. Напр. https://www.linkedin.com/in/pagename';
$string['gplussetting'] = 'Настройки за Google Plus';
$string['gplussettingdesc'] = 'Въведете линк за Google Plus страница за сайта ви. Напр. https://plus.google.com/pagename';
$string['youtubesetting'] = 'Настройки за YouTube';
$string['youtubesettingdesc'] = 'Въведете линк за YouTube страница за сайта ви. Напр. https://www.youtube.com/channel/UCU1u6QtAAPJrV0v0_c2EISA';
$string['instagramsetting'] = 'Настройки за Instagram';
$string['instagramsettingdesc'] = 'Въведете линк за Instagram страница за сайта ви. Напр. https://www.linkedin.com/company/name';
$string['pinterestsetting'] = 'Настройки за Pinterest';
$string['pinterestsettingdesc'] = 'Въведете линк за Pinterest страница за сайта ви. Напр. https://www.pinterest.com/name';

// Footer Section Settings
$string['footersetting'] = 'Настройки за футър';
// Footer  Column 1
$string['footercolumn1heading'] = 'Съдържание на футър за 1ва колона (ляво)';
$string['footercolumn1headingdesc'] = 'Тази секция се отнася за долната част (Колона 1) на Вашата начална страница.';

$string['footercolumn1title'] = 'Заглавие на 1ва колона на футър';
$string['footercolumn1titledesc'] = 'Добавете заглавие на тази колона.';
$string['footercolumn1customhtml'] = 'Персонализиран HTML';
$string['footercolumn1customhtmldesc'] = 'Можете да персонализирате HTML на тази колона чрез текстовата кутия горе.';


// Footer  Column 2
$string['footercolumn2heading'] = 'Съдържание на футър за 2ра колона (по средата)';
$string['footercolumn2headingdesc'] = 'Тази секция се отнася за долната част (Колона 2) на Вашата начална страница.';

$string['footercolumn2title'] = 'Заглавие на 2ра колона на футър';
$string['footercolumn2titledesc'] = 'Добавете заглавие на тази колона.';
$string['footercolumn2customhtml'] = 'Персонализиран HTML';
$string['footercolumn2customhtmldesc'] = 'Можете да персонализирате HTML на тази колона чрез текстовата кутия горе.';

// Footer  Column 3
$string['footercolumn3heading'] = 'Съдържание на футър за 3та колона (дясно)';
$string['footercolumn3headingdesc'] = 'Тази секция се отнася за долната част (Колона 3) на Вашата начална страница.';

$string['footercolumn3title'] = 'Заглавие на 3та колона на футър';
$string['footercolumn3titledesc'] = 'Добавете заглавие на тази колона.';
$string['footercolumn3customhtml'] = 'Персонализиран HTML';
$string['footercolumn3customhtmldesc'] = 'Можете да персонализирате HTML на тази колона чрез текстовата кутия горе.';

// Footer Bottom-Right Section
$string['footerbottomheading'] = 'Настройки на долен футър';
$string['footerbottomdesc'] = 'Тук може да посочите собствената си връзка, която искате да въведете в долната част на Футъра';
$string['footerbottomtext'] = 'Текст на футър долу-дясно';
$string['footerbottomtextdesc'] = 'Добавете текст за Настройки на долен футър.';
$string['footerbottomlink'] = 'Линк на футър долу-дясно';
$string['footerbottomlinkdesc'] = 'Въведете Линк за секцията долу-дясно на Футъра. Напр. http://www.yourcompany.com';
$string['poweredbyedwiser'] = 'Осъществено от Edwiser';
$string['poweredbyedwiserdesc'] = 'Премахнете отметката, за да премахнете  \'Осъществено от Edwiser\' от Вашия уебсайт.';

// Login settings page code begin.

$string['loginsettings'] = 'Настройки на Страницата за влизане';
$string['navlogin_popup'] = 'Активиране на изскачащия прозорец за влизане';
$string['navlogin_popupdesc'] = 'Активиране на изскачащия прозорец за влизане в заглавната лента.';
$string['loginsettingpic'] = 'Качване на изображение за фон';
$string['loginsettingpicdesc'] = 'Качете изображение като фон на формата за влизане.';
$string['signuptextcolor'] = 'Цвят на текста на панела за регистрация';
$string['signuptextcolordesc'] = 'Изберете цвета на текста за панела за регистрация.';
$string['left'] = "Лява страна";
$string['right'] = "Дясна страна";
$string['remember_me'] = "Запомни ме";
// Login settings Page code end.

// From theme snap
$string['title'] = 'Заглавие';
$string['contents'] = 'Съдържание';
$string['addanewsection'] = 'Създайте нова секция';
$string['createsection'] = 'Създаване на секция';

/* User Profile Page */

$string['blogentries'] = 'Записи в блога';
$string['discussions'] = 'Дискусии';
$string['discussionreplies'] = 'Отговори';
$string['aboutme'] = 'За мен';

$string['addtocontacts'] = 'Добавяне в Контакти';
$string['removefromcontacts'] = 'Премахване от Контакти';
$string['block'] = 'Блокирай';
$string['removeblock'] = 'Отблокирай';

$string['interests'] = 'Интереси';
$string['institution'] = 'Институция';
$string['location'] = 'Местоположение';
$string['description'] = 'Описание';

$string['commoncourses'] = 'Общи курсове';
$string['editprofile'] = 'Редактирай профила';
$string['editavatar'] = 'Аватар';

$string['firstname'] = 'Първо име';
$string['surname'] = 'Фамилия';
$string['email'] = 'Електронна поща';
$string['citytown'] = 'Град';
$string['selectcity'] = 'Избери град';
$string['country'] = 'Държава';
$string['selectcountry'] = 'Избери държава';
$string['region'] = 'Област';
$string['selectregion'] = 'Избери област';
$string['municipality'] = 'Община';
$string['selectmunicipality'] = 'Избери община';
$string['school'] = 'Училище';
$string['selectschool'] = 'Избери училище';
$string['class'] = 'Клас';
$string['birthdate'] = 'Дата на раждане';
$string['gander'] = 'Пол';
$string['description'] = 'Описание';
$string['entertags'] = 'Въведете таг...';
$string['noselection'] = 'Няма избрани';

$string['nocommoncourses'] = 'Не сте записан в общи курсове с този потребител.';
$string['notenrolledanycourse'] = 'Не сте записан за никакви курсове.';
$string['usernotenrolledanycourse'] = '{$a} не е записан за никакъв курс.';
$string['nobadgesyetcurrent'] = 'Все още нямате никакви значки.';
$string['nobadgesyetother'] = 'Този потребител няма никакви значки все още.';
$string['grade'] = "Клас";
$string['viewnotes'] = "Преглед на бележки";

// User profile page js

$string['actioncouldnotbeperformed'] = 'Това действие не може да бъде извършено!';
$string['enterfirstname'] = 'Моля въведете вашето Първо име.';
$string['enterlastname'] = 'Моля въведете вашата Фамилия.';
$string['enteremailid'] = 'Моля въведете вашата Електронна поща.';
$string['enterproperemailid'] = 'Моля въведете правилен адрес на електронна поща.';
$string['enterbirthdate'] = 'Моля въведете вашата дата на раждане.';
$string['detailssavedsuccessfully'] = 'Данните бяха запазени успешно!';

/* Header */

$string['startsignup'] = 'Регистрация';
$string['startedsince'] = 'Започнат от';
$string['startingin'] = 'Започва след';

$string['userimage'] = 'Изображение на потребител';

$string['seeallmessages'] = 'Вижте всички съобщения';
$string['viewallnotifications'] = 'Преглед на всички известия';
$string['viewallupcomingevents'] = 'Преглед на всички предстоящи събития';

$string['youhavemessages'] = 'Имате {$a} непрочетени съобщения';
$string['youhavenomessages'] = 'Нямате непрочетени съобщения';

$string['youhavenotifications'] = 'Имате {$a} известия';
$string['youhavenonotifications'] = 'Нямате нови известия';

$string['youhaveupcomingevents'] = 'Имате {$a} предстоящи събития';
$string['youhavenoupcomingevents'] = 'Нямате предстоящи събития';


/* Dashboard elements */

// Add notes
$string['addnotes'] = 'Добавете бележки';
$string['selectacourse'] = 'Изберете Курс';

$string['addsitenote'] = 'Добавете бележка за сайта';
$string['addcoursenote'] = 'Добавете бележка за курса';
$string['addpersonalnote'] = 'Добавете лична бележка';
$string['deadlines'] = 'Срокове';

// Add notes js
$string['selectastudent'] = 'Изберете студент';
$string['total'] = 'Общо';
$string['nousersenrolledincourse'] = 'Няма потребители записани в {$a} курс.';
$string['selectcoursetodisplayusers'] = 'Изберете курс, за да се покажат записаните потребители тук.';


// Deadlines
$string['gotocalendar'] = 'Отиди на Календар';
$string['noupcomingdeadlines'] = 'Няма предстоящи срокове!';
$string['in'] = 'След';
$string['since'] = 'От';

// Latest Members
$string['latestmembers'] = 'Последни членове';
$string['viewallusers'] = 'Преглед на всички потребители';

// Recently Active Forums
$string['recentlyactiveforums'] = 'Последно активни форуми';

// Recent Assignments
$string['assignmentstobegraded'] = 'Задания, които трябва да бъдат оценени';
$string['assignment'] = 'Задание';
$string['recentfeedback'] = 'Последни отзиви';

// Recent Events
$string['upcomingevents'] = 'Предстоящи събития';
$string['productimage'] = 'Изображение на продукт';
$string['noupcomingeventstoshow'] = 'Няма предстоящи събития, които да се показват!';
$string['viewallevents'] = 'Преглед на всички събития';
$string['addnewevent'] = 'Добавете ново събитие';

// Enrolled users stats
$string['enrolleduserstats'] = 'Статистики за записани потребители по категории курсове';
$string['problemwhileloadingdata'] = 'Съжаляваме, Възникна проблем при зареждането на данни.';
$string['nocoursecategoryfound'] = 'Не са открити категории на курсове в системата.';
$string['nousersincoursecategoryfound'] = 'Не са открити записани потребители в тази категория курсове.';

// Quiz stats
$string['quizstats'] = 'Статистика за опити за тест за Курсове';
$string['totalusersattemptedquiz'] = 'Общо потребители опитали теста';
$string['totalusersnotattemptedquiz'] = 'Общо потребители не опитали теста';

/* Theme Controller */

$string['years'] = 'година(и)';
$string['months'] = 'месец(и)';
$string['days'] = 'ден(и)';
$string['hours'] = 'час(ове)';
$string['mins'] = 'минута(и)';

$string['parametermustbeobjectorintegerorstring'] = 'Параметърът {$a} трябва да бъде обект или число или цифров низ';
$string['usernotenrolledoncoursewithcapability'] = 'Потребителят не е записан в курса с възможности';
$string['userdoesnothaverequiredcoursecapability'] = 'Потребителят няма необходимите възможности за курса';
$string['coursesetuptonotshowgradebook'] = 'Курсът е настроен да не показва класната книжка на студентите';
$string['coursegradeishiddenfromstudents'] = 'Оценката на курса е скрита от студентите';
$string['feedbackavailable'] = 'Налични отзиви';
$string['nograding'] = 'Не сте подали нищо за оценяване.';


/* Calendar page */
$string['selectcoursetoaddactivity'] = 'Изберете курс, за да добавите активност';
$string['addnewactivity'] = 'Добавете нова активност';

// Calendar page js
$string['redirectingtocourse'] = 'Пренасочване към {$a} страница на курс...';
$string['nopermissiontoaddactivityinanycourse'] = 'Съжаляваме, Нямате разрешение да добавяте активност в никакви курс.';
$string['nopermissiontoaddactivityincourse'] = 'Съжаляваме, Нямате разрешение да добавяте активност в {$a} курс.';
$string['selectyouroption'] = 'Изберете Вашата опция';


/* Blog Archive page */
$string['viewblog'] = 'Вижте пълния блог';


/* Course js */

$string['hidesection'] = 'Събери';
$string['showsection'] = 'Разшири';
$string['hidesections'] = 'Събери секциите';
$string['showsections'] = 'Разшири секциите';
$string['addsection'] = 'Добави секция';

$string['overdue'] = 'Просрочено';
$string['due'] = 'Дължимо';

/* Footer headings */
$string['quicklinks'] = 'Бързи връзки';
