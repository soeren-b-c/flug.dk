var calendar = Object();
calendar.data = null;

calendar.getData = function() {
    return calendar.data;
};

calendar.getFirstItem = function() {
    if (calendar.data !== null && calendar.data.items.length > 0) {
        return calendar.data.items[0];
    }
    return null;
};

calendar.format = function(data) {
    var formatDescription = function(text) {
        
    }
    
    var formatMonth = function(month) {
        switch (month) {
            case 0: return 'Jan';
            case 1: return 'Feb';
            case 2: return 'Mar';
            case 3: return 'Apr';
            case 4: return 'Maj';
            case 5: return 'Jun';
            case 6: return 'Jul';
            case 7: return 'Aug';
            case 8: return 'Sep';
            case 9: return 'Okt';
            case 10: return 'Nov';
            case 11: return 'Dec';
        }
        return 'Unk';
    };
    
    var padNumberStr = function(num) {
        if (num.length == 1) {
            return "0"+num;
        }
        return num.toString();
    }
    
    if (typeof data.items !== 'undefined') {
        for (i = 0; i < data.items.length; i++) {

            if (typeof data.items[i].summary === 'undefined' || data.items[i].summary === '') {
                data.items[i].summary = 'Ingen titel';
            }
            if (typeof data.items[i].description === 'undefined' || data.items[i].description === '') {
                data.items[i].description = 'Ingen beskrivelse';
            }
            if (typeof data.items[i].start === 'undefined' || typeof data.items[i].start.dateTime === 'undefined') {
                data.items[i].start = {};
                data.items[i].start.dateTime = '';
                data.items[i].start.day = '';
                data.items[i].start.month = '';
                data.items[i].start.year = '';
                data.items[i].start.hour = '';
                data.items[i].start.minute = '';
                data.items[i].start.seconds = '';
            }
            if (typeof data.items[i].end === 'undefined' || typeof data.items[i].end.dateTime === 'undefined') {
                data.items[i].end = {};
                data.items[i].end.dateTime = '';
                data.items[i].end.day = '';
                data.items[i].end.month = '';
                data.items[i].end.year = '';
                data.items[i].end.hour = '';
                data.items[i].end.minute = '';
                data.items[i].end.seconds = '';
            }
            
            if (data.items[i].start.dateTime !== '') {
                var date = new Date(data.items[i].start.dateTime);
                data.items[i].start.day = padNumberStr(date.getDate());
                data.items[i].start.month = formatMonth(date.getMonth());
                data.items[i].start.year = date.getFullYear().toString();
                data.items[i].start.hour = padNumberStr(date.getHours().toString());
                data.items[i].start.minute = padNumberStr(date.getMinutes().toString());
                data.items[i].start.seconds = padNumberStr(date.getSeconds().toString());
            }
            
            if (data.items[i].end.dateTime !== '') {
                var date = new Date(data.items[i].end.dateTime);
                data.items[i].end.day = padNumberStr(date.getDate());
                data.items[i].end.month = formatMonth(date.getMonth());
                data.items[i].end.year = date.getFullYear().toString();
                data.items[i].end.hour = padNumberStr(date.getHours().toString());
                data.items[i].end.minute = padNumberStr(date.getMinutes().toString());
                data.items[i].end.seconds = padNumberStr(date.getSeconds().toString());
            }

        }
        
        calendar.data = data;
    }
};