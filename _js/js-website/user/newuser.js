addAction('controller_user', 'newuser', {
    run: function() {
        $("#newUser").validate(aFormValidate);
    },
});