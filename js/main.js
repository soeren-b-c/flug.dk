$(function() {
    var calendarReady = false;
    var calendarItemsAdded = false;

    $(".main").onepage_scroll({
        sectionContainer: "section", // sectionContainer accepts any kind of selector in case you don't want to use section
        easing: "ease", // Easing options accepts the CSS3 easing animation such "ease", "linear", "ease-in",
        // "ease-out", "ease-in-out", or even cubic bezier value such as "cubic-bezier(0.175, 0.885, 0.420, 1.310)"
        animationTime: 1000, // AnimationTime let you define how long each section takes to animate
        pagination: true, // You can either show or hide the pagination. Toggle true for show, false for hide.
        updateURL: true, // Toggle this true if you want the URL to be updated automatically when the user scroll to each page.
        beforeMove: function(index) {
        }, // This option accepts a callback function. The function will be called before the page moves.
        afterMove: function(index) {
            if (index == 2) {
                if (calendarReady) {
                    addCalendarElements();
                } else {
                    fetchCalendar(function(status) {
                        if (status === true) {
                            addCalendarElements();
                        }
                    });
                }
            }
        }, // This option accepts a callback function. The function will be called after the page moves.
        loop: true, // You can have the page loop back to the top/bottom when the user navigates at up/down on the first/last page.
        keyboard: true, // You can activate the keyboard controls
        responsiveFallback: false, // You can fallback to normal page scroll by defining the width of the browser in which
        // you want the responsive fallback to be triggered. For example, set this to 600 and whenever
        // the browser's width is less than 600, the fallback will kick in.
        direction: "vertical"            // You can now define the direction of the One Page Scroll animation. Options available are "vertical" and "horizontal". The default value is "vertical".  
    });

    function addCalendarElements() {
        if (calendarItemsAdded) {
            return;
        }
        
        var calAll = calendar.getData();        
        for (i = 0; i < calAll.items.length; i++) {
            if (i >= 10) {
                break;
            }
            calendar.formatEventsToHtml(calAll.items[i]).appendTo('#calendar-container');
        }
        renderCalendar(true);
        calendarItemsAdded = true;
    }

    function renderCalendar(fade) {
        var visibleEl = 0;
        var totalHeight = $('#calendar-container').height();
        $('.cal-item').each(function() {
            var elementBottomCoordinate = $(this).position().top + $(this).height();
            visibleEl++;
            if (fade === true) {
                $(this).fadeIn('slow')
                        .css({opacity: 0, visibility: "visible"})
                        .animate({opacity: 1}, 'slow');
            } else {
                $(this).fadeIn('fast').css({opacity: 1, visibility: "visible"});
            }

        });
    }

    function displayNextEvent() {
        calendar.formatEventsToHtml(calendar.getFirstItem())
                .css({display: 'block', opacity: 1, visibility: "visible"})
                .appendTo('#next-event-container');
    }

    function fetchCalendar(callback) {
        calendar.fetch('', function(status) {
            if (status === true) {
                calendarReady = true;
                callback(true);
            } else {
                callback(false);
            }
        });
    }

    fetchCalendar(function(status) {
        if (status === true) {
            displayNextEvent();
        }
    });

    $('.event-link').on('click', function() {
        $(".main").moveTo(2);
    });
    $('.home-link').on('click', function() {
        $(".main").moveTo(1);
    });
    $('.who-link').on('click', function() {
        $(".main").moveTo(3);
    });

});