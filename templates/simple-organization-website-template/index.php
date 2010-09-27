<?php defined('_JOOS_CORE') or die(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">

	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
		<?php
		echo Jdocument::head();

		Jdocument::getInstance()
				->addCSS(JPATH_SITE . '/templates/simple-organization-website-template/css/style.css', array('media' => 'all'))
				->addCSS(JPATH_SITE . '/templates/simple-organization-website-template/css/pagenav.css');

		echo Jdocument::stylesheet();
		?>
	</head>

	<body>

		<div id="site-wrapper">

			<div id="header">

				<div id="top">

					<div class="left" id="logo">
						<a href="index.html"><img src="img/logo.gif" alt="" /></a>
					</div>

					<div class="left navigation" id="main-nav">

						<ul class="tabbed">
							<li class="current-tab"><a href="<?php echo Jroute::href('blog') ?>">Блог</a></li>
							<li><a href="<?php echo Jroute::href('news') ?>">Новости</a></li>
							<li><a href="<?php echo Jroute::href('pages_view', array('page_name' => 'about')) ?>">Про нас</a></li>
							<li><a href="#">Archives</a></li>
							<li><a href="#">Join Us!</a></li>
						</ul>

						<div class="clearer">&nbsp;</div>

					</div>

					<div class="clearer">&nbsp;</div>

				</div>

				<div class="navigation" id="sub-nav">

					<ul class="tabbed">
						<li><a href="index.html">Template Info</a></li>
						<li><a href="style-demo.html">Style Demo</a></li>
						<li><a href="comments.html">Comments</a></li>
						<li><a href="archives.html">Archives</a></li>
						<li><a href="no-subnavigation.html">No Subnavigation</a></li>
						<li><a href="left-sidebar.html">Left Sidebar</a></li>
						<li><a href="single-column.html">Single Column</a></li>
						<li class="current-tab"><a href="empty-page.html">Empty Page</a></li>
					</ul>

					<div class="clearer">&nbsp;</div>

				</div>

			</div>


			<div id="splash">

				<div class="col3 left">

					<h2 class="label label-green">Mission Statement</h2>

					<p class="quiet large">What we want to achieve</p>

					<p>Vestibulum eu pellentesque ante. Sed tincidunt quam eu nisl luctus id mattis tellus rhoncus.</p>
					<p>Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae. Donec dapibus eros vitae nibh venenatis faucibus.</p>
					<p><a href="#" class="more">Learn more &raquo;</a></p>

				</div>

				<div class="col3-mid left">

					<h2 class="label label-orange">Next Event</h2>

					<p class="quiet large">Friday, August 18, 2009</p>

					<p><img src="images/sample-event.jpg" width="240" height="80" alt="" class="bordered" /></p>
					<p><em>Aliquam augue neque, rhoncus et dictum in, cursus eget mauris.</em></p>

				</div>

				<div class="col3 right">

					<h2 class="label label-blue">Мы на Twitter</h2>

					<p class="quiet large">http://twitter.com/joostinacms</p>

					<p>Nulla mollis sollicitudin nulla et mattis.<span class="quiet">(2 hours ago)</span></p>
					<p>Torquent per conubia nostra, per inceptos himenaeos. <span class="quiet">(2 hours ago)</span></p>
					<p>In sed ante at velit hendrerit blandit a et nibh. Cras sed cursus nulla. <span class="quiet">(3 hours ago)</span></p>
					<p>Nullam vitae mi at nulla blandit. <span class="quiet">(5 hours ago)</span></p>

				</div>

				<div class="clearer">&nbsp;</div>

			</div>

			<div class="main" id="main-two-columns">

				<div class="left" id="main-content">
					<?php echo Jdocument::body(); ?>
				</div>

				<div class="right sidebar" id="sidebar">

			Empty Sidebar

				</div>

				<div class="clearer">&nbsp;</div>

			</div>

			<div id="footer">

				<div class="left" id="footer-left">

					<img src="img/logo-small.gif" alt="" class="left" />

					<p>&copy; 2002-2009 Simple Organization. All rights Reserved</p>

					<p class="quiet"><a href="http://templates.arcsin.se/">Website template</a> by <a href="http://arcsin.se/">Arcsin</a></p>

					<div class="clearer">&nbsp;</div>

				</div>

				<div class="right" id="footer-right">

					<p class="large"><a href="#">Blog</a> <span class="text-separator">|</span> <a href="#">Events</a> <span class="text-separator">|</span> <a href="#">About</a> <span class="text-separator">|</span> <a href="#">Archives</a> <span class="text-separator">|</span> <strong><a href="#">Join Us!</a></strong> <span class="text-separator">|</span> <a href="#top" class="quiet">Page Top &uarr;</a></p>

				</div>

				<div class="clearer">&nbsp;</div>

			</div>

		</div>
		<?php
					Jdocument::getInstance()
							->addJS(JPATH_SITE . '/media/js/jquery.js', array('first' => true));

					echo Jdocument::javascript();
					echo Jdocument::footer();
		?>
	</body>
</html>