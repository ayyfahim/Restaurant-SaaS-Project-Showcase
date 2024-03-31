'use strict'
$(document).ready(function () {

    /* color style picker */
    if ($.type($.cookie("stylesheet")) != 'undefined' && $.cookie("stylesheet") != '') {
        var currentstyle = $('#style');
        $('head').append('<link href="assets/css/style-' + $.cookie('stylesheet') + '.css" rel="stylesheet"  id="style">');
        setTimeout(function () {
            currentstyle.remove();
        }, 1000);

        $('.colorselect').each(function () {
            var activestyle = $(this).find('input[type="radio"]');
            if (activestyle.next('label').attr('data-title') === $.cookie("stylesheet")) {
                activestyle.prop("checked", true).parent().addClass('active');
            }
        })

    } else {
        $('.colorselect > input[type="radio"]').prop("checked", false);
        $.cookie("stylesheet", "", {
            expires: 1
        });
    }
    $('.colorselect > input[type="radio"]').on('click', function () {
        $('.colorselect').removeClass('active');
        var setstyle = $(this).next().attr('data-title');
        var currentstyle = $('#style');

        if ($(this).is(':checked')) {
            $.cookie("stylesheet", setstyle, {
                expires: 1
            });
            $('head').append('<link href="assets/css/style-' + setstyle + '.css" rel="stylesheet"  id="style">');
            setTimeout(function () {
                currentstyle.remove();
            }, 1000);

            $(this).parent().addClass('active');

        } else {
            $.cookie("stylesheet", "", {
                expires: 1
            });
        }
    });

    /* round layout layout */
    if ($.type($.cookie("roundlayout")) != 'undefined' && $.cookie("roundlayout") != '') {
        $('#roundlayout').prop("checked", true);
        $('#roundlayout').parent().addClass('active');
        $('body').addClass($.cookie("roundlayout"));
    } else {
        /*$('#roundlayout').parent().removeClass('active');
        $('#roundlayout').prop("checked", false);
        $.removeCookie("roundlayout", "");
        $('body').removeClass('ui-rounded');*/
    }
    $('#roundlayout').on('click', function () {
        $(this).parent().addClass('active');
        if ($(this).is(':checked')) {
            $.cookie("roundlayout", 'ui-rounded', {
                expires: 1
            });
            $('body').addClass('ui-rounded');
            $('#roundlayout').parent().addClass('active');
        } else {
            $.removeCookie("roundlayout", "");
            $('body').removeClass('ui-rounded');
            $('#roundlayout').parent().removeClass('active');
        }
    });

    /* RTL layout layout */
    if ($.type($.cookie("rtllayout")) != 'undefined' && $.cookie("rtllayout") != '') {
        $('#rtllayout').prop("checked", true);
        $('#rtllayout').parent().addClass('active');
        $('body').addClass($.cookie("rtllayout"));
    } else {
        $('#rtllayout').parent().removeClass('active');
        $('#rtllayout').prop("checked", false);
        $.removeCookie("rtllayout", "");
    }

    $('#rtllayout').on('click', function () {
        $(this).parent().addClass('active');
        if ($(this).is(':checked')) {
            $.cookie("rtllayout", 'rtl', {
                expires: 1
            });
            $('body').addClass('rtl');
            $('#rtllayout').parent().addClass('active');

        } else {
            $.removeCookie("rtllayout", "");
            $('body').removeClass('rtl');
            $('#rtllayout').parent().removeClass('active');
        }
    });
});
