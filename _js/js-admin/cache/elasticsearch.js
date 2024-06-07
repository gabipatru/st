addAction('controller_admin_cache', 'elasticsearch', {
    run: function() {
        console.log('here elastic');
        $( ".js-delete" ).each(function () {
            $( this ).click(function() {
                return confirm('Are you sure you want to delete this item ?');
            });
        });
    },
});