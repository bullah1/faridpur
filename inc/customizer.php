<?php
/**
 * Customizer: interface language, header bar, homepage sections (order, visibility,
 * titles, item counts) and footer details.
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

add_action( 'customize_register', 'fc_customize_register' );

/**
 * Register Customizer settings.
 *
 * @param WP_Customize_Manager $wp_customize Manager.
 */
function fc_customize_register( $wp_customize ) {

	/**
	 * Sortable list with visibility checkboxes. Value: "key:1,key:0,…".
	 */
	class FC_Sections_Control extends WP_Customize_Control {
		/**
		 * Control type.
		 *
		 * @var string
		 */
		public $type = 'fc_sections';

		/**
		 * Enqueue control script.
		 */
		public function enqueue() {
			wp_enqueue_script( 'fc-customizer-sections', get_template_directory_uri() . '/assets/js/customizer-sections.js', array( 'jquery', 'jquery-ui-sortable', 'customize-controls' ), FC_THEME_VERSION, true );
			wp_add_inline_style( 'customize-controls', '.fc-sections{margin:8px 0}.fc-sections li{display:flex;align-items:center;gap:6px;background:#fff;border:1px solid #dcdcde;border-radius:4px;padding:6px 8px;margin:0 0 6px;cursor:move}.fc-sections label{flex:1}.fc-sections button{min-width:30px}' );
		}

		/**
		 * Render.
		 */
		public function render_content() {
			$labels = fc_home_section_labels();
			echo '<span class="customize-control-title">' . esc_html( $this->label ) . '</span>';
			if ( $this->description ) {
				echo '<span class="description customize-control-description">' . esc_html( $this->description ) . '</span>';
			}
			echo '<ul class="fc-sections">';
			foreach ( fc_home_sections() as $key => $on ) {
				printf(
					'<li data-key="%1$s"><label><input type="checkbox" %2$s> %3$s</label><button type="button" class="button-link fc-up" aria-label="%4$s">↑</button><button type="button" class="button-link fc-down" aria-label="%5$s">↓</button></li>',
					esc_attr( $key ),
					checked( $on, true, false ),
					esc_html( $labels[ $key ] ),
					/* translators: %s: section name. */
					esc_attr( sprintf( __( 'Move %s up', 'faridpur-city' ), $labels[ $key ] ) ),
					/* translators: %s: section name. */
					esc_attr( sprintf( __( 'Move %s down', 'faridpur-city' ), $labels[ $key ] ) )
				);
			}
			echo '</ul><input type="hidden" class="fc-sections-value" ';
			$this->link();
			echo ' value="' . esc_attr( $this->value() ) . '">';
		}
	}

	$wp_customize->add_panel(
		'fc_theme',
		array(
			'title'    => __( 'Faridpur City', 'faridpur-city' ),
			'priority' => 30,
		)
	);

	// General.
	$wp_customize->add_section(
		'fc_general',
		array(
			'title' => __( 'Language & header', 'faridpur-city' ),
			'panel' => 'fc_theme',
		)
	);
	$wp_customize->add_setting( 'fc_ui_lang', array( 'default' => 'bn', 'sanitize_callback' => 'fc_sanitize_lang' ) );
	$wp_customize->add_control(
		'fc_ui_lang',
		array(
			'label'   => __( 'Interface language', 'faridpur-city' ),
			'section' => 'fc_general',
			'type'    => 'radio',
			'choices' => array(
				'bn' => 'বাংলা',
				'en' => 'English',
			),
		)
	);
	$wp_customize->add_setting( 'fc_bangla_digits', array( 'default' => true, 'sanitize_callback' => 'fc_sanitize_bool' ) );
	$wp_customize->add_control(
		'fc_bangla_digits',
		array(
			'label'   => __( 'Show dates and numbers in Bangla digits', 'faridpur-city' ),
			'section' => 'fc_general',
			'type'    => 'checkbox',
		)
	);
	$wp_customize->add_setting( 'fc_topbar', array( 'default' => true, 'sanitize_callback' => 'fc_sanitize_bool' ) );
	$wp_customize->add_control(
		'fc_topbar',
		array(
			'label'   => __( 'Show top bar with date', 'faridpur-city' ),
			'section' => 'fc_general',
			'type'    => 'checkbox',
		)
	);
	$text_settings = array(
		'fc_topbar_link_label' => array( __( 'Top bar link label', 'faridpur-city' ), 'text' ),
		'fc_topbar_link_url'   => array( __( 'Top bar link URL', 'faridpur-city' ), 'url' ),
		'fc_alt_lang_label'    => array( __( 'Other-language link label (e.g. EN)', 'faridpur-city' ), 'text' ),
		'fc_alt_lang_url'      => array( __( 'Other-language link URL (leave empty to hide)', 'faridpur-city' ), 'url' ),
		'fc_report_url'        => array( __( '“Report wrong information” link URL', 'faridpur-city' ), 'url' ),
	);
	foreach ( $text_settings as $id => $def ) {
		$wp_customize->add_setting( $id, array( 'default' => '', 'sanitize_callback' => 'url' === $def[1] ? 'esc_url_raw' : 'sanitize_text_field' ) );
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $def[0],
				'section' => 'fc_general',
				'type'    => 'url' === $def[1] ? 'url' : 'text',
			)
		);
	}

	// Homepage.
	$wp_customize->add_section(
		'fc_home',
		array(
			'title'       => __( 'Homepage sections', 'faridpur-city' ),
			'panel'       => 'fc_theme',
			'description' => __( 'Banners, notices, services, news and listings are managed from their own admin menus. Here you choose which sections appear, their order, headings and how many items they show.', 'faridpur-city' ),
		)
	);
	$wp_customize->add_setting( 'fc_home_sections', array( 'default' => '', 'sanitize_callback' => 'fc_sanitize_sections' ) );
	$wp_customize->add_control(
		new FC_Sections_Control(
			$wp_customize,
			'fc_home_sections',
			array(
				'label'       => __( 'Sections and order', 'faridpur-city' ),
				'description' => __( 'Drag or use the arrows to reorder. Untick to hide.', 'faridpur-city' ),
				'section'     => 'fc_home',
			)
		)
	);

	$headings = array(
		'services'  => __( 'City services', 'faridpur-city' ),
		'news'      => __( 'Latest news', 'faridpur-city' ),
		'directory' => __( 'Business directory', 'faridpur-city' ),
		'emergency' => __( 'Emergency services', 'faridpur-city' ),
		'places'    => __( 'Places', 'faridpur-city' ),
		'events'    => __( 'Events', 'faridpur-city' ),
		'links'     => __( 'Useful links', 'faridpur-city' ),
	);
	foreach ( $headings as $key => $label ) {
		$wp_customize->add_setting( 'fc_title_' . $key, array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
		$wp_customize->add_control(
			'fc_title_' . $key,
			array(
				/* translators: %s: section name. */
				'label'       => sprintf( __( '%s — heading', 'faridpur-city' ), $label ),
				'description' => __( 'Leave empty for the default.', 'faridpur-city' ),
				'section'     => 'fc_home',
				'type'        => 'text',
			)
		);
		if ( in_array( $key, array( 'services', 'emergency', 'directory' ), true ) ) {
			$wp_customize->add_setting( 'fc_sub_' . $key, array( 'default' => '', 'sanitize_callback' => 'sanitize_text_field' ) );
			$wp_customize->add_control(
				'fc_sub_' . $key,
				array(
					/* translators: %s: section name. */
					'label'   => sprintf( __( '%s — subtitle', 'faridpur-city' ), $label ),
					'section' => 'fc_home',
					'type'    => 'text',
				)
			);
		}
	}

	$counts = array(
		'fc_count_services'  => array( __( 'Number of service categories', 'faridpur-city' ), 12 ),
		'fc_count_news'      => array( __( 'Number of news items', 'faridpur-city' ), 5 ),
		'fc_count_directory' => array( __( 'Number of directory listings', 'faridpur-city' ), 4 ),
		'fc_count_emergency' => array( __( 'Number of emergency services', 'faridpur-city' ), 6 ),
		'fc_count_places'    => array( __( 'Number of places', 'faridpur-city' ), 3 ),
		'fc_count_events'    => array( __( 'Number of events', 'faridpur-city' ), 3 ),
	);
	foreach ( $counts as $id => $def ) {
		$wp_customize->add_setting( $id, array( 'default' => $def[1], 'sanitize_callback' => 'absint' ) );
		$wp_customize->add_control(
			$id,
			array(
				'label'       => $def[0],
				'section'     => 'fc_home',
				'type'        => 'number',
				'input_attrs' => array(
					'min' => 1,
					'max' => 24,
				),
			)
		);
	}

	// Footer.
	$wp_customize->add_section(
		'fc_footer',
		array(
			'title' => __( 'Footer & contact', 'faridpur-city' ),
			'panel' => 'fc_theme',
		)
	);
	$footer = array(
		'fc_footer_about'     => array( __( 'Short description', 'faridpur-city' ), 'textarea' ),
		'fc_footer_address'   => array( __( 'Address', 'faridpur-city' ), 'textarea' ),
		'fc_footer_phone'     => array( __( 'Phone', 'faridpur-city' ), 'text' ),
		'fc_footer_email'     => array( __( 'Email', 'faridpur-city' ), 'email' ),
		'fc_footer_copyright' => array( __( 'Copyright line', 'faridpur-city' ), 'text' ),
	);
	foreach ( $footer as $id => $def ) {
		$sanitize = 'textarea' === $def[1] ? 'sanitize_textarea_field' : ( 'email' === $def[1] ? 'sanitize_email' : 'sanitize_text_field' );
		$wp_customize->add_setting( $id, array( 'default' => '', 'sanitize_callback' => $sanitize ) );
		$wp_customize->add_control(
			$id,
			array(
				'label'   => $def[0],
				'section' => 'fc_footer',
				'type'    => $def[1],
			)
		);
	}
}

/**
 * Sanitise language.
 *
 * @param string $v Value.
 * @return string
 */
function fc_sanitize_lang( $v ) {
	return 'en' === $v ? 'en' : 'bn';
}

/**
 * Sanitise boolean.
 *
 * @param mixed $v Value.
 * @return bool
 */
function fc_sanitize_bool( $v ) {
	return (bool) $v;
}

/**
 * Sanitise the sections string.
 *
 * @param string $v Value.
 * @return string
 */
function fc_sanitize_sections( $v ) {
	$allowed = array_keys( fc_home_section_labels() );
	$out     = array();
	foreach ( explode( ',', (string) $v ) as $pair ) {
		list( $key, $on ) = array_pad( explode( ':', $pair ), 2, '1' );
		$key              = sanitize_key( $key );
		if ( in_array( $key, $allowed, true ) && ! isset( $out[ $key ] ) ) {
			$out[ $key ] = $key . ':' . ( '0' === $on ? '0' : '1' );
		}
	}
	return implode( ',', $out );
}
