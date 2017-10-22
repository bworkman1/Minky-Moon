var form = {

    initForms: function() {
        form.saveUserForm();
        form.addCheckBoxStyling();
        form.addStatesToSelectionByClass();
    },

    saveUserForm: function() {
        $('#submitUserForm').click(function(event) {
            event.preventDefault();
            var elem = $(this);
            var elemText = $(this).html();
            var formInputs = {};
            var options = [];
            $('#user-form input, #user-form select, #user-form textarea').each(
                function(index){
                    var input = $(this);
                    var name = input.attr('name');
                    var value = input.val();
                    if(input.attr('type') == 'radio' || input.attr('type') == 'checkbox') {

                        name = name.replace('[', '');
                        name = name.replace(']', '');
                        if(typeof formInputs[name] == 'undefined') {
                            formInputs[name] = [];
                        }
                        if(typeof options[name] == 'undefined') {
                            options[name] = [];
                        }

                        if(input.is(':checked')) {
                            options[name].push(value);
                            formInputs[name] = options[name];
                        }

                    } else {
                        formInputs[name] = value;
                    }
                }
            );
            $.ajax({
                method: 'POST',
                dataType: 'json',
                data: {'form': formInputs},
                url: $('#base_url').data('base')+'forms/save-user-form',
                success: function(data) {
                    if(data.success) {
                        alertify.success(data.msg);
                        $('#fullScreenLoading').remove();
                        document.getElementById("user-form").reset();
                        $(elem).html(elemText).attr('disabled', false);
                        $('#paymentModal').modal('hide');

                        window.location.href = data.data['submission_page'];
                    } else {
                        form.handleFormFail(data.msg, data.errors);
                    }
                },
                beforeSend: function() {
                    $(elem).html('<i class="fa fa-spinner fa-spin"></i> Saving').attr('disabled', true);
                    $('body').append('<div id="fullScreenLoading" class="loading">Saving ....</div>');
                    $('.form-group').removeClass('has-error');
                    $('.errorString').remove();
                },
                complete: function() {
                    $(elem).html(elemText).attr('disabled', false);
                    $('.tooltip ').remove();
                    $('#fullScreenLoading').remove();
                },
                error: function() {
                    $(elem).html(elemText).attr('disabled', false);
                    $('.tooltip ').remove();
                    $('#fullScreenLoading').remove();
                }
            });
        });
    },

    addStatesToSelectionByClass: function() {
        if($('.input_states').length > 0) {
            var states = [
                {
                    "name": "Alabama",
                    "abbreviation": "AL"
                },
                {
                    "name": "Alaska",
                    "abbreviation": "AK"
                },
                {
                    "name": "American Samoa",
                    "abbreviation": "AS"
                },
                {
                    "name": "Arizona",
                    "abbreviation": "AZ"
                },
                {
                    "name": "Arkansas",
                    "abbreviation": "AR"
                },
                {
                    "name": "California",
                    "abbreviation": "CA"
                },
                {
                    "name": "Colorado",
                    "abbreviation": "CO"
                },
                {
                    "name": "Connecticut",
                    "abbreviation": "CT"
                },
                {
                    "name": "Delaware",
                    "abbreviation": "DE"
                },
                {
                    "name": "District Of Columbia",
                    "abbreviation": "DC"
                },
                {
                    "name": "Federated States Of Micronesia",
                    "abbreviation": "FM"
                },
                {
                    "name": "Florida",
                    "abbreviation": "FL"
                },
                {
                    "name": "Georgia",
                    "abbreviation": "GA"
                },
                {
                    "name": "Guam",
                    "abbreviation": "GU"
                },
                {
                    "name": "Hawaii",
                    "abbreviation": "HI"
                },
                {
                    "name": "Idaho",
                    "abbreviation": "ID"
                },
                {
                    "name": "Illinois",
                    "abbreviation": "IL"
                },
                {
                    "name": "Indiana",
                    "abbreviation": "IN"
                },
                {
                    "name": "Iowa",
                    "abbreviation": "IA"
                },
                {
                    "name": "Kansas",
                    "abbreviation": "KS"
                },
                {
                    "name": "Kentucky",
                    "abbreviation": "KY"
                },
                {
                    "name": "Louisiana",
                    "abbreviation": "LA"
                },
                {
                    "name": "Maine",
                    "abbreviation": "ME"
                },
                {
                    "name": "Marshall Islands",
                    "abbreviation": "MH"
                },
                {
                    "name": "Maryland",
                    "abbreviation": "MD"
                },
                {
                    "name": "Massachusetts",
                    "abbreviation": "MA"
                },
                {
                    "name": "Michigan",
                    "abbreviation": "MI"
                },
                {
                    "name": "Minnesota",
                    "abbreviation": "MN"
                },
                {
                    "name": "Mississippi",
                    "abbreviation": "MS"
                },
                {
                    "name": "Missouri",
                    "abbreviation": "MO"
                },
                {
                    "name": "Montana",
                    "abbreviation": "MT"
                },
                {
                    "name": "Nebraska",
                    "abbreviation": "NE"
                },
                {
                    "name": "Nevada",
                    "abbreviation": "NV"
                },
                {
                    "name": "New Hampshire",
                    "abbreviation": "NH"
                },
                {
                    "name": "New Jersey",
                    "abbreviation": "NJ"
                },
                {
                    "name": "New Mexico",
                    "abbreviation": "NM"
                },
                {
                    "name": "New York",
                    "abbreviation": "NY"
                },
                {
                    "name": "North Carolina",
                    "abbreviation": "NC"
                },
                {
                    "name": "North Dakota",
                    "abbreviation": "ND"
                },
                {
                    "name": "Northern Mariana Islands",
                    "abbreviation": "MP"
                },
                {
                    "name": "Ohio",
                    "abbreviation": "OH"
                },
                {
                    "name": "Oklahoma",
                    "abbreviation": "OK"
                },
                {
                    "name": "Oregon",
                    "abbreviation": "OR"
                },
                {
                    "name": "Palau",
                    "abbreviation": "PW"
                },
                {
                    "name": "Pennsylvania",
                    "abbreviation": "PA"
                },
                {
                    "name": "Puerto Rico",
                    "abbreviation": "PR"
                },
                {
                    "name": "Rhode Island",
                    "abbreviation": "RI"
                },
                {
                    "name": "South Carolina",
                    "abbreviation": "SC"
                },
                {
                    "name": "South Dakota",
                    "abbreviation": "SD"
                },
                {
                    "name": "Tennessee",
                    "abbreviation": "TN"
                },
                {
                    "name": "Texas",
                    "abbreviation": "TX"
                },
                {
                    "name": "Utah",
                    "abbreviation": "UT"
                },
                {
                    "name": "Vermont",
                    "abbreviation": "VT"
                },
                {
                    "name": "Virgin Islands",
                    "abbreviation": "VI"
                },
                {
                    "name": "Virginia",
                    "abbreviation": "VA"
                },
                {
                    "name": "Washington",
                    "abbreviation": "WA"
                },
                {
                    "name": "West Virginia",
                    "abbreviation": "WV"
                },
                {
                    "name": "Wisconsin",
                    "abbreviation": "WI"
                },
                {
                    "name": "Wyoming",
                    "abbreviation": "WY"
                }
            ];
            $('.input_states').html('');

            var options = '<option value="">Select One</option>';
            for(var i in states) {
                if(states[i]['abbreviation'] == 'OH') {
                    options += '<option selected value="'+states[i]['abbreviation']+'">'+states[i]['name']+'</option>';
                } else {
                    options += '<option value="'+states[i]['abbreviation']+'">'+states[i]['name']+'</option>';
                }
            }

            $('.input_states').html(options);
        }
    },

    handleFormFail: function(msg , errors) {
        alertify.error(msg);
        var ccErrors = ['cardNumber', 'cardExpiry', 'cardCVC', 'amount', 'billing_name', 'billing_address', 'billing_city', 'billing_state', 'billing_zip'];
        var keepCCUp = false;
        for(var i in errors) {
            var field = i;
            var errorTxt = errors[i];
            if(ccErrors.indexOf(field) != -1) {
                keepCCUp = true;
            }
            var errorHtml = '<div class="errorString clearfix text-danger"><i class="fa fa-exclamation-triangle"></i> '+errorTxt+'</div>';
            $('#'+field).closest('.form-group').addClass('has-error').append(errorHtml);
        }
        if(!keepCCUp) {
            $('#paymentModal').modal('hide');
        }
    },

    addCheckBoxStyling: function() {
        $('input').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_square',
            increaseArea: '20%' // optional
        });
    },

}

$(function() {
    form.initForms();

    if(typeof $('#errorFeedback').data('error') != 'undefined' && $('#errorFeedback').data('error') != '') {
        alertify.error($('#errorFeedback').data('error'));
    }
    if(typeof $('#successFeedback').data('error') != 'undefined' && $('#successFeedback').data('error') != '') {
        alertify.success($('#successFeedback').data('error'));
    }
    $('[data-toggle="tooltip"]').tooltip();

    $('.date').mask('00/00/0000');
    $('.cc-expires').mask('00/00');
    $('.credit-card').mask('0000-0000-0000-0000');
    $('.time').mask('00:00:00');
    $('.date_time').mask('00/00/0000 00:00:00');
    $('.cep').mask('00000-000');
    $('.phone').mask('(000) 000-0000');

    $('.money').mask('000.00', {reverse: true});
    $('.money').focusout(function() {
        if($(this).val() == 0) {
            $(this).val('0.00');
        } else {
            $('.money').mask('000.00', {reverse: true});
        }
    });

    $('.ssn').mask('000-00-0000');
})