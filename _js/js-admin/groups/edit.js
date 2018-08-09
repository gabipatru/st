addAction('controller_admin_groups', 'edit', {
    run: function() {
        $("#editForm").validate(aFormValidate);
    }
});