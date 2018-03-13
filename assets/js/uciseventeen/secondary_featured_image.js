jQuery(document).ready(function ($) {

  // Uploading files
  var file_frame;

  jQuery.fn.upload_secondary_image = function (button) {
    var button_id = button.attr('id');
    var field_id = button_id.replace('_button', '');

    // If the media frame already exists, reopen it.
    if (file_frame) {
      file_frame.open();
      return;
    }

    // Create the media frame.
    file_frame = wp.media.frames.file_frame = wp.media({
      title: button.data('uploader_title'),
      button: {
        text: button.data('uploader_button_text'),
      },
      multiple: false
    });

    // When an image is selected, run a callback.
    file_frame.on('select', function () {
      var attachment = file_frame.state().get('selection').first().toJSON();
      jQuery("#" + field_id).val(attachment.id);
      jQuery("#secondaryimgdiv img").attr('src', attachment.url);
      jQuery('#secondaryimgdiv img').show();
      jQuery('#' + button_id).attr('id', 'remove_secondary_image_button');
      jQuery('#remove_secondary_image_button').text('Remove secondary image');
    });

    // Finally, open the modal
    file_frame.open();
  };

  jQuery('#secondaryimgdiv').on('click', '#upload_secondary_image_button', function (event) {
    event.preventDefault();
    jQuery.fn.upload_secondary_image(jQuery(this));
  });

  jQuery('#secondaryimgdiv').on('click', '#remove_secondary_image_button', function (event) {
    event.preventDefault();
    jQuery('#upload_secondary_image').val('');
    jQuery('#secondaryimgdiv img').attr('src', '');
    jQuery('#secondaryimgdiv img').hide();
    jQuery(this).attr('id', 'upload_secondary_image_button');
    jQuery('#upload_secondary_image_button').text('Set secondary image');
  });

});