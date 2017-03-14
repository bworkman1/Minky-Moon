var login = {
    action: $('#loginForm').attr('action'),

    init_login: function() {
        $('#loginButton').click(function(event) {
            event.preventDefault();
            var user = $('#username').val();
            var password = $('#password').val();
            var remember = $('#remember').is(':checked')?true:false;
            $.ajax({
                method: 'post',
                dataType: 'json',
                url: login.action,
                data: {identity:user,password:password,remember:remember},
                success: function(data) {
                    if(data.success) {
                        $('#loginFeedback').html('<div class="alert alert-success"><i class="fa fa-check-circle"></i> '+data.msg+'</div>');
                        window.location.replace($('#loginForm').data('baseurl')+'dashboard');
                    } else {
                        $('#loginFeedback').html('<div class="alert alert-danger"><i class="fa fa-times-circle-o"></i> '+data.msg+'</div>');
                    }
                },
                beforeSend: function() {
                    $('#loginFeedback').html('');
                    $('#loginButton').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Logging In');
                },
                complete: function() {
                    $('#loginButton').attr('disabled', false).html('Log In');
                },
                error: function() {
                    $('#loginButton').attr('disabled', false).html('Log In');
                    $('#loginFeedback').html('<div class="alert alert-danger"><i class="fa fa-times-circle-o"></i> There was a problem logging you in, please try again!</div>');
                }
            });

        });

    }
}


$(document).ready(function() {
   login.init_login();
});