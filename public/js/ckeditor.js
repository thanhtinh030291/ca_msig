$(function(){
    $('.editor_default').each(function(e){
        CKEDITOR.replace( this.id, { customConfig: '/js/ckeditor_default.js' });
    });
});

$(function(){
    $('.editor').each(function(e){
        CKEDITOR.replace( this.id, { customConfig: '/js/ckeditor_template_letter.js' });
    });
});

$(function(){
    $('.editor2').each(function(e){
        CKEDITOR.replace( this.id, { customConfig: '/js/ckeditor_reason_reject.js' });
    });
});