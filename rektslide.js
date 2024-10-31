var thisbutton = "";
jQuery(document).ready(function() {
	jQuery('.photo_upload_button').click(function() {
		thisbutton = this.id;
		formfield = jQuery('#textbox_'+thisbutton).attr('name');
		tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
		return false;
	});

	window.send_to_editor = function(html) {
		imgurl = jQuery('img',html).attr('src');
		jQuery('#textbox_'+thisbutton).val(imgurl);
		jQuery('#image_'+thisbutton).attr('src', imgurl);
		tb_remove();
	}
});