var formSubmit = {
    action: $('#input-form').attr('action'),

    call: function() {
        $('#submitButton').click(function(event) {
            event.preventDefault();
            var elem = $(this);
            var elemText = $(this).html();
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
                    $('#submitButton').attr('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Checking');
                },
                complete: function() {
                    $('#submitButton').attr('disabled', false).html(elemText);
                },
                error: function() {
                    $('#submitButton').attr('disabled', false).html(elemText);
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
        forms.saveNewForm();
        forms.activateForm();
        forms.saveUserForm();
        forms.usePrebuiltClass();
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
            var inputs = {};

            $('#validationForm input[type="checkbox"], #validationForm input[type="text"], #validationForm input[type="number"]').each(function() {
                if($(this).parent().hasClass('checked')) {
                    var name = $(this).attr('name');
                    inputs[name] = $(this).val();
                }
            });

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
                sequence: $('select[name="sequence"]').val(),
                encrypted: ($('input[name="encrypt_data"]').is(':checked') ? 1 : 0) ,
                form_id: $('#formId').val(),
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
                            //inputs.insert_id = data.data.input_id;
                            //var inputHtml = forms.getInputHtml(inputs);
                            //if(data.data.db_type == 'update') {
                            //    $('.formInputObject[data-id="'+inputs.insert_id+'"]').after(inputHtml).remove();
                            //} else {
                            //    $('#form-inputs').append(inputHtml);
                            //}
                            $('#form-inputs').html(data.data['page']);
                            alertify.success('Form input added successfully');

                            forms.resetFormInputForm();
                            forms.addCheckBoxStyling();
                            forms.setSequenceValues();
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
                label = label.replace(/[^\w\s]/gi, '');
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
        var inputId = $('#formInputId').val();
        $('#form-inputs *').filter(':input').each(function() {
            if(inputId == '' && inputId != $(this).closest('.formInputObject').data('id')) {
                var inputName = $(this).attr('name').split('[');
                if(inputName[0] == name) {
                    valid = false;
                }
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
        var input = '<div class="' + inputObject.columns + ' formInputObject" data-formid="'+inputObject.form_id+'" data-sequence="'+inputObject.sequence+'" data-validation="'+inputObject.validations+'" data-id="'+inputObject.insert_id+'">';
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
        var input = '<div class="' + inputObject.columns + ' formInputObject" data-formid="'+inputObject.form_id+'" data-sequence="'+inputObject.sequence+'" data-validation="'+inputObject.validations+'" data-id="'+inputObject.insert_id+'">';
            input += '<div class="form-group">';
                var required = '';
                if(inputObject.validations.indexOf('required') != -1) {
                    required = '<span class="text-danger">*</span> ';
                }
                input += '<label>'+required+inputObject.label+'</label>';
                input += '<input class="' + inputObject.classes + ' form-control" name="' + inputObject.name + '"/>';
                if(inputObject.encrypted) {
                    input += '<i class="encryptedIcon fa fa-lock"></i>';
                }
            input += '</div>';
        input += '</div>';

        return input;
    },

    buildInputTextAreaOption: function(inputObject) {
        var input = '<div class="' + inputObject.columns + ' formInputObject" data-formid="'+inputObject.form_id+'" data-validation="'+inputObject.validations+'" data-id="'+inputObject.insert_id+'">';
            input += '<div class="form-group">';
                var required = '';
                if(inputObject.validations.indexOf('required') != -1) {
                    required = '<span class="text-danger">*</span> ';
                }
                input += '<label>'+required+inputObject.label+'</label>';
                input += '<textarea class="' + inputObject.classes + ' form-control" name="' + inputObject.name + '"></textarea>';
                if(inputObject.encrypted) {
                    input += '<i class="encryptedIcon fa fa-lock"></i>';
                }
            input += '</div>';
        input += '</div>';

        return input;
    },

    buildInputCheckboxesOption: function(inputObject) {
        var input = '<div class="' + inputObject.columns + ' formInputObject" data-formid="'+inputObject.form_id+'" data-sequence="'+inputObject.sequence+'" data-validation="'+inputObject.validations+'" data-id="'+inputObject.insert_id+'">';
        var required = '';
        if(inputObject.validations.indexOf('required') != -1) {
            required = '<span class="text-danger">*</span> ';
        }
        input += '<label>'+required+inputObject.label+'</label>';
        for(var i in inputObject.extras) {
            var inline = 'checkbox';
            if(inputObject.inline == 'yes') {
                inline = 'checkbox-inline';
            }
            input += '<div class="'+inline+'">';
                input += '<label><input type="checkbox" class="' + inputObject.classes + '" name="' + inputObject.name + '[]" value="'+inputObject.extras[i].values+'"> ' + inputObject.extras[i].label + '</label>';
            input += '</div>';
        }
        if(inputObject.encrypted) {
            input += '<i class="encryptedIcon fa fa-lock"></i>';
        }
        input += '</div>';

        return input;
    },

    buildInputRadioOption: function(inputObject) {
        var input = '<div class="' + inputObject.columns + ' formInputObject" data-formid="'+inputObject.form_id+'" data-sequence="'+inputObject.sequence+'" data-validation="'+inputObject.validations+'" data-id="'+inputObject.insert_id+'">';
        var required = '';
        if(inputObject.validations.indexOf('required') != -1) {
            required = '<span class="text-danger">*</span> ';
        }
        input += '<label>'+required+inputObject.label+'</label>';
        for(var i in inputObject.extras) {
            var inline = 'radio';
            if(inputObject.inline == 'yes') {
                inline = 'radio-inline';
            }
            input += '<div class="'+inline+'">';
                input += '<label><input type="radio" class="' + inputObject.classes + '" name="' + inputObject.name + '" value="'+inputObject.extras[i].values+'"> ' + inputObject.extras[i].label + '</label>';
            input += '</div>';
        }
        if(inputObject.encrypted) {
            input += '<i class="encryptedIcon fa fa-lock"></i>';
        }
        input += '</div>';

        return input;
    },

    onFormInputHover: function() {
        var htmlObject = '<div class="inputObjectEditableOptions in text-right">' +
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
            var id = $(this).closest('.formInputObject').attr('data-id');
            var form_id = $('#formId').val();
            $.ajax({
                url: $('#base_url').data('base')+'forms/get-form-input',
                dataType: 'json',
                type: 'post',
                data: {id: id, form_id: form_id},
                success: function(data) {
                    if(data.success) {
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
        if(inputObject.id != $('#formInputId').val()) {
            forms.resetFormInputForm();

            $('input[name="input_label"]').val(inputObject.input_label);
            $('input[name="input_name"]').val(inputObject.input_name);
            $('#input_validations').val(inputObject.input_validation);
            $('input[name="input_class"]').val(inputObject.custom_class);
            $('select[name="input_type"]').val(inputObject.input_type);
            $('select[name="input_columns"]').val(inputObject.input_columns);
            $('#formInputId').val(inputObject.id);
            $('#formId').val(inputObject.form_id);
            $('select[name="sequence"]').val(inputObject.sequence);

            if(inputObject.encrypt_data == 1) {
                $('input[name="encrypt_data"]').iCheck('check');
            } else {
                $('input[name="encrypt_data"]').iCheck('uncheck');
            }

            $('.form-group').removeClass('has-error');

            $('select[name="input_type"]').trigger('change');

            if(inputObject.input_inline > 0) {
                $('#inlineElement').iCheck('check');
            }

            if (typeof inputObject.options != 'undefined') {
                for (var obj in inputObject.options) {
                    forms.insetNewOptionalInput(inputObject.options[obj]);
                }
            }

            forms.setValidationCheckboxes(inputObject.input_validation);

            $('*[href="#inputs"]').trigger('click');

            alertify.success('You are now editing the form input');
        } else {
            alertify.error('You are already editing this input');
        }
    },

    setValidationCheckboxes: function(validationObj) {
        if(validationObj) {
            var full = validationObj.split('|');
            if(full) {
                for(var i in full) {
                    if(full[i].indexOf("[") !== -1) {
                        var vals = full[i].split('[');
                        $('#validationForm input[value="'+vals[0]+'"]').iCheck('check');
                        $('#validationForm input[value="'+vals[0]+'"]').closest('.col-md-8').next().find('input').val(vals[1].replace(']', ''));
                    } else {
                        $('#validationForm input[value="'+full[i]+'"]').iCheck('check');
                    }
                }
            }
        }
    },

    resetFormInputForm: function() {
        $('input[name="input_label"]').val('');
        $('input[name="input_name"]').val('');
        $('#input_validations').val('');
        $('input[name="input_class"]').val('');
        $('select[name="input_type"]').val('');
        $('select[name="input_columns"]').val('');
        $('#formInputId').val('');

        $('#inputSequence').val( ( ($('#form-inputs .formInputObject').length) +1) );
        $('input[name="encrypt_data"]').iCheck('uncheck');
        $('#validationForm input').iCheck('uncheck');
        $('#inputValuesSet').html('');
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
    },

    saveNewForm: function() {
        $('#saveNewForm').click(function() {
            var inputs = $('#form-inputs .formInputObject').length;
            if(inputs) {
                var buttonText = $('#saveNewForm').html();
                var button = $('#saveNewForm');
                var name    = $('#settings input[name="form_name"]').val();
                var cost    = $('#settings input[name="form_cost"]').val();
                var min     = $('#settings input[name="min_payment"]').val();
                var header  = $('#settings textarea[name="form_header"]').val();
                var footer  = $('#settings textarea[name="form_footer"]').val();
                var active  = $('#settings input[name="is_active"]').parent().hasClass('checked')?true:false;
                var form_id  = $('#formId').val();

                $.ajax({
                    url: $('#base_url').data('base')+'forms/save-form',
                    dataType: 'json',
                    type: 'post',
                    data: {name:name, cost:cost, min:min, header:header, footer:footer, active:active, form_id:form_id},
                    success: function(data) {
                        if(data.success) {
                            alertify.success(data.msg);
                            var id = data.data['id'];
                            window.location.href = $('#base_url').data('base')+'forms/view-form/'+id;
                        } else {
                            var errors = data.errors;
                            if(errors) {
                                for (var input in errors) {
                                    alertify.error(errors[input]);
                                }
                            } else {
                                alertify.error(data.msg);
                            }
                        }
                    },
                    error: function() {
                        alertify.error('Failed to save form, try refreshing the page and trying again');
                        $(button).html(buttonText).attr('disabled', false);
                    },
                    beforeSend: function() {
                        $(button).html('<i class="fa fa-gear fa-spin"></i>').attr('disabled', true);
                    },
                    complete: function() {
                        $(button).html(buttonText).attr('disabled', false);
                    }
                });

            } else {
                alertify.error('You must add some inputs before you can save a form');
            }
        });
    },

    activateForm: function() {
        $('#toggleForm').click(function() {
            var status = $(this).attr('data-status');
            var id = $(this).data('id');
            var elemData = $(this).html();
            $.ajax({
                url: $('#base_url').data('base')+'forms/toggle_form',
                dataType: 'json',
                type: 'post',
                data: {id:id,status:status},
                success: function(data) {
                    if(data['success']) {
                        if(data['data']['status'] == 0) {
                            var status = 'active';
                            var buttonTxt = 'Activate Form';
                            var listTxt = '<span class="text-danger">Inactive</span>';
                        } else {
                            var buttonTxt = 'Deactivate Form';
                            var status = 'inactive';
                            var listTxt = '<span class="text-success">Active</span>';
                        }
                        $('#toggleForm').attr('data-status', status).html(buttonTxt);
                        $('#formStatusView').html(listTxt);
                        alertify.success(data['msg']);
                    } else {
                        alertify.error(data['msg']);
                    }
                    $('#toggleForm').attr('disabled', false);
                },
                error: function() {
                    alertify.error('Something went wrong updating the form, try again');
                    $(elem).html(elemData).attr('disabled', false);
                },
                beforeSend: function() {
                    $('#toggleForm').html('<i class="fa fa-gear fa-spin"></i> Updating Form').attr('disabled', true);
                }
            });
        });
    },

    setSequenceValues: function() {
        console.log('setsequence');
        var options = '';
        var count = 0;
        for(var i = 0; i < $('#form-inputs .formInputObject').length; i++) {
            count = count+1;
            options += '<option>'+count+'</option>';
        }
        $('#inputSequence').html(options);
    },

    usePrebuiltClass: function() {
        $('.prebuiltClass').click(function() {
            var type = $(this).data('type');
            var current = $('input[name="input_class"]').val();
            var classes = current+' '+type;
            $('input[name="input_class"]').val(classes.trim());
            $('#preBuiltClasses').modal('hide');
        });
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
                        console.log('Stripped Name: '+name);
                        if(typeof formInputs[name] == 'undefined') {
                            formInputs[name] = [];
                        }
                        if(typeof options[name] == 'undefined') {
                            options[name] = [];
                        }

                        if(input.is(':checked')) {
                            console.log('PUSH: '+name);
                            options[name].push(value);
                            formInputs[name] = options[name];
                            console.log('Is Checked! NO!')
                        }

                    } else {
                        formInputs[name] = value;
                    }
                }
            );
            console.log(formInputs);
            $.ajax({
                method: 'POST',
                dataType: 'json',
                data: {'form': formInputs},
                url: $('#base_url').data('base')+'forms/save-user-form',
                success: function(data) {
                    if(data.success) {

                    } else {
                        forms.handleFormFail(data.msg, data.errors);
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

    handleFormFail: function(msg , errors) {
        alertify.error(msg);
        var ccErrors = ['cardNumber', 'cardExpiry', 'cardCVC', 'amount'];
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
    })

    $('.ssn').mask('000-00-0000');

});



