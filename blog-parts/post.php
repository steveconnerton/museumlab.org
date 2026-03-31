<div class="post  pb-3">
  <div class="row">
    <?php if(has_post_thumbnail()): ?>
    <div class="col-md-3">
      <a href="<?php echo get_permalink() ?>">
        <figure>
          <?php echo get_the_post_thumbnail( $post->ID, 'post-thumbnail', array( 'class' => 'post__image img-fluid' ) ); ?>
        </figure>
      </a>

    </div>
    <?php endif; ?>
    <div class="<?php echo (has_post_thumbnail())?'col-md-9':'col-md-12' ?>">

      <h2 class="post__title"><a href="<?php echo get_permalink() ?>"><?php the_title(); ?>  </a></h2>

      <div class="post__excerpt"> <?php the_excerpt(); ?></div>
      <p class="post__date"><?php the_date() ?></p>
      <div class="post__categories">
        <?php the_category( '', ' ' ); ?>
      </div>
    </div>

  </div>


</div>
