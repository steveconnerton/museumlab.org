<?php
$show_date = true;
$post_type = get_post_type();
if ( in_array( $post_type, [ 'research' ] )){
  $show_date = false;
}
?>
<div class="block-1 section-content  bg-white py-5 single-post ">
  <div class="grid-container container">
    <?php /*
    $image = get_the_post_thumbnail_url( null, 'full' );;
    ?>
    <?php if ( $image ): ?>
      <div class="post__image-container pb-2 ">
        <figure>
          <img src='<?php echo $image; ?>' class="post__image img-fluid"/>
        </figure>
      </div>
    <?php endif; */ ?>
    <?php if ( $show_date ): ?>
      <p class="post__date"><?php the_date() ?></p>
    <?php endif; ?>
    <h1 class="text-black text-33 mb-3"><?php the_title(); ?></h1>
    <div class="recipe__content">
      <?php the_content(); ?>
    </div>
  </div>
</div>
