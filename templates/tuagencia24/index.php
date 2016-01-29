<?php

defined( '_JEXEC' ) or die( 'Restricted access' );
include_once (dirname(__FILE__).DS.'/scripts/layout.php');

?>
<!doctype html>
<html>
<head>
    <jdoc:include type="head" />
</head>
<?php
//MICOD:
//Sistema de seguridad para bloquear el clic derecho del mouse y la tecla F12 del teclado
$url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
$buscar = "soto2";
$confirmar_soto = strpos($url, $buscar);

if ($confirmar_soto != false) {
	?>
	<body oncontextmenu="return false" onkeydown="return false">
	<?php
}else{
	?>
	<body>
	<?php
}
?>

	<!--[if lt IE 9]>
        <div id="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</div>
    <![endif]-->

	<?php if ($this->countModules('menu-tools')) : ?>
		<section id="box-menu-tools">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div id="menu-tools">
							<jdoc:include type="modules" name="menu-tools" style="none" />
						</div>
					</div>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<div id="box-page">
		<div class="container">
			
			<header id="box-header">
				
				<?php if ($this->countModules('logo') || $this->countModules('info')) : ?>
					<div class="row border-dashed">
						
						<?php if ($this->countModules('logo')) : ?>
							<div class="<?php echo $col_logo; ?>">
								<div id="logo">
									<jdoc:include type="modules" name="logo" style="none" />
								</div>
							</div>
						<?php endif; ?>

						<?php if ($this->countModules('info')) : ?>
							<div class="<?php echo $col_info; ?>">
								<div id="info">
									<jdoc:include type="modules" name="info" style="none" />
								</div>
							</div>
						<?php endif; ?>

					</div>
				<?php endif; ?>

				<?php if ($this->countModules('menu')) : ?>
					<div class="row">
						<div class="col-12 full">
							<div id="menu">
								<jdoc:include type="modules" name="menu" style="none" />
							</div>
						</div>
					</div>
				<?php endif; ?>
			</header>

			<jdoc:include type="message" />

			<?php if ($this->countModules('top-a')) : ?>
				<section id="box-top-a">
					<div class="row">
						<div class="col-12">
							<div id="top-a">
								<jdoc:include type="modules" name="top-a" style="xhtml" />
							</div>
						</div>
					</div>
				</section>
			<?php endif; ?>

			<?php if ($this->countModules('top-b') || $this->countModules('top-c')) : ?>
				<section id="box-top-bc">
					<div class="row">
						
						<?php if ($this->countModules('top-b')) : ?>
							<div class="<?php echo $col_top_b; ?>">
								<div id="top-b">
									<jdoc:include type="modules" name="top-b" style="xhtml" />
								</div>
							</div>
						<?php endif; ?>

						<?php if ($this->countModules('top-c')) : ?>
							<div class="<?php echo $col_top_c; ?>">
								<div id="top-c">
									<jdoc:include type="modules" name="top-c" style="xhtml" />
								</div>
							</div>
						<?php endif; ?>

					</div>
				</section>
			<?php endif; ?>

			<section id="box-main">
				<div class="row">
					
					<?php if ($this->countModules('side-a')) : ?>
						<div class="<?php echo $col_side_a; ?>">
							<div id="side-a">
								<jdoc:include type="modules" name="side-a" style="xhtml" />
							</div>
						</div>
					<?php endif; ?>

					<div class="<?php echo $col_main; ?>">
						<div id="main">
							
							<?php if ($this->countModules('main-top')) : ?>
								<div class="row">
									<div class="col-12 full">
										<div id="main-top">
											<jdoc:include type="modules" name="main-top" style="xhtml" />
										</div>
									</div>
								</div>
							<?php endif; ?>

							<div class="row">
								<div class="col-12 full">
									<div id="main-content">
										<jdoc:include type="component" />
									</div>
								</div>
							</div>
							
							<?php if ($this->countModules('main-bottom')) : ?>
								<div class="row">
									<div class="col-12 full">
										<div id="main-bottom">
											<jdoc:include type="modules" name="main-bottom" style="xhtml" />
										</div>
									</div>
								</div>
							<?php endif; ?>

						</div>
					</div>

					<?php if ($this->countModules('side-b')) : ?>
						<div class="<?php echo $col_side_b; ?>">
							<div id="side-b">
								<jdoc:include type="modules" name="side-b" style="xhtml" />
							</div>
						</div>
					<?php endif; ?>

				</div>
			</section>

			<?php if ($this->countModules('bottom-b') || $this->countModules('bottom-c')) : ?>
				<section id="box-bottom-bc">
					<div class="row">
						
						<?php if ($this->countModules('bottom-b')) : ?>
							<div class="<?php echo $col_bottom_b; ?>">
								<div id="bottom-b">
									<jdoc:include type="modules" name="bottom-b" style="xhtml" />
								</div>
							</div>
						<?php endif; ?>

						<?php if ($this->countModules('bottom-c')) : ?>
							<div class="<?php echo $col_bottom_c; ?>">
								<div id="bottom-c">
									<jdoc:include type="modules" name="bottom-c" style="xhtml" />
								</div>
							</div>
						<?php endif; ?>

					</div>
				</section>
			<?php endif; ?>

			<?php if ($this->countModules('bottom-a')) : ?>
				<section id="box-bottom-a">
					<div class="row">
						<div class="col-12">
							<div id="bottom-a">
								<jdoc:include type="modules" name="bottom-a" style="xhtml" />
							</div>
						</div>
					</div>
				</section>
			<?php endif; ?>

		</div>
	</div>

	<?php if ($this->countModules('menu-footer') || $this->countModules('info-footer') || $this->countModules('footer')) : ?>
		<footer id="box-footer">
			<div class="container">

				<?php if ($this->countModules('menu-footer') || $this->countModules('info-footer')) : ?>
					<div class="row">
						
						<?php if ($this->countModules('menu-footer')) : ?>
							<div class="<?php echo $col_menu_footer; ?>">
								<div id="menu-footer">
									<jdoc:include type="modules" name="menu-footer" style="none" />
								</div>
							</div>
						<?php endif; ?>
						
						<?php if ($this->countModules('info-footer')) : ?>
							<div class="<?php echo $col_info_footer; ?>">
								<div id="info-footer">
									<jdoc:include type="modules" name="info-footer" style="none" />
								</div>
							</div>
						<?php endif; ?>

					</div>
				<?php endif; ?>

				<?php if ($this->countModules('footer')) : ?>
					<div class="row">
						<div class="col-12">
							<div id="footer">
								<jdoc:include type="modules" name="footer" style="none" />
							</div>
						</div>
					</div>
				<?php endif; ?>

			</div>
		</footer>
	<?php endif; ?>

	<pre style="display:none;">
	<?php var_dump($user); ?>
	</pre>

</body>
</html>