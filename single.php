<?php
//default page layout
get_header();
?>
<?php
if ( have_posts() ) : while ( have_posts() ) :
the_post();
?>
<div class="single-post">
  <?php get_template_part( 'blog-parts/blog-header' ); ?>
  <div class="container py-5">

    <div class="row">

      <div class="col-sm-8">
        <?php
        $image = get_the_post_thumbnail_url( null, 'full' );;
        ?>
        <?php if ( $image ): ?>
          <div class="post__image-container pb-2 ">
            <figure>
              <img src='<?php echo $image; ?>' class="post__image img-fluid"/>
            </figure>
          </div>
        <?php endif; ?>
        <p class="post__date"><?php the_date() ?></p>
        <div class="post__content ">
          <?php the_content(); ?>
        </div>
        <div class="post__categories">
          <?php the_category( '', ' ' ); ?>
        </div>

        <?php
        endwhile;
        endif;
        ?>
      </div>
      <?php get_sidebar(); ?>
    </div>


  </div>
</div>
<?php
get_footer();
?>
