/**************************************
 * Fonctions liees au CSS
 **************************************/

function js_css_search_glyphicon_load(action) {
    id = "glyph-" + action;
    $('#' + id).removeClass('glyphicon-search');
    $('#' + id).addClass('glyphicon-refresh');
    $('#' + id).addClass('gly-spin');
}

function js_css_search_glyphicon_init(action) {
    id = "glyph-" + action;
    $('#' + id).removeClass('gly-spin');
    $('#' + id).removeClass('glyphicon-refresh');
    $('#' + id).addClass('glyphicon-search');
}

function js_css_edit_glyphicon_load(id) {
    idname = 'glyph-edit[name="' + id + '"]';
    $('#' + idname).removeClass('glyphicon-pencil');
    $('#' + idname).addClass('glyphicon-refresh');
    $('#' + idname).addClass('gly-spin');
}

function js_css_edit_glyphicon_init(id) {
    idname = 'glyph-edit[name="' + id + '"]';
    $('#' + idname).removeClass('gly-spin');
    $('#' + idname).removeClass('glyphicon-refresh');
    $('#' + idname).addClass('glyphicon-pencil');
}

function js_css_errors_init() {
    $('.form-group').removeClass('has-error');
    $('.help-block').empty();
    $('.help-block').hide();
    $('#message-info').hide();
    $('#message-info-modal').hide();
}

function js_css_error_init(thisobject) {
    $('.help-block').hide();
    thisobject.closest('.form-group').removeClass('has-error');
    thisobject.next('.help-block').empty();
    thisobject.parent().next('.help-block').empty();
}

function js_css_error_set(thisobject, message) {
    $('.help-block').show();
    thisobject.closest('.form-group').addClass('has-error');
    thisobject.nextAll('.help-block').text(message);
    thisobject.parent().nextAll('.help-block').text(message);
}

function js_css_message_info_show(message, cssclass, fadeout) {
    fadeout = typeof fadeout !== 'undefined' ? fadeout : true;
    $('#message-info').show();
    $('#message-info').addClass('alert alert-dismissable text-center fade in');
    $('#message-info').addClass(cssclass);
    if (message.indexOf("<br") >= 0) {
        $('#message-info-text').wrapInner("<span>" + message + "</span>");
    } else {
        $('#message-info-text').text(message);
    }
    if (fadeout === true) {
        $('#message-info').delay(5000).fadeOut("slow", function () {
            $('#message-info-text').empty();
            $('#message-info').removeClass(cssclass);
        });
    }
}

function js_css_message_info_modal_show(message, cssclass, fadeout) {
    fadeout = typeof fadeout !== 'undefined' ? fadeout : true;
    $('#message-info-modal').show();
    $('#message-info-modal').addClass('alert alert-dismissable text-center fade in');
    $('#message-info-modal').addClass(cssclass);
    $('#message-info-modal').text(message);
    if (fadeout === true) {
        $('message-info-modal').delay(5000).fadeOut("slow", function () {
            $('#message-info-modal-text').empty();
            $('#message-info-modal').removeClass(cssclass);
        });
    }
}

/* Bootstrap's modal enforce focus for accessibility reasons but that causes problems with LOTS of third-party libraries, including clipboard.js. */
$.fn.modal.Constructor.prototype.enforceFocus = function () { };
// $.fn.modal.Constructor.prototype._enforceFocus = function () { } // For Bootstrap 4




/**************************************
 * Gestion globale document
 **************************************/

$(document).ready(function () {

    //-------------
    // Code commun a toutes les pages
    // -----------

    // On efface et masque tous les blocs d'erreur et messages d'info
    $('.help-block').hide();
    $('#message-info').hide();
    $('#message-info-modal').hide();
    $('#usernameornum').keyup(function() {
        this.value = this.value.toUpperCase();
    });
    $("input").change(function () {
        js_css_error_init($(this));
    });
    $("textarea").change(function () {
        js_css_error_init($(this));
    });
    $("select").change(function () {
        js_css_error_init($(this));
    });
    $('.link-info').on("click", function () {
        $('.p-info').toggle("fast");
    });

});

