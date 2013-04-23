<?php include(dirname(__FILE__).'/header.php'); ?>

    <div role="region" aria-labelledby="sectionType" style="margin-top:37px;"><span id="sectionType" style="display:none">Article</span>
        
        <?php $plxShow->comMessage('<div id="msg" class="alert alert-error">#com_message</div>'); ?>
        
        <article role="article" class="well">
        
            <div class="clearfix">
                <div class="pull-right">
                    <span class="date label label-info"><?php $plxShow->artDate('#num_day #month #num_year(4)'); ?></span>
                </div>
                <h1 class="pull-left"><?php $plxShow->artTitle(); ?></h1>
            </div>
            
            <div class="media well well-small">
              <div class="media-body">
                <div class="media-heading">Intro</div>
                    <em><?php $plxShow->artChapo('resume', false); ?></em>
              </div>
            </div>
            
            <hr>
    
            <div class="resume article-content">
                 <?php $plxShow->artChapo('', true); ?>
            </div>
            
            <div><hr></div>
    
            <div class="author article-info pull-right">
                <p>
                    <?php $plxShow->lang('WRITTEN_BY'); ?> <?php $plxShow->artAuthor(); ?>
                </p>
            </div>
            
            <div class="category article-info pull-left">
                <p>
                    <?php $plxShow->lang('CLASSIFIED_IN') ?> <?php $plxShow->artCat(); ?> avec, pour <?php $plxShow->lang('TAGS') ?> <?php $plxShow->artTags(); ?>
                </p>
            </div>
            
            <br class="clearfix">
            
            <div class="info-author article-info media well well-small">
              <div class="media-body">
                <div class="media-heading"><?php $plxShow->artAuthor(); ?></div>
                    <em><?php $plxShow->artAuthorInfos('#art_authorinfos'); ?></em>
              </div>
            </div>

            <span><hr class="clearfix"></span>
            
            <div class="comments article-info clearfix">
            
            <?php include(dirname(__FILE__).'/commentaires.php'); ?>
            
            </div>
    
        </article>
        
    </div>
    
<?php include(dirname(__FILE__).'/footer.php'); ?>
