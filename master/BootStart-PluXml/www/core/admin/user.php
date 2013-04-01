<?php

/**
 * Edition des options d'un utilisateur
 *
 * @package PLX
 * @author	Stephane F.
 * modification 30/03/2013 @author Jonathan Maris for © littleRabbitLabs
 **/

include(dirname(__FILE__).'/prepend.php');

# Control du token du formulaire
plxToken::validateFormToken($_POST);

# Hook Plugins
eval($plxAdmin->plxPlugins->callHook('AdminUserPrepend'));

# Control de l'accès à la page en fonction du profil de l'utilisateur connecté
$plxAdmin->checkProfil(PROFIL_ADMIN);

# On édite la page statique
if(!empty($_POST) AND isset($plxAdmin->aUsers[ $_POST['id'] ])) {
	$plxAdmin->editUser($_POST);
	header('Location: user.php?p='.$_POST['id']);
	exit;
}
elseif(!empty($_GET['p'])) { # On vérifie l'existence de l'utilisateur
	$id = plxUtils::strCheck(plxUtils::nullbyteRemove($_GET['p']));
	if(!isset($plxAdmin->aUsers[ $id ])) {
		plxMsg::Error(L_USER_UNKNOWN);
		header('Location: parametres_users.php');
		exit;
	}
} else { # Sinon, on redirige
	header('Location: parametres_users.php');
	exit;
}

# On inclut le header
include(dirname(__FILE__).'/top.php');
?>

<div class="row-fluid">
  <div class="span12 widget">
    <div class="widget-title"><span class="icon"><i class="icon-user icon-grey icon-shadowed"></i></span>
      <h5><?php echo L_USER_PAGE_TITLE ?> "<?php echo plxUtils::strCheck($plxAdmin->aUsers[$id]['name']); ?>"</h5>
      <a href="parametres_users.php" class="btn btn-mini pull-right" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo L_USER_BACK_TO_PAGE ?>"><i class="icon icon-undo"></i></a> </div>
    <div class="widget-content">
      <?php eval($plxAdmin->plxPlugins->callHook('AdminUserTop')) # Hook Plugins ?>
      <form class="form form-horizontal" action="user.php" method="post" id="form_user">
        <?php plxUtils::printInput('id', $id, 'hidden');?>
        <div class="control-group">
          <label class="control-label" for="id_lang"><?php echo L_USER_LANG ?></label>
          <div class="controls">
            <?php plxUtils::printSelect('lang', plxUtils::getLangs(), $plxAdmin->aUsers[$id]['lang']) ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_email"><?php echo L_USER_MAIL ?></label>
          <div class="controls">
            <?php plxUtils::printInput('email', plxUtils::strCheck($plxAdmin->aUsers[$id]['email']), 'text', '30-255') ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="id_content"><?php echo L_USER_INFOS ?></label>
          <div class="controls">
            <?php plxUtils::printArea('content',plxUtils::strCheck($plxAdmin->aUsers[$id]['infos']),95,8) ?>
          </div>
        </div>
        <?php eval($plxAdmin->plxPlugins->callHook('AdminUser')) ?>
        <?php echo plxToken::getTokenPostMethod() ?>
        <div class="control-group">
          <label class="control-label" for="id_content">&nbsp;</label>
          <div class="controls">
            <input class="btn btn-responsive" type="submit" value="<?php echo L_USER_UPDATE ?>"/>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php
# Hook Plugins
eval($plxAdmin->plxPlugins->callHook('AdminUserFoot'));
# On inclut le footer
include(dirname(__FILE__).'/foot.php');
?>
