$(document).ready(function() {
    $('.collapse').on('shown.bs.collapse', function () {
        $(this).parent().find(".fa-caret-right").removeClass("fa-caret-right").addClass("fa-caret-down");
    }).on('hidden.bs.collapse', function () {
        $(this).parent().find(".fa-caret-down").removeClass("fa-caret-down").addClass("fa-caret-right");
    });


    $('[data-toggle="tooltip"]').tooltip();

});

var shop = {
    init: function() {
        $('#loading').hide();
        shop.addToCart();
        shop.updateShopcartQty();
        shop.showCartButton();
        shop.sameAsShipping();
        shop.noSubmit();
        shop.discountCode();
        shop.removeItemFromCart();
        shop.removeAlerts();
        shop.submitPayment();
    },

    loadPeronalizationFonts: function() {
        var base = $('#base_url').data('base');
        $("head").append('<link href="' + base + 'assets/themes/minky-moon/css/fonts.css" rel="stylesheet" type="text/css">');
    },

    setUserSetting: function(elemId, setting) {
        if(elemId && setting) {
            $('#' + elemId).find('.priceSelected').removeClass('hide').html('<i class="fa fa-check-circle"></i> ' + setting);
        }
    },

    ajaxCall: function (url, callback, data) {
        data.product_type = $('#productType').data('type');

        var sendData = {'data': data};
        $.ajax({
            url: $('#base_url').data('base')+url,
            dataType: 'json',
            data: sendData,
            type: 'post',
            success: function(data) {
                if(data.success) {
                    if(data.msg) {
                        alertify.success(data.msg);
                    }
                    shop.calculatePrice(data.product);
                    callback(data);
                } else {
                    alertify.error(data.msg);
                    $('#loading').hide();
                }
            },
            beforeSend: function() {
                $('#loading').show();
            },
            error: function() {
                alertify.error('There was an error processing the request, try again');
                $('#loading').hide();
            },
            complete: function() {
                $('#loading').hide();
            },
        });
    },

    openNextPanel: function() {
        $('.panel-collapse.in').parent().next().find('.panel-heading a').trigger('click');
    },

    calculatePrice: function (product) {
        if(product) {
            var price = 0;
            for(var i in product) {
                var currentOption = product[i];
                if($.type(currentOption) == 'object') {
                    if(currentOption.hasOwnProperty('price')) {
                        if(currentOption.price > 0) {
                            price = price+parseFloat(currentOption.price);
                        }
                    } else if(i == 'line') {
                        for(var c in currentOption) {
                            if(currentOption[c].hasOwnProperty('price')) {
                                if(currentOption[c].price > 0) {
                                    price = price+parseFloat(currentOption[c].price);
                                }
                            }
                        }
                    }
                }
            }
            if(price>0) {
                $('#currentPrice').removeClass('hide').html('$'+price);
            } else {
                $('#currentPrice').addClass('hide').html('');
            }
        }
    },

    addToCart: function() {
        $('.addToCart').click(function() {
            var data = {
                'model':        $('#productType').attr('data-type'),
                'type':         'add',
            };
            shop.ajaxCall('shop/ajax-call', shop.itemAdded, data);
        });
    },

    itemAdded: function(data) {
        if(data.success) {
            window.location.replace($('#base_url').data('base')+'shop/my-cart');
        } else {
            alertify.error(data.msg);
        }
    },

    capitalizeFirstLetter: function(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    },

    updateShopcartQty: function() {
        $('.updateCart').click(function() {
            var data = [];
            var update = false;
            $('.qtyInput').each(function() {
                if($(this).attr('data-qty') != $(this).val()) {
                    data.push( {
                        rowid : $(this).attr('data-id'),
                        qty : $(this).val(),
                    });
                    update = true;
                }
            });

            if(update) {
                var data = {
                    'products':         data,
                    'type':             'update',
                    'model':            'Shop',
                };
                shop.ajaxCall('shop/ajax-call', shop.cartUpdated, data);
            }
        });
    },

    cartUpdated: function(data) {
        window.location.replace($('#base_url').data('base')+'shop/my-cart');
    },

    showCartButton: function() {
        if($('.itemsInCart').length > 0) {
           $('body').append('<a href="'+$('#base_url').attr('data-base')+'shop/my-cart" data-toggle="tooltip" data-title="Go to my cart" class="btn btn-primary shoppingCartButton"><i class="fa fa-shopping-cart"></i></a>');
            $('[data-toggle="tooltip"]').tooltip();
        }
    },

    sameAsShipping: function() {
        $('#sameAsShipping').click(function() {
            $(this).attr('checked', false);
            $('#billing-full-name').val($('#full-name').val());
            $('#billing-address-line1').val($('#address-line1').val());
            $('#billing-address-line2').val($('#address-line2').val());
            $('#billing-city').val($('#city').val());
            $('#billing-state').val($('#shipping-state').val());
            $('#billing-zip').val($('#shipping-zip').val());

            alertify.success('Shipping details copied to billing details');
        });
    },

    noSubmit: function() {
        $('#giftCard, #submitPayment').click(function(event) {
            event.preventDefault();
        });
    },

    discountCode: function() {
        $('#applyDiscount').click(function() {
            var code = $('#discountCode').val();
            if(code) {
                $.ajax({
                    url: $('#base_url').data('base')+'shop/apply-discount-code',
                    dataType: 'json',
                    data: {code: code},
                    type: 'post',
                    success: function (data) {
                        if (data.success) {
                            location.reload();
                        } else {
                            alertify.error(data.msg);
                        }
                    },
                    beforeSend: function () {
                        $('#loading').show();
                    },
                    error: function () {
                        alertify.error('There was an error processing the request, try again');
                        $('#loading').hide();
                    },
                    complete: function () {
                        $('#loading').hide();
                    },
                });
            } else {
                alertify.error('The code field is required');
            }
        });
    },

    removeItemFromCart: function() {
        $('.removeItemFromCart').click(function() {
            var id = $(this).attr('data-id');

            var data = {
                'cart_id':          id,
                'type':             'remove',
                'model':            'Shop',
            };
            shop.ajaxCall('shop/ajax-call', shop.cartUpdated, data);
        });
    },

    removeAlerts: function() {
        setTimeout(function() {
            $('.alert').slideUp();
        }, 5000);
    },

    submitPayment: function() {
        $('#submitPayment').click(function(event) {
           event.preventDefault();
           var data = $('#paymentForm').serialize();
           $('.errorTxt').remove();
           $('.has-error').removeClass('has-error');
           $.ajax({
                url: $('#base_url').data('base')+'shop/submit-payment',
                dataType: 'json',
                data: data,
                type: 'post',
                success: function (data) {
                    if (data.success) {

                    } else {
                        alertify.error(data.msg);
                        shop.processFormErrors(data.data);
                    }
                },
                beforeSend: function () {
                    $('#loading').show();
                },
                error: function () {
                    alertify.error('There was an error processing the request, try again');
                    $('#loading').hide();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });
    },

    processFormErrors: function (errors) {
        if(errors) {
            for(var i in errors) {
                $('[name='+i+']').closest('.control-group').addClass('has-error');
                $('[name='+i+']').parent().append('<p class="text-danger errorTxt"><i class="fa fa-exclamation-triangle"></i> '+errors[i]+'</p>');
            }
        }
    },

}

var pillows = {
    init: function() {
        $('#loading').hide();
        shop.loadPeronalizationFonts();
    },
}

var minkies = {
    nameSetting: '',
    elemId: '',

    init: function() {
        $('#loading').hide();
        minkies.sizeSelection();
        minkies.trimSelection();
        minkies.onTextPersonizationChange();
        minkies.onFontChange();
        minkies.onFontColorChange();
        minkies.setLineNumberEntries();
        minkies.fabricSelection();
        shop.addToCart();
        shop.showCartButton();
    },

    sizeSelection: function () {
        $('.sizeSelection').click(function() {
            if($(this).hasClass('.selectedOption')) {return false;}
            minkies.nameSetting = $(this).attr('data-name');
            minkies.elemId = $(this).closest('.panel').attr('id');
            var data = {
                'pricing_id':       $(this).attr('data-id'),
                'product_id':  $('#productType').attr('data-productid'),
                'type': 'size',
                'model': 'Minkies',
            };
            shop.ajaxCall('shop/ajax-call', minkies.selectionProcessed, data);
        });
    },

    trimSelection: function () {
        $('.trimSelection').click(function() {
            if($(this).hasClass('.selectedOption')) {return false;}

            minkies.nameSetting = $(this).attr('data-name');
            minkies.elemId = $(this).closest('.panel').attr('id');

            var data = {
                'pricing_id':       $(this).attr('data-id'),
                'product_id':       $('#productType').attr('data-productid'),
                'type':             'trim',
                'model':            'Minkies',
            };
            shop.ajaxCall('shop/ajax-call', minkies.selectionProcessed, data);
        });
    },

    fabricSelection: function () {
        $('.fabricSelection').click(function() {
            if($(this).hasClass('.selectedOption')) {return false;}

            minkies.nameSetting = $(this).attr('data-name');
            minkies.elemId = $(this).closest('.panel').attr('id');

            var data = {
                'pricing_id':       $(this).attr('data-id'),
                'product_id':       $(this).attr('data-productid'),
                'type':             'fabric',
                'model':            'Minkies',
                'section':          $(this).attr('data-section'),
            };
            shop.ajaxCall('shop/ajax-call', minkies.selectionProcessed, data);
        });
    },

    selectionProcessed: function (data) {
        switch(data.post.type) {
            case 'size':
                minkies.setSizeOption(data);
                break;
            case 'back':
                // do something
                console.log('back found');
                break;
            case 'trim':
                minkies.setTrimOption(data);
                break;
            case 'accolide':
                console.log('do something');
                // do something
                break;
            case 'font':
                console.log('Font Set');
                break;
            case 'font-color':
                console.log('font-color');
                break;
            case 'line':
                // do something
                minkies.setLineOption(data);
                break;
            case 'fabric':
                minkies.setFabricSelection(data);
                // do something
                break;
            default:
                alertify.error('Selection option not set, try again');
                break;
        }
        $('#loading').hide();
    },

    setFabricSelection: function(data) {
        if(data.post.type == 'fabric' && data.product.trim  != null && data.product.trim.name != 'None' && data.product.trim_fabric.name != '' && data.post.section == 'trim') {
            shop.openNextPanel();
        } else if(data.product.trim == null) {
            return false;
        } else {
            $('#'+shop.capitalizeFirstLetter(data.post.section)+'Fabric li').removeClass('selectedOption');
            $('#'+shop.capitalizeFirstLetter(data.post.section)+'Fabric').find('[data-id="'+data.post.pricing_id+'"]').addClass('selectedOption');
            shop.setUserSetting(shop.capitalizeFirstLetter(data.post.section)+'Fabric', data.product[data.post.section+'_fabric'].name);
            shop.openNextPanel();
        }
    },

    setSizeOption: function(data) {
        $('.sizeSelection').removeClass('selectedOption');
        $('.sizeSelection[data-id="'+data.post.pricing_id+'"]').addClass('selectedOption');
        shop.setUserSetting(minkies.elemId, minkies.nameSetting);
        $('#itemSize').html('<b>'+data.product.size.name+' '+data.product.size.size+'</b>');
        shop.openNextPanel();
    },

    setTrimOption: function(data) {
        $('.trimSelection').removeClass('selectedOption');
        $('.trimSelection[data-id="'+data.post.pricing_id+'"]').addClass('selectedOption');
        shop.setUserSetting(minkies.elemId, minkies.nameSetting);
        if(data.post.type == 'trim' && data.product.trim.name == 'None') {
            shop.openNextPanel();
        }
    },

    onTextPersonizationChange: function() {
        $('.personalizationLines').change(function() {
            var data = {
                line:             $(this).attr('data-line'),
                value:             $.trim($(this).val()),
                pricing_id:       $(this).attr('data-id'),
                type:             'line',
                model:            'Minkies',
            };
            shop.ajaxCall('shop/ajax-call', minkies.selectionProcessed, data);
        });
    },

    setLineNumberEntries: function() {
        $('#textLine1').on('focus', function() {
            if($('#textLine0').val() == "") {
                $('#textLine0').focus();
            }
        });

        $('#textLine2').on('focus', function() {
            if($('#textLine1').val() == "") {
                $('#textLine1').focus();
            }
        });
        $('#textLine0').on('change', function() {
            if($('#textLine1').val() != "") {
                $('#textLine1').val('').trigger('change');
            }
            if($('#textLine2').val() != '') {
                $('#textLine2').val('').trigger('change');
            }
        });
    },

    setLineOption: function () {
        var count = 0;
        $('.personalizationLines').each(function() {
            var text = $(this).val() != '' ? $.trim($(this).val()) : '';
            if(text != '') {
               count++;
            }
        });

        if(count > 0) {
            $('#personalization').find('.priceSelected').removeClass('hide').html('<i class="fa fa-check-circle"></i> ' + count + ' Lines');
        } else {
            $('#personalization').find('.priceSelected').removeClass('hide').html('<i class="fa fa-check-circle"></i> None');
        }
    },

    onFontChange: function() {
        $('#customFont').change(function() {
            var data = {
                'pricing_id':       0,
                'product_id':       0,
                'type':             'font',
                'model':            'Minkies',
                'value':            $(this).val()
            };
            shop.ajaxCall('shop/ajax-call', minkies.selectionProcessed, data);
        });
    },

    onFontColorChange: function() {
        $('.fontColorSelection').click(function() {
            if(!$(this).hasClass('selectedFont')) {
                $('.fontColorSelection').removeClass('selectedFont');
                $(this).addClass('selectedFont');
                var data = {
                    'pricing_id': 0,
                    'product_id': 0,
                    'type': 'font-color',
                    'model': 'Minkies',
                    'value': $(this).attr('data-name')
                };
                shop.ajaxCall('shop/ajax-call', minkies.selectionProcessed, data);
            }
        });
    },

}

var burprags = {
    init: function() {
        $('#loading').hide();
    }
}

var account = {
    init: function() {
        account.createAccount();
        account.login();
    },

    createAccount: function() {
        $('#createAccountBtn').click(function(event) {
            event.preventDefault();
            var form = $('#createAccount').serialize();

            var data = {
                'model': 'Account',
                'type': 'createAccount',
                'data': {
                    'email' : $('#createAccount [name="email"]').val(),
                    'password' : $('#createAccount [name="password"]').val(),
                    'password2' : $('#createAccount [name="password2"]').val(),
                },
            };

            shop.ajaxCall('shop/ajax-call', account.processForm, data);
        });
    },

    login: function() {
        $('#loginButton').click(function(event) {
            event.preventDefault();

            var data = {
                'model': 'Account',
                'type': 'login',
                'data': {
                    'email' : $('#loginForm [name="email"]').val(),
                    'password' : $('#loginForm [name="password"]').val(),
                },
            };

            shop.ajaxCall('shop/ajax-call', account.processForm, data);
        });
    },

    processForm: function(data) {
        if(data.success) {
            location.reload();
        }
    }
}
