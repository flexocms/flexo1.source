NOTE
=====================

The page by type Tagger should be created for plugin to work propely.
The content of this page is presented bellow:
```php
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
```
For showing tags of the page with links at list of other pages wish such a tag to add:
```php
<?php echo tags_list($this->tags()); ?>
```
