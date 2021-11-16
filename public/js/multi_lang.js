var class_show = 'arrow-down';
var class_hide = 'arrow-up';
var list_lang_hide = ['cn', 'tw', 'kr'];

$(document).on('ready', function(){
    $('.show_multi_lang').on('click', function(){
        toggle_lang($(this));
    });
});

/**
 * Check for show or hide & display button
 */
function toggle_lang(div_toggle) {
    var class_name = div_toggle.attr('class');
    var div_parent = div_toggle.closest('div.multi_lang.parent');
    var div_class_arr = div_parent.attr('class').split(" ");
    var key_name = div_class_arr[div_class_arr.length - 1];

    if (class_name.includes(class_show)) {
        div_toggle.removeClass(class_show);
        div_toggle.addClass(class_hide);
        //show all multi lang
        show_hide_list(key_name, true);
    } else {
        div_toggle.removeClass(class_hide);
        div_toggle.addClass(class_show);
        //hide all multi lang
        show_hide_list(key_name, false);
    }
}

/**
 * base on $key_name => find div with class lead by $key_name and $lang in list_lang_hide
 * Then toogle them by value of $show
 */
function show_hide_list(key_name, show)
{
    $.each(list_lang_hide, function(index, value) {
        var div_key     = key_name + '_' + value;
        var class_begin = 'multi_lang ' + div_key;
        if (typeof $('div[class^="' + class_begin + '"]') == 'undefined') {
            return;
        }
        if (show == true) {
            $('div[class^="' + class_begin + '"]').removeClass('off');
        } else {
            $('div[class^="' + class_begin + '"]').addClass('off');
        }
    });
}
