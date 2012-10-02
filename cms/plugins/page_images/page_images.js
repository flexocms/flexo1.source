// Add page inits for pages page/edit, page/add
cms.init.add(['page_edit', 'page_add'], function()
{	
	$('#PIAddButton').click(function()
	{
		if (cms.plugins.uploader == undefined)
			cms.error('Plugin page_images reuire plugin files_manager!');
		
		var uploader_callback_handler = function(files)
		{
			if (files.length > 0)
			{
				for(var i=0; i<files.length; i++)
				{
					$('#PIList').append('<li rel="0"><img class="pi-image" src="'+PUBLIC_URL+'page_images/80x80-'+files[i].name+'" alt="'+files[i].name+'" title="'+files[i].name+'" /><a class="pi-remove-link" href="'+CMS_URL + ADMIN_DIR_NAME+'/plugin/page_images/delete/'+files[i].image_id+'" title="'+ __('Remove') +'"><img src="images/remove.png" /></a></li>');
				}
			}
		};
		
		var page_id = parseInt( $('#PIPlugin').attr('rel') );
		
		cms.plugins.uploader({
			upload_url: CMS_URL + ADMIN_DIR_NAME + '/plugin/page_images/upload',
			multiple: true,
			folder:   'page_images',
			callback: uploader_callback_handler,
			params: {
				page_id: page_id
			}
		});
		
		return false;
	});
	
	$('#PIList .pi-remove-link').live('click', function()
	{
		cms.loader.show();
		
		var link_el = $(this);
		
		link_el.parent().css('opacity', 0.5);
		
		$.ajax({
			url: $(this).attr('href'),
			success: function(data)
			{
				cms.loader.hide();
				
				link_el.parent().remove();
			},
			error: function()
			{
				alert(__('Image not removed!'));
			}
		});
		
		return false;
	});
	
}); // end cms.init.add page_edit, page_add