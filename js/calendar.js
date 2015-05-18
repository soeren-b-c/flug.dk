$(function() {
    var request = $.ajax({
        url: "/calendar/2015-04-30T18:00:00Z",
        method: "GET",
        dataType: "json"
    });
    request.done(function(data) {
        if (data.items.length > 0) {
            var nextEvent = data.items[0];
            var summary = 'Ingen titel';
            var desc = 'Ingen beskrivelse';
            var startDate = null;
            
            if (typeof nextEvent.summary !== 'undefined') {
                summary = nextEvent.summary;
            }
            if (typeof nextEvent.description !== 'undefined') {
                desc = nextEvent.description;
            }
            if (typeof nextEvent.start.dateTime !== 'undefined') {
                startDate = nextEvent.start.dateTime;
            }

            $('#next-event-title').text(summary);
            $('#next-event-desc').text(desc);
            $('#next-event').css('display', 'block');
            
            if (startDate !== null) {
                var date = new Date(startDate);
                
                $('#next-event-day').text(date.getDay());
                $('#next-event-year').text(date.getYear());
                $('#next-event-clock').text(date.getHours()+':'+date.getMinutes()+':'+date.getSeconds());
                
                var month = '';
                switch (date.getMonth()) {
                    case 1:
                        month = 'Jan';
                        break;
                    case 2:
                        month = 'Feb';
                        break;
                    case 3:
                        month = 'Mar';
                        break;
                    case 4:
                        month = 'Apr';
                        break;
                    case 5:
                        month = 'Maj';
                        break;
                    case 6:
                        month = 'Jun';
                        break;
                    case 7:
                        month = 'Jul';
                        break;
                    case 8:
                        month = 'Aug';
                        break;
                    case 9:
                        month = 'Sep';
                        break;
                    case 10:
                        month = 'Okt';
                        break;
                    case 11:
                        month = 'Nov';
                        break;
                    case 12:
                        month = 'Dec';
                        break;
                }
                $('#next-event-month').text(month);
            }
        }
        
    });
});