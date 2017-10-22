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

        $('.changePassword').click(function(e) {
            e.preventDefault();
            var url = $(this).data('url');
            alertify.prompt("<h3>Change Users Password</h3>Enter their new password below. Password must be 8 characters long and have at least one of each of the following: uppercase, lowercase, special character.", function (e, password) {
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
        forms.colorSubmittedFormsRed();
        forms.usePrebuiltClass();
        forms.formSubmissionPerPage();
        forms.deleteForm();
        forms.submitFormSearch();
        forms.showOnlyFormsSubmittedByName();
        forms.addStatesToSelectionByClass();
        forms.sortSubmittedForms();
        forms.deleteFormSubmission();
        forms.toggleFormActive();
        forms.duplicateForm();
        forms.addCalendarToDates();
    },

    submitFormSearch: function() {
        $('#searchFormSubmission').click(function() {
            $('#formSearchForm').submit();
        });
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

            $('#resetFormInputFields').addClass('hide');
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
                            $('#form-inputs').html(data.data['page']);
                            alertify.success('Form input added successfully');

                            forms.resetFormInputForm();
                            forms.addCheckBoxStyling();
                            forms.setSequenceValues();
                            forms.addStatesToSelectionByClass();
                            forms.addCalendarToDates();
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

    addCalendarToDates: function() {
        $('.date').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true
        });

        $('.date_time').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            timePicker: true,
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
                        $('#resetFormInputFields').removeClass('hide');
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
                var submission  = $('#settings textarea[name="submission"]').val();
                var active  = $('#settings input[name="is_active"]').parent().hasClass('checked')?true:false;
                var form_id  = $('#formId').val();
console.log('here');
                $.ajax({
                    url: $('#base_url').data('base')+'forms/save-form',
                    dataType: 'json',
                    type: 'post',
                    data: {name:name, cost:cost, min:min, header:header, footer:footer, active:active, submission:submission, form_id:form_id},
                    success: function(data) {
                        if(data.success) {
                            alertify.success(data.msg);
                            var id = data.data['id'];
                            window.location.href = $('#base_url').data('base')+'forms/view-form/'+id;
                        } else {
                            var errorData = data.errors;
                            if(errorData != '') {
                                for (var input in errorData) {
                                    alertify.error(errorData[input]);
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

    toggleFormActive: function() {
        $('.toggleForm').click(function() {
            var elem = $(this);
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
                        location.reload();
                    } else {
                        alertify.error(data['msg']);
                    }
                    elem.attr('disabled', false);
                },
                error: function() {
                    alertify.error('Something went wrong updating the form, try again');
                    elem.html(elemData).attr('disabled', false);
                },
                beforeSend: function() {
                    elem.html('<i class="fa fa-gear fa-spin"></i>').attr('disabled', true);
                }
            });
        });
    },

    duplicateForm: function() {
        $('#duplicateForm').click(function() {
            var elem = $(this);
            var id = $(this).data('id');
            var elemData = $(this).html();
            $.ajax({
                url: $('#base_url').data('base')+'forms/duplicate-form',
                dataType: 'json',
                type: 'post',
                data: {id:id},
                success: function(data) {
                    if(data['success']) {
                        window.location.replace($('#base_url').data('base')+'forms/edit-form/'+data.new_form_id);
                    } else {
                        alertify.error(data['msg']);
                    }
                    $('#duplicateForm').attr('disabled', false);
                },
                error: function() {
                    alertify.error('Something went wrong duplicating the form, try again');
                    $(elem).html(elemData).attr('disabled', false);
                },
                beforeSend: function() {
                    $('#duplicateForm').html('<i class="fa fa-gear fa-spin"></i> Making a Copy').attr('disabled', true);
                },
                complete: function() {
                    $(elem).html(elemData).attr('disabled', false);
                }
            });
        });
    },

    setSequenceValues: function() {
        var options = '';
        var count = 0;
        for(var i = 0; i < $('#form-inputs .formInputObject').length; i++) {
            count = count+1;
            options += '<option>'+count+'</option>';
        }
        options += '<option selected>'+(count+1)+'</option>';

        $('#inputSequence').html(options);
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

    usePrebuiltClass: function() {
        $('.prebuiltClass').click(function() {
            var type = $(this).data('type');
            var current = $('input[name="input_class"]').val();
            var classes = current+' '+type;
            $('input[name="input_class"]').val(classes.trim());
            $('#preBuiltClasses').modal('hide');

            if(type == 'input_states') {
                $('select[name="input_type"]').val('select').trigger('change');
                $('#inputOptionLabel').val('Select One');
                $('#inputOptionValue').val('Select One');
                setTimeout(function(){
                    $('.insertNewOption').trigger('click');
                }, 1);
            }
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

                        if(data.data['redirect']) {
                            window.location = data.data['redirect'];
                        }
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



    colorSubmittedFormsRed: function() {
        $('.table tr td i').each(function() {
            if($(this).hasClass('notYetViewedForm')) {
                $(this).closest('tr').addClass('text-danger');
            }
        });
    },

    formSubmissionPerPage: function() {
        $('#formSubmissionsPerPage').change(function() {
            $('#perPageForm').submit();
        });
    },

    showOnlyFormsSubmittedByName: function() {
        $('#sortByFormNames').change(function() {
            $('#viewByFormSubmitted').submit();
        });
    },

    deleteForm: function() {
      $('.deleteForm').click(function() {
          var url = $(this).data('url');
          var elem = $(this);
          var elemText = $(this).html();
          var msg = 'Are you sure you want to delete this form? Deleting it won\'t affect submitted forms but will not allow any new submissions to be added';
          alertify.confirm(msg, function (e) {
              if (e) {
                  if(url) {
                      $.ajax({
                          dataType: 'json',
                          url: url,
                          success: function(data) {
                              if(data.success) {
                                  alertify.success(data.msg);
                                  $(elem).closest('tr').remove();
                              } else {
                                  alertify.error(data.msg);
                              }
                          },
                          beforeSend: function() {
                              $(elem).html('<i class="fa fa-gear fa-spin"></i>').attr('disabled', true);
                          },
                          complete: function() {
                              $(elem).html(elemText).attr('disabled', false);
                          },
                          error: function() {
                              $(elem).html(elemText).attr('disabled', false);
                              alertify.error('Could not process request, please refresh your page and try again');
                          }
                      });
                  } else {
                      alertify.error('Could not process request, please refresh your page and try again');
                  }
              }
          });

      });
    },

    sortSubmittedForms: function() {
        $('.sortFormBy').click(function() {
            var direction = $(this).data('direction');
            var order = $(this).data('order');
            $.ajax({
                url: $('#base_url').data('base')+'forms/sortFormSubmissionsBy',
                data: {direction:direction,order:order},
                type: 'post',
                dataType: 'json',
                success: function(data) {
                    if(data.success) {
                        location.reload();
                    } else {
                        alertify.error('Something went wrong, try again');
                    }
                },
                error: function() {
                    alertify.error('Something went wrong, try again');
                },
            });
        });
    },

    deleteFormSubmission: function() {
        $('.deleteFormSubmission').click(function() {
            var elem = $(this);
            var elemText = $(this).html();

            alertify.confirm("Are you sure you want to delete this form submission?", function (e) {
                if (e) {
                    var id = elem.data('id');
                    var form_id = elem.data('formid');
                    $.ajax({
                        url: $('#base_url').data('base')+'forms/delete-form-submission',
                        data: {id:id,form_id:form_id},
                        type: 'post',
                        dataType: 'json',
                        success: function(data) {
                            if(data.success) {
                                location.reload();
                            } else {
                                alertify.error(data.msg);
                                elem.html(elemText).attr('disabled', false);
                            }
                        },
                        error: function() {
                            alertify.error('Something went wrong, try again');
                            elem.html(elemText).attr('disabled', false);
                        },
                        beforeSend: function() {
                            elem.html('<i class="fa fa-gear fa-spin"></i> Removing').attr('disabled', true);
                        },
                        complete: function() {
                            elem.html(elemText).attr('disabled', false);
                        }
                    });
                }
            });


        });
    }

}

var payments = {
    copyAddressToBilling: function() {
        $("#copyHomeAddress").on("ifChecked", function() {
            //$('#copyHomeAddress').is(':checked', function() {
                $('#billing_name').val($('#name').val());
                $('#billing_address').val($('#address').val());
                $('#billing_city').val($('#city').val());
                $('#billing_state').val($('#state').val());
                $('#billing_zip').val($('#zip').val());
            //});
        });
    },

    submitSinglePayment: function() {
        $('#submitPayment').click(function() {
            var elem = $(this);
            var elemText = $(this).html();
            $('#payment-form .has-error').removeClass('has-error');
            $.ajax({
                url: $('#base_url').data('base')+'payments/submitPaymentDetails',
                dataType: 'json',
                type: 'post',
                data: $('#payment-form').serialize(),
                success: function(data) {
                    if(data.success) {
                        window.location.href = $('#base_url').data('base')+'payments/all';
                    } else {
                        if(typeof data.errors != 'undefined') {
                            if(data.errors) {
                                for (var key in data.errors){
                                    $('#payment-form [name="'+key+'"]').closest('.form-group').addClass('has-error');
                                }
                            }
                        }
                        alertify.error(data.msg);
                    }
                },
                error: function() {
                    $(elem).html(elemText).attr('disabled', false);
                    alertify.error(data.msg);
                },
                beforeSend: function() {
                    $(elem).html('<i class="fa fa-gear fa-spin"></i> Submitting Payment').attr('disabled', true);
                },
                complete: function() {
                    $(elem).html(elemText).attr('disabled', false);
                }
            });
        });
    }
}

$(document).ready(function() {
    if(typeof $('#errorFeedback').data('error') != 'undefined' && $('#errorFeedback').data('error') != '') {
        alertify.error($('#errorFeedback').data('error'));
    }
    if(typeof $('#successFeedback').data('error') != 'undefined' && $('#successFeedback').data('error') != '') {
        alertify.success($('#successFeedback').data('error'));
    }

    setTimeout(function() {
        $('.alert.alert-success').slideUp();
    }, 4000);

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


    var canvas = document.createElement('canvas');
    canvas.width = 250;
    canvas.height = 250;

    var canvas_context = canvas.getContext("2d");

    var img = new Image();
    img.onload = function(){
        var msk = new Image();
        msk.onload = function(){

            var ptrn = canvas_context.createPattern(img, 'repeat');
            canvas_context.fillStyle = ptrn;
            canvas_context.fillRect(0, 0, canvas.width, canvas.height);

            canvas_context.drawImage(img, 30, 30);
            canvas_context.globalCompositeOperation = "destination-in";
            canvas_context.drawImage(msk, 30, 30);
            canvas_context.globalCompositeOperation = "source-over";


        };

        msk.src = $('#base_url').data('base')+'assets/themes/minky-moon/img/download.png';
    }

    img.src = $('#base_url').data('base')+'assets/uploads/thumbs/1381413411Purple-Night-Owls-thumb.jpg';


    $('#itemPreview').html(canvas);


    // var canvas = document.getElementById("canvas");
    // var ctx = canvas.getContext("2d");
    //
    // var img1 = new Image();
    // var img = new Image();
    // img.onload = function () {
    //
    //     img1.onload = function () {
    //         start();
    //     }
    //     img1.src = ;
    // }
    // img.src = $('#base_url').data('base')+'assets/uploads/1379526006Green-Diggers-lg.jpg';
    //
    // function start() {
    //
    //     ctx.drawImage(img1, 0, 0);
    //
    //     ctx.globalCompositeOperation = "source-atop";
    //
    //     var pattern = ctx.createPattern(img, 'repeat');
    //     ctx.rect(0, 0, canvas.width, canvas.height);
    //     ctx.fillStyle = pattern;
    //     ctx.fill();
    //
    //     ctx.globalAlpha = .10;
    //     ctx.drawImage(img1, 0, 0);
    //     ctx.drawImage(img1, 0, 0);
    //     ctx.drawImage(img1, 0, 0);
    //
    // }

    pricing.openEditPanel();
    pricing.savePricingOptions();
    pricing.deletePricingOption();

    fabric.fabricSelection();
    //fabric.fabricCategorySwitch();
    fabric.fabricAddCategory();
    fabric.saveFabric();
    fabric.modalsCloseEvents();
    fabric.viewFabricsByProduct();
    fabric.addNewFabric();
    fabric.deleteFabric();

    product.editProduct();
    product.deleteProduct();
    product.saveProductOptions();
    product.deleteProductImage();
    product.addNewProduct();
    product.modalCloseEvents();

    cart.setImageCanvas('assets/themes/minky-moon/img/download.png', 'assets/uploads/1379953375Minky-Dot-Colors-Apple-lg.jpg');
});


var pricing = {

    openEditPanel: function() {
        $('.openEditPanel').click(function () {
            pricing.optionModalCloseEvent();

            var title = $(this).data('modaltitle');
            var category = $(this).data('category');
            var name = $(this).data('name');
            var elem = $(this);
            var elemHtml = $(this).html();
            $('#pricingOptionForm select[name="category"]').val(category);
            $('#optionModal .modal-title').html(title);
            if(category && name) {
                $.ajax({
                    url: $('#base_url').data('base')+'pricing/getPricingOption',
                    dataType: 'json',
                    data: {category:category, name:name},
                    type: 'post',
                    success: function(data) {
                        if(data.id) {
                            $('.categoryInput').closest('.form-group').addClass('hide');
                            $('.categorySelect').closest('.form-group').removeClass('hide');

                            $('.categorySelect').attr('name', 'category');
                            $('.categorySelect').attr('id', 'category');
                            $('.categoryInput').attr('name', '');
                            $('.categoryInput').attr('id', '');

                            $('#pricingOptionForm input[name="name"]').val(data.name);
                            $('#pricingOptionForm input[name="price"]').val(data.price);
                            $('#pricingOptionForm select[name="category"]').val(data.category);
                            $('#pricingOptionForm input[name="size"]').val(data.size);
                            $('#pricingOptionForm input[name="id"]').val(data.id);
                            $('#pricingOptionForm select[name="sequence"]').val(data.sequence);

                            $('#optionModal').modal('show');
                        } else {
                            alertify.error('There was an error pulling the items pricing up, please try again');
                        }
                    },
                    error: function() {
                        alertify.error('There was an error pulling the items pricing up, please try again');
                        elem.html(elemHtml).attr('disabled', false);
                    },
                    beforeSend: function() {
                        elem.html('<i class="fa fa-gear"></i>').attr('disabled', true);
                    },
                    complete: function() {
                        elem.html(elemHtml).attr('disabled', false);
                    },
                });
            } else {
                $('.categorySelect').closest('.form-group').addClass('hide');
                $('.categoryInput').closest('.form-group').removeClass('hide');

                $('.categorySelect').attr('name', '');
                $('.categorySelect').attr('id', '');
                $('.categoryInput').attr('name', 'category');
                $('.categoryInput').attr('id', 'category');
                $('#pricingOptionForm select[name="sequence"]').val(1);
                $('#optionModal').modal('show');
            }
        });
    },

    savePricingOptions: function() {
        $('#savePricingOptions').click(function() {
            var data = $('#pricingOptionForm').serialize();
            var elem = $(this);
            var elemHtml = $(this).html();
            $('.errorString').remove();
            $('.form-group').removeClass('has-error');
            $.ajax({
                url: $('#base_url').data('base')+'pricing/savePricingOption',
                dataType: 'json',
                data: data,
                type: 'post',
                success: function(data) {
                    if(data.success) {
                        location.reload();
                    } else {
                        pricing.handleFormFail(data.msg, data.error, 'pricingOptionForm');
                    }
                    $('.closeModalButtons').attr('disabled', false);
                },
                error: function() {
                    alertify.error('There was an error saving the form, please try again');
                    elem.html(elemHtml).attr('disabled', false);
                    $('.closeModalButtons').attr('disabled', false);
                },
                beforeSend: function() {
                    elem.html('<i class="fa fa-gear"></i> Saving').attr('disabled', true);
                    $('.closeModalButtons').attr('disabled', true);
                },
                complete: function() {
                    elem.html(elemHtml).attr('disabled', false);
                    $('.closeModalButtons').attr('disabled', false);
                },
            });
        });
    },

    deletePricingOption: function() {
        $('.deleteItem').click(function() {
            var elem = $(this);
            var elemHtml = $(this).html();
            var category = $(this).data('category');
            var name = $(this).data('name');

            alertify.confirm("Are you sure you want to delete this item?", function (e) {
                if (e) {
                    $.ajax({
                        url: $('#base_url').data('base')+'pricing/deletePricingOption',
                        dataType: 'json',
                        data: {category:category, name:name},
                        type: 'post',
                        success: function(data) {
                            if(data.success) {
                                location.reload();
                            } else {
                                alertify.error('There was an error deleting the form, please try again');
                            }
                        },
                        error: function() {
                            alertify.error('There was an error deleting the form, please try again');
                            elem.html(elemHtml).attr('disabled', false);
                        },
                        beforeSend: function() {
                            elem.html('<i class="fa fa-gear"></i>').attr('disabled', true);
                        },
                        complete: function() {
                            elem.html(elemHtml).attr('disabled', false);
                        },
                    });
                }
            });

        });
    },

    optionModalCloseEvent:function() {
        $('#optionModal').on('hidden.bs.modal', function () {
            $('#pricingOptionForm input[name="id"]').val('');
            document.getElementById("pricingOptionForm").reset();
        });
    },

    handleFormFail: function(msg, errors, formId) {
        alertify.error(msg);
        if(errors) {
            for(var i in errors) {
                var field = i;
                var errorTxt = errors[i];
                var errorHtml = '<div class="errorString clearfix text-danger"><i class="fa fa-exclamation-triangle"></i> '+errorTxt+'</div>';
                $('#'+formId+' #'+field).closest('.form-group').addClass('has-error').append(errorHtml);
            }
        }
    },

}

var fabric = {
    modalsCloseEvents: function() {
        $('#editFabricModal').on('hidden.bs.modal', function () {
            $('#editFabricForm input[name="id"]').val('');
            document.getElementById("editFabricForm").reset();
            $('#editFabricForm #deleteFabricImage').removeAttr('data-id');
            $('#editFabricForm .fabricProducts').attr('checked', false);
            $('#editFabricForm #category').val(null).trigger('change');
            $('#fabricPreview').removeClass('hide');
            $('#fabricUpload').removeClass('hide');
        });
    },

    viewFabricsByProduct: function() {
        $('#fabricByProduct').change(function() {
            var selected = $(this).val();
            if(selected) {
                window.location.href = $('#base_url').data('base') + 'fabrics/products/' + selected;
            } else {
                window.location.href = $('#base_url').data('base') + 'fabrics';
            }
        });
    },

    fabricSelection: function() {
        $('.fabricImage').click(function() {
            var id = $(this).data('id');
            if(id) {
                $.ajax({
                    url: $('#base_url').data('base') + 'fabrics/editFabric',
                    type: 'post',
                    dataType: 'json',
                    data: {id: id},
                    success: function (data) {
                        var base = $('#base_url').data('base');
                        $('#previewImg').attr('src', base+'/assets/'+data.fabric.image);
                        $('#name').val(data.fabric.name);
                        $('#active').val(data.fabric.active);
                        $('#category').val(data.fabric.category);
                        $('#featured').val(data.fabric.Featured);
                        $('#deleteFabricImage').attr('data-id', data.fabric.id);
                        $('#id').val(data.fabric.id);

                        if(data.fabric.image) {
                            $('#fabricPreview').removeClass('hide');
                            $('#fabricUpload').addClass('hide');
                            $('#previewImg').attr('src', base+'/assets/'+data.fabric.image);
                        } else {
                            $('#fabricPreview').addClass('hide');
                            $('#fabricUpload').removeClass('hide');
                        }

                        if(data.products) {
                            for(var i in data.products) {
                                $('.fabricProducts[value="'+data.products[i].product_id+'"]').prop('checked', true);
                            }
                        }

                        var fabricCategories = [];
                        if(data.categories) {
                            for(var i in data.categories) {
                                if(data.categories[i].category_id) {
                                    fabricCategories.push(data.categories[i].category_id);
                                }
                            }
                            $('#category').val(fabricCategories).trigger('change');
                        }

                        if(data.fabric.side > 0) {
                            if(data.fabric.side == 1) {
                                $('.fabricSide[value="1"]').prop('checked', true);
                            } else if(data.fabric.side == 2) {
                                $('.fabricSide[value="2"]').prop('checked', true);
                            } else if(data.fabric.side == 3) {
                                $('.fabricSide[value="1"]').prop('checked', true);
                                $('.fabricSide[value="2"]').prop('checked', true);
                            }
                        }


                        $('#editFabricModal').modal('show');
                        $('#editFabricModal select').select2({
                            width: '100%'
                        });
                    },
                    error: function () {
                        alertify.error('There was an error pulling the fabric, try again');
                    },
                    beforeSend: function () {

                    }
                });
            }
        });
    },

    addNewFabric: function() {
        $('.openFabricPanel').click(function() {
            $('#editFabricModal select').select2({
                width: '100%'
            });

            $('#fabricPreview').addClass('hide');
            $('#fabricUpload').removeClass('hide');

            $('#editFabricModal').modal('show');
        })
    },

    deleteFabric: function() {
        $('#deleteFabricImage').click(function(event) {
            event.preventDefault();
            $('#fabricPreview').addClass('hide');
            $('#fabricUpload').removeClass('hide');
        });
    },

    saveFabric: function() {
        $('#saveFabricData').click(function() {

            var products = $('.fabricProducts:checkbox:checked').map(function() {
                return this.value;
            }).get();
            var sides = $('.fabricSide:checkbox:checked').map(function() {
                return this.value;
            }).get();

            var formData = new FormData();
            formData.append('name', $('#editFabricForm #name').val());
            formData.append('active', $('#editFabricForm #active').val());
            formData.append('category', $('#editFabricForm #category').val());
            formData.append('featured', $('#editFabricForm #featured').val());
            formData.append('products', products);
            formData.append('sides', sides);
            formData.append('id', $('#editFabricForm #id').val());
            formData.append('fabric_image', document.getElementById("fabric_image").files[0]);

            $.ajax({
                url: $('#base_url').data('base') + 'fabrics/save-fabric',
                type: 'post',
                dataType: 'json',
                processData: false,
                contentType: false,
                data: formData  ,
                success: function (data) {
                    if(data.success) {
                        alertify.success(data.msg);
                        window.location.href = $('#currentUrl').data('url');
                        $('#editFabricModal').modal('toggle');
                    } else {
                        alertify.error(data.msg);
                    }
                },
                error: function () {
                    alertify.error('There was an error editing the fabric, try refreshing the page and trying again')
                },
            });
        });
    },

    fabricAddCategory: function() {
        $('.addCategory').click(function() {
            var type = $(this).attr('data-type');
            $('#editFabricModal').removeAttr('tabindex');
            alertify.prompt("Add New "+type, function (e, str) {
                if (e) {
                    if(str) {
                        console.log(str);
                        $.ajax({
                            url: $('#base_url').data('base') + 'fabrics/add-fabric-category',
                            type: 'post',
                            dataType: 'json',
                            data: {'category': str},
                            success: function (data) {
                                if(data.success) {
                                    $('#' + type.toLowerCase()).append('<option value="' + data.id + '">' + data.category + '</option>');
                                    alertify.success(data.category + ' Added');
                                } else {
                                    alertify.error('Failed to add category, try again');
                                }
                            },
                            error: function () {
                                alertify.error('There was an error editing the fabric, try refreshing the page and trying again')
                            },
                        });
                    }
                }
            });
        });
    },
}

var product = {

    editProduct: function() {
        $('.editProduct').click(function() {
            var elem = $(this);
            var elemHtml = $(this).html();
            var id = $(this).data('id');
            $('[data-toggle="tooltip"]').tooltip("destroy");
            $.ajax({
                url: $('#base_url').data('base')+'products/getProductById',
                type: 'POST',
                data: {id:id},
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    if(data.success) {
                        $('#name').val(data.data.name);
                        if(data.data.fabrics) {
                            for(var i in data.data.fabrics) {
                                $('#fabrics input[value="'+data.data.fabrics[i]+'"]').prop('checked', true);
                            }
                        }
                        if(data.data.pricing_options) {
                            for(var i in data.data.pricing_options) {
                                $('#pricing_options input[type="checkbox"][value="'+data.data.pricing_options[i]+'"]').prop('checked', true);
                            }
                        }

                        if(data.data.image) {
                            $('#imagePreview').prop('src', $('#base_url').data('base') + 'assets/' + data.data.image);
                            $('#imagePreviewArea').removeClass('hide');
                            $('#image').addClass('hide');
                            $('#deleteProductImage').attr('data-id', data.data.id);
                        } else {
                            $('#imagePreviewArea').addClass('hide');
                            $('#image').removeClass('hide');
                        }
                        $('#id').val(data.data.id);

                        $('#productModal').modal('show');
                    } else {
                        alertify.error(data.msg);
                    }
                    elem.html(elemHtml).prop('disabled', false);
                },
                error: function() {
                    elem.html(elemHtml).prop('disabled', false);
                },
                beforeSend: function() {
                    elem.html('<i class="fa fa-gear fa-spin"></i>').prop('disabled', true);
                }
            });
        });
    },

    deleteProduct: function() {
        $('.deleteProduct').click(function() {
            var productId = $(this).data('id');
            var elem = $(this);
            var elemHtml = $(this).html();
            if(productId) {
                alertify.confirm("Are you sure you want to delete this product?", function (e) {
                    if (e) {
                        var deleteFailMsg = 'There was an error deleting the product, try refreshing the page and try again';
                        $.ajax({
                            url: $('#base_url').data('base') + 'products/deleteProduct',
                            type: 'post',
                            dataType: 'json',
                            data: {id: productId},
                            success: function (data) {
                                if (data.success) {
                                    location.reload();
                                } else {
                                    alertify.error(deleteFailMsg);
                                }
                                elem.html(elemHtml).attr('disabled', false);
                            },
                            error: function () {
                                alertify.error(deleteFailMsg);
                                elem.html(elemHtml).attr('disabled', false);
                            },
                            beforeSend: function() {
                                elem.html('<i class="fa fa-gear fa-spin"></i>').attr('disabled', true);
                            },
                        });
                    }
                });
            }
        });
    },

    saveProductOptions: function() {
        $('#saveProductOptions').click(function() {
            $('.form-group').removeClass('has-error');
            $('.errorString ').remove();
            var formData = new FormData($("#productForm")[0]);
            var elem = $(this);
            var elemHtml = $(this).html();
            $.ajax({
                url: $('#base_url').data('base')+'products/saveProduct',
                type: 'POST',
                data: formData,
                async: false,
                dataType: 'json',
                success: function (data) {
                    if(data.success) {
                        location.reload();
                    } else {
                        pricing.handleFormFail(data.msg, data.error, 'productForm');
                    }
                    elem.html(elemHtml).attr('disabled', false);
                    $('.closeModalButtons').attr('disabled', false);
                },
                error: function() {
                    elem.html(elemHtml).attr('disabled', false);
                    $('.closeModalButtons').attr('disabled', false);
                },
                beforeSend: function() {
                    elem.html('<i class="fa fa-gear fa-spin"></i> Saving').attr('disabled', true);
                    $('.closeModalButtons').attr('disabled', true);
                },
                cache: false,
                contentType: false,
                processData: false,
            });

        });
    },

    deleteProductImage: function() {
        $('#deleteProductImage').click(function() {
            var id = $(this).attr('data-id');
            var elem = $(this);
            var elemHtml = $(this).html();
            $.ajax({
                url: $('#base_url').data('base')+'products/deleteProductImage',
                type: 'POST',
                data: {id:id},
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    if(data.success) {
                        $('#imagePreviewArea').addClass('hide');
                        $('#image').removeClass('hide');
                        alertify.success(data.msg);
                    } else {
                        alertify.error(data.msg);
                    }
                    elem.html(elemHtml).attr('disabled', false);
                },
                error: function() {
                    elem.html(elemHtml).attr('disabled', false);
                },
                beforeSend: function() {
                    elem.html('<i class="fa fa-gear fa-spin"></i> Removing').attr('disabled', true);
                }
            });
        });
    },

    addNewProduct: function() {
        $('#newProduct').click(function() {
            document.getElementById("productForm").reset();
            $('#productForm input[name="id"]').val('');
            $('#productForm input[name="name"]').val('');
            $('input[type="checkbox"]').attr('checked', false);
            $('#imagePreview').attr('src', '');
            $('#imagePreviewArea').addClass('hide');
            $('#image').removeClass('hide');
            $('#deleteProductImage').removeAttr('data-id');
        });
    },

    modalCloseEvents: function() {
        $('#productModal').on('hidden.bs.modal', function () {
            document.getElementById("productForm").reset();
            $('#productForm input[name="id"]').val('');
            $('#productForm input[name="name"]').val('');
            $('#imagePreview').attr('src', '');
            $('#imagePreviewArea').addClass('hide');
            $('#image').removeClass('hide');
            $('#deleteProductImage').removeAttr('data-id');
            $('input:checkbox').removeAttr('checked');
        });
    },

}

var cart = {
    base_url: $('#base_url').data('base'),

    setImageCanvas: function(imgBase, imgPattern) {
        var canvas = document.createElement('canvas');
        canvas.width = 250;
        canvas.height = 250;

        var canvas_context = canvas.getContext("2d");

        var img = new Image();
        img.onload = function(){
            var msk = new Image();
            msk.onload = function(){

                // var ptrn = canvas_context.createPattern(img, 'repeat');
                // canvas_context.fillStyle = ptrn;
                // canvas_context.fillRect(0, 0, canvas.width, canvas.height);

                canvas_context.drawImage(img, 0, 0);
                canvas_context.globalCompositeOperation = "destination-in";

                canvas_context.drawImage(msk, 30, 30);
                canvas_context.globalCompositeOperation = "source-over";
            };

            msk.src = $('#base_url').data('base')+imgBase;
        }

        img.src = $('#base_url').data('base')+imgPattern;


        $('#itemPreview').html(canvas);
    }
}