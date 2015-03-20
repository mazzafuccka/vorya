<section class="post-comments">
    <?php
// Do not delete these lines
    if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
        die(__('Please do not load this page directly. Thanks!','framework'));
    if (post_password_required()) {
        ?>
        <p class="nocomments"><?php _e('This post is password protected. Enter the password to view comments.', 'framework') ?></p>
        <?php
        return;
    }
    /* ----------------------------------------------------------------------------------- */
    /* 	Display the comments + Pings
      /*----------------------------------------------------------------------------------- */
    if (have_comments()) : // if there are comments 
        ?>
        <div id="comments" class="clearfix">
            <?php if (!empty($comments_by_type['comment'])) : // if there are normal comments  ?>
                <h3><i class="fa fa-comment"></i><?php comments_number(__(' No Comments', 'framework'), __(' Comment(1)', 'framework'), __(' Comments(%)', 'framework')); ?></h3>
                <ol class="comments">
                    <?php wp_list_comments('type=comment&avatar_size=51&callback=imic_comment'); ?>
                </ol>
            <?php endif; ?>
            <?php
            /* ----------------------------------------------------------------------------------- */
            /* 	Deal with no comments or closed comments
              /*----------------------------------------------------------------------------------- */
            if ('closed' == $post->comment_status) : // if the post has comments but comments are now closed 
                ?>
                <p class="nocomments"><?php _e('Comments are now closed for this article.', 'framework') ?></p>
            <?php endif; ?>
        <?php else : ?>
            <?php if ('open' == $post->comment_status) : // if comments are open but no comments so far  ?>
            <?php else : // if comments are closed ?>
                <?php if (is_single()) { ?><p class="nocomments"><?php _e('Comments are closed.', 'framework') ?></p><?php } ?>
            <?php endif; ?>
        <?php endif; ?>
</section>
<?php
/* ----------------------------------------------------------------------------------- */
/* 	Comment Form
  /*----------------------------------------------------------------------------------- */
add_filter('comment_form_defaults', 'your_comment_form');
function your_comment_form($form_options)
{
	$commenter = wp_get_current_commenter();
$req = get_option( 'require_name_email' );
$aria_req = ( $req ? " aria-required='true'" : '' );
    // Fields Array
    $fields = array(
        'author' => '<div class="row">
                                <div class="form-group">
                                    <div class="col-md-4 col-sm-4">
                                        <input type="name" class="form-control input-lg" name="author" id="author" value="'.esc_attr( $commenter['comment_author'] ).'" size="22" tabindex="1" placeholder="Your name" />
                                    </div>',
        'email' => '<div class="col-md-4 col-sm-4">
                                        <input type="email" name="email" class="form-control input-lg" id="email" value="'.esc_attr( $commenter['comment_author_email'] ).'" size="22" tabindex="2" placeholder="Your email" />
                                    </div>',
        'url' => '<div class="col-md-4 col-sm-4">
                                        <input type="url" class="form-control input-lg" name="url" id="url" value="'.esc_attr( $commenter['comment_author_url'] ).'" size="22" tabindex="3" placeholder="Website (optional)" /></div>
                                </div>
                            </div>',
    );
    // Form Options Array
    $form_options = array(
        // Include Fields Array
        'fields' => apply_filters( 'comment_form_default_fields', $fields ),
        // Template Options
        'comment_field' =>
        '<div class="row">
                            <div class="form-group">
                                <div class="col-md-12">
                                    <textarea name="comment" id="comment-textarea" class="form-control input-lg" cols="8" rows="4"  tabindex="4" placeholder="Your comment" ></textarea>
                                </div>
                            </div>
                        </div>',
        'must_log_in' => '',
        'logged_in_as' =>
       '',
        'comment_notes_before' =>'',
        'comment_notes_after' => '',
        // Rest of Options
        'id_form' => 'form-comment',
        'id_submit' => 'comment-submit',
        'title_reply' => '<div id="respond-wrap" class="clearfix">
           <section class="post-comment-form">
           <div id="respond" class="clearfix">
                <h3><i class="fa fa-share"></i>'.__( 'Post a comment','framework' ).'</h3>
                <div class="cancel-comment-reply"></div></div></section></div>',
        'title_reply_to' => __( 'Leave a Reply to %s','framework' ),
        'cancel_reply_link' => __( 'Cancel reply','framework' ),
        'label_submit' => __( 'Submit your comment', 'framework' ),
    );
    return $form_options;
}
comment_form();