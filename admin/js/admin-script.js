jQuery(document).ready(function ($) {
    var galleryUploader;

    $('#upload_gallery_images_button').on('click', function (e) {
        e.preventDefault();

        if (galleryUploader) {
            galleryUploader.open();
            return;
        }

        galleryUploader = wp.media({
            title: 'Select Images',
            multiple: true,
            library: {
                type: 'image'
            },
            button: {
                text: 'Insert Images'
            }
        });

        galleryUploader.on('select', function () {
            var attachments = galleryUploader.state().get('selection').toJSON();

            $.each(attachments, function (index, attachment) {
                // Gambar kecil (<= ukuran thumbnail) tidak punya sizes.thumbnail
                var url = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;
                $('#gallery_images_list').append('<div class="grid-item"><img src="' + url + '" alt="Gallery Image"><input type="hidden" name="gallery_images[]" value="' + parseInt(attachment.id, 10) + '"><span class="remove-gallery-image">X</span></div>');
            });
        });

        galleryUploader.open();
    });

    $(document).on('click', '.remove-gallery-image', function () {
        $(this).parent('.grid-item').remove();
    });
});