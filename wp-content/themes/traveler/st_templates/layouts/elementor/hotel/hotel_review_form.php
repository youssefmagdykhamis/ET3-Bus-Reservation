<?php
    /**
     * Created by PhpStorm.
     * User: Administrator
     * Date: 20-11-2018
     * Time: 9:18 AM
     * Since: 1.0.0
     * Updated: 1.0.0
     */ ?>
<div class="form-wrapper">
    <div class="row">
        <div class="col-12 col-xs-12 col-sm-6">
            <div class="form-group">
                <input type="text" class="form-control st-border-radius"
                       name="author"
                       placeholder="<?php _e('Name *', 'traveler') ?>">
            </div>
        </div>
        <div class="col-12 col-xs-12 col-sm-6">
            <div class="form-group">
                <input type="email" class="form-control st-border-radius"
                       name="email"
                       placeholder="<?php _e('Email *', 'traveler') ?>">
            </div>
        </div>
        <div class="col-12 col-xs-12">
            <div class="form-group">
                <input type="text" class="form-control st-border-radius"
                       name="comment_title"
                       placeholder="<?php _e('Title', 'traveler') ?>">
            </div>
        </div>
    </div>
    <div class="row flex-nowrap align-self-stretch">
        <div class="col-12 col-xs-12 col-md-4 order-2 col-md-push-8">
            <div class="form-group review-items has-matchHeight">
                <?php
                    $stats = STReview::get_review_stats( get_the_ID() );
                    if ( !empty( $stats ) ) {
                        foreach ( $stats as $stat ) {
                            ?>
                            <div class="item">
                                <label><?php echo esc_html($stat[ 'title' ]); ?></label>
                                <input class="st_review_stats" type="hidden"
                                       name="st_review_stats[<?php echo trim( $stat[ 'title' ] ); ?>]">
                                <div class="rates">
                                    <?php
                                        for ( $i = 1; $i <= 5; $i++ ) {
                                            echo '<i class="far fa-smile grey"></i>';
                                        }
                                    ?>
                                </div>
                            </div>
                            <?php
                        }
                    }
                ?>
            </div>
        </div>
        <div class="col-xs-12 col-md-8 order-1 col-md-pull-4">
            <div class="form-group">
                <textarea name="comment"
                          class="form-control st-border-radius has-matchHeight"
                          placeholder="<?php _e('Content', 'traveler') ?>"></textarea>
            </div>
        </div>
    </div>
</div>
