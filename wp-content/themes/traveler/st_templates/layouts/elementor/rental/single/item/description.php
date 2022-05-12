<div class="accordion-item">
    <h2 class="st-heading-section" id="headingDescription">
    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDescription" aria-expanded="true" aria-controls="collapseDescription">
        <?php echo esc_html__('Description', 'traveler') ?>
    </button>
    </h2>
    <div id="collapseDescription" class="accordion-collapse collapse show" aria-labelledby="headingDescription" data-bs-parent="#headingDescription">
        <div class="accordion-body">
            <?php
            global $post;
            $content = $post->post_content;
            $count = str_word_count($content);
            ?>
            <div class="st-description" data-toggle-section="st-description" <?php if ($count >= 120) {
                echo 'data-show-all="st-description"
                    data-height="120"'; } ?>>
                <?php the_content(); ?>
                <?php if ($count >= 120) { ?>
                    <div class="cut-gradient"></div>
                <?php } ?>
            </div>
            <?php if ($count >= 120) { ?>
                <a href="#" class="st-link block" data-show-target="st-description"
                data-text-less="<?php echo esc_html__('View Less', 'traveler') ?>"
                data-text-more="<?php echo esc_html__('View More', 'traveler') ?>"><span
                            class="text"><?php echo esc_html__('View More', 'traveler') ?></span><i
                            class="fa fa-caret-down ml3"></i></a>
            <?php } ?>
        </div>
    </div>
</div>