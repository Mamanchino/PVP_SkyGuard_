var blink_speed=Math.floor(Math.random() * 500)+1000;
var blink_speed_2 = Math.floor(Math.random() * 500)+1500
var blink_speed_3 = Math.floor(Math.random() * 500)+1500

var t = setInterval(function() {
    var ele = document.getElementById('blink');
    ele.style.visibility = (ele.style.visibility == 'hidden' ? '' : 'hidden');
}, blink_speed);
var t_1 = setInterval(function() {
    var ele = document.getElementById('blink-2');
    ele.style.visibility = (ele.style.visibility == 'hidden' ? '' : 'hidden');
}, blink_speed_2);
var t_2 = setInterval(function() {
    var ele = document.getElementById('blink-3');
    ele.style.visibility = (ele.style.visibility == 'hidden' ? '' : 'hidden');
}, blink_speed_3);