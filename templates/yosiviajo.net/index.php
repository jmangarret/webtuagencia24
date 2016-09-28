<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
<head>
<link rel="stylesheet" href="templates/system/css/general.css" type="text/css" />
<link rel="stylesheet" href="templates/<?php echo $this->template ?>/css/template.css" type="text/css" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,300' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Oswald:400,300,700' rel='stylesheet' type='text/css'>
<jdoc:include type="head" />
<?php if($_SERVER['QUERY_STRING']=='e'){
$application = JFactory::getApplication();
$application->enqueueMessage(JText::_('SOME_ERROR_OCCURRED'), 'error');
}?>
</head>

<body>
   <!--Start header-->
   <div id="MainHeader">

		<?php if($this->countModules('menuUsuarios')) : ?>
		<div class="MainGlobal">
			<div class="Cont">
				<div class="menuUsuarios">
					<jdoc:include type="modules" name="menuUsuarios" style="xhtml" />
					<div class="clear"></div>
				</div>
				<div class="clear"></div>
			</div>
		</div>
		<?php endif; ?>

		<?php if($this->countModules('logotipo or areaSuperior')) : ?>
		<div class="MainLogo">
			<div class="Cont">

				<?php if($this->countModules('logotipo')) : ?>
				<div class="Logo">
					<jdoc:include type="modules" name="logotipo" style="xhtml" />
				</div>
				<?php endif; ?>

				<?php if($this->countModules('areaSuperior')) : ?>
				<div class="MailSuscription">
					<jdoc:include type="modules" name="areaSuperior" style="xhtml" />
				</div>
				<?php endif; ?>
				<div class="clear"></div>
			</div>
		</div>
		<?php endif; ?>

		<?php if($this->countModules('menuPrincipal or buscador')) : ?>
		<div class="menuAndSearch">
			<div class="Cont">

				<?php if($this->countModules('menuPrincipal')) : ?>
				<div class="MainMenu">
					<jdoc:include type="modules" name="menuPrincipal" style="xhtml" />
				</div>
				<?php endif; ?>

				<?php if($this->countModules('buscador')) : ?>
				<div class="MainSearch">
					<jdoc:include type="modules" name="buscador" style="xhtml" />
				</div>
				<?php endif; ?>
				<div class="clear"></div>
			</div>
		</div>
		<?php endif; ?>
		<div class="clear"></div>
   </div>
   <!--End Header header-->

   <!--Start Messages and Breadcrums-->
   <div id="mainSystem">
		<div class="mainBreadCrums">
		<?php if($this->countModules('migaDePan')) : ?>
			<jdoc:include type="modules" name="migaDePan" style="xhtml" />
		<?php endif; ?>
		</div>
		<div class="mainSystemMessages">
			<jdoc:include type="message" />
		</div>
		<div class="clear"></div>
   </div>
   <!--End Messages and Breadcrums-->

   <!--Start Slider and QuickSearch-->

	<?php if($this->countModules('quickSearch or destacados')) : ?>
	<div id="SliderAndQuickSearch">
		<div class="Cont">
		<?php if($this->countModules('quickSearch')) : ?>
		<div class="MainQuickSearch">
			<jdoc:include type="modules" name="quickSearch" style="xhtml" />
		</div>
		<?php endif; ?>

		<?php if($this->countModules('destacados')) : ?>
		<div class="mainSlider">
			<jdoc:include type="modules" name="destacados" style="xhtml" />
		</div>
		<?php endif; ?>
		</div>
              <div class="clear"></div>
    </div>
	<?php endif; ?>

   <!--End Slider and QuickSearch-->

   <!--Start Content page-->
   <div id="MainContent">

		<div class="Cont">

			<?php if($this->countModules('ofertas or destinos')) { ?>
			<div class="modulesHome">

				<?php if($this->countModules('ofertas')) : ?>
				<div class="mainOfertas">
					<jdoc:include type="modules" name="ofertas" style="xhtml" />
				</div>
				<?php endif; ?>

				<?php if($this->countModules('destinos')) : ?>
				<div class="mainDestinos">
					<jdoc:include type="modules" name="destinos" style="xhtml" />
				</div>
				<?php endif; ?>

			</div>
			<?}else{?>

			<!--Start inside pages-->
			<div class="MainComponent">

				<?php if($this->countModules('derecha')  &&  !$this->countModules('izquierda')) { ?>
				<!--Start only right activate-->
				<div class="compAndModLeft">

					<?php if($this->countModules('extrasArriba')) : ?>
					<div class="mainExtrasUp">
						<jdoc:include type="modules" name="extrasArriba" style="xhtml" />
				    </div>
					<?php endif; ?>

					<div class="componentLeft">
						<jdoc:include type="component" />
					</div>

					<?php if($this->countModules('extrasAbajo')) : ?>
					<div class="mainExtrasBelow">
						<jdoc:include type="modules" name="extrasAbajo" style="xhtml" />
				    </div>
					<?php endif; ?>

				</div>

				<?php if($this->countModules('derecha')) : ?>
				<div class="ModulesRight">
					<jdoc:include type="modules" name="derecha" style="xhtml" />
				</div>
				<?php endif; ?>
				<!--End only right activate-->
				<?php } ?>

				<?php if($this->countModules('izquierda')  &&  !$this->countModules('derecha')) { ?>
				<!--Start only left activate-->
				<?php if($this->countModules('izquierda')) { ?>
				<div class="ModulesLeft">
					<jdoc:include type="modules" name="izquierda" style="xhtml" />
				</div>
				<?php } ?>

				<div class="compAndModRight">
					<?php if($this->countModules('extrasArriba')) { ?>
					<div class="mainExtrasUp">
						<jdoc:include type="modules" name="extrasArriba" style="xhtml" />
					</div>
					<?php } ?>

					<div class="componentRight">
						<jdoc:include type="component" />
					</div>

					<?php if($this->countModules('extrasAbajo')) : ?>
					<div class="mainExtrasBelow">
						<jdoc:include type="modules" name="extrasAbajo" style="xhtml" />
					</div>
					<?php endif; ?>

				</div>
				<!--End only left activate-->
				<?php }?>

				<?php if($this->countModules('izquierda')  &&  $this->countModules('derecha')) { ?>
				<!--Star left and right activate-->

				<?php if($this->countModules('izquierda')) : ?>
				<div class="ModulesLeft">
					<jdoc:include type="modules" name="izquierda" style="xhtml" />
				</div>
				<?php endif; ?>

				<div class="compAndModRightCenter">
					<?php if($this->countModules('extrasArriba')) : ?>
					<div class="mainExtrasUp">
						<jdoc:include type="modules" name="extrasArriba" style="xhtml" />
					</div>
					<?php endif; ?>

					<div class="componentCenter">
						<jdoc:include type="component" />
					</div>

					<?php if($this->countModules('derecha')) : ?>
					<div class="ModulesRight">
						<jdoc:include type="modules" name="derecha" style="xhtml" />
					</div>
					<?php endif; ?>

					<?php if($this->countModules('extrasAbajo')) : ?>
					<div class="mainExtrasBelow">
						<jdoc:include type="modules" name="extrasAbajo" style="xhtml" />
					</div>
					<?php endif; ?>

				</div>
				<!--End left and right activate-->
				<?php } ?>

				<?php if(!$this->countModules('izquierda')  &&  !$this->countModules('derecha')) { ?>
				<!--Start left and right off-->
				<div class="compAndModFull">
					<?php if($this->countModules('extrasArriba')) : ?>
					<div class="mainExtrasUp">
						<jdoc:include type="modules" name="extrasArriba" style="xhtml" />
					</div>
					<?php endif; ?>

					<div class="componentFull">
						<jdoc:include type="component" />
						<div class="clear"></div>
					</div>

					<?php if($this->countModules('extrasAbajo')) : ?>
					<div class="mainExtrasBelow">
						<jdoc:include type="modules" name="extrasAbajo" style="xhtml" />
					</div>
					<?php endif; ?>
					<div class="clear"></div>
				</div>
				<!--End left and right off-->
				<?php } ?>
			</div>
			<!--End inside pages-->
			<?php }?>

			<?php if($this->countModules('publicidad1 or publicidad2')) : ?>
			<div class="mainAdds">
				<?php if($this->countModules('publicidad1')) : ?>
				<div class="Add1">
					<jdoc:include type="modules" name="publicidad1" style="xhtml" />
				</div>
				<?php endif; ?>

				<?php if($this->countModules('publicidad2')) : ?>
				<div class="Add1">
					<jdoc:include type="modules" name="publicidad2" style="xhtml" />
				</div>
				<?php endif; ?>
			</div>
			<?php endif; ?>
			<div class="clear"></div>
		</div>


   </div>
   <!--End Content page-->

   <!--Start Footer-->
   <div id="MainFooter">

		<div class="Cont">
			<?php if($this->countModules('piePagina1')) : ?>
			<div class="mainMiscellaneous">
				<jdoc:include type="modules" name="piePagina1" style="xhtml" />
				<div class="clear"></div>
			</div>
			<?php endif; ?>
			<div class="clear"></div>
		</div>

		<div class="Cont">
			<?php if($this->countModules('piePagina2')) : ?>
			<div class="mainMiscellaneous">
				<jdoc:include type="modules" name="piePagina2" style="xhtml" />
				<div class="clear"></div>
			</div>
			<?php endif; ?>
			<div class="clear"></div>
		</div>

		<div class="Cont">
		<div class="menuAndSocial">
			<?php if($this->countModules('piePagina3')) : ?>
			<div class="mainMenuFooter">
				<jdoc:include type="modules" name="piePagina3" style="xhtml" />
				<div class="clear"></div>
			</div>
			<?php endif; ?>

			<?php if($this->countModules('social')) : ?>
			<div class="mainSocial">
				<jdoc:include type="modules" name="social" style="xhtml" />
				<div class="clear"></div>
			</div>
			<?php endif; ?>
			<div class="clear"></div>
		</div>
		</div>

		<div class="Cont">
		<div class="SyndicateAndCopy">
			<?php if($this->countModules('syndicate')) : ?>
			<div class="mainSyndicate">
				<jdoc:include type="modules" name="syndicate" style="xhtml" />
				<div class="clear"></div>
			</div>
			<?php endif; ?>

			<div class="Copy">
				© 2013 Todos los derechos reservados
			</div>
		</div>
		</div>
	</div>

   <!--End Footer-->
</body>
</html>