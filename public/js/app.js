document.addEventListener('DOMContentLoaded', function () {
    var form = document.querySelector('form[data-validate]');
    if (!form) return;

    form.addEventListener('submit', function (ev) {
        var email = form.querySelector('[name="email"]');
        if (email && email.value.indexOf('@') === -1) {
            ev.preventDefault();
            alert('Informe um e-mail válido.');
            email.focus();
        }
    });
});
