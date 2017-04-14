addAction('controller_admin_cache', 'memcached', {
    run: function() {
        $("#memcacheFlush").validate(aFormValidate);
        $("#memcacheAllFlush").submit(function(){
            return confirm('Are you sure you want to flush memcached ?');
        });
    },
});