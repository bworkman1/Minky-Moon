var settings = {

    init: function() {
        settings.authorizeAddSettings();
        settings.authorizeRemoveSettings();
        settings.saveSecuritySettings();
        settings.saveUserGroup();
        settings.onUserGroupHover();
        settings.deleteUserGroup();
        settings.saveEmailSettings();
    },

    authorizeAddSettings: function(data) {
        $('#saveAuthorize').click(function(event) {
            event.preventDefault();
            var data = {
                api_key: $('input[name="api_key"]').val(),
                auth_key: $('input[name="auth_key"]').val(),
                test_mode: $('#test-mode').is(':checked') ? 'y' : 'n',
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
    },

    saveEmailSettings: function() {
        $('#saveEmailSettings').click(function(event) {
            event.preventDefault();
            var elem = $(this);
            var elemText = $(this).html();
            var data = {
                emails: $('input[name="emails"]').val(),
                submission:   $('textarea[name="default_submission"]').val(),
            }
            $.ajax({
                url: $('#baseUrl').attr('data-base')+'admin-settings/saveEmailSettings',
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
    },

    saveUserGroup: function() {
        $('#addNewGroup').click(function() {
            var name = $('input[name="security_page"]').val();
            var desc = $('input[name="security_page_desc"]').val();
            var elem = $(this);
            var elemText = $(this).html();
            $.ajax({
                url: $('#baseUrl').attr('data-base')+'admin-settings/saveUserGroup',
                type: 'post',
                dataType: 'json',
                data: {name:name,desc:desc},
                success: function(data) {
                    if(data.success) {
                        alertify.success(data.msg);
                        settings.addNewUserGroup(name, desc, data.data['id']);
                        $('input[name="security_page"]').val('');
                        $('input[name="security_page_desc"]').val('');
                    } else {
                        alertify.error(data.msg);
                    }
                },
                error: function() {
                    alertify.error('Oops, we failed to save the page setting. Please refresh your page and try again.');
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
    },

    addNewUserGroup: function(name, desc, id) {
       var elem = '<li class="list-group-item" style="position:relative;">';
            elem += '<h4 class="list-group-item-heading">'+name+'</h4>';
            elem += '<p class="list-group-item-text">'+desc+'</p>';
            elem += '<span class="deleteGroup hide" data-groupid="'+id+'" style="position:absolute;top: -15px;right: -15px;">';
            elem += '<i class="fa fa-times-circle fa-3x pull-right text-danger"></i>';
            elem += '</span>';
            elem += '</li>';

        $('#userGroupList').append(elem);
    },

    onUserGroupHover: function() {
        $(document).on({
            mouseenter: function () {
                $(this).find('.deleteGroup').removeClass('hide');
            },
            mouseleave: function () {
                $(this).find('.deleteGroup').addClass('hide');
            }
        }, "#userGroupList li");
    },

    deleteUserGroup: function() {
      $('body').on('click', '.deleteGroup', function() {
          var elem = $(this);
          var elemText = $(this).html();
          var id = $(this).attr('data-groupid');
          if(id>0) {
              $.ajax({
                  url: $('#baseUrl').attr('data-base') + 'admin-settings/deleteUserGroup',
                  type: 'post',
                  dataType: 'json',
                  data: {id: id},
                  success: function (data) {
                      if (data.success) {
                          alertify.success(data.msg);
                          $(elem).closest('.list-group-item').remove();
                      } else {
                          alertify.error(data.msg);
                      }
                  },
                  error: function () {
                      alertify.error('Oops, we failed to save the page setting. Please refresh your page and try again.');
                      $(elem).html(elemText).attr('disabled', false).removeClass('sureShow');
                  },
                  beforeSend: function () {
                      $(elem).html('<i class="fa fa-gear fa-spin text-danger fa-3x"></i>').addClass('sureShow').attr('disabled', true);
                  },
                  complete: function () {
                      $(elem).html(elemText).attr('disabled', false).removeClass('sureShow');
                  }
              });
          } else {
              alertify.error('Failed to find the page id, try refreshing the page and trying again!');
          }
      });
    }

}

$(function() {
   settings.init();
});