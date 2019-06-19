function createSeatsMap(n_rows, n_cols, seats_status, booked_seats) {
    var mapContainer = document.getElementById('seats-map');
    var i, j;

    // Create header row with first cell empty
    var thead = document.createElement("tr");
    var cell = document.createElement("th");
    thead.appendChild(cell);

    // Header contains seats number
    for (j = 0; j < n_cols; j++) {
        var cell = document.createElement("th");
        cell.innerText = String.fromCharCode('A'.charCodeAt(0) + j);
        thead.appendChild(cell);
    }
    mapContainer.appendChild(thead);

    // Create grid of seats
    for (i = 1; i < n_rows + 1; i++) {
        var row = document.createElement("tr");

        // First cell contains letter
        var fcell = document.createElement("td");
        fcell.innerText = i;
        row.appendChild(fcell);

        for (j = 1; j < n_cols + 1; j++) {
            var cell = document.createElement("td");
            var content = document.createElement("p");
            content.row = i;
            content.col = j;
            content.status = -1;

            var seat_name = String.fromCharCode('A'.charCodeAt(0) + j - 1) + i;

            // Seat status
            if (i in seats_status && j in seats_status[i])
                switch (seats_status[i][j]) {
                    case 0:
                        // Posto prenotato dall'utente corrente
                        $(content).attr("class", "selected");
                        content.status = 0;
                        booked_n++;
                        booked_seats.push(seat_name);
                        break;
                    case 2:
                        // Posto prenotato da un altro utente
                        $(content).attr("class", "booked");
                        content.status = 2;
                        booked_n++;
                        break;
                    case 3:
                        // Posto acquistato da un utente
                        $(content).attr("class", "bought");
                        content.status = 3;
                        bought_n++;
                        break;
                    default:
                        alert("Errore nella prenotazione del posto. I dati visualizzati potrebbero non essere aggiornati.");
                        break;
                }

            cell.appendChild(content);
            row.appendChild(cell);
        }

        // Last cell contains letter
        var lcell = document.createElement("td");
        lcell.innerText = i;
        row.appendChild(lcell);

        mapContainer.appendChild(row);
        updateCounters();
    }
}

function checkPassword(password) {

    var n_re = /([a-z]+)/;
    var c_re = /([0-9A-Z]+)/;

    /* At least one number and at least one char (upper or lower case)
    *  so with both expressions we should find at least one match */
    return password.match(n_re) != null && password.match(c_re) != null;

}

/* Check email address validity
*   It should be
*   - One or more characters
*   - @ (at) symbol
*   - One or more characters and . (dot) symbol - one or more times
*   - Two to four characters (top level domain) */
function checkEmail(email) {

    // As per the HTML5 Specification with one correction - required also domain with dot
    // (so @localhost or @something won't work)
    var email_re = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)+$/;

    return email.match(email_re) != null;

}