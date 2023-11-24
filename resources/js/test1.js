$('#searchButton').click(function () {

    let area = $('input[id="area"]').val();

    $.ajax({
        url: '/api/workers:search',
        type: 'POST',
        dataType: 'json',
        data: {
            area: area,
        },
        beforeSend: function () {
            $('#searchButton').prop('disabled', true);
        },
        success: function (data) {
            showModal(data);
        },
        error: function (data) {
            showModal(data.responseJSON);
        },
        complete: function () {
            $('#searchButton').prop('disabled', false);
        }
    });
});
