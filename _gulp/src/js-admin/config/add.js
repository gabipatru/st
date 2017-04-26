addAction('controller_admin_config', 'add', {
    run: function() {
        $("#addForm").validate(aFormValidate);
        
        $("#typeSelect").change(this.typeSelect);
    },
    
    typeSelect: function() {
        if ($("#typeSelect").val() == 'textarea') {
            $("#div-textarea").show();
            $("#config-textarea").attr("disabled", false);
            
            $("#div-text").hide();
            $("#config-text").attr("disabled", true);
            
            $("#div-yesno").hide();
            $("#config-yesno").attr("disabled", true);
        }
        if ($("#typeSelect").val() == 'text') {
            $("#div-textarea").hide();
            $("#config-textarea").attr("disabled", true);
            
            $("#div-text").show();
            $("#config-text").attr("disabled", false);
            
            $("#div-yesno").hide();
            $("#config-yesno").attr("disabled", true);
        }
        if ($("#typeSelect").val() == 'yesno') {
            $("#div-textarea").hide();
            $("#config-textarea").attr("disabled", true);
            
            $("#div-text").hide();
            $("#config-text").attr("disabled", true);
            
            $("#div-yesno").show();
            $("#config-yesno").attr("disabled", false);
        }
    }
});