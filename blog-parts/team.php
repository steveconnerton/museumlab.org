<?php
$image = get_field('image');
global $post;
?>
<div class="team " id="team-member-<?php the_ID(); ?>">
  <div class="row align-items-center">
    <div class="col-md-3">
      <div class="team__image  mb-3 mb-sm-0">
        <img src="<?php echo $image ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>"
             class="img-fluid rounded-circle"/>
      </div>
    </div>
    <div class="col-md-8">
      <div class="team__content pl-0 pl-sm-4 mb-3">
        <h3 class=" team__title "><?php
          echo get_the_title();
          ?></h3>
        <p class="text-large team__subtitle"><?php the_field('sub_title'); ?></p>
        <div class="team__description"><?php the_field('description'); ?></div>
      </div>
    </div>
  </div>
</div>
