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
  $image = get_field( 'image' );
  $title=get_the_title();

  $ingredients =get_field( 'ingredients' );
  $directions = get_field( 'directions' );
  $serves = get_field( 'serves' )
  ?>


  <div class=" grid-container container pt-5 no-print ">
    <div class="text-right">
      <a class="btn btn__print" href="javascript::void(0)" onclick="window.print()"><i class=" mr-1 fa fa-print"
                                                                                       aria-hidden="true"></i> Print</a>
    </div>
  </div>

  <div class="block-1 section-content  bg-white pb-5 pt-0 single-recipe no-print ">

    <div class="grid-container container">
      <h1 class="text-black text-33 d-block d-sm-none"><?php echo $title; ?></h1>
      <div class="image-content image-content--left " id="image-content">

        <div class="row w-100">
          <div class="col-lg-7  image-content__content pb-2">

            <h1 class="text-black text-33 d-none d-sm-block mb-0"><?php echo $title; ?></h1>
            <p class="text-large recipe__subtitle"><em><?php the_field( 'sub_title' ); ?></em></p>
            <?php if ( $credit = get_field( 'credit' ) ): ?>
              <p class="text-large recipe__credit"><?php echo $credit; ?></p>
            <?php endif; ?>
            <h4>Ingredients</h4>
            <?php echo $ingredients; ?>
            <?php if ( $serves  ): ?>
              <p class="recipe__serve"><strong>Serves: <?php echo $serves; ?></strong></p>
            <?php endif; ?>

          </div>
          <div class="col-lg-5 image-content__image pr-lg-0 pl-0 pt-0 aos-init aos-animate" data-aos="fade-up">
            <img src="<?php echo $image; ?>" class="img-fluid pt-0">
          </div>
        </div>
      </div>


      <div class="recipe__direction">
        <h4>Directions</h4>
        <?php echo $directions; ?>
      </div>
    </div>
  </div>

  <?php get_template_part('blog-parts/print-receipe'); ?>


<?php

endwhile; endif;

get_footer();
?>
