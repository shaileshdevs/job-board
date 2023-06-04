document.addEventListener('DOMContentLoaded', function() {
    var file_upload_buttons = document.querySelectorAll('.jb-file-upload-button');
    var fileRemoveButtons   = document.querySelectorAll('.jb-file-remove-button');
    var gallery;

    file_upload_buttons.forEach(function(file_upload_button) {
        file_upload_button.addEventListener('click', function( e ) {
            e.preventDefault();
            // if( gallery ){
            //     gallery.open();
            //     return;
            // }

            let file_upload_field_wrapper = this.closest('.file-upload-field-wrapper');
            let file_url_input = file_upload_field_wrapper.querySelector('.file_url_input');
            let file_id_input = file_upload_field_wrapper.querySelector('.file_id_input');

            gallery = wp.media.frames.image_gallery = wp.media( {
                title: 'Choose File',
                button: {
                    text: 'Add File'
                },
                // library: {
                //     type: 'file'
                // },
                // state: 'file'
            } );
            gallery.on('select', function(){
                var selection     = gallery.state().get('selection');
                var i = 0;
                var attachmentIds = [];
                selection.map( function( attachment ) {
                    file_id_input.value  = attachment['id'];
                    file_url_input.value = attachment.attributes.url;
                });
            });
            gallery.open();

        });
    });

    fileRemoveButtons.forEach(function(file_remove_button) {
        file_remove_button.addEventListener('click', function() {
            let file_upload_field_wrapper = this.closest('.file-upload-field-wrapper');
            let file_id_input             = file_upload_field_wrapper.querySelector('.file_id_input');
            let file_url_input            = file_upload_field_wrapper.querySelector('.file_url_input');
            
            file_id_input.value  = '0';
            file_url_input.value = '';
        });
    });
});
