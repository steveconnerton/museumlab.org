<?php
//default page layout
get_header();
?>
<div class="posts">
  <?php get_template_part( 'blog-parts/blog-header' ); ?>

  <div class="container py-5">

    <div class="row">

      <div class="col-sm-8">
        <?php
        if ( have_posts() ) :?>
          <div class="row">
            <?php while ( have_posts() ) : the_post(); ?>
              <div class="col-md-12">
                <div class="border-bottom my-2">
                  <?php get_template_part( 'blog-parts/post' ); ?>
                </div>
              </div>
            <?php endwhile; ?>
          </div>
          <?php
          get_template_part( 'blog-parts/pagination' );
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

