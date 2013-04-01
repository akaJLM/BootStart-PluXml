<?php

/**
 * Edition du profil utilisateur
 *
 * @package PLX
 * @author	Stephane F
 * modification 30/03/2013 @author Jonathan Maris for © littleRabbitLabs
 **/

include(dirname(__FILE__).'/prepend.php');

# Control du token du formulaire
plxToken::validateFormToken($_POST);

# Hook Plugins
eval($plxAdmin->plxPlugins->callHook('AdminProfilPrepend'));

# On édite la configuration
if(!empty($_POST)) {

	if(!empty($_POST['profil']))
		$plxAdmin->editProfil($_POST);
	elseif(!empty($_POST['password']))
		$plxAdmin->editPassword($_POST);

	header('Location: profil.php');
	exit;

}

# On inclut le header
include(dirname(__FILE__).'/top.php');

$_profil = $plxAdmin->aUsers[$_SESSION['user']];
?>

<div class="row-fluid">
  <div class="span6 widget">
    <div class="widget-title"><span class="icon"><i class="icon-cogs icon-grey icon-shadowed"></i></span>
      <h5><?php echo L_PROFIL_EDIT_TITLE ?></h5>
    </div>
    <div class="widget-content">
      <?php eval($plxAdmin->plxPlugins->callHook('AdminProfilTop')) # Hook Plugins ?>
      <form class="form form-vertical" action="profil.php" method="post" id="form_profil">
        <div class="control-group">
          <label class="control-label"><?php echo L_PROFIL_LOGIN ?></label>
          <div class="controls">
            <input class="uneditable-input" type="text" disabled="disabled" value="<?php echo plxUtils::strCheck($_profil['login']) ?>" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_name"><?php echo L_PROFIL_USER ?></label>
          <div class="controls">
            <?php plxUtils::printInput('name', plxUtils::strCheck($_profil['name']), 'text', '20-255') ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_email"><?php echo L_PROFIL_MAIL ?></label>
          <div class="controls">
            <?php plxUtils::printInput('email', plxUtils::strCheck($_profil['email']), 'text', '30-255') ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_lang"><?php echo L_PROFIL_ADMIN_LANG ?></label>
          <div class="controls">
            <?php plxUtils::printSelect('lang', plxUtils::getLangs(), $_profil['lang'], false, 'span2') ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_content"><?php echo L_PROFIL_INFOS ?></label>
          <div class="controls">
            <?php plxUtils::printArea('content',plxUtils::strCheck($_profil['infos']),140,5); ?>
          </div>
        </div>
        <?php eval($plxAdmin->plxPlugins->callHook('AdminProfil')) # Hook Plugins ?>
        <?php echo plxToken::getTokenPostMethod() ?>
        <div class="control-group">
          <label class="control-label">&nbsp;</label>
          <div class="controls">
            <input class="btn btn-responsive update" type="submit" name="profil" value="<?php echo L_PROFIL_UPDATE ?>" />
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="span6 widget">
    <div class="widget-title"><span class="icon"><i class="icon-cogs icon-grey icon-shadowed"></i></span>
      <h5><?php echo L_PROFIL_CHANGE_PASSWORD ?></h5>
    </div>
    <div class="widget-content">
      <form class="form form-vertical" action="profil.php" method="post" id="form_password">
        <div class="control-group">
          <label class="control-label" for="id_password1"><?php echo L_PROFIL_PASSWORD ?></label>
          <div class="controls">
            <?php plxUtils::printInput('password1', '', 'password', '20-255') ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_password2"><?php echo L_PROFIL_CONFIRM_PASSWORD ?></label>
          <div class="controls">
            <?php plxUtils::printInput('password2', '', 'password', '20-255') ?>
          </div>
        </div>
        <?php echo plxToken::getTokenPostMethod() ?>
        <div class="control-group">
          <label class="control-label">&nbsp;</label>
          <div class="controls">
            <input class="btn btn-responsive update" type="submit" name="password" value="<?php echo L_PROFIL_UPDATE_PASSWORD ?>" />
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php
# Hook Plugins
eval($plxAdmin->plxPlugins->callHook('AdminProfilFoot'));
# On inclut le footer
include(dirname(__FILE__).'/foot.php');
?>
