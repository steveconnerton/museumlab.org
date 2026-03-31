<?php
$layout = get_sub_field('layout');
$video = get_sub_field('video');
$video_poster = get_sub_field('video_poster');
$video_poster = $video_poster['url'];
$width = (ml_is_mobile()) ? 500 : 1600;
$image_url =aq_resize($video_poster, $width, null, true);
?>
<section class="section banner-<?php echo $layout; ?>">
  <div class="s-back">
    <?php if ($layout == 'video'): ?>
      <div class="section-bg">
        <?php if ($video_poster) : ?>
          <picture>
            <img class="banner__img" src="<?php echo $image_url; ?>" alt=""/>
          </picture>
        <?php endif; ?>
		  
        <?php if ($video && !ml_is_mobile()) : ?>
		  <video style="width: 100%; height: 100%; position: absolute; object-fit: cover; z-index: 0;" poster="<?php echo $image_url; ?>" autoplay muted loop>
			<source src="<?php echo $video; ?>" type="video/mp4"  />
		  </video>
		  
		  <!--
		  <iframe src="<?php echo $video; ?>?background=1&autoplay=1&loop=1&byline=0&title=0&muted=1"
                  frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen id="home-video"
                  class="video" title="Museum Lab"></iframe>
          <script src="https://player.vimeo.com/api/player.js"></script>
          <div class="play-pause"><a href="javascript:void(0)" class="js-play-pause play" rel="noreferrer" alt="pause"><i
                class="far fa-pause-circle"></i></a></div>
		  -->
        <?php endif; ?>
		  
        <div class="banner__content">
          <?php
          $tc=get_sub_field('background');
          $bg=($tc=='bg-grad')?'bg-grad':'';
          ?>
          <div class="banner__content__inner <?php echo $bg; ?>">
            <div class="container">

              <?php get_template_part_args('templates/content-modules-text', array('v' => 'heading', 'tc' => '', 't' => 'h1')); ?>
              <?php get_template_part_args('templates/content-modules-text', array('v' => 'sub_title', 'tc' => $tc, 't' => 'h2','w'=>'div','wc'=>'subheading')); ?>
              <?php get_template_part_args('templates/content-modules-cta-button', array('v' => 'cta_button', 'c' => 'btn',)); ?>
            </div>
          </div>
        </div>
      </div>
    <?php else: ?>
      <?php if (get_sub_field('images')): ?>
        <div class="slider">
          <?php $i = 0;
          while (has_sub_field('images')): $i++;
            if ($layout == 'image' && $i == 2) {
              break;
            } ?>
            <div class="slide">
              <?php get_template_part_args('templates/content-modules-image', array('v' => 'image', 'is' => 'hero-bg')); ?>
            </div>
          <?php endwhile; ?>
        </div>
      <?php endif ?>

    <?php endif; ?>
  </div>
</section>
