require(['core/first'], function() {
    require(['jquery', 'theme_remui/app', 'theme_remui/bootstrap', 'theme_remui/owlcarousel', 'theme_remui/TimeCircles', 'core/log'],
        function($, AdminLTE, bootstrap, owlCarousel, TimeCircles, log) {
            log.debug('remUI JS initialised');
			function setEqualHeightMax(selector) {
                if (selector.length > 0) {
                    var arr = [];
                    var selector_height;
                    selector.css("min-height", "initial");
                    selector.each(function(index, elem) {
                        selector_height = elem.offsetHeight;
                        arr.push(selector_height);
                    });
                    selector_height = Math.max.apply(null, arr);
                    selector.css("min-height", selector_height);
                }
            }
            function setEqualHeightMin(selector) {
                if (selector.length > 0) {
                    var arr = [];
                    var selector_height;
                    selector.css("max-height", "initial");
                    selector.each(function(index, elem) {
                        selector_height = elem.offsetHeight;
                        arr.push(selector_height);
                    });
                    selector_height = Math.min.apply(null, arr);
                    selector.css("max-height", selector_height);
                }
            }

            $.fn.visibleHeight = function() {
                var elBottom, elTop, scrollBot, scrollTop, visibleBottom, visibleTop;
                scrollTop = $(window).scrollTop();
                scrollBot = scrollTop + $(window).height();
                elTop = this.offset().top;
                elBottom = elTop + this.outerHeight();
                visibleTop = elTop < scrollTop ? scrollTop : elTop;
                visibleBottom = elBottom > scrollBot ? scrollBot : elBottom;
                if((visibleBottom - visibleTop) > 0) {
                    return visibleBottom - visibleTop
                } else {
                    return 0;
                }
            }

            $(function() {
                // $("[data-controlsidebar]").on('click', function () {
                //     var pinned = 0;
                //     if ($(this).is(":checked"))
                //         pinned = 1;
                //     change_layout($(this).data('controlsidebar'));
                //     var slide = 1;
                //     if(AdminLTE.options.controlSidebarOptions.slide) {
                //         slide = 0;
                //     }
                //     AdminLTE.options.controlSidebarOptions.slide = slide;
                //     $(".rightsidebar-toggle").data('slide', slide);
                //     M.util.set_user_preference('postsidebar_pinned', pinned);
                //     if (!slide)
                //         $('.control-sidebar').removeClass('control-sidebar-open');
                //     else
                //         $('.control-sidebar').addClass('control-sidebar-open');
                // });

                $("[data-pincontrolsidebar]").on('click', function (e) {
                    e.preventDefault();
                    // toggle the class
                    $(this).toggleClass('pinned');
                    
                    var pinned = 0;
                    if($(this).hasClass('pinned')){
                        pinned = 1;
                    }

                    change_layout($(this).data('pincontrolsidebar'));
                    var slide = 1;
                    if(AdminLTE.options.controlSidebarOptions.slide) {
                        slide = 0;
                    }
                    AdminLTE.options.controlSidebarOptions.slide = slide;
                    $(".rightsidebar-toggle").data('slide', slide);
                    M.util.set_user_preference('postsidebar_pinned', pinned);
                    if (!slide)
                        $('.control-sidebar').removeClass('control-sidebar-open');
                    else
                        $('.control-sidebar').addClass('control-sidebar-open');
                });

                /**
                 * Toggles layout classes
                 *
                 * @param String cls the layout class to toggle
                 * @returns void
                 */
                function change_layout(cls) {
                    $("body").toggleClass(cls);
                    AdminLTE.layout.fixSidebar();
                    if ($('body').hasClass('fixed') && cls == 'fixed') {
                        AdminLTE.pushMenu.expandOnHover();
                        AdminLTE.controlSidebar._fixForFixed($('.control-sidebar'));
                        AdminLTE.layout.activate();
                    }
                }

                // initialize homepage carousel
                $('.wdm_Carousel').carousel();
                $('#frontpage-course-list').show();
                // initilize homepage course carousel
                $('#course-carousel').owlCarousel({
                    loop: true,
                    margin: 10,
                    navigation: true,
                    responsiveClass: true,
                    autoPlay: false,
                    responsive: {
                        0: {
                            items: 1,
                            nav: true
                        },
                        600: {
                            items: 2,
                            nav: false
                        },
                        1000: {
                            items: 4,
                            nav: true,
                            loop: false
                        }
                    },
                    responsiveBaseWidth: '.content-wrapper',
                    items: 4,
                    itemsDesktop: [1199, 4],
                    itemsDesktopSmall: [979, 3],
                    itemsTablet: [768, 2],
                    itemsMobile: [479, 1],
                    navigationText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"]
                });

                // set equal heights for all grid columns  in theme
                setEqualHeightMax($('.wdm_generalbox .iconbox .iconbox-content'));
                setEqualHeightMax($('.course-grid > div .box-body'));
                setEqualHeightMax($('#frontpage-course-list .fp-coursebox'));
                setEqualHeightMax($('#frontpage-course-list .card-content'));
                setEqualHeightMax($('.blog .recent-caption'));

                // quiz time circles for timed quizzes
                $("#quiztimer").TimeCircles({
                    time: {
                        Days: {
                            show: false
                        },
                        Hours: {
                            color: "#3c8dbc"
                        },
                        Minutes: {
                            color: "#00a65a"
                        },
                        Seconds: {
                            color: "#f56954"
                        }
                    },
                    bg_width: 0.9,
                    fg_width: 0.1,
                    circle_bg_color: "#797D82",
                    number_size: 0.24,
                    text_size: 0.11,
                    refresh_interval: 1,
                    animation_interval: "ticks"
                }).addListener(quizTimeEllapsed);

                // listner for quiz timer
                function quizTimeEllapsed(unit, value, total) {
                    if (total <= 0) {
                        $(this).fadeOut('medium').replaceWith('<div style="text-align: center; background: rgba(0, 0, 0, 0.13); border-radius: 5px; height: 80px; line-height: 80px; font-size: 18px; color: red;">' + M.util.get_string('timesup', 'quiz') + '</div>');
                    }
                }

                // For scrolling large tables for small screen
                setTimeout(function() {
                    $('.content table').each(function(ind, obj) {
                        if ($(this).width() > $('.content-wrapper > .content').width()) {
                            $(this).wrap("<div class='no-overflow table-wrap-remui'></div>");
                            $(this).parent('.table-wrap-remui').prepend(function() {
                                return "<span class='indicate-right'><i class='fa fa-arrow-right fa-lg' style='padding: 10px 1px;' aria-hidden='true'></i></span>";
                            });
                        }
                    });

                    $('body').on('click', '.indicate-right', function() {
                        var in_pr = $(this).parent('.table-wrap-remui');
                        $(in_pr).scrollLeft(1000);
                    });
                }, 2000);
                
                // back to top button
                var offset = 220;
                var duration = 500;

                var footer_height = $('#moodle-footer').height();
                var social_height = $('.social-section').height();
                var remui_backtop_height = $('.remui-back-to-top').height();

                function calculate_margin(ooter_height, social_height) {
                    var margin = Math.round(footer_height - social_height);
                    margin = margin + 2;
                    $('.remui-back-to-top').css('bottom', margin+'px');
                }

                // calculate margin from bottom
                calculate_margin(footer_height, social_height);

                $(window).resize(function(){
                    footer_height = $('#moodle-footer').height();
                    social_height = $('.social-section').height();
                    remui_backtop_height = $('.remui-back-to-top').height();

                    calculate_margin(footer_height, social_height);
                });

                $(window).scroll(function() {
                    if ($(this).scrollTop() > offset) {
                        $('.remui-back-to-top').fadeIn(duration);
                    } else {
                        $('.remui-back-to-top').fadeOut(duration);
                    }
                });

                $('.remui-back-to-top').click(function(event) {
                    event.preventDefault();
                    $('html, body').animate({
                        scrollTop: 0
                    }, duration);
                    return false;
                });

                // auto save theme settings on option change
                $('#id_s_theme_remui_colorscheme').change(function() {
                    this.form.submit();
                    window.onbeforeunload = null;
                });
                $('#id_s_theme_remui_frontpageimagecontent').change(function() {
                    this.form.submit();
                    window.onbeforeunload = null;
                });
                $('#id_s_theme_remui_contenttype').change(function() {
                    this.form.submit();
                    window.onbeforeunload = null;
                });
                $('#id_s_theme_remui_slidercount').change(function() {
                    this.form.submit();
                    window.onbeforeunload = null;
                });
                $('#id_s_theme_remui_logoorsitename').change(function() {
                    this.form.submit();
                    window.onbeforeunload = null;
                });
                $('#id_s_theme_remui_courseperpage').change(function() {
                    this.form.submit();
                    window.onbeforeunload = null;
                });
                $('#id_s_theme_remui_enableimgsinglecourse').change(function() {
                    this.form.submit();
                    window.onbeforeunload = null;
                });
                $('#id_s_theme_remui_enabledashboardelements').change(function() {
                    this.form.submit();
                    window.onbeforeunload = null;
                });
                $('#id_s_theme_remui_colorscheme').change(function() {
                    this.form.submit();
                    window.onbeforeunload = null;
                });
                $('#id_s_theme_remui_layout').change(function() {
                    this.form.submit();
                    window.onbeforeunload = null;
                });
                $('#id_s_theme_remui_rightsidebarslide').change(function() {
                    this.form.submit();
                    window.onbeforeunload = null;
                });
                $('#id_s_theme_remui_leftsidebarslide').change(function() {
                    this.form.submit();
                    window.onbeforeunload = null;
                });
                $('#id_s_theme_remui_rightsidebarskin').change(function() {
                    this.form.submit();
                    window.onbeforeunload = null;
                });
                $('#id_s_theme_remui_fontselect').change(function() {
                    this.form.submit();
                    window.onbeforeunload = null;
                });
                $('#id_s_theme_remui_enablesectionbutton').change(function() {
                    this.form.submit();
                    window.onbeforeunload = null;
                });
                $('#id_s_theme_remui_enablefrontpageaboutus').change(function() {
                    this.form.submit();
                    window.onbeforeunload = null;
                });
                $('#id_s_theme_remui_enablefrontpagecourseimg').change(function() {
                    this.form.submit();
                    window.onbeforeunload = null;
                });

                function reloadDdata() {
                    this.form.submit();
                    window.onbeforeunload = null;
                }
            });
        });
});