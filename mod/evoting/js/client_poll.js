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
 * Version information
 *
 * @package    mod_evoting
 * @copyright  2016 Cyberlearn
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
var idClient;
var idPoll;
var lang;
var nbrOptionTotal = 5;

$(function () {

    // Lang of the user
    lang = $("#lang").val();

    // Get the id of the current poll
    idPoll = $("#spanIdPoll").text();

    if (typeof(Storage) !== "undefined") {
        // Retrieve idClient
        idClient = localStorage.getItem("idClient");
        if (idClient == null) {
            // Generate an unique value for the ID end user
            idClient = Math.floor(Math.random() * 26) + Date.now();
            // Store idClient
            localStorage.setItem("idClient", idClient);

        }
        // Get options for the poll
        getOptions(idPoll);

    } else {
        var alertTxt = "";
        switch (lang) {
            case 'fr':
                alertTxt = "E-Voting n'est pas supporté sur votre navigateur";
                break;
            case 'de':
                alertTxt = "E-Voting wird von Ihrem Browser nicht unterstützt";
                break;
            case 'en':
                alertTxt = "E-Voting not supported by your browser";
                break;
        }
    }
});

/*
 * Function for voting (INSERT / UPDATE)
 * @param : idClient
 * @param : myChoice
 * @param : idQuestion
 */
function vote(idClient, myChoice, idPoll, lang) {

    $.ajax({
        url: './ws/ajax_poll_vote.php',
        type: 'POST',
        data: {
            // MyChoice-1 because position start to 0 and button to 1
            choice: myChoice,
            idClient: idClient,
            idPoll: idPoll,
            lang: lang,
            action: 'client_vote',
            sesskey: $("#sesskey").val()
        },
        beforeSend: function () {
            // Show loading message
            $('#loader').show();
            $('.toastBg').fadeIn(400)
            $('#myToast').fadeIn(400);
            $('#myToast').removeClass('toast');
            $('#myToast').addClass('toastLoading');
            $("#myToast span").hide();
        },
        success: function (data, statut) {
            // Show sucess message
            $('#myToast span').html("");
            $('#myToast span').text((data));
            $('#myToast').addClass('toast');
            $('#myToast').removeClass('toastLoading');
            $("#myToast span").show();
            $('.toastBg').delay(1000).fadeOut(400);
            $('#myToast').delay(1000).fadeOut(400);
        },

        error: function (resultat, statut, erreur) {
            // Show error message
            $('#myToast span').html("");
            $('#myToast span').text("Error");
            $('#myToast').addClass('toast');
            $('#myToast').removeClass('toastLoading');
            $("#myToast span").show();
            $('.toastBg').delay(1000).fadeOut(400);
            $('#myToast').delay(1000).fadeOut(400);
        },

        complete: function (resultat, statut) {
        }
    });
}

/*
 * Function for get options
 * @param : idPoll
 */
function getOptions(idPoll) {

    $.ajax({
        url: './ws/ajax_poll_vote.php',
        type: 'POST',
        data: {
            idPoll: idPoll,
            action: 'get_options',
            sesskey: $("#sesskey").val()
        },
        beforeSend: function () {
        },
        success: function (data, statut) {

            nbrOptionTotal = data;

            // Sum Rows
            var nbrRow = Math.ceil(nbrOptionTotal / 2);
            var rowHeight = 100 / nbrRow;

            for (var i = 1; i <= nbrRow * 2; i++) {
                var caseVote = '<div id="evotingOption" style="height: ' + rowHeight + '% "><button class="evotingOptionBtn" id="' + i + '"><span id="optionNo" style="font-size : 30px ; font-size: 10vm ">' + i + '</span></button></div>';
                $("#clientOptions").append(caseVote);
            }

            //Function onClick buttons
            $("button").click(function () {
                vote(idClient, this.id, idPoll, lang);
            });

        },

        error: function (resultat, statut, erreur) {
        },

        complete: function (resultat, statut) {
            console.log("complete");
        }
    });
}