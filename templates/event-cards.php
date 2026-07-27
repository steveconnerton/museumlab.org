<?php
$class = isset( $args['class'] ) ? $args['class'] : '';

// Explicitly pass Season 13 (FY26 General Admission)
$season_id = 13; 
$query_string = 'productionSeasonId=' . $season_id;

// 2. Call WPGetAPI endpoint
$response = function_exists( 'wpgetapi_endpoint' ) 
    ? wpgetapi_endpoint( 'tessitura', 'perf_summary', array( 'query_variables' => $query_string ) ) 
    : array();

$performances = array();
if ( is_array( $response ) ) {
    if ( isset( $response['PerformanceSummary'] ) ) {
        $performances = $response['PerformanceSummary'];
    } elseif ( isset( $response['PerformanceSummaries'] ) ) {
        $performances = $response['PerformanceSummaries'];
    } else {
        $performances = $response;
    }
}

if ( isset( $performances['Id'] ) ) {
    $performances = array( $performances );
}

$unique_events = array();
$now           = time();

if ( ! empty( $performances ) && is_array( $performances ) ) {
    foreach ( $performances as $perf ) {
        if ( ! is_array( $perf ) ) continue;

        $id         = isset( $perf['Id'] ) ? $perf['Id'] : '';
        $date       = isset( $perf['PerformanceDateTime'] ) ? $perf['PerformanceDateTime'] : '';
        $base_title = isset( $perf['Description'] ) ? $perf['Description'] : '';

        if ( ! $id ) continue;

        // Verify status & web contents
        if ( function_exists( 'mlab_is_performance_on_sale' ) && ! mlab_is_performance_on_sale( $id ) ) {
            continue;
        }

        $web_contents = function_exists( 'mlab_get_web_contents' ) ? mlab_get_web_contents( $id ) : array();
        $final_title  = ! empty( $web_contents['title_override'] ) ? $web_contents['title_override'] : $base_title;
        $image_url    = ! empty( $web_contents['image'] ) ? $web_contents['image'] : '';

        if ( $date && strtotime( $date ) >= $now && ! isset( $unique_events[ $final_title ] ) ) {
            $unique_events[ $final_title ] = array(
                'id'    => $id,
                'title' => $final_title,
                'date'  => $date,
                'image' => $image_url,
            );
        }

        if ( count( $unique_events ) >= 2 ) break;
    }
}
?>

<?php if ( ! empty( $unique_events ) ) : ?>
  <?php foreach ( $unique_events as $event ) : 
      $display_month = date( 'M', strtotime( $event['date'] ) );
      $display_day   = date( 'j', strtotime( $event['date'] ) );
      $link          = "https://secure.pittsburghkids.org/0/" . $event['id'] . "/?site=mlab";
      $img_url       = ! empty( $event['image'] ) ? $event['image'] : get_stylesheet_directory_uri() . '/assets/images/placeholder.jpg';
  ?>
    <div class="<?php echo esc_attr( $class ); ?>">
      <div class="event">
        <a href="<?php echo esc_url( $link ); ?>" class="event__image">
          <img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $event['title'] ); ?>">
          <div class="event__detail">
            <div class="event__dates">
              <span><?php echo esc_html( $display_month ); ?> <?php echo esc_html( $display_day ); ?></span>
            </div>
            <div class="event__title"><?php echo esc_html( $event['title'] ); ?></div>
          </div>
        </a>
      </div>
    </div>
  <?php endforeach; ?>
<?php else : ?>
  <!-- Tessitura API returned no active events for season <?php echo esc_html( $season_id ); ?> -->
<?php endif; ?>