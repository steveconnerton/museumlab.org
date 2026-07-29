<?php
/**
 * Template Part: Event Cards (Homepage - Dynamic, Unique & Fixed Images)
 */

$class           = isset( $args['class'] ) ? $args['class'] : '';
$placeholder_img = get_stylesheet_directory_uri() . '/assets/images/placeholder.jpg';

// Dynamic transient key for daily cache refresh
$transient_key = 'mlab_homepage_events_' . date( 'Y_m_d' );
$events        = get_transient( $transient_key );

if ( false === $events || empty( $events ) ) {
    $events    = array();
    $today_iso = date( 'Y-m-d' );

    // 1. Fetch marketing image/title overrides from web_contents
    $web_overrides = array();
    if ( function_exists( 'wpgetapi_endpoint' ) ) {
        $raw_contents = wpgetapi_endpoint( 'tessitura', 'web_contents', array() );
        if ( is_array( $raw_contents ) ) {
            foreach ( $raw_contents as $group ) {
                $p_id = isset( $group['RequestedOwner']['ElementId'] ) ? (string) $group['RequestedOwner']['ElementId'] : '';
                if ( ! $p_id || empty( $group['WebContents'] ) ) continue;

                foreach ( $group['WebContents'] as $content ) {
                    $desc = isset( $content['Type']['Description'] ) ? $content['Type']['Description'] : '';
                    $val  = isset( $content['Value'] ) ? trim( $content['Value'] ) : '';

                    if ( ! $val ) continue;

                    if ( false !== strpos( $desc, 'Title Override' ) ) {
                        $web_overrides[$p_id]['title'] = $val;
                    }
                    if ( false !== strpos( $desc, 'Image URL' ) ) {
                        $web_overrides[$p_id]['image'] = $val;
                    }
                }
            }
        }
    }

    // 2. Active Schedule mapped with explicit fallback images
    $upcoming_schedule = array(
        array(
            'perf_id' => '3319',
            'prod_id' => '2792',
            'title'   => 'Workshop: Polymer Clay',
            'date'    => '2026-07-31',
            'image'   => 'https://pittsburghkids.org/wp-content/uploads/2025/08/Bookbinding.jpg',
        ),
        array(
            'perf_id' => '3362',
            'prod_id' => '2792',
            'title'   => 'Workshop: Pewter Casting',
            'date'    => '2026-08-07',
            'image'   => 'https://pittsburghkids.org/wp-content/uploads/2025/09/TNEW-MuseumLab-Workshop.jpg',
        ),
        array(
            'perf_id' => '3367',
            'prod_id' => '2792',
            'title'   => 'Workshop: Wood Carving',
            'date'    => '2026-08-14',
            'image'   => 'https://pittsburghkids.org/wp-content/uploads/2025/09/TNEW-MuseumLab-Workshop.jpg',
        ),
        array(
            'perf_id' => '3372',
            'prod_id' => '2792',
            'title'   => 'Workshop: Wax Seals',
            'date'    => '2026-08-21',
            'image'   => 'https://pittsburghkids.org/wp-content/uploads/2025/09/TNEW-MuseumLab-Workshop.jpg',
        ),
        array(
            'perf_id' => '3377',
            'prod_id' => '2792',
            'title'   => 'Workshop: Bookbinding',
            'date'    => '2026-08-29',
            'image'   => 'https://pittsburghkids.org/wp-content/uploads/2025/08/Bookbinding.jpg',
        ),
    );

    // 3. Filter strictly for future dates AND deduplicate
    $seen_titles = array();

    foreach ( $upcoming_schedule as $item ) {
        if ( strtotime( $item['date'] ) >= strtotime( $today_iso ) ) {
            $title_clean = strtolower( trim( $item['title'] ) );

            if ( isset( $seen_titles[ $title_clean ] ) ) {
                continue;
            }

            $pid   = $item['perf_id'];
            $title = ! empty( $web_overrides[$pid]['title'] ) ? $web_overrides[$pid]['title'] : $item['title'];

            // Priority: API override > explicit schedule image > placeholder
            $image = ! empty( $web_overrides[$pid]['image'] ) ? $web_overrides[$pid]['image'] : ( ! empty( $item['image'] ) ? $item['image'] : $placeholder_img );

            $events[] = array(
                'id'        => $pid,
                'title'     => $title,
                'image'     => $image,
                'date_text' => date( 'M j', strtotime( $item['date'] ) ),
                'timestamp' => strtotime( $item['date'] ),
                'link'      => 'https://secure.pittsburghkids.org/' . esc_attr( $item['prod_id'] ) . '/' . esc_attr( $pid ),
            );

            $seen_titles[ $title_clean ] = true;

            if ( count( $events ) >= 2 ) {
                break;
            }
        }
    }

    // Cache until midnight tonight
    $seconds_until_midnight = strtotime( 'tomorrow' ) - time();
    set_transient( $transient_key, $events, $seconds_until_midnight );
}
?>

<?php if ( ! empty( $events ) ) : ?>
  <?php foreach ( $events as $event ) : ?>
    <div class="<?php echo esc_attr( $class ); ?>">
      <div class="event">
        <a href="<?php echo esc_url( $event['link'] ); ?>" class="event__image">
          <img src="<?php echo esc_url( $event['image'] ); ?>" alt="<?php echo esc_attr( $event['title'] ); ?>">
          <div class="event__detail">
            <div class="event__dates">
              <span><?php echo esc_html( $event['date_text'] ); ?></span>
            </div>
            <div class="event__title"><?php echo esc_html( $event['title'] ); ?></div>
          </div>
        </a>
      </div>
    </div>
  <?php endforeach; ?>
<?php else : ?>
  <div class="<?php echo esc_attr( $class ); ?>">
    <p style="color: #fff; padding: 20px;">No upcoming events found.</p>
  </div>
<?php endif; ?>