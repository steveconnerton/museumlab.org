<?php if (get_sub_field('images')): ?>
  <section class="section section-images">
    <div class="container">
      <?php get_template_part_args('templates/content-modules-text', array('v' => 'heading', 't' => 'h2', 'tc' => ' heading--2 mb-4')); ?>
      <ul class="section-images__wrapper js-images-grid">
        <?php
        $_columnCount = 3;
        $total = count(get_sub_field('images'));
        $i = 0;
        while (has_sub_field('images')): ?>
          <?php if ($i++ % $_columnCount == 0): ?>
            <li class="images images_<?php echo $i; ?>">
          <?php endif ?>
          <div class="image image__<?php echo $i; ?>">
            <?php
            $height = ($i == 1) ? 600 : 300;
            $width = ($i == 1) ? 890 : 440;
            $image = get_sub_field('image');
            //get_template_part_args('templates/content-modules-image', array('v' => 'image', 'is' => '', 'w' => '', 'wc' => ''));
            $image_url_default = $image['url'];
            $image_url = aq_resize($image_url_default, $width, $height, true);
            if (!$image_url) {
              $image_url = $image_url_default;
            }

            ?>
            <?php if ($image_url): ?>
              <img src="<?php echo $image_url ?>" alt="<?php echo $image['alt'] ?>"/>
            <?php endif; ?>

            <?php if (get_sub_field('title')): ?>
              <div class="image__overlay">
                <div class="image__overlay__gradient">
                  <?php get_template_part_args('templates/content-modules-text', array('v' => 'title', 't' => 'h4', 'tc' => '')); ?>
                  <?php get_template_part_args('templates/content-modules-text', array('v' => 'content', 't' => 'p', 'tc' => '')); ?>
                </div>
              </div>
            <?php endif ?>
          </div>
          <?php if ($i % $_columnCount == 0 || $i == $total): ?>
            </li><!--images-->
          <?php endif ?>

        <?php endwhile; ?>
      </ul><!--section-images__wrapper-->
      <?php if ($i > 6): ?>
        <div class="text-center mt-5">
          <a class="btn js-load-more btn--more" href="#">Load more photos</a>
        </div>
      <?php endif; ?>
    </div>
  </section>
<?php endif ?>
