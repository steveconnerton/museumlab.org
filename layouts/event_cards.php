<section class="event-cards">
  <div class="container">
    <?php $image = get_sub_field('image'); ?>
    <?php if (!empty($image)):

      $link = get_sub_field('cta_link');
      $url = (isset($link['url'])) ? $link['url'] : ''
      ?>
      <div class="row">
        <div class="col-md-7 px-0 pb-2 pb-md-0">
          <?php if ($url): ?>
          <a href="<?php echo $url; ?>">
            <?php endif; ?>
            <?php get_template_part_args('templates/content-modules-image', array('v' => 'image', 'is' => 'ci', 'w' => 'div', 'wc' => 'event-cards__image')); ?>
            <div class="event-cards__details">

              <?php get_template_part_args('templates/content-modules-text', array('v' => 'heading', 'tc' => '', 't' => 'h2')); ?>

            </div>
            <?php if ($url): ?>
          </a>
        <?php endif; ?>
        </div>
        <div class="col-md-5 px-md-2 px-0 events-holder">
          <?php get_template_part('templates/event-cards', null, ['class' => 'stacked']); ?>
        </div>
      </div>

      <div class="event-cards__btn text-center mt-5">
        <a class="btn " href="/events">see full calendar</a>
      </div>
    <?php else: ?>

      <div class="row">
        <div class="col-md-4">
          <div class="event-cards__title">
            <?php get_template_part_args('templates/content-modules-text', array('v' => 'heading', 'tc' => 'heading--2', 't' => 'h2')); ?>
            <?php get_template_part_args('templates/content-modules-cta-button', array('v' => 'cta_link', 'c' => 'more-link', 'w' => 'div', 'wc' => '')); ?>
          </div>
        </div>
        <?php get_template_part('templates/event-cards', null, ['class' => 'col-md-4']); ?>
      </div>
    <?php endif ?>
  </div>
</section>
