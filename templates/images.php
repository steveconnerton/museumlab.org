<?php if ($images = get_sub_field('images')): ?>
  <div class="images">
    <?php
    $class = 'col-md-6';
    $layout = get_sub_field('image_layout');
    if ($layout == 'two')
      $class = 'col-md-6';
    elseif ($layout == 'three')
      $class = 'col-md-4';
    elseif ($layout == 'four')
      $class = 'col-md-3';
    ?>

    <?php if (have_rows('images')): ?>
      <div class="row">
        <?php while (have_rows('images')): the_row(); ?>
          <div class="<?php echo $class ?> image mb-3">
            <div class="image-wrapper">
              <?php get_template_part_args('templates/content-modules-image', array('v' => 'image', 'is' => '', 'w' => '', 'wc' => '')); ?>
            </div>
            <?php get_template_part_args('templates/content-modules-text', array('v' => 'title', 't' => 'h4')); ?>

          </div>
        <?php endwhile; ?>
      </div>
    <?php endif ?>
  </div>
<?php endif; ?>
