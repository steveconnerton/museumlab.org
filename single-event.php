<?php
/**
 * @package WordPress
 * @subpackage mihelp
 *
 */
get_header();

if ( have_posts() ) : while ( have_posts() ) : the_post();

  $image = get_field( 'image' );
  if ( $image ) {
    $image = aq_resize( $image, 450, 450 );
  }

  $type = get_field( 'type' );
  if ( $type == 'offline' ) {
    $image = null;
  }
  $slots   = get_field( 'time_slots' );
  $today   = date( 'm/d/Y' );
  $expired = false;
  $endDate = $slots[ count( $slots ) - 1 ]['date'];

  $ts1=strtotime($today);
  $ts2=strtotime($endDate);

  if ( $ts1 > $ts2 ) {
    $expired = true;
  }
  $hasRecap = false;
  if ( $type == 'offline' && get_field( 'recap_title' ) ) {
    $hasRecap = true;
  }

  ?>
  <div class="block-1 section-content  bg-white py-5 single-event ">
    <div class="grid-container container">

      <div class="row">

        <?php if ( $image ): ?>
          <div class="col-md-4">
            <div class="event__image mb-3">
              <img src="<?php echo $image ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>"
                   class="img-fluid rounded"/>
            </div>
          </div>
        <?php endif ?>
        <div class="<?php echo ( $image ) ? 'col-md-8' : 'col-md-12' ?>">

          <?php if ( $type == 'offline' && ! $expired ): ?>
            <a class=" btn btn__primary btn__arrow js-other-register-button mb-3 rounded"
               href="javascript:void(0)">
              Register Now <span class="arrow__icon">
<svg width="7" height="14" viewBox="0 0 7 14" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M7 7L0 0L4.45455 7L0 14L7 7Z" fill="#7FABC5"></path>
</svg></span>
            </a>
          <?php endif; ?>

          <?php if ( $type == 'online' ): ?>
            <h1 class="text-black text-33 mt-3 mt-sm-0"><?php the_title(); ?></h1>
          <?php endif; ?>

          <?php if ( ! $hasRecap ): ?>
            <div class="event__content">
              <?php echo get_field( 'description' ); ?>
            </div>
          <?php else: ?>
            <?php get_template_part( 'blog-parts/event-recap' ); ?>
          <?php endif; ?>

        </div>
      </div>


      <?php

      $dates = [];
      foreach ( $slots as $slot ) {
        $i ++;
        $date  = $slot['date'];
        $today = date( 'm/d/Y' );
        $ts1=strtotime($date);
        $ts2=strtotime($today);
        if ( $ts1 > $ts2 ) {
          $dates[0]['date'] = $date;
          $dates[0]['time'] = $slot['time'];
          if ( isset( $slots[ $i ]['date'] ) ) {
            $dates[1]['date'] = $slots[ $i ]['date'];
            $dates[1]['time'] = $slots[ $i ]['time'];
          }
          break;
        }
      }
      ?>
      <?php
      if ( count( $dates ) ): ?>
        <div class="event__slots mt-5 my-2 w-75 js-events <?php echo $type ?> ">
          <?php
          foreach ( $dates as $slot ):
            $date = $slot['date'];
            $time = $slot['time'];
            ?>
            <?php if ( $type == 'offline' ): ?>
            <a class=" btn btn__primary btn__arrow js-select-event"
               data-show-image="<?php echo $image ?>"
               data-show-date="<?php echo $date; ?>"
               data-show-time="<?php echo $time; ?>"
               data-show-type="<?php echo $type; ?>"
               data-show-location="<?php echo get_field( 'location' ); ?>"
               href="javascript:void(0)">
              Register Now <span class="arrow__icon">
<svg width="7" height="14" viewBox="0 0 7 14" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M7 7L0 0L4.45455 7L0 14L7 7Z" fill="#7FABC5"></path>
</svg></span>
            </a>
          <?php else: ?>
            <a href="javascript:void(0)"
               data-show-image="<?php echo $image ?>"
               data-show-date="<?php echo $date; ?>"
               data-show-time="<?php echo $time; ?>"
               data-show-type="<?php echo $type; ?>"
               data-show-location="<?php echo get_field( 'location' ); ?>"
               class="js-select-event shadow">

              <div class="d-block d-sm-flex align-items-baseline justify-content-between">
                <div>
                  <i class="far fa-calendar-alt"></i> <?php echo $date; ?></h3>
                </div>
                <div class="event__dates">
                  <div class="event__start-date"><i class="far fa-clock"></i> <?php echo $time ?>
                  </div>
                </div>

              </div>
            </a>
          <?php endif; ?>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <div class="event__form js-event-form" id="event-registration-form" style="display: none">
        <div class="event__form__date">
          <span class="js-event-date"></span>
          <?php if ( $type == 'online' ): ?>
            <a href="javascript:void(0)" class="js-show-event-all ml-2">Change date</a>
          <?php endif; ?>

        </div>

        <?php
        $form_id = 4;
        echo '<div class="event__form__inner gf-form-container">' . do_shortcode( '[gravityform id="' . $form_id . '" title="false" description="false" ajax="true"]' ) . '</div>';
        ?>
      </div>

    </div>
  </div>
<?php

endwhile; endif;

get_footer();
?>
