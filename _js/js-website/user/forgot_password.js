addAction('controller_user', 'forgot_password', {
    run: function() {
        $("#forgot_passwd").validate(aFormValidate);
    },
});