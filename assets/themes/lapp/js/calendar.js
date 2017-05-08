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

    viewEvent: function() {
        $('.event-list .event').click(function() {
            var id = $(this).data('id');
            var elem = $(this);
            var elemText = $(this).html();
            $.ajax({
                url: $('#baseUrl').data('base')+'/calendar/view-event',
                data: {event: id},
                type: 'post',
                dataType: 'json',
                success: function(data) {
                    if(data.success) {
                        $('#deleteEventBtn').attr('data-id', data.data['id']);
                        $('#view-event .modal-title').html('<i class="fa fa-calendar-o"></i> '+data.data['name']);
                        $('#view-event .modal-body').html('<p>'+data.data['description']+'</p>');

                        if(data.data.all_day == 1) {
                            $('#view-event .modal-body').append('<br><div class="alert alert-success" style="padding: 8px 15px">All Day Event starts at '+data.data['start_time']+'</div>');
                        } else {
                            var html = '<div class="well well-sm" style="margin:0"><div class="row">';
                            html += '<div class="col-xs-6">';
                            html += '<p style="margin:0"><i class="fa fa-clock-o"></i> <b>Start:</b> ' + data.data['start']+'</p>';
                            html += '</div>';
                            html += '<div class="col-xs-6">';
                            html += '<p style="margin:0"><i class="fa fa-clock-o"></i> <b>Ends:</b> ' + data.data['end'] + '</p>';
                            html += '</div>';
                            html += '</div></div>';

                            if(data.data['link_to_form'] != '') {
                                html += '<hr><p>'+data.data['link_to_form']+'</p>';
                            }

                            $('#view-event .modal-body').append(html);
                        }
                        $('#view-event').modal('show');
                    } else {
                        alertify.error(data.msg);
                    }
                },
                error: function() {
                    $(elem).html(elemText);
                },
                beforeSend: function() {
                    $(elem).html('<i class="fa fa-gear fa-spin"></i> Loading Event');
                },
                complete: function() {
                    $(elem).html(elemText);
                }
            });
        });
    },

    deleteEvent: function() {
        $('#deleteEventBtn').click(function() {
            var id = $(this).data('id');
            var elem = $(this);
            var elemText = $(this).html();
            $.ajax({
                url: $('#baseUrl').data('base')+'/calendar/delete-event',
                data: {event: id},
                type: 'post',
                dataType: 'json',
                success: function(data) {
                    if(data.success) {
                        location.reload();
                    } else {
                        alertify.error(data.msg);
                    }
                },
                error: function() {
                    $(elem).html(elemText);
                },
                beforeSend: function() {
                    $(elem).html('<i class="fa fa-gear fa-spin"></i> Deleting Event');
                },
                complete: function() {
                    $(elem).html(elemText);
                }
            });
        });
    },


}

$(function() {
    calendar.addDateInput();
    calendar.saveCalendarEvent();
    calendar.viewEvent();
    calendar.deleteEvent();
});