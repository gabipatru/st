addAction('controller_website', 'contact', {
    run: function() {
        if (typeof(aFormValidate) != 'undefined') {
            $("#contact-form").validate(aFormValidate);
        }
    },
});