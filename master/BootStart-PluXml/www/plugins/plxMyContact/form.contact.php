<?php if(!defined('PLX_ROOT')) exit; ?>
<?php

# récuperation d'une instance de plxShow
$plxShow = plxShow::getInstance();
$plxShow->plxMotor->plxCapcha = new plxCapcha();
$plxPlugin = $plxShow->plxMotor->plxPlugins->getInstance('plxMyContact');

$error=false;
$success=false;

if(!empty($_POST)) {
	$name=plxUtils::unSlash($_POST['name']);
	$mail=plxUtils::unSlash($_POST['mail']);
	$content=plxUtils::unSlash($_POST['content']);
	if(trim($name)=='')
		$error = $plxPlugin->getLang('L_ERR_NAME');
	elseif(!plxUtils::checkMail($mail))
		$error = $plxPlugin->getLang('L_ERR_EMAIL');
	elseif(trim($content)=='')
		$error = $plxPlugin->getLang('L_ERR_CONTENT');
	elseif($plxShow->plxMotor->aConf['capcha'] AND $_POST['rep2'] != sha1($_POST['rep']))
		$error = $plxPlugin->getLang('L_ERR_ANTISPAM');
	if(!$error) {
		if(plxUtils::sendMail($name,$mail,$plxPlugin->getParam('email'),$plxPlugin->getParam('subject'),$content,'text',$plxPlugin->getParam('email_cc'),$plxPlugin->getParam('email_bcc')))
			$success = $plxPlugin->getParam('thankyou');
		else
			$error = $plxPlugin->getLang('L_ERR_SENDMAIL');
	}
} else {
	$name='';
	$mail='';
	$content='';
}

?>

	<?php if($error): ?>
    <div id="msg" class="alert alert-error"><?php echo $error ?></div>
	<?php endif; ?>
	
	<?php if($success): ?>
	<div id="msg" class="alert alert-error"><?php echo plxUtils::strCheck($success) ?></div>
	
	<?php else: ?>
	
    <form class="form-horizontal" action="#form" method="post">
    
    <div class="control-group">
		<label for="name" class="control-label"><?php $plxPlugin->lang('L_FORM_NAME') ?></label>
        <div class="controls">
		<input class="span4" id="name" name="name" type="text" size="30" value="<?php echo plxUtils::strCheck($name) ?>" maxlength="30" />
        </div>
    </div>
    
    <div class="control-group">  
		<label for="mail" class="control-label"><?php $plxPlugin->lang('L_FORM_MAIL') ?></label>
        <div class="controls">
		<input class="span4" id="mail" name="mail" type="text" size="30" value="<?php echo plxUtils::strCheck($mail) ?>" />
        </div>
    </div>
    
    <div class="control-group">    
		<label for="message" class="control-label"><?php $plxPlugin->lang('L_FORM_CONTENT') ?></label>
        <div class="controls">
		<textarea class="span8" id="message" name="content" cols="60" rows="12"><?php echo plxUtils::strCheck($content) ?></textarea>
        </div>
    </div>
        
		<?php if($plxShow->plxMotor->aConf['capcha']): ?>
    <div class="control-group">
		<label for="id_rep" class="control-label"><?php $plxPlugin->lang('L_FORM_ANTISPAM') ?></label>
        <div class="controls">
        <input class="span4" id="id_rep" name="rep" type="text" size="10" />
        <span class="help-block"><?php echo $plxShow->capchaQ() ?></span>
        </div>
        <input name="rep2" type="hidden" value="<?php echo $plxShow->capchaR() ?>" />
    </div>
		<?php endif; ?>
	
    <div class="control-group">	
    	<label class="control-label">&nbsp;</label>
        <div class="controls">
        <input class="btn btn-responsive" type="submit" name="submit" value="<?php $plxPlugin->lang('L_FORM_BTN_SEND') ?>" />
	    <input class="btn btn-responsive" type="reset" name="reset" value="<?php $plxPlugin->lang('L_FORM_BTN_RESET') ?>" />
        </div>
    </div>
    
	</form>
	<?php endif; ?>