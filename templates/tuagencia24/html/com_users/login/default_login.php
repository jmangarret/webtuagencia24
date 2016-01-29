<?php
/**
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.5
 */

defined('_JEXEC') or die;
JHtml::_('behavior.keepalive');
?>

<div class="row">
<div class="col-6">
<div class="login <?php echo $this->pageclass_sfx?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
	<h1>
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
	<?php endif; ?>

	<?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
	<div class="login-description">
	<?php endif ; ?>

		<?php if($this->params->get('logindescription_show') == 1) : ?>
			<?php echo $this->params->get('login_description'); ?>
		<?php endif; ?>

		<?php if (($this->params->get('login_image')!='')) :?>
			<img src="<?php echo $this->escape($this->params->get('login_image')); ?>" class="login-image" alt="<?php echo JTEXT::_('COM_USER_LOGIN_IMAGE_ALT')?>"/>
		<?php endif; ?>

	<?php if (($this->params->get('logindescription_show') == 1 && str_replace(' ', '', $this->params->get('login_description')) != '') || $this->params->get('login_image') != '') : ?>
	</div>
	<?php endif ; ?>
	
    <div class="loginBox">
	<h2>
		Iniciar sesión en Buscador SOTO de <br><br>TuAgencia24.com
	</h2>
	<form action="<?php echo JRoute::_('index.php?option=com_users&task=user.login'); ?>" method="post">

		
		<!--FORMULARIO DONDE ESTÁN LOS CAMPOS DE INICIO DE SESIÓN-->

			<!--<?php //foreach ($this->form->getFieldset('credentials') as $field): ?>
				<?php //if (!$field->hidden): ?>
					<div class="login-fields"><?php //echo $field->label; ?>
					<?php //echo $field->input; ?></div>-->
					<label id="username-lbl" for="username" class="username"><?php echo "Usuario"; //echo JText::_('JGLOBAL_REMEMBER_ME') ?></label>
					<input id="username" type="text" name="username" class="inputtext"/><br><br>
					<label id="password-lbl" for="password" class="password"><?php echo "Contraseña"; //echo JText::_('JGLOBAL_REMEMBER_ME') ?></label>	
			    	<input id="password" type="password" name="password" class="inputtext"/>
				<!--<?php //endif; ?>
			<?php //endforeach; ?>-->

		<!--///////////////////////////////////////////////////////////////-->
			<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
			<div class="login-fields inline">
				<input id="remember" type="checkbox" name="remember" class="inputbox" value="yes"  alt="<?php echo JText::_('JGLOBAL_REMEMBER_ME') ?>" />
			    <label id="remember-lbl" for="remember" class="remember"><?php echo JText::_('JGLOBAL_REMEMBER_ME') ?></label>
				<div class="clear"></div>
			</div>
			<?php endif; ?>
			<button type="submit" class="button"><?php echo JText::_('JLOGIN'); ?></button>
			<input type="hidden" name="return" value="<?php echo base64_encode($this->params->get('login_redirect_url', $this->form->getValue('return'))); ?>" />
			<?php echo JHtml::_('form.token'); ?>
			<span class="rememberPassword"> 
			<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
			<?php echo JText::_('COM_USERS_LOGIN_RESET'); ?></a>
		    </span>
		
	</form>
	</div>
</div>
</div>

<div class="col-6">
<div class="otherOption">
	<!--<h2>
		También puedes:
	</h2>-->
	
		<?php
		$usersConfig = JComponentHelper::getParams('com_users');
		if ($usersConfig->get('allowUserRegistration')) : ?>
		<div class="newRegistration">
			<a type="button" href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>">
				<?php echo JText::_('COM_USERS_LOGIN_REGISTER'); ?></a>
		</div>
		<?php endif; ?>
		<div>
			<img src="images/SOTO.gif" width="100%" height="257px">			
		</div>

		<!--<ul class="benefits">
			<li style="font-size: 15px; text-align: justify; font-style: oblique;">
				Utilizamos la más avanzada tecnología para dar servicio a nuestros Clientes Satélites.</br></br>
			</li>
			<li style="font-size: 15px; text-align: justify; font-style: oblique;">
				Con nuestro sistema podrá reservar vuelos Internacionales para el mercado Venezolano.</br></br>
			</li>
			<li style="font-size: 15px; text-align: justify; font-style: oblique;">
				Búsqueda de vuelos Internacionales por Matrix de Calendario (3 dias antes y 3 dias despues a la fechas seleccionada), envío por email de la reservación.</br></br>
			</li>
		</ul>-->
	
</div>
</div>
</div>