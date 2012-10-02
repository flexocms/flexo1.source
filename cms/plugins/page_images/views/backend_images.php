<?php if (!defined('CMS_ROOT')) die; ?>

<div id="PIPlugin" rel="<?php echo $page_id; ?>" class="plugin">
	<ul id="PIList">
		<?php foreach( $images as $item ): ?>
		<li rel="<?php echo $item->id; ?>">
			<img class="pi-image" src="<?php echo $item->url(80, 80); ?>" alt="<?php echo $item->file_name; ?>" title="<?php echo $item->file_name; ?>" />
			<a class="pi-remove-link" href="<?php echo get_url('plugin/page_images/delete/' . $item->id); ?>" title="<?php echo __('Remove'); ?>"><img src="images/remove.png" /></a>
		</li>
		<?php endforeach; ?>
	</ul>
	
	<button id="PIAddButton" role="button"><img src="images/add.png" /> <?php echo __('Add images'); ?></button>
</div>