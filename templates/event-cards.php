<?php
/**
 * Template Part: Tessitura Event Cards (MuseumLab)
 */

$wpgetapi_setup_id   = 'tessitura';
$action_performance  = 'perf_summary';   // Mapped to /TXN/Performances/Summary
$action_web_contents = 'web_contents';   // Mapped to /TXN/WebContents

// Helper function to invoke WPGetAPI endpoints safely
if ( ! function_exists( 'ml_fetch_wpgetapi' ) ) {
    function ml_fetch_wpgetapi( $setup_id, $action_id, $args = array() ) {
        if ( function_exists( 'wpgetapi_endpoint' ) ) {
            return wpgetapi_endpoint( $setup_id, $action_id, $args );
        } elseif ( function_exists( 'wpgetapi_a_get_endpoint' ) ) {
            return wpgetapi_a_get_endpoint( $setup_id, $action_id, $args );
        } elseif ( function_exists( 'wpgetapi_get_endpoint' ) ) {
            return wpgetapi_get_endpoint( $setup_id, $action_id, $args );
        }
        return false;
    }
}

// Helper function to extract title override, description, and image from WebContents
if ( ! function_exists( 'ml_parse_web_contents' ) ) {
    function ml_parse_web_contents( $response ) {
        if ( is_string( $response ) ) {
            $response = json_decode( $response, true );
        }

        $parsed_data = array(
            'title_override' => '',
            'description'    => '',
            'image_url'      => '',
        );

        if ( empty( $response ) || ! is_array( $response ) ) {
            return $parsed_data;
        }

        $web_contents = array();
        if ( isset( $response[0]['WebContents'] ) ) {
            $web_contents = $response[0]['WebContents'];
        } elseif ( isset( $response['WebContents'] ) ) {
            $web_contents = $response['WebContents'];
        } elseif ( is_array( $response ) ) {
            $web_contents = $response;
        }

        $candidates = array(
            'title_override' => array(),
            'description'    => array(),
            'image_url'      => array(),
        );

        foreach ( $web_contents as $item ) {
            if ( ! is_array( $item ) ) continue;

            $type_id    = isset( $item['Type']['Id'] ) ? (int) $item['Type']['Id'] : null;
            $owner_type = isset( $item['Owner']['Type'] ) ? (int) $item['Owner']['Type'] : 999;
            $value      = isset( $item['Value'] ) ? trim( $item['Value'] ) : '';

            if ( empty( $value ) ) continue;

            if ( $type_id === 132 ) {
                $candidates['title_override'][] = array( 'owner' => $owner_type, 'val' => $value );
            } elseif ( $type_id === 131 ) {
                $candidates['description'][]    = array( 'owner' => $owner_type, 'val' => $value );
            } elseif ( $type_id === 134 ) {
                $candidates['image_url'][]      = array( 'owner' => $owner_type, 'val' => $value );
            }
        }

        foreach ( $candidates as $key => $items ) {
            if ( ! empty( $items ) ) {
                usort( $items, function( $a, $b ) {
                    return $a['owner'] <=> $b['owner'];
                } );
                $parsed_data[ $key ] = $items[0]['val'];
            }
        }

        return $parsed_data;
    }
}

// --- Step 1: Target Performance IDs ---
$performance_ids = array( 3378, 3379 );

// --- Step 2: Fetch Performance Summaries via query_variables ---
$perf_args = array(
    'query_variables' => array(
        'performanceIds' => implode( ',', $performance_ids ),
    ),
);

$perf_response = ml_fetch_wpgetapi( $wpgetapi_setup_id, $action_performance, $perf_args );

if ( is_string( $perf_response ) ) {
    $perf_response = json_decode( $perf_response, true );
}

if ( isset( $perf_response['Id'] ) ) {
    $perf_response = array( $perf_response );
}

$events = array();

// --- Step 3: Process Responses & Fetch WebContents ---
if ( ! empty( $perf_response ) && is_array( $perf_response ) && ! isset( $perf_response['Code'] ) ) {

    foreach ( $perf_response as $performance ) {
        if ( ! is_array( $performance ) || ! isset( $performance['Id'] ) ) continue;

        $p_id    = (int) $performance['Id'];
        $prod_id = $performance['ProductionSeason']['Id'] ?? $performance['ProductionSeason']['ID'] ?? '';

        // Status check (1 = active)
        $status_val = $performance['Status'] ?? null;
        $status_id  = is_array( $status_val ) ? ( $status_val['Id'] ?? null ) : $status_val;
        if ( ! empty( $status_id ) && $status_id != 1 ) {
            continue;
        }

        // Fetch WebContents per event
        $wc_args = array(
            'query_variables' => array(
                'productionElementIds' => $p_id,
                'contentTypeIds'       => '131,132,134',
                'showAll'              => 'true',
            ),
        );

        $wc_response = ml_fetch_wpgetapi( $wpgetapi_setup_id, $action_web_contents, $wc_args );
        $wc_data     = ml_parse_web_contents( $wc_response );

        $title = ! empty( $wc_data['title_override'] ) 
            ? $wc_data['title_override'] 
            : ( isset( $performance['Description'] ) ? $performance['Description'] : 'MuseumLab Event' );

        $start_date_raw = $performance['PerformanceDateTime'] ?? $performance['Date'] ?? '';
        $badge_date     = '';
        $formatted_time = '';

        if ( ! empty( $start_date_raw ) ) {
            try {
                $perf_dt        = new DateTime( $start_date_raw );
                $badge_date     = $perf_dt->format( 'M j' );
                $formatted_time = $perf_dt->format( 'g:i a' );
            } catch ( Exception $e ) {
                // Ignore parsing errors
            }
        }

        $ticket_url = $performance['WebUrl'] ?? $performance['PerformanceUrl'] ?? '';

        if ( empty( $ticket_url ) ) {
            if ( ! empty( $prod_id ) && ! empty( $p_id ) ) {
                $ticket_url = "https://secure.pittsburghkids.org/{$prod_id}/{$p_id}";
            } else {
                $ticket_url = "https://secure.pittsburghkids.org/overview/{$p_id}";
            }
        }

        $events[] = array(
            'id'          => $p_id,
            'title'       => $title,
            'image_url'   => $wc_data['image_url'],
            'description' => $wc_data['description'],
            'badge_date'  => $badge_date,
            'time'        => $formatted_time,
            'ticket_url'  => $ticket_url,
        );
    }
}
?>

<!-- HTML Markup -->
<?php if ( ! empty( $events ) ) : ?>
    <div class="ml-events-grid">
        <?php foreach ( $events as $event ) : ?>
            <a href="<?php echo esc_url( $event['ticket_url'] ); ?>" class="ml-event-card" target="_blank" rel="noopener noreferrer">
                <div class="ml-event-card__image-container">
                    <?php if ( ! empty( $event['badge_date'] ) ) : ?>
                        <div class="ml-event-card__badge">
                            <?php echo esc_html( $event['badge_date'] ); ?>
                        </div>
                    <?php endif; ?>

                    <?php if ( ! empty( $event['image_url'] ) ) : ?>
                        <img src="<?php echo esc_url( $event['image_url'] ); ?>" alt="<?php echo esc_attr( $event['title'] ); ?>" loading="lazy" class="ml-event-card__image" />
                    <?php endif; ?>

                    <div class="ml-event-card__overlay">
                        <h3 class="ml-event-card__title">
                            <?php echo esc_html( strtolower( $event['title'] ) ); ?>
                        </h3>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
<?php elseif ( current_user_can( 'administrator' ) ) : ?>
    <div style="background: #f8d7da; color: #721c24; padding: 15px; border: 1px solid #f5c6cb; margin: 20px 0;">
        <strong>Tessitura Event Cards Debug:</strong> No events returned.
        <pre>Performance Response: <?php print_r( $perf_response ); ?></pre>
    </div>
<?php endif; ?>