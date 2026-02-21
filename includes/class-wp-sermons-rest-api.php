<?php
/**
 * Register and handle REST API endpoints.
 *
 * @package    WP_Sermons
 */

class WP_Sermons_REST_API {

	/**
	 * The namespace for the REST API.
	 *
	 * @var string
	 */
	private $namespace = 'wp-sermons/v1';

	/**
	 * Register REST API routes.
	 */
	public function register_routes() {
		// Get sermons
		register_rest_route(
			$this->namespace,
			'/sermons',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_sermons' ),
				'permission_callback' => '__return_true',
				'args'                => $this->get_collection_params(),
			)
		);

		// Get single sermon
		register_rest_route(
			$this->namespace,
			'/sermons/(?P<id>\d+)',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_sermon' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'id' => array(
						'validate_callback' => function( $param ) {
							return is_numeric( $param );
						},
					),
				),
			)
		);

		// Get sermon series
		register_rest_route(
			$this->namespace,
			'/series',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_series' ),
				'permission_callback' => '__return_true',
			)
		);

		// Get speakers
		register_rest_route(
			$this->namespace,
			'/speakers',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_speakers' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	/**
	 * Get sermons endpoint.
	 *
	 * @param WP_REST_Request $request Full request data.
	 * @return WP_REST_Response|WP_Error Response object or error.
	 */
	public function get_sermons( $request ) {
		$args = array(
			'post_type'      => 'sermon',
			'posts_per_page' => $request->get_param( 'per_page' ) ?: 10,
			'paged'          => $request->get_param( 'page' ) ?: 1,
			'orderby'        => $request->get_param( 'orderby' ) ?: 'date',
			'order'          => $request->get_param( 'order' ) ?: 'DESC',
		);

		// Add taxonomy filters
		$tax_query = array();

		if ( $request->get_param( 'series' ) ) {
			$tax_query[] = array(
				'taxonomy' => 'sermon_series',
				'field'    => 'slug',
				'terms'    => sanitize_text_field( $request->get_param( 'series' ) ),
			);
		}

		if ( $request->get_param( 'speaker' ) ) {
			$tax_query[] = array(
				'taxonomy' => 'sermon_speaker',
				'field'    => 'slug',
				'terms'    => sanitize_text_field( $request->get_param( 'speaker' ) ),
			);
		}

		if ( ! empty( $tax_query ) ) {
			$args['tax_query'] = $tax_query;
		}

		$query   = new WP_Query( $args );
		$sermons = array();

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$sermons[] = $this->prepare_sermon_data( get_post() );
			}
			wp_reset_postdata();
		}

		$response = rest_ensure_response( $sermons );
		$response->header( 'X-WP-Total', $query->found_posts );
		$response->header( 'X-WP-TotalPages', $query->max_num_pages );

		return $response;
	}

	/**
	 * Get single sermon endpoint.
	 *
	 * @param WP_REST_Request $request Full request data.
	 * @return WP_REST_Response|WP_Error Response object or error.
	 */
	public function get_sermon( $request ) {
		$post = get_post( $request->get_param( 'id' ) );

		if ( ! $post || 'sermon' !== $post->post_type ) {
			return new WP_Error( 'rest_not_found', __( 'Sermon not found.', 'wp-sermons' ), array( 'status' => 404 ) );
		}

		return rest_ensure_response( $this->prepare_sermon_data( $post ) );
	}

	/**
	 * Get sermon series endpoint.
	 *
	 * @param WP_REST_Request $request Full request data.
	 * @return WP_REST_Response Response object.
	 */
	public function get_series( $request ) {
		$terms  = get_terms(
			array(
				'taxonomy'   => 'sermon_series',
				'hide_empty' => false,
			)
		);
		$series = array();

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$series[] = $this->prepare_term_data( $term );
			}
		}

		return rest_ensure_response( $series );
	}

	/**
	 * Get speakers endpoint.
	 *
	 * @param WP_REST_Request $request Full request data.
	 * @return WP_REST_Response Response object.
	 */
	public function get_speakers( $request ) {
		$terms    = get_terms(
			array(
				'taxonomy'   => 'sermon_speaker',
				'hide_empty' => false,
			)
		);
		$speakers = array();

		if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				$speakers[] = $this->prepare_term_data( $term );
			}
		}

		return rest_ensure_response( $speakers );
	}

	/**
	 * Prepare sermon data for API response.
	 *
	 * @param WP_Post $post Post object.
	 * @return array Prepared data.
	 */
	private function prepare_sermon_data( $post ) {
		$data = array(
			'id'        => $post->ID,
			'title'     => get_the_title( $post ),
			'content'   => apply_filters( 'the_content', $post->post_content ),
			'excerpt'   => get_the_excerpt( $post ),
			'date'      => get_the_date( 'c', $post ),
			'link'      => get_permalink( $post ),
			'thumbnail' => get_the_post_thumbnail_url( $post, 'medium' ),
		);

		// Add taxonomy data
		$series = get_the_terms( $post->ID, 'sermon_series' );
		if ( $series && ! is_wp_error( $series ) ) {
			$data['series'] = array_map( array( $this, 'prepare_term_data' ), $series );
		}

		$speakers = get_the_terms( $post->ID, 'sermon_speaker' );
		if ( $speakers && ! is_wp_error( $speakers ) ) {
			$data['speakers'] = array_map( array( $this, 'prepare_term_data' ), $speakers );
		}

		$topics = get_the_terms( $post->ID, 'sermon_topic' );
		if ( $topics && ! is_wp_error( $topics ) ) {
			$data['topics'] = array_map( array( $this, 'prepare_term_data' ), $topics );
		}

		return $data;
	}

	/**
	 * Prepare term data for API response.
	 *
	 * @param WP_Term $term Term object.
	 * @return array Prepared data.
	 */
	private function prepare_term_data( $term ) {
		return array(
			'id'          => $term->term_id,
			'name'        => $term->name,
			'slug'        => $term->slug,
			'description' => $term->description,
			'count'       => $term->count,
			'link'        => get_term_link( $term ),
		);
	}

	/**
	 * Get collection parameters for API requests.
	 *
	 * @return array Collection parameters.
	 */
	private function get_collection_params() {
		return array(
			'page'     => array(
				'description'       => __( 'Current page of the collection.', 'wp-sermons' ),
				'type'              => 'integer',
				'default'           => 1,
				'sanitize_callback' => 'absint',
				'minimum'           => 1,
			),
			'per_page' => array(
				'description'       => __( 'Maximum number of items to be returned in result set.', 'wp-sermons' ),
				'type'              => 'integer',
				'default'           => 10,
				'minimum'           => 1,
				'maximum'           => 100,
				'sanitize_callback' => 'absint',
			),
			'orderby'  => array(
				'description'       => __( 'Sort collection by object attribute.', 'wp-sermons' ),
				'type'              => 'string',
				'default'           => 'date',
				'enum'              => array( 'date', 'title', 'modified' ),
				'sanitize_callback' => 'sanitize_text_field',
			),
			'order'    => array(
				'description'       => __( 'Order sort attribute ascending or descending.', 'wp-sermons' ),
				'type'              => 'string',
				'default'           => 'DESC',
				'enum'              => array( 'ASC', 'DESC' ),
				'sanitize_callback' => 'sanitize_text_field',
			),
			'series'   => array(
				'description'       => __( 'Filter by sermon series slug.', 'wp-sermons' ),
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			),
			'speaker'  => array(
				'description'       => __( 'Filter by speaker slug.', 'wp-sermons' ),
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			),
		);
	}
}
