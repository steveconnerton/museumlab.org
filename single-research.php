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
  $title=get_the_title();
  ?>
  <div class=" grid-container container pt-5 no-print ">
    <div class="text-right">
      <a class="btn btn__print" href="javascript::void(0)" onclick="window.print()"><i class=" mr-1 fa fa-print"
                                                                                       aria-hidden="true"></i> Print</a>
    </div>
  </div>
  <div class="block-1 section-content  bg-white pb-5 pt-0  single-post ">
    <div class="grid-container container">
      <h1 class="text-33 mb-3"><?php echo $title ?></h1>
      <div class="research__content">
        <?php the_content(); ?>
      </div>
    </div>
  </div>

  <div class="block-1 section-content  bg-white pb-5 pt-0  single-post " id="section-to-print">

    <div class="grid-container container">
      <div class="single-recipe__header border-bottom border-top py-1 my-3 row align-content-center">
        <div class="col-print-9">
          <h1 class="text-black text-33 "><?php echo $title; ?></h1>
          <p class="text-large recipe__subtitle"><em><?php the_field( 'sub_title' ); ?></em></p>
        </div>
        <div class="col-print-3">
          <img
            src="<?php bloginfo('template_url'); ?>/images/logo-print.png" class="navbar__logo mt-1"
            alt="<?php bloginfo('name') ?>">
        </div>
      </div>
      <div class="research__content">
        <?php the_content(); ?>
      </div>
    </div>
  </div>
<?php

endwhile; endif;

get_footer();
?>
