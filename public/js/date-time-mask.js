$(document).on('ready', function () {
    var momentFormat = 'HH:mm';
    object = {
        mask: Date,
            pattern: momentFormat,
            lazy: false,
            format: function (date) {
                return moment(date).format(momentFormat);
            },
            parse: function (str) {
                return moment(str, momentFormat);
            },
            // define str -> date convertion
            blocks: {
                HH: {
                    mask: IMask.MaskedRange,
                    from: 0,
                    to: 23
                },
                mm: {
                    mask: IMask.MaskedRange,
                    from: 0,
                    to: 59
                }
            }
    }
    $('.time-mask').each(function(index, obj){
        IMask(obj, object);
    });
    function updateValue() {
        this.masked.value = this.el.value;
      }
});

function changeTime(time) {
    var time_minute = new Array();
    $.each(time, function(index, value) {
        var hour = String(Math.floor(value / 60));
        if(hour.length==1) { hour = "0" + hour;}
        var minute = String(value % 60);
        if(minute.length==1) { minute = "0" + minute;}
        var time = hour + ':' + minute;
        time_minute.push(time);
    })
    return time_minute;
}