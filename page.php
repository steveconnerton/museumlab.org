<?php
/**
 * front-page .php
 * Load Front Page
 * @package WordPress
 * @subpackage mihelp
 *
 */
get_header();

if (have_posts()) : while (have_posts()) : the_post();

  if (have_rows('content_modules')) :
    $front=(is_front_page())?true:false;

    $block_count = 1;

    while (have_rows('content_modules')) : the_row();
      //echo get_row_layout();
      if($block_count==2 && $front){
        echo '<div class="home-shapes">';
      }
      ACF_Layout::render(get_row_layout(), $block_count);
      $block_count++;
      if($block_count==5 && $front){
        echo '</div><!--home-shapes-->';
      }
    endwhile; ?>
  <?php else: ?>
    <div class="block-1 section-content  bg-white py-4 ">
      <div class="grid-container container">
        <h1 class="heading mb-3"><?php the_title() ?></h1>
        <?php the_content(); ?>
      </div>
    </div>
  <?php
  endif;
endwhile; endif;

get_footer();
?>
