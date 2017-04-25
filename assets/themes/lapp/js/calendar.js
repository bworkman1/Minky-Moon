var calendar = {


    saveCalendarEvent: function() {
        $('#addEventBtn').click(function() {
            var inputs = $('#addEvent').serialize();
            var elem = $(this);
            var elemText = $(this).html();
            $.ajax({
                url: $('#baseUrl').data('base')+'/calendar/add-event',
                data: inputs,
                type: 'post',
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    if(data.success) {
                        alertify.success(data.msg);
                        window.location.replace($('#baseUrl').data('base')+'calendar/'+data.data['redirect']);
                    } else {
                        alertify.error(data.msg);
                    }
                },
                error: function() {

                },
                beforeSend: function() {
                    $(elem).attr('disabled', true).html('<i class="fa fa-gear fa-spin"></i> Adding');
                },
                complete: function() {
                    $(elem).attr('disabled', false).html(elemText);
                }
            });
        });
    },

    addDateInput: function() {
        $('input[name="start"]').daterangepicker({
            timePicker: true,
            timePickerIncrement: 15,
            locale: {
                format: 'MM/DD/YYYY h:mm A'
            }
        });
    },


}

$(function() {
    calendar.addDateInput();
    calendar.saveCalendarEvent();
});