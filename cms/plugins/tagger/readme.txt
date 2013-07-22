== NOTE:

 The page by type Tagger should be created for plugin to work propely.
 The content of this page is presented bellow:
  <?php $pages = $this->tagger->pagesByTag(); ?>
	<?php if($pages): ?>
		<h3>Here is pages by tag "<?php echo $this->tagger->tag(); ?>"</h3>
	        <ul>
		<?php foreach ($pages as $page): ?>
			<li><a href="<?php echo $page->url; ?>"><?php echo $page->title; ?></a></li>
		<?php endforeach ?>
	        </ul>
	<?php else: ?>
		<h3>There are no pages with tag "<?php echo $this->tagger->tag(); ?>"</h3>
	<?php endif ?>


== LICENSE:

 Frog CMS - Content Management Simplified. <http://www.madebyfrog.com>
 Copyright (C) 2008 Philippe Archambault <philippe.archambault@gmail.com>
 
 This file is part of Frog CMS.

 Frog CMS is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 Frog CMS is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Frog CMS.  If not, see <http://www.gnu.org/licenses/>.

 Frog CMS has made an exception to the GNU General Public License for plugins.
 See exception.txt for details and the full text.
