var apiURL = './api.php';
var getPlaneStatus = 'REQ_PLANE_STATUS';
var bookSeat = 'REQ_BOOK_SEAT';
var freeSeat = 'REQ_FREE_SEAT';
var buySeat = 'REQ_BUY_SEATS';

var plane_width = 0, plane_length = 0;

var selected = [];
var free_n = 0, booked_n = 0, bought_n = 0, total_n = 0;

// Callback on document completely loaded
$(document).ready(function () {

    // Request plane size
    $.ajax(apiURL, {
        dataType: 'json',
        data: {
            'method': 'GET',
            'request': getPlaneStatus
        },
        method: 'POST',
        success: function (res) {
            if (res.status === 200) {
                // Response OK - get data
                plane_width = res.data.plane_width;
                plane_length = res.data.plane_length;

                var seats_notfree = res.data.seats_status;

                total_n = plane_width * plane_length;

                // Create table in page and bind click event
                createSeatsMap(plane_length, plane_width, seats_notfree, selected);

                updateCounters();

                // Bind functions only if user logged in
                if ($('#seats-map').hasClass('auth')) {
                    $('#seats-map p:not(.bought)').click(seatSelected);
                }
            } else {
                alert("Errore nell'aggiornamento della mappa dei posti. I dati visualizzati potrebbero non essere aggiornati.")
            }
        },
        complete: function (jqXHR, textStatus) {
            if (textStatus !== "success")
                alert("Errore nell'aggiornamento della mappa dei posti. I dati visualizzati potrebbero non essere aggiornati.")
        }
    });

});

function seatSelected() {
    var req;
    var elem = $(this);

    if ($(this).hasClass('selected')) {
        req = freeSeat;
    } else req = bookSeat;

    /* Return index of element's column (relative to its row)
        (1-based because first column is list of numbers)
     */
    var col_i = $(this)[0].col;

    /* Return index of row
        (1-based because first row is list of letters)
    */
    var row_i = $(this)[0].row;

    // P tag for displaying status of last operation
    var status_text = $('#status');

    var seat_name = String.fromCharCode('A'.charCodeAt(0) + col_i - 1) + row_i;

    // Book selected seat or free if booked
    $.ajax(apiURL, {
        dataType: 'json',
        data: {
            'method': 'POST',
            'request': req,
            'row': row_i,
            'col': col_i
        },
        method: 'POST',
        success: function (res) {
            if (res.status === 200) {
                // Response OK - get data
                elem.status = res.data.result;
                switch (res.data.result) {
                    case 0:
                        // Posto prenotato correttamente
                        elem.attr("class", "selected");
                        booked_n++;
                        status_text.text("Posto " + seat_name + " prenotato correttamente.");
                        selected.push(seat_name);
                        break;
                    case 1:
                        // Posto liberato correttamente
                        elem.attr("class", "");
                        booked_n--;
                        status_text.text("Posto " + seat_name + " liberato correttamente.");
                        var index = selected.indexOf(seat_name);
                        if (index !== -1) selected.splice(index, 1);
                        break;
                    case 2:
                        // Posto prenotato da un altro utente
                        elem.attr("class", "booked");
                        status_text.text("Posto " + seat_name + " liberato correttamente.");
                        booked_n++;
                        var index = selected.indexOf(seat_name);
                        if (index !== -1) selected.splice(index, 1);
                        break;
                    case 3:
                        // Posto acquistato da un altro utente
                        elem.attr("class", "bought");
                        status_text.text("Posto " + seat_name + " acquistato da un altro utente.");
                        elem.unbind("click");
                        bought_n++;
                        var index = selected.indexOf(seat_name);
                        if (index !== -1) selected.splice(index, 1);
                        break;
                    default:
                        alert("Errore nella prenotazione del posto. I dati visualizzati potrebbero non essere aggiornati.");
                        break;
                }
                console.log(selected);
                updateCounters();
            } else if (res.status === 401) {
                alert("Sessione scaduta. Effettua nuovamente il login");
                document.location.reload()
            } else {
                alert("Errore nella prenotazione del posto. I dati visualizzati potrebbero non essere aggiornati.");
            }
        },
        complete: function (jqXHR, textStatus) {
            if (jqXHR.responseJSON.status === 401) {
                alert("Sessione scaduta. Effettua nuovamente il login");
                document.location.reload();
            } else if (textStatus !== "success")
                alert("Errore nella prenotazione del posto. I dati visualizzati potrebbero non essere aggiornati.")
        }
    });
}

function buySeats() {

    if (selected.length < 1) {
        alert("Prenota almeno un posto per poter acquistare.");
        return;
    }

    $.ajax(apiURL, {
        dataType: 'json',
        data: {
            'method': 'POST',
            'request': buySeat,
            'seats': selected
        },
        method: 'POST',
        success: function (res) {
            if (res.status === 200) {
                // Response OK - get data
                // Check if seats bought
                if (res.data.bought === 1) {
                    alert("Posti " + selected + " acquistati!");
                } else {
                    alert("Impossibile acquistare i posti selezionati!");
                }
                // Refresh the page
                window.location.reload();
            }
        },
        complete: function (jqXHR, textStatus) {
            if (jqXHR.responseJSON.status === 401) {
                alert("Sessione scaduta. Effettua nuovamente il login");
                document.location.reload();
            } else if (textStatus !== "success")
                alert("Errore nell'acquisto dei posti. I dati visualizzati potrebbero non essere aggiornati.")
        }
    });
}

function updateCounters() {
    free_n = total_n - (booked_n + bought_n);

    $('#total-n').text(total_n);
    $('#booked-n').text(booked_n);
    $('#bought-n').text(bought_n);
    $('#free-n').text(free_n);
}