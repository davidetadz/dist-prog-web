$(document).ready(function () {

    email_field = $('#email');
    password_field = $('#password');

    email_field.change(function () {
       if (!checkEmail($(this).val())) {
           this.setCustomValidity('Inserisci un indirizzo email valido.');
       }
       else this.setCustomValidity('');
    });

    password_field.change(function () {
        if (!checkPassword($(this).val())) {
            this.setCustomValidity('La password deve avere almeno una lettera minuscola, ed una lettera maiuscola o un numero');
        }
        else this.setCustomValidity('');
    });

    $('#register-container form').submit(function (e) {

        var valid = true;

        // Check email and password validity
        if (!checkEmail(email_field.val())) {

            valid = false;
        }

        if (!checkPassword(password_field.val())) {

            valid = false;
        }

        /* Prevent registration if not valid */
        if (!valid)
            e.preventDefault();
    });

});