<section class="section sponsors">
  <div class="container">
    <div class="sponsors__wrapper">
      <div class="sponsors__content">
        <?php get_template_part_args('templates/content-modules-text', array('v' => 'heading', 't' => 'h2', 'tc' => 'heading--1','w'=>'div','wc'=>'sponsors__heading')); ?>
        <div class="text-description">
          <?php $content = get_sub_field('content');


          $images=get_sub_field('sponsors');
          $content_class=(!empty($images))?'col-md-6':'col-md-12';
          ?>

          <div class="images">

            <div class="row">
              <?php if ($content): ?>
                <div class="<?php echo $content_class ?> mb-3"><?php echo nl2br($content) ?></div>
              <?php endif ?>
              <?php if (have_rows('sponsors')): ?>
                <?php $i = 0;
                while (have_rows('sponsors')): the_row();
                  $i++ ?>
                  <?php if ($i > 1) {
                    continue;
                  } ?>
                  <div class="col-md-6 mb-3">
                    <?php get_template_part_args('templates/content-modules-image', array('v' => 'image', 'is' => '', 'w' => '', 'wc' => '')); ?>
                  </div>
                <?php endwhile; ?>
              <?php endif ?>
            </div>
          </div>
          <?php if ($images = get_sub_field('sponsors')): ?>
            <div class="images">
              <?php
              $class = 'col-lg-3 col-6';
              ?>
              <?php if (have_rows('sponsors')): ?>
                <div class="row">
                  <?php while (have_rows('sponsors')): the_row(); ?>
                    <div class="<?php echo $class ?> mb-3 px-2">
                      <?php get_template_part_args('templates/content-modules-image', array('v' => 'image', 'is' => '', 'w' => '', 'wc' => '')); ?>
                    </div>
                  <?php endwhile; ?>
                </div>
              <?php endif ?>
            </div>
          <?php endif; ?>

        </div>
      </div>
    </div>
  </div>
</section>

