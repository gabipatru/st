addAction('controller_user', 'login', {
    run: function() {
        $("#login").validate(aFormValidate);
    },
});