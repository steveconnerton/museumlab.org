<?php
/**
 * front-page .php
 * Load Front Page
 * @package WordPress
 * @subpackage mihelp
 *
 */
get_header();

if ( have_posts() ) : while ( have_posts() ) : the_post();
  ?>
  <?php get_template_part('blog-parts/content-single'); ?>
<?php

endwhile; endif;

get_footer();
?>
