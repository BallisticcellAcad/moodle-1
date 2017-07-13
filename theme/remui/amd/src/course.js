 define(['jquery'], function($) {
     return {
         initialise: function(Params) {

             //$('[data-toggle=tooltip]').tooltip();
             
             $('.material-button-toggle').click(function() {
                 
                 var element = $(this).parent().siblings('.course-content ul li.section ul.section');

                 if(!$(element).hasClass('open')){
                    // show content
                    $(element).addClass('open');
                    
                    // change button state
                    $(this).find('span').text(M.util.get_string('hidesection', 'theme_remui'));
                    $(this).find('i').removeClass('fa-angle-down');
                    $(this).find('i').addClass('fa-angle-up');
                 } else if($(element).hasClass('open')){
                    
                    // hide content
                    $(element).removeClass('open');
                    
                    // change button state
                    $(this).find('span').text(M.util.get_string('showsection', 'theme_remui'));
                    $(this).find('i').removeClass('fa-angle-up');
                    $(this).find('i').addClass('fa-angle-down');
                 }
             });

             $('.toggle-section-btn').click(function() {

                 var course_id = $(this).data('courseid');
                 var expanded  = $(this).data('expanded');

                 if(expanded === 0) {
                    $('.course-content ul li.section ul.section').addClass('open');

                    // change button state
                    $('.toggle-section-btn span').text(M.util.get_string('hidesections', 'theme_remui'));
                    $('.toggle-section-btn i').removeClass('fa-angle-down');
                    $('.toggle-section-btn i').addClass('fa-angle-up');

                    // change button state
                    $('.material-button-toggle span').text(M.util.get_string('hidesection', 'theme_remui'));
                    $('.material-button-toggle i').removeClass('fa-angle-down');
                    $('.material-button-toggle i').addClass('fa-angle-up');
                    
                    $(this).data('expanded', 1);
                    M.util.set_user_preference("activities_expanded_"+course_id, 1);
                 } else if(expanded == 1) {

                    $('.course-content ul li.section ul.section').removeClass('open');

                    // change button state
                    $('.toggle-section-btn span').text(M.util.get_string('showsections', 'theme_remui'));
                    $('.toggle-section-btn i').removeClass('fa-angle-up');
                    $('.toggle-section-btn i').addClass('fa-angle-down');

                    // change button state
                    $('.material-button-toggle span').text(M.util.get_string('showsection', 'theme_remui'));
                    $('.material-button-toggle i').removeClass('fa-angle-up');
                    $('.material-button-toggle i').addClass('fa-angle-down');

                    $(this).data('expanded', 0);
                    M.util.set_user_preference("activities_expanded_"+course_id, 0);
                 }
             });
         }
     };
 });