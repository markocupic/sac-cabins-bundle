/**
 * SAC Event Tool Web Plugin for Contao
 * Copyright (c) 2008-2017 Marko Cupic
 * @package sac-event-tool-bundle
 * @author Marko Cupic m.cupic@gmx.ch, 2017
 * @link    https://sac-kurse.kletterkader.com
 */


//Provides methods for filtering the kursliste

var SacTourFilter =  {
        /**
         * globalEventId
         * This is used in self.queueRequest
         */
        globalEventId: 0,

        /**
         * time to wait before launching the xhr request, when making changes to the filter form
         */
        delay: 1000,


        /**
         * queueRequest
         */
        queueRequest: function () {
            SacTourFilter.showLoadingIcon();
            SacTourFilter.resetEventList();
            SacTourFilter.globalEventId++;
            var eventId = SacTourFilter.globalEventId;
            window.setTimeout(function () {
                if (eventId == SacTourFilter.globalEventId) {
                    SacTourFilter.fireXHR();
                }
            }, SacTourFilter.delay);
        },

        /**
         * Reset/Remove List
         */
        resetEventList: function () {
            $('.alert-no-results-found').remove();
            $('.event-item-tour').each(function () {
                $(this).hide();
                $(this).removeClass('visible');
            });
        },


        /**
         * Show the loading icon
         */
        showLoadingIcon: function () {
            // Add loading icon
            $('.loading-icon-lg').remove();
            $('.mod_eventlist').append('<div class="loading-icon-lg"><div><span class="fa fa-spinner fa-spin"></span></div></div>');
        },

        /**
         * Hide the loading icon
         */
        hideLoadingIcon: function () {
            // Add loading icon
            $('.loading-icon-lg').remove();
        },

        /**
         * List events starting from a certain date
         * @param dateStart
         */
        listEventsStartingFromDate: function (dateStart) {
            var regex = /^(.*)-(.*)-(.*)$/g;
            var match = regex.exec(dateStart);
            if (match) {
                // JavaScript counts months from 0 to 11. January is 0. December is 11.
                var date = new Date(match[3], match[2] - 1, match[1]);
                var tstamp = Math.round(date.getTime() / 1000);
                if (!isNaN(tstamp)) {
                    $('#ctrl_dateStartHidden').val(tstamp);
                    $('#ctrl_dateStartHidden').attr('value', tstamp);
                    SacTourFilter.queueRequest();
                    return;
                }
            }
            $('#ctrl_dateStartHidden').attr('value', '0');
            $('#ctrl_dateStartHidden').val('0');
            SacTourFilter.queueRequest();
        },


        /**
         * filterRequest
         */
        fireXHR: function () {
            var itemsFound = 0;

            // Event-items
            var arrIds = [];
            $('.event-item-tour').each(function () {
                arrIds.push($(this).attr('data-id'));
            });

            // Tour type
            var idTourType = $('#ctrl_tourType').val();
            // Save Input to sessionStorage
            try {
                sessionStorage.setItem('ctrl_tourType_' + modEventFilterListId, idTourType);
            }
            catch (e) {
                console.log('Session Storage is disabled or not supported on this browser.')
            }

            // Sektionen
            var arrOGS = [];
            $('.ctrl_sektion:checked').each(function () {
                arrOGS.push(this.value);
            });

            try {
                // Save Input to sessionStorage
                sessionStorage.setItem('ctrl_sektion_' + modEventFilterListId, JSON.stringify(arrOGS));
            }
            catch (e) {
                console.log('Session Storage is disabled or not supported on this browser.')
            }

            // StartDate
            var intStartDate = Math.round($('#ctrl_dateStartHidden').val()) > 0 ? $('#ctrl_dateStartHidden').val() : 0;
            intStartDate = Math.round(intStartDate);

            // Textsuche
            var strSuchbegriff = $('#ctrl_suche').val();
            // Save Input to sessionStorage
            try {
                sessionStorage.setItem('ctrl_suche_' + modEventFilterListId, strSuchbegriff);
            }
            catch (e) {
                console.log('Session Storage is disabled or not supported on this browser.')
            }

            var url = 'ajax';
            var request = $.ajax({
                method: 'post',
                url: url,
                data: {
                    action: 'filterTourList',
                    REQUEST_TOKEN: request_token,
                    year: SacTourFilter.getUrlParam('year'),
                    ids: JSON.stringify(arrIds),
                    tourtype: idTourType,
                    ogs: JSON.stringify(arrOGS),
                    suchbegriff: strSuchbegriff,
                    startDate: intStartDate
                },
                dataType: 'json'
            });
            request.done(function (json) {
                if (json) {
                    SacTourFilter.hideLoadingIcon();

                    $.each(json.filter, function (key, id) {
                        $('.event-item-tour[data-id="' + id + '"]').each(function () {
                            //intFound++;
                            $(this).show();
                            $(this).addClass('visible');
                            itemsFound++;
                        });
                    });
                    if (itemsFound == 0 && $('.alert-no-results-found').length == 0) {

                        $('.mod_eventlist').append('<div class="alert alert-danger alert-no-results-found text-lg" role="alert"><h4><i class="fa fa-meh" aria-hidden="true"></i> Leider wurden zu deiner Suchanfrage keine Events gefunden. &Uuml;berp&uuml;fe bitte die Filtereinstellungen.</h4></div>');
                    }
                }
            });
            request.fail(function (jqXHR, textStatus, errorThrown) {
                SacTourFilter.hideLoadingIcon();
                console.log(jqXHR);
                alert('Fehler: Die Anfrage konnte nicht bearbeitet werden! Überprüfe Sie die Internetverbindung.');
            });
        },
        /**
         * get url param
         * @param strParam
         * @returns {*|number}
         */
        getUrlParam: function (strParam) {
            var results = new RegExp('[\?&]' + strParam + '=([^&#]*)').exec(window.location.href);
            if (results === null) return 0;
            return results[1] || 0;
        }

}



$().ready(function () {

    if($('.filter-board[data-event-type="tour"]').length < 1) {
        // Add a valid filter board
        return;
    }
    // Check if the request token is set in the template
    if (!request_token) {
        alert('Please set the request-token in the template');
    }

    // Check if the request token is set in the template
    if (!modEventFilterListId) {
        alert('Please set the modEventFilterListId in the template');
    }

    // filter List if there are some values in the browser's sessionStorage
    try {
        if (typeof window.sessionStorage !== 'undefined') {
            var blnFilterList = false;
            if (sessionStorage.getItem('ctrl_suche_' + modEventFilterListId) !== null) {
                $('#ctrl_suche').val(sessionStorage.getItem('ctrl_suche_' + modEventFilterListId));
                blnFilterList = true;
            }

            if (sessionStorage.getItem('ctrl_sektion_' + modEventFilterListId) !== null) {
                var arrSektionen = JSON.parse(sessionStorage.getItem('ctrl_sektion_' + modEventFilterListId));
                if (arrSektionen.length) {
                    $('.ctrl_sektion').each(function () {
                        if (jQuery.inArray($(this).attr('value'), arrSektionen) > -1) {
                            $(this).prop('checked', true);
                        } else {
                            $(this).prop('checked', false);
                        }
                    });
                    blnFilterList = true;
                }
            }

            if (sessionStorage.getItem('ctrl_tourType_' + modEventFilterListId) !== null) {
                $('#ctrl_tourType').val(sessionStorage.getItem('ctrl_tourType_' + modEventFilterListId));
                blnFilterList = true;
            }


            // Filter list, if there are some filter stored in the sessionStorage
            if (blnFilterList) {
                SacTourFilter.resetEventList();
                SacTourFilter.queueRequest();
            }
        }
    }
    catch (e) {
        console.log('Session Storage is disabled or not supported for this browser.')
    }





    // Init iCheck
    // http://icheck.fronteed.com/
    $('#organizers input').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-grey',
        increaseArea: '20%' // optional
    });



    /** Trigger Filter **/
    // Redirect to selected year
    $('#ctrl_eventYear').on('change', function () {
        var arrUrl = window.location.href.split("?");
        window.location.href = arrUrl[0] + '?year=' + $(this).prop('value');
    });

    $('#ctrl_tourType, .ctrl_sektion').on('change', function () {
        SacTourFilter.queueRequest();
    });

    $('#ctrl_suche').on('keyup', function () {
        SacTourFilter.queueRequest();
    });

    $('#organizers input').on('ifClicked', function (event) {
        SacTourFilter.queueRequest();
    });

    // List events starting from a certain date
    var dateStart = $('#ctrl_dateStart').val();
    SacTourFilter.listEventsStartingFromDate(dateStart);


    /** Set Datepicker **/
    var opt = {
        format: "dd-mm-yyyy",
        autoclose: true,
        maxViewMode: 0,
        language: "de"
    };
    // Set datepickers start and end date
    if (SacTourFilter.getUrlParam('year') > 0) {
        opt['startDate'] = "01-01-" + SacTourFilter.getUrlParam('year');
        opt['endDate'] = "31-12-" + SacTourFilter.getUrlParam('year');
    } else {
        var now = new Date();
        //opt['startDate'] = "01-01-" + now.getFullYear();
        //opt['endDate'] = "31-12-" + now.getFullYear();
    }

    $('.filter-board .input-group.date').datepicker(opt).on('changeDate', function (e) {
        var dateStart = $('#ctrl_dateStart').val();
        SacTourFilter.listEventsStartingFromDate(dateStart);
    });



    // Entferne die Suchoptionen im Select-Menu, wenn ohnehin keine Events dazu existieren
    var arrTourTypes = [];
    $('.event-item-tour').each(function () {
        if ($(this).attr('data-tourType') != '') {
            var arten = $(this).attr('data-tourType').split(',');
            jQuery.each(arten, function (i, val) {
                arrTourTypes.push(val);
            });
        }
    });
    arrTourTypes = jQuery.unique(arrTourTypes);
    $('#ctrl_tourType option').each(function () {
        if ($(this).attr('value') > 0) {
            var id = $(this).attr('value');
            if (jQuery.inArray(id, arrTourTypes) < 0) {
                $(this).remove();
            }
        }
    });
});
