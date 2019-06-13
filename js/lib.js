function createSeatsMap(n_rows, n_cols) {
    let mapContainer = document.getElementById('seats-map');
    let i, j;

    // Create header row with first cell empty
    let thead = document.createElement("tr");
    let cell = document.createElement("th");
    thead.appendChild(cell);

    // Header contains seats number
    for (j = 0; j < n_cols; j++){
        let cell = document.createElement("th");
        cell.innerText = String.fromCharCode('A'.charCodeAt(0) + j);
        thead.appendChild(cell);
    }
    mapContainer.appendChild(thead);

    // Create grid of seats
    for (i = 0; i < n_rows; i++){
        let row = document.createElement("tr");

        // First cell contains letter
        let fcell = document.createElement("td");
        fcell.innerText = i + 1;
        row.appendChild(fcell);

        for (j = 0; j < n_cols; j++){
            let cell = document.createElement("td");
            let content = document.createElement("p");
            cell.appendChild(content);
            row.appendChild(cell);
        }

        // Last cell contains letter
        let lcell = document.createElement("td");
        lcell.innerText = i + 1;
        row.appendChild(lcell);

        mapContainer.appendChild(row);
    }
}

function checkPassword(password) {
    const n_re = /([a-z]+)/;
    const c_re =  /([0-9A-Z]+)/;

    /* At least one number and at least one char (upper or lower case)
    *  so with both expressions we should find at least one match */
    return password.match(n_re) != null && password.match(c_re) != null;
}