addAction('controller_admin_dashboard', 'stats', {
    _construct: function() {
        console.log('Javascript constructor');
    },
    
    run: function() {
        console.log('Javascript run');
    },

    _destruct: function() {
        console.log('Javascript destructor');
    }
});