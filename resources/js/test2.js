let timeFields = [
    $('#timeFrom'),
    $('#timeTo'),
];

timeFields.forEach(function (selector) {
    selector.timepicker({
        'timeFormat': 'H:i',
        'className': 'form-control',
        'step': '30',
    });
})

$.ajaxSetup({
    beforeSend: function () {
        $('#addTimeslot').prop('disabled', true);
    },
    complete: function () {
        $('#addTimeslot').prop('disabled', false);
    },
})

$('#addTimeslot').click(function () {
    let timeFrom = $('input[id="timeFrom"]').val();
    let timeTo = $('input[id="timeTo"]').val();

    let interval = null;

    if (timeFrom && timeTo) {
        interval = timeFrom + '-' + timeTo;
    }

    $.post('/api/timeslots', {
        timeInterval: interval,
    }).done(function (data) {
        showModal(data);
    }).fail(function (data) {
        showModal(data.responseJSON);
    });
});
