var formSubmit = {
    action: $('#input-form').attr('action'),

    call: function() {
        $('#submitButton').click(function(event) {
            event.preventDefault();
            $.ajax({
                method: 'post',
                dataType: 'json',
                url: formSubmit.action,
                data: $('#input-form').serialize(),
                success: function(data) {
                    if(data.success) {
                        $('#formFeedback').html('<div class="alert alert-success"><i class="fa fa-check-circle"></i> '+data.msg+'</div>');
                        document.getElementById("input-form").reset();
                        if(typeof data.redirect != 'undefined') {
                            window.location.href = data.redirect;
                        }
                    } else {
                        if(typeof data.errors != 'undefined') {
                            if(data.errors) {
                                for (var key in data.errors){
                                    $('#input-form [name="'+key+'"]').closest('.form-group').addClass('has-error');
                                    $('#input-form [name="'+key+'"]').next().html(data.errors[key]);
                                }
                            }
                        }
                        $('#formFeedback').html('<div class="alert alert-danger"><i class="fa fa-times-circle-o"></i> '+data.msg+'</div>');
                    }
                },
                beforeSend: function() {
                    $('#formFeedback, .helper-error').html('');
                    $('.has-error').removeClass('has-error');
                    $('#submitButton').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Logging In');
                },
                complete: function() {
                    $('#submitButton').attr('disabled', false).html('Log In');
                },
                error: function() {
                    $('#submitButton').attr('disabled', false).html('Log In');
                    $('#formFeedback').html('<div class="alert alert-danger"><i class="fa fa-times-circle-o"></i> There was a problem logging you in, please try again!</div>');
                }
            });

        });
    }
}

var buttonActions  = {
    listenForAction: function() {
        $('.button-action').click(function (event) {
            event.preventDefault();
            var elem = $(this);
            var link = $(elem).data('url');
            var html = $(elem).html();
            $.ajax({
                method: 'post',
                dataType: 'json',
                url: link,
                success: function(data) {
                    if(data.success) {
                        alertify.success(data.msg);
                        $(elem).closest('tr').removeClass('danger');
                        $(elem).remove();
                    } else {
                        alertify.error(data.msg);
                    }
                },
                beforeSend: function() {
                    $(elem).html('<i class="fa fa-spinner fa-spin"></i>');
                },
                complete: function() {
                    $(elem).html(html);
                    $('.tooltip ').remove();
                },
                error: function() {
                    $(elem).html(html);
                    $('.tooltip ').remove();
                }
            });
        });

        $('.deleteUser').click(function(event) {
            event.preventDefault();
            $('.tooltip ').remove();
            var url = $(this).data('url');
            var id = $(this).attr('id');
            alertify.confirm("<h3>Please Confirm This Action</h3>Are you sure you want to delete this user?", function (e) {
                if (e) {
                    console.log(url);
                    $.ajax({
                        method: 'post',
                        dataType: 'json',
                        url: url,
                        success: function(data) {
                            if(data.success) {
                                alertify.success(data.msg);
                                $('#'+id).closest('tr').remove();
                                $('.tooltip ').remove();
                            } else {
                                alertify.error(data.msg);
                            }
                        },
                        error: function() {
                            alertify.error('There was an error deleting the user, try refreshing the page and try again.');
                        }
                    });
                }
            });
        });

        $('.changePassword').click(function() {
            var url = $(this).data('url');
            alertify.prompt("<h3>Change Users Password</h3>Enter their new password below. Please keep in mind that the password must have at least one lower case,  upper case, special character, a number, and at least 8 characters long. Due to the sensitive information being stored we must enforce strict password.", function (e, password) {
                if (e) {
                    $.ajax({
                        method: 'post',
                        dataType: 'json',
                        data: {password:password},
                        url: url,
                        success: function(data) {
                            if(data.success) {
                                alertify.success(data.msg);
                                $('.tooltip ').remove();
                            } else {
                                alertify.error(data.msg);
                            }
                        },
                        error: function() {
                            alertify.error('There was an error update the users password, try refreshing the page and try again.');
                        }
                    });
                }
            }, "");
            $('#alertify-text').focusin().attr('type', 'password');
        });
    }
}

var forms = {
    init: function() {
        forms.addFormName();
        forms.addFormHeader();
        forms.addFormFooter();
        forms.addCheckBoxStyling();
        forms.inputType();
        forms.addNewOptionalInput();
        forms.removeNewOptionalInput();
        forms.addInputValidation();
        forms.addNewInputToForm();
        forms.clearInputValidation();
        forms.formatFormInputName();
        forms.onFormInputHover();
        forms.deleteFormInput();
        forms.editFormInput();
    },

    addFormName: function() {
        $('input[name="form_name"]').keyup(function() {
            $('#formName').html($(this).val());
        });
    },

    addFormHeader: function() {
        $('textarea[name="form_header"]').keyup(function() {
            $('#form-header').html($(this).val());
        });
    },

    addFormFooter: function() {
        $('textarea[name="form_footer"]').keyup(function() {
            $('#form-footer').html($(this).val());
        });
    },

    addCheckBoxStyling: function() {
        $('input').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_square',
            increaseArea: '20%' // optional
        });
    },

    addInputValidation: function() {
        $('#addValidationToInput').click(function() {
            var validation = $('#validationForm').serialize();
            var buttonText = $('#addValidationToInput').html();
            $.ajax({
                url: $('#validationForm').attr('action'),
                dataType: 'json',
                type: 'post',
                data: validation,
                success: function(data) {
                    if(data.success) {
                        alertify.success(data.msg);
                        $('#validationRules').modal('hide');
                        $('#input_validations').val(data['data']['rule']);
                    } else {
                        $('#input_validations').val('');
                        if(typeof data.errors != 'undefined') {
                            if(data.errors) {
                                for (var key in data.errors){
                                    $('#validationForm [name="'+key+'"]').closest('.form-group').addClass('has-error');
                                    $('#validationForm [name="'+key+'"]').parent().find('.formErrors').html(data.errors[key]);
                                }
                            }
                        }
                        alertify.error(data.msg);
                    }
                },
                error: function() {
                    $('#addValidationToInput').html(buttonText);
                },
                beforeSend: function() {
                    $('.formErrors').html('');
                    $('.has-error').removeClass('has-error');
                    $('#addValidationToInput').html('<i class="fa fa-gear fa-spin fa-fw"></i> Adding Validation');
                },
                complete: function() {
                    $('#addValidationToInput').html(buttonText);
                }
            });
        });
    },

    clearInputValidation: function() {
        $('.clear-validation .btn').click(function() {
            $('#input_validations').val('');
        });
    },

    inputType: function() {
        $('select[name="input_type"]').change(function() {
            $('#inlineElement').addClass('hide');
            $('#inlineElement [type="checkbox"]').attr('checked', false);
            var type = $(this).val();
            if(type == 'radio') {
                $('#inlineElement').removeClass('hide');
                $('#inputOptions').removeClass('hide');
            } else if(type == 'checkbox') {
                $('#inlineElement').removeClass('hide');
                $('#inputOptions').removeClass('hide');
            } else if(type == 'select') {
                $('#inputOptions').removeClass('hide');
            } else {
                $('#inputOptions').addClass('hide');
            }
        });
    },

    addNewOptionalInput: function() {
        $('.insertNewOption').click(function() {
            var label = $('#inputOptionLabel').val();
            var value = $('#inputOptionValue').val();

            if(label != '' && value != '') {

                var html = '<li class="list-group-item" style="line-height:2.3em;padding: 3px 15px;"><div class="row">';
                html += '<div class="col-xs-5 labels" style="border-bottom:1px solid #ccc">';
                html += label;
                html += '</div>';
                html += '<div class="col-xs-5 value" style="border-left: 1px solid #ccc;border-bottom:1px solid #ccc">';
                html += value;
                html += '</div>';
                html += '<div class="col-xs-2">';
                html += '<button class="btn btn-danger btn-sm removeNewOptionalInput pull-right"><i class="fa fa-times-circle-o"></i></button>';
                html += '</div>';
                html += '</div></li>';

                $('#inputValuesSet').append(html);
                $('#inputOptionLabel').val('');
                $('#inputOptionValue').val('');
            }
        });
    },

    insetNewOptionalInput: function(inputObject) {
        console.log(inputObject);
        console.log(inputObject.name);
        if(inputObject.name != '' && inputObject.value != '') {

            var html = '<li class="list-group-item" style="line-height:2.3em;padding: 3px 15px;"><div class="row">';
            html += '<div class="col-xs-5 labels" style="border-bottom:1px solid #ccc">';
            html += inputObject.name;
            html += '</div>';
            html += '<div class="col-xs-5 value" style="border-left: 1px solid #ccc;border-bottom:1px solid #ccc">';
            html += inputObject.value;
            html += '</div>';
            html += '<div class="col-xs-2">';
            html += '<button class="btn btn-danger btn-sm removeNewOptionalInput pull-right"><i class="fa fa-times-circle-o"></i></button>';
            html += '</div>';
            html += '</div></li>';

            $('#inputOptions').removeClass('hide');
            $('#inputValuesSet').append(html);
        }
    },

    removeNewOptionalInput: function() {
        $('body').on('click', '.removeNewOptionalInput', function() {
            console.log('clicked');
            $(this).closest('li').remove();
        });
    },

    addNewInputToForm: function() {
        $('#addInput').click(function() {
            $('.has-error').removeClass('has-error');
            var inputs = {
                label: $('input[name="input_label"]').val(),
                name: $('input[name="input_name"]').val(),
                validations: $('#input_validations').val(),
                classes: $('input[name="input_class"]').val(),
                type: $('select[name="input_type"]').val(),
                columns: $('select[name="input_columns"]').val(),
                input_id: $('#formInputId').val(),
                inline: $('#inlineElement [name="inline-element"]').is(':checked')?'yes':'',
            }

            var validInputs = forms.requiredNewFormInputs(inputs);
            if(validInputs.success) {
                inputs.extras = validInputs.inputs;
                var elemText = $('#addInput').html();
                $.ajax({
                    url: $('#inputs').data('url'),
                    dataType: 'json',
                    type: 'post',
                    data: inputs,
                    success: function(data) {
                        if(data['success']) {
                            inputs.insert_id = data.data.input_id;
                            var inputHtml = forms.getInputHtml(inputs);
                            $('#form-inputs').append(inputHtml);
                            alertify.success('Form input added successfully');
                        } else {
                            alertify.error(data['msg']);
                        }
                    },
                    error: function() {
                        alertify.error('Something went wrong inserting the form input, try again');
                    },
                    beforeSend: function() {
                        $('#addInput').attr('disabled', true).html('<i class="fa fa-gear fa-spin"></i> Adding');
                    },
                    complete: function() {
                        $('#addInput').attr('disabled', false).html(elemText);
                    }
                });

            } else {
                var errors = forms.setNewInputErrorLabels(validInputs.failed);
                if(errors['error_string']) {
                    alertify.error(errors['error_string']);
                    for(var i in errors['input_names']) {
                        $('[name="'+errors['input_names'][i]+'"]').parent().addClass('has-error');
                    }
                } else {
                    alertify.error('Something went wrong, try again');
                }
            }
        });
    },

    formatFormInputName: function() {
        $('input[name="input_label"]').keyup(function() {
            var label = $(this).val();
            if(label) {
                label = label.replace(/-/g,"_");
                label = label.replace(/ /g,"_");
                label = label.toLowerCase();
            }
            $('input[name="input_name"]').val(label);
        });
    },

    getInputHtml: function(input) {
        var elem = '';
        switch(input.type) {
            case 'text':
                elem = forms.buildInputTextOption(input);
                break;
            case 'select':
                elem = forms.buildInputSelectOption(input);
                break;
            case 'textarea':
                elem = forms.buildInputTextAreaOption(input);
                break;
            case 'radio':
                elem = forms.buildInputRadioOption(input);
                break;
            case 'checkbox':
                elem = forms.buildInputCheckboxesOption(input);
                break;
            default:
                break;
        }
        return elem;
    },

    setNewInputErrorLabels: function(errors) {
        var labels = {
            label: 'Input label is required',
            name: 'Input name is required and must be unique',
            type: 'Input type is required',
            columns: 'Input column size is required',
            addedInputs: 'Missing added values for the type of input you selected',
        };
        var errorString = '';
        var form_elements = [];
        if(errors) {
            for(var input in errors) {
                errorString += 'Input '+labels[errors[input]]+' is required<br>';
                form_elements.push('input_'+errors[input]);
            }
        }
        var output = {
            error_string: errorString,
            input_names: form_elements
        }
        return output;
    },

    requiredNewFormInputs: function(inputs) {
        var requiredValues = ['label', 'name', 'type', 'columns'];
        var extraInputRequired = ['checkbox', 'radio', 'select'];
        var validForm = true;
        var failedInputs = [];
        var extraInputs = [];

        for(var input in inputs) {
            if($.inArray(input, requiredValues) !== -1) {
                if(inputs[input] == '') {
                    validForm = false;
                    failedInputs.push(input);
                }
            }
            // checking for input type and if it needs extra options
            if(input == 'type') {
                if($.inArray(inputs[input], extraInputRequired) !== -1) {
                    extraInputs = forms.getExtraInputValues();
                    if(extraInputs.length == 0) {
                        validForm = false;
                        failedInputs.push('addedInputs');
                    }
                }
            }

            if(input == 'name') {
                if(!forms.uniqueFormName(inputs[input])) {
                    validForm = false;
                    failedInputs.push(input);
                }
            }
        }

        var addInput = {
            success: validForm,
            failed: failedInputs,
            inputs: extraInputs,
        }
        return addInput;
    },

    uniqueFormName: function(name) {
        var valid = true;
        $('#form-inputs *').filter(':input').each(function(){
            var inputName = $(this).attr('name');
            if(inputName == name) {
                valid = false;
            }
        });

        return valid;
    },

    getExtraInputValues: function() {
        var extraInputs = [];
        $('#inputValuesSet li').each(function() {
            var label = $(this).find('.labels').html();
            var value = $(this).find('.value').html();
            if(label != '' && value != '') {
                extraInputs.push({label: label, values: value});
            }
        });
        return extraInputs;
    },

    buildInputSelectOption: function(inputObject) {
        var input = '<div class="' + inputObject.columns + ' formInputObject" data-validation="'+inputObject.validations+'" data-id="'+inputObject.insert_id+'">';
            input += '<div class="form-group">';
                var required = '';
                if(inputObject.validations.indexOf('required') != -1) {
                    required = '<span class="text-danger">*</span> ';
                }
                input += '<label>'+required+inputObject.label+'</label>';
                input += '<select class="' + inputObject.classes + ' form-control" name="' + inputObject.name + '">';
                input += '<option value="">Select One</option>';
                for(var i in inputObject.extras) {
                    input += '<option  value="'+inputObject.extras[i].values+'">' + inputObject.extras[i].label + '</option>';
                }
                input += '</select>';
            input += '</div>';
        input += '</div>';

        return input;
    },

    buildInputTextOption: function(inputObject) {
        var input = '<div class="' + inputObject.columns + ' formInputObject" data-validation="'+inputObject.validations+'" data-id="'+inputObject.insert_id+'">';
            input += '<div class="form-group">';
                var required = '';
                if(inputObject.validations.indexOf('required') != -1) {
                    required = '<span class="text-danger">*</span> ';
                }
                input += '<label>'+required+inputObject.label+'</label>';
                input += '<input class="' + inputObject.classes + ' form-control" name="' + inputObject.name + '"/>';
            input += '</div>';
        input += '</div>';

        return input;
    },

    buildInputTextAreaOption: function(inputObject) {
        var input = '<div class="' + inputObject.columns + ' formInputObject" data-validation="'+inputObject.validations+'" data-id="'+inputObject.insert_id+'">';
            input += '<div class="form-group">';
                var required = '';
                if(inputObject.validations.indexOf('required') != -1) {
                    required = '<span class="text-danger">*</span> ';
                }
                input += '<label>'+required+inputObject.extras[i].label+'</label>';
                input += '<textarea class="' + inputObject.classes + ' form-control" name="' + inputObject.name + '"></textarea>';
            input += '</div>';
        input += '</div>';

        return input;
    },

    buildInputCheckboxesOption: function(inputObject) {
        var input = '<div class="' + inputObject.columns + ' formInputObject" data-validation="'+inputObject.validations+'" data-id="'+inputObject.insert_id+'">';
        var required = '';
        if(inputObject.validations.indexOf('required') != -1) {
            required = '<span class="text-danger">*</span> ';
        }
        input += '<label>'+required+inputObject.extras[i].label+'</label>';
        for(var i in inputObject.extras) {
            var inline = 'checkbox';
            if(inputObject.inline == 'yes') {
                inline = 'checkbox-inline';
            }
            input += '<div class="'+inline+'">';
                input += '<label><input type="radio" class="' + inputObject.classes + '" name="' + inputObject.name + '[]" value="'+inputObject.extras[i].values+'">' + inputObject.extras[i].label + '</label>';
            input += '</div>';
        }
        input += '</div>';

        return input;
    },

    buildInputRadioOption: function(inputObject) {
        var input = '<div class="' + inputObject.columns + ' formInputObject" data-validation="'+inputObject.validations+'" data-id="'+inputObject.insert_id+'">';
        var required = '';
        if(inputObject.validations.indexOf('required') != -1) {
            required = '<span class="text-danger">*</span> ';
        }
        input += '<label>'+required+inputObject.extras[i].label+'</label>';
        for(var i in inputObject.extras) {
            var inline = 'radio';
            if(inputObject.inline == 'yes') {
                inline = 'radio-inline';
            }
            input += '<div class="'+inline+'">';
                input += '<label><input type="radio" class="' + inputObject.classes + '" name="' + inputObject.name + '" value="'+inputObject.extras[i].values+'">' + inputObject.extras[i].label + '</label>';
            input += '</div>';
        }
        input += '</div>';

        return input;
    },

    onFormInputHover: function() {
        var htmlObject = '<div class="inputObjectEditableOptions text-right">' +
            '<span class="deleteInputObject"><i class="fa fa-times-circle"></i></span>' +
            '<span class="editInputObject"><i class="fa fa-pencil-square-o"></i></span>' +
            '</div>';

        $(document).on('mouseenter','.formInputObject', function (event) {
            $(this).prepend(htmlObject);
        }).on('mouseleave','.formInputObject',  function(){
            $(this).find('.inputObjectEditableOptions').remove();
        });
    },

    editFormInput: function() {
        $('body').on('click', '.editInputObject', function() {
            var id = $(this).closest('.formInputObject').data('id');
            $.ajax({
                url: $('#base_url').data('base')+'forms/get-form-input',
                dataType: 'json',
                type: 'post',
                data: {id: id},
                success: function(data) {
                    if(data.success) {
                        alertify.success(data.msg);
                        forms.setFormInputElements(data.data[id]);
                    } else {
                        alertify.error(data.msg);
                    }
                },
                error: function() {
                    $('#addValidationToInput').html(buttonText);
                },
                beforeSend: function() {

                },
                complete: function() {
                }
            });
        });
    },

    setFormInputElements: function(inputObject) {
        $('input[name="input_label"]').val(inputObject.input_label);
        $('input[name="input_name"]').val(inputObject.input_name);
        $('#input_validations').val(inputObject.input_validation);
        $('input[name="input_class"]').val(inputObject.custom_class);
        $('select[name="input_type"]').val(inputObject.input_type);
        $('select[name="input_columns"]').val(inputObject.input_columns);
        $('#formInputId').val(inputObject.id);
        $('#formId').val(inputObject.form_id);
        //$('#inlineElement').icheck('check');

        if(typeof inputObject.options != 'undefined') {
            for(var obj in inputObject.options) {
                forms.insetNewOptionalInput(inputObject.options[obj]);
            }
        }
        $('*[href="#inputs"]').trigger('click');
    },

    deleteFormInput: function() {
        $(document).on('click', '.deleteInputObject', function() {
            var elem = $(this);
            var elemData = $(this).html();
            var id = $(this).closest('.formInputObject').data('id');
            if(id) {
                $.ajax({
                    url: $('#base_url').data('base')+'forms/delete-input',
                    dataType: 'json',
                    type: 'post',
                    data: {id:id},
                    success: function(data) {
                        if(data['success']) {
                            $(elem).closest('.formInputObject').remove();
                            alertify.success('Form input deleted');
                        } else {
                            alertify.error(data['msg']);
                        }
                    },
                    error: function() {
                        alertify.error('Something went wrong deleting the form input, try again');
                    },
                    beforeSend: function() {
                        $(elem).html('<i class="fa fa-gear fa-spin"></i>');
                    },
                    complete: function() {
                        $(elem).html(elemData);
                    }
                });
            } else {
                alertify.error('No id was found for the form input, try again');
            }
        });
    }

}

$(document).ready(function() {
    formSubmit.call();
    buttonActions.listenForAction();

    if(typeof $('#errorFeedback').data('error') != 'undefined' && $('#errorFeedback').data('error') != '') {
        alertify.error($('#errorFeedback').data('error'));
    }
    if(typeof $('#successFeedback').data('error') != 'undefined' && $('#successFeedback').data('error') != '') {
        alertify.success($('#successFeedback').data('error'));
    }
    $('[data-toggle="tooltip"]').tooltip();

    forms.init();
});



