addAction('controller_user', 'reset_password', {
    run: function() {
        $("#reset_passwd").validate(aFormValidate);
    },
});