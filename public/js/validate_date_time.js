function validateHoursMinutes(val, input_element)
{
    var is_valid = /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/gm.test(val);

    if (typeof input_element !== 'undefined') {
        if (is_valid) {
            input_element.removeClass('invalid_time');
            input_element.addClass('valid_time');
        } else {
            input_element.removeClass('valid_time');
            input_element.addClass('invalid_time');
        }
    }

    return is_valid;
}
function sumHourMinute(str1, str2) 
{
    var is_valid_1, is_valid_2, over_day;
    over_day   = 0;
    is_valid_1 = validateHoursMinutes(str1);
    is_valid_2 = validateHoursMinutes(str2);
    if (is_valid_1 != true || is_valid_2 != true) {
        return ['00:00', over_day];
    }
    var hour, min, part1, part2;
    part1 = str1.split(":");
    part2 = str2.split(":");

    hour = parseInt(part1[0]) + parseInt(part2[0]);
    min = parseInt(part1[1]) + parseInt(part2[1]);
    if (min >= 60) {
        hour = hour + parseInt(1);
        min = min - parseInt(60);
    }
    if (hour >= 24) {
        hour = hour - parseInt(24);
        over_day = 1;
    }

    return [hour.pad(2) + ':' + min.pad(2), over_day];
}

function formatHourMinutePad(str)
{
    if (validateHoursMinutes(str) != true) {
        return '00:00';
    }
    var part = str.split(":");
    return part[0].pad(2) + ':' + part[1].pad(2);
}

function addDayInStrDate(str_date, day_add)
{
    var obj_date = new Date(str_date);
    obj_date.setDate(obj_date.getDate() + day_add);
    var new_date = obj_date.getFullYear();
    //because getMonth -> return from 0->11
    new_date += '-' + ('0' + (obj_date.getMonth() + parseInt(1))).slice(-2);
    new_date += '-' + ('0' + obj_date.getDate()).slice(-2);

    return new_date;
}

Number.prototype.pad = function(size) {
  var s = String(this);
  while (s.length < (size || 2)) {s = "0" + s;}
  return s;
}

String.prototype.pad = function(size) {
  var s = String(this);
  while (s.length < (size || 2)) {s = "0" + s;}
  return s;
}
