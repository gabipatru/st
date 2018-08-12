addAction('controller_admin_surprises', 'edit', {
    run: function() {
        $("#editForm").validate(aFormValidate);
    }
});