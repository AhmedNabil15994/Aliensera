/**
 * Header Js
 */

console.log("[x] Loading Header js .... Done");

function server_date_time() {

    getDateCustom(+2);

    document.getElementById('serverTime').innerHTML = result;
    setTimeout('server_date_time();', '1000');
    return true;
}

function london_date_time() {

    getDateCustom(+1);

    document.getElementById('londonTime').innerHTML = result;
    setTimeout('london_date_time();', '1000');
    return true;
}

function getDateCustom(TimezoneOffset) {

    var localTime = new Date();

    // var ms = localTime.getTime() + (localTime.getTimezoneOffset() * 60000) + TimezoneOffset * 3600000;

    var ms = localTime.setTime( localTime.getTime() + localTime.getTimezoneOffset() * 60 * 1000 );

    var date = new Date(ms);

    year = date.getFullYear();
    month = date.getMonth();
    months = ['January', 'February', 'March', 'April', 'May', 'June', 'Jully', 'August', 'September', 'October', 'November', 'December'];

    day = date.getDate();
    // day = date.getDay();
    days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

    hours24 = date.getHours();
    hours = date.getHours();
    hours = appendZero(((hours + 11) % 12) + 1);

    minutes = date.getMinutes();
    minutes = appendZero(minutes);

    sec = date.getSeconds();
    sec = appendZero(sec);

    result = day + ' ' + months[month] + ' ' + year + ' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>  ' + hours + ':' + minutes + ':' + sec + ' ' + (hours24 > 11 ? 'PM' : 'AM');

    return result;
}

function appendZero(num) {
    if (num < 10) {
        return "0" + num;
    }
    return num;
}
