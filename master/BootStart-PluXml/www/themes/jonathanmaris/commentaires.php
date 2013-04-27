<?php if(!defined('PLX_ROOT')) exit; ?>

<div id="comments-box" class="well well-small">
    <a id="goto-comments" name="goto-comments" style="position:absolute;margin-top:-340px;"></a>
	<span id="number-of-comments" class="label label-info" style="position:absolute;"><?php echo $plxShow->artNbCom('','#nb','#nb') ?></span> <i class="icon-3x icon-comments-alt icon-blue"></i>
  <?php if($plxShow->plxMotor->plxRecord_coms): ?>
  <div id="rss-comments" class="rss pull-right"> <a class="btn" rel="tooltip" data-toggle="tooltip" data-placement="top" data-original-title="Fil RSS des commentaires de cet article" href="<?php echo $plxShow->comFeed('link',$plxShow->artId()); ?>"><i class="icon-2x icon-rss icon-blue"></i></a> </div>
  <hr class="clearfix">
  <?php while($plxShow->plxMotor->plxRecord_coms->loop()): # On boucle sur les commentaires ?>
  <a id="<?php $plxShow->comId(); ?>" name="<?php $plxShow->comId(); ?>" style="position:absolute;margin-top:-48.75%;"></a>
  <?php 
		$i++;
		$side = $i % 2 == 0 ? 'left' : 'right';
		$sideInverse = $i % 2 == 0 ? 'right' : 'left';
		?>
  <span class="badge badge-info badge-hour <?php echo 'pull-'.$sideInverse.''; ?>" style=" <?php echo 'margin-'.$sideInverse.''; ?>: 47.2%; margin-top: 6.1%;">
  <time><?php $plxShow->comDate('#hour:#minute'); ?></time>
  </span>
  
  <div class="btn-group btn-group-vertical <?php echo 'pull-'.$side; ?>" style="margin-<?php echo $sideInverse; ?>:5px;"> 
  	
    <a class="btn btn-mini" rel="tooltip" data-toggle="tooltip" data-placement="<?php echo $sideInverse; ?>" data-original-title="<?php $plxShow->comAuthor(); ?> #<?php $plxShow->comId(); ?>" href="#discuss"><i class="icon-user"></i></a>
  	
    <a class="btn btn-mini" rel="tooltip" data-toggle="tooltip" data-placement="<?php echo $sideInverse; ?>" data-original-title="<?php $plxShow->lang('WRITE_A_COMMENT') ?> @ <?php $plxShow->comAuthor(); ?>" href="javascript:answerCom('content','<?php $plxShow->comId(); ?>','<?php $plxShow->comAuthor(); ?>'); return false;"><i class="icon-reply"></i></a>
  </div>
   
  <blockquote id="quote-<?php $plxShow->comId(); ?>" style="max-width: 40%;" class="<?php echo 'pull-'.$side; ?> type-<?php $plxShow->comType(); ?>">
    <?php $plxShow->comContent(); ?>
    <hr>
    <span class="label label-author label-date <?php echo 'pull-'.$side; ?>"> <span class="label label-clear">
    <?php $plxShow->comAuthor('link'); ?>
    </span>
    <?php $plxShow->lang('SAID') ?>
    @
    <time><?php $plxShow->comDate('#day #num_day #month #num_year(4)'); ?></time>
    </span>
  </blockquote>
  
  <div class="clearfix">&nbsp;</div>
  <?php endwhile; # Fin de la boucle sur les commentaires ?>
  <?php endif; ?>
  <hr>
  <?php if($plxShow->plxMotor->plxRecord_arts->f('allow_com') AND $plxShow->plxMotor->aConf['allow_com']): ?>
  <a id="discuss" name="discuss" style="position:absolute;margin-top:-140px;"></a>
  <form class="form-horizontal" action="<?php $plxShow->artUrl(); ?>#form" method="post">
    <div class="control-group">
      <label for="id_name" class="control-label">
        <?php $plxShow->lang('NAME') ?>
      </label>
      <div class="controls">
        <input id="id_name" name="name" type="text" size="20" value="<?php $plxShow->comGet('name',''); ?>" maxlength="30" />
      </div>
    </div>
    <div class="control-group">
      <label for="id_site" class="control-label">
        <?php $plxShow->lang('WEBSITE') ?>
      </label>
      <div class="controls">
        <input id="id_site" name="site" type="text" size="20" value="<?php $plxShow->comGet('site',''); ?>" />
      </div>
    </div>
    <div class="control-group">
      <label for="id_mail" class="control-label">
        <?php $plxShow->lang('EMAIL') ?>
      </label>
      <div class="controls">
        <input id="id_mail" name="mail" type="text" size="20" value="<?php $plxShow->comGet('mail',''); ?>" />
      </div>
    </div>
    <div class="control-group">
      <label for="id_content" class="lab_com control-label">
        <?php $plxShow->lang('COMMENT') ?>
      </label>
      <div class="controls">
        <textarea id="id_content" name="content" cols="35" rows="6"><?php $plxShow->comGet('content',''); ?></textarea>
      </div>
    </div>
    <?php if($plxShow->plxMotor->aConf['capcha']): ?>
    <div class="control-group">
      <label for="id_rep" class="control-label"><?php echo $plxShow->lang('ANTISPAM_WARNING') ?></label>
      <div class="controls">
        <input id="id_rep" name="rep" type="text" size="10" />
        <span class="help-block"><?php $plxShow->capchaQ(); ?></span>
      </div>
    </div>
    <?php endif; ?>
    <div class="control-group">
      <label class="control-label">&nbsp;</label>
      <div class="controls">
        <span><input class="btn btn-responsive" type="submit" value="<?php $plxShow->lang('SEND') ?>" /></span>
      </div>
    </div>
  </form>
  <?php else: ?>
  	<div class="alert alert-info">
    <?php $plxShow->lang('COMMENTS_CLOSED') ?>
    </div>
  <?php endif; # Fin du if sur l'autorisation des commentaires ?>
</div>
