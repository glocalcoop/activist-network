/*jslint regexp: true, confusion: true, undef: true, sloppy: true, eqeq: true, vars: true, white: true, plusplus: true, maxerr: 50, indent: 4 */
var gdbbPressAttachments = {
    storage: {
        files_counter: 1
    },
    init: function() {
        jQuery("form#new-post").attr("enctype", "multipart/form-data");
        jQuery("form#new-post").attr("encoding", "multipart/form-data");

        jQuery(document).on("click", ".d4p-attachment-addfile", function(e){
            e.preventDefault();

            if (gdbbPressAttachments.storage.files_counter < gdbbPressAttachmentsInit.max_files) {
                jQuery(this).before('<input type="file" size="40" name="d4p_attachment[]"><br/>');
                gdbbPressAttachments.storage.files_counter++;
            }

            if (gdbbPressAttachments.storage.files_counter == gdbbPressAttachmentsInit.max_files) {
                jQuery(this).remove();
            }
        });
    }
};

jQuery(document).ready(function() {
    gdbbPressAttachments.init();
});
