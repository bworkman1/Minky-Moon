var settings = {

    init: function() {
        settings.authorizeAddSettings();
        settings.authorizeRemoveSettings();
        settings.saveSecuritySettings();
    },

    authorizeAddSettings: function(data) {
        $('#saveAuthorize').click(function(event) {
            event.preventDefault();
            var data = {
                api_key: $('input[name="api_key"]').val(),
                auth_key: $('input[name="auth_key"]').val(),
                type: 'save',
            }
            settings.sendAuthorizeRequest(data, $(this), $(this).html());
            $('#removeAuthorizeSettings').removeClass('hide');
        });
    },

    authorizeRemoveSettings: function() {
        $('#removeAuthorizeSettings').click(function(event) {
            event.preventDefault();
            var data = {
                api_key:   '',
                auth_key:   '',
                type: 'remove',
            }
            settings.sendAuthorizeRequest(data, $(this), $(this).html());
            $('input[name="api_key"]').val('');
            $('input[name="auth_key"]').val('');
            $('#removeAuthorizeSettings').addClass('hide');
        });
    },

    sendAuthorizeRequest: function(data, elem, elemText) {
        $.ajax({
            url: $('#baseUrl').attr('data-base')+'admin-settings/saveAuthorizeSettings',
            type: 'post',
            dataType: 'json',
            data: data,
            success: function(data) {
                console.log(data);
                if(data.success) {
                    alertify.success(data.msg);
                } else {
                    alertify.error(data.msg);
                }
            },
            error: function() {
                alertify.error('Oops, we failed to save those settings. Please refresh your page and try again.');
                $(elem).html(elemText).attr('disabled', false);
            },
            beforeSend: function() {
                $(elem).html('<i class="fa fa-gear fa-spin"></i> Sending').attr('disabled', true);
            },
            complete: function() {
                $(elem).html(elemText).attr('disabled', false);
            }
        });
    },

    saveSecuritySettings: function() {
        $('#saveSecuritySettings').click(function(event) {
            event.preventDefault();
            var elem = $(this);
            var elemText = $(this).html();
            var data = {
                failed: $('input[name="failed"]').val(),
                time:   $('input[name="time"]').val(),
                emails: $('input[name="emails"]').val(),
            }
            $.ajax({
                url: $('#baseUrl').attr('data-base')+'admin-settings/saveSecuritySettings',
                type: 'post',
                dataType: 'json',
                data: data,
                success: function(data) {
                    if(data.success) {
                        alertify.success(data.msg);
                    } else {
                        alertify.error(data.msg);
                    }
                },
                error: function() {
                    alertify.error('Oops, we failed to save those settings. Please refresh your page and try again.');
                    $(elem).html(elemText).attr('disabled', false);
                },
                beforeSend: function() {
                    $(elem).html('<i class="fa fa-gear fa-spin"></i> Sending').attr('disabled', true);
                },
                complete: function() {
                    $(elem).html(elemText).attr('disabled', false);
                }
            });
        });
    }

}

$(function() {
   settings.init();
});