<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />

		<title><?= $this->title() ?> | <?= Setting::get('admin_title') ?></title>

		<link href="<?= PUBLIC_URL ?>themes/demo/favicon.ico" rel="favourites icon" />
		<link href="<?= PUBLIC_URL ?>themes/demo/stylesheets/layout.css" media="screen" rel="stylesheet" type="text/css" charset="utf-8" />
	</head>
	<body>
		
		<div id="layout">
			<header id="header">
				<div class="logo">
					<a href="<?php echo get_url(); ?>"><em><?php echo Setting::get('admin_title'); ?></em></a>
					<small>Light flexible content management system</small>
				</div>
			</header>
			
			<nav id="nav">
				<a href="<?= get_url() ?>" <?= !$this->slug ? 'class="current"': '' ?> >Home</a>
				
				<?php foreach ($this->find('/')->children() as $item): ?>
					<?= $item->link(null, null, true) ?>
				<?php endforeach ?>
			</nav>
			
			<div id="middler">
				<aside id="sidebar">
					
					<?= $this->content('sidebar', true) ?>
					
				</aside>
				<section id="content">
					
					<?php if ($this->slug): ?> 
						<?= $this->breadcrumbs(' &raquo; ') ?>
					<?php endif ?>
					
					<h1><?= $this->title() ?></h1>
					
					<?= $this->content() ?>
					
				</section>
			</div>
			
			<footer id="footer">
				<p class="copyrights">© Flexo CMS demonstration site, 2010‒2011</p>
				<p class="madeby">Made by <a href="http://flexo.up.dn.ua/">Flexo CMS</a></p>
			</footer>
		</div>
		
	</body>
</html>
