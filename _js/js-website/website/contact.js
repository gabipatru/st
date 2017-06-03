addAction('controller_website', 'contact', {
    run: function() {
        $("#contact-form").validate(aFormValidate);
    },
});