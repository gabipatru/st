addAction('controller_admin_config', 'add', {
    run: function() {
        $("#addForm").validate(aFormValidate);
        
        $("#typeSelect").change(this.typeSelect);
    },
    
    typeSelect: function() {
        if ($("#typeSelect").val() == 'textarea') {
            $("#div-textarea").show();
            $("#div-text").hide();
            $("#div-yesno").hide();
        }
        if ($("#typeSelect").val() == 'text') {
            $("#div-textarea").hide();
            $("#div-text").show();
            $("#div-yesno").hide();
        }
        if ($("#typeSelect").val() == 'yesno') {
            $("#div-textarea").hide();
            $("#div-text").hide();
            $("#div-yesno").show();
        }
    }
});