<?php
$files = bbm_get_message_attachments();
$attachment_preview = buddyboss_messages()->option( 'attachment_preview' );
?>
<div class="bbm-attachment <?php echo $attachment_preview; ?>">
    
    <h4><?php echo count($files); ?> <?php echo _e( 'Attachments', 'buddyboss-inbox' ); ?></h4>
    
            <?php
            
            foreach($files as $file):					
                    $ext = explode(".",$file["url"]);
                    $ext = ".".end($ext);
            ?>
            
            <?php
            if($attachment_preview == "thumbnail" || empty($attachment_preview)): 
            ?>
            
            <a href="<?php echo esc_url(apply_filters("bbm_attachment_download_url", $file[ 'url' ] )); ?>" title="<?php esc_attr_e( 'Download attachment', 'buddyboss-inbox' ); ?>" target="_blank" class="attachment-single">
            <?php if(!empty($file["thumb"])){ ?>
                    <img src="<?php echo $file["thumb"]; ?>" class="athumbnail"  />
            <?php } else { ?>
                    <span class="vprev">
                            <?php echo apply_filters("bbm_icon_preview",$ext,$file); ?>
                    </span>
            <?php } ?>
             <span><?php echo $file[ 'name' ]; ?></span>
            </a>
            
            <?php endif; ?>
            
            <?php
            if($attachment_preview == "filename"): 
            ?>
            
            <a href="<?php echo esc_url(apply_filters("bbm_attachment_download_url", $file[ 'url' ] )); ?>" title="<?php esc_attr_e( 'Download attachment', 'buddyboss-inbox' ); ?>" target="_blank" class="attachment-single">
             <span><i class="fa fa-download"></i> <?php echo $file[ 'name' ]; ?></span>
            </a>
            
            <?php endif; ?>
            
            <?php endforeach; ?>
</div>