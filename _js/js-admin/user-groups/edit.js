addAction('controller_admin_user_groups', 'edit', {
    run: function() {
        $("#editForm").validate(aFormValidate);
    }
});