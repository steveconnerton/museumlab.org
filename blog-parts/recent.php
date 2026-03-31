<div class="post  pb-3">
  <div class="row">
    <div class="col-md-5">
      <a href="<?php echo get_permalink() ?>">
        <figure>
          <?php echo get_the_post_thumbnail( $post->ID, 'post-thumbnail', array( 'class' => 'post__image img-fluid' ) ); ?>
        </figure>
      </a>

    </div>
    <div class="col-md-7">
      <a href="<?php echo get_permalink() ?>"><h4 class="post__title"><?php the_title(); ?></h4>
      </a>
      <p class="post__date"><?php the_date() ?></p>
    </div>

  </div>


</div>
