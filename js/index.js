const apiURL = '/api.php';
const getPlaneSize = 'REQ_PLANE_SIZE';

let plane_width = 0, plane_length = 0;

let selected = [];
let free_n = 0, booked_n = 0, bought_n = 0, total_n = 0;

// Callback on document completely loaded
$(document).ready(function () {

    // Request plane size
    $.ajax(apiURL, {
        dataType: 'json',
        data: {
            'method': 'GET',
            'request': getPlaneSize
        },
        method: 'POST',
        success: function (res) {
            if (res.status === 200) {
                // Response OK - get data
                plane_width = res.data.plane_width;
                plane_length = res.data.plane_length;

                total_n = plane_width * plane_length;

                // Create table in page and bind click event
                createSeatsMap(plane_length, plane_width);

                updateCounters();

                // TODO: Do not bind event if user not logged in
                $('#seats-map p').click(seatSelected);
            }
        }
    });

});

function seatSelected() {
    if ($(this).hasClass('selected'))
        booked_n--;
    else booked_n++;
    $(this).toggleClass('selected');
    updateCounters();

    /* Return index of element relative to row
        (1-based because first row is list of numbers)
     */
    let col_i = $(this).parent().index();

    /* Return index of row
        (1-based because first col is list of letters)
    */
    let row_i = $(this).parent().parent().index();
}

function updateCounters() {
    $('#total-n').text(total_n);
    $('#booked-n').text(booked_n);
    $('#bought-n').text(bought_n);
    $('#free-n').text(total_n - (booked_n + bought_n));
}