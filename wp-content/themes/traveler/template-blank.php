<?php
/*
  Template Name: Blank template
 */

get_header();
?>
<div id="st-content-wrapper">
    <?php
    if(have_posts()){
        while (have_posts()) {
            the_post();
            the_content();
        }
    }
    ?>
</div>
<?php
get_footer();
