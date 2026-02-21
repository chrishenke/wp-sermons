<?php
/**
 * Register and handle shortcodes.
 *
 * @package    WP_Sermons
 */

class WP_Sermons_Shortcodes {

	/**
	 * Register all shortcodes.
	 */
	public function register() {
		add_shortcode( 'sermons', array( $this, 'display_sermons' ) );
		add_shortcode( 'sermon_series', array( $this, 'display_sermon_series' ) );
		add_shortcode( 'recent_sermons', array( $this, 'display_recent_sermons' ) );
	}

	/**
	 * Display sermons shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string HTML output.
	 */
	public function display_sermons( $atts ) {
		$atts = shortcode_atts(
			array(
				'number'  => 10,
				'series'  => '',
				'speaker' => '',
				'topic'   => '',
				'orderby' => 'date',
				'order'   => 'DESC',
			),
			$atts,
			'sermons'
		);

		$args = array(
			'post_type'      => 'sermon',
			'posts_per_page' => intval( $atts['number'] ),
			'orderby'        => sanitize_text_field( $atts['orderby'] ),
			'order'          => sanitize_text_field( $atts['order'] ),
		);

		// Add taxonomy filters
		$tax_query = array();

		if ( ! empty( $atts['series'] ) ) {
			$tax_query[] = array(
				'taxonomy' => 'sermon_series',
				'field'    => 'slug',
				'terms'    => sanitize_text_field( $atts['series'] ),
			);
		}

		if ( ! empty( $atts['speaker'] ) ) {
			$tax_query[] = array(
				'taxonomy' => 'sermon_speaker',
				'field'    => 'slug',
				'terms'    => sanitize_text_field( $atts['speaker'] ),
			);
		}

		if ( ! empty( $atts['topic'] ) ) {
			$tax_query[] = array(
				'taxonomy' => 'sermon_topic',
				'field'    => 'slug',
				'terms'    => sanitize_text_field( $atts['topic'] ),
			);
		}

		if ( ! empty( $tax_query ) ) {
			$args['tax_query'] = $tax_query;
		}

		$query = new WP_Query( $args );

		if ( ! $query->have_posts() ) {
			return '<p>' . __( 'No sermons found.', 'wp-sermons' ) . '</p>';
		}

		ob_start();
		?>
		<div class="sermon-list">
			<?php while ( $query->have_posts() ) : $query->the_post(); ?>
				<article class="sermon-list__item">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="sermon-list__thumbnail">
							<?php the_post_thumbnail( 'medium' ); ?>
						</div>
					<?php endif; ?>
					
					<div class="sermon-list__content">
						<h3 class="sermon-list__title">
							<a href="<?php the_permalink(); ?>" class="sermon-list__title-link"><?php the_title(); ?></a>
						</h3>
						
						<div class="sermon-list__meta">
							<?php
							$series = get_the_terms( get_the_ID(), 'sermon_series' );
							if ( $series && ! is_wp_error( $series ) ) {
								echo '<span class="sermon-list__meta-item sermon-list__meta-item--series">' . esc_html( $series[0]->name ) . '</span>';
							}

							$speaker = get_the_terms( get_the_ID(), 'sermon_speaker' );
							if ( $speaker && ! is_wp_error( $speaker ) ) {
								echo '<span class="sermon-list__meta-item sermon-list__meta-item--speaker">' . esc_html( $speaker[0]->name ) . '</span>';
							}

							echo '<span class="sermon-list__meta-item sermon-list__meta-item--date">' . get_the_date() . '</span>';
							?>
						</div>
						
						<div class="sermon-list__excerpt">
							<?php the_excerpt(); ?>
						</div>
					</div>
				</article>
			<?php endwhile; ?>
		</div>
		<?php
		wp_reset_postdata();

		return ob_get_clean();
	}

	/**
	 * Display sermon series shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string HTML output.
	 */
	public function display_sermon_series( $atts ) {
		$atts = shortcode_atts(
			array(
				'number'  => -1,
				'orderby' => 'name',
				'order'   => 'ASC',
			),
			$atts,
			'sermon_series'
		);

		$args = array(
			'taxonomy'   => 'sermon_series',
			'number'     => intval( $atts['number'] ),
			'orderby'    => sanitize_text_field( $atts['orderby'] ),
			'order'      => sanitize_text_field( $atts['order'] ),
			'hide_empty' => true,
		);

		$terms = get_terms( $args );

		if ( empty( $terms ) || is_wp_error( $terms ) ) {
			return '<p>' . __( 'No sermon series found.', 'wp-sermons' ) . '</p>';
		}

		ob_start();
		?>
		<div class="sermon-series-list">
			<?php foreach ( $terms as $term ) : ?>
				<div class="sermon-series-list__item">
					<h3 class="sermon-series-list__title">
						<a href="<?php echo esc_url( get_term_link( $term ) ); ?>" class="sermon-series-list__title-link">
							<?php echo esc_html( $term->name ); ?>
						</a>
					</h3>
					<?php if ( ! empty( $term->description ) ) : ?>
						<div class="sermon-series-list__description">
							<?php echo wp_kses_post( $term->description ); ?>
						</div>
					<?php endif; ?>
					<span class="sermon-series-list__count">
						<?php printf( _n( '%s sermon', '%s sermons', $term->count, 'wp-sermons' ), number_format_i18n( $term->count ) ); ?>
					</span>
				</div>
			<?php endforeach; ?>
		</div>
		<?php

		return ob_get_clean();
	}

	/**
	 * Display recent sermons shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string HTML output.
	 */
	public function display_recent_sermons( $atts ) {
		$atts = shortcode_atts(
			array(
				'number' => 5,
			),
			$atts,
			'recent_sermons'
		);

		// Reuse the sermons shortcode
		return $this->display_sermons( $atts );
	}
}
