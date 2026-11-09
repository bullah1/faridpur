<?php
/**
 * One-click demo content so the homepage and listings can be previewed.
 * Admin: Appearance → Demo content. Everything it creates can be removed again.
 *
 * @package FaridpurCity
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'admin_menu',
	static function () {
		add_theme_page(
			fc_t( 'ডেমো কনটেন্ট', 'Demo content' ),
			fc_t( 'ডেমো কনটেন্ট', 'Demo content' ),
			'manage_options',
			'fc-demo',
			'fc_demo_page'
		);
	}
);

/**
 * Admin page.
 */
function fc_demo_page() {
	$count = count( fc_demo_ids() );
	echo '<div class="wrap"><h1>' . esc_html( fc_t( 'ডেমো কনটেন্ট', 'Demo content' ) ) . '</h1>';
	if ( isset( $_GET['fc_done'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		echo '<div class="notice notice-success"><p>' . esc_html( 'added' === $_GET['fc_done'] ? fc_t( 'ডেমো কনটেন্ট যোগ হয়েছে। এখন সাইট দেখুন।', 'Demo content added. Visit the site now.' ) : fc_t( 'ডেমো কনটেন্ট মুছে ফেলা হয়েছে।', 'Demo content removed.' ) ) . '</p></div>'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}
	echo '<p>' . esc_html( fc_t( 'নমুনা সেবা, ব্যবসা, বিজ্ঞপ্তি, স্থান, অনুষ্ঠান, ব্যানার ও মেনু যোগ করে হোমপেজের প্রিভিউ দেখুন। নম্বর ও নাম কাল্পনিক।', 'Adds sample services, businesses, notices, places, events, banners and menus so you can preview the homepage. Names and numbers are made up.' ) ) . '</p>';
	echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '">';
	wp_nonce_field( 'fc_demo' );
	echo '<input type="hidden" name="action" value="fc_demo">';
	if ( $count ) {
		echo '<p><button class="button button-secondary" name="mode" value="remove">' . esc_html( fc_t( 'ডেমো কনটেন্ট মুছুন', 'Remove demo content' ) ) . '</button></p>';
	} else {
		echo '<p><button class="button button-primary" name="mode" value="add">' . esc_html( fc_t( 'ডেমো কনটেন্ট যোগ করুন', 'Add demo content' ) ) . '</button></p>';
	}
	echo '</form></div>';
}

/**
 * IDs of posts created by the demo.
 *
 * @return int[]
 */
function fc_demo_ids() {
	return get_posts(
		array(
			'post_type'      => 'any',
			'post_status'    => 'any',
			'posts_per_page' => -1,
			'fields'         => 'ids',
			'meta_key'       => '_fc_demo', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'     => '1', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
		)
	);
}

add_action(
	'admin_post_fc_demo',
	static function () {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Not allowed.', 'faridpur-city' ) );
		}
		check_admin_referer( 'fc_demo' );
		$mode = isset( $_POST['mode'] ) ? sanitize_key( wp_unslash( $_POST['mode'] ) ) : '';
		if ( 'remove' === $mode ) {
			fc_demo_remove();
		} elseif ( 'add' === $mode && ! fc_demo_ids() ) {
			fc_demo_add();
		}
		wp_safe_redirect( admin_url( 'themes.php?page=fc-demo&fc_done=' . ( 'remove' === $mode ? 'removed' : 'added' ) ) );
		exit;
	}
);

/**
 * Remove everything the demo made.
 */
function fc_demo_remove() {
	foreach ( fc_demo_ids() as $id ) {
		wp_delete_post( $id, true );
	}
	foreach ( get_option( 'fc_demo_terms', array() ) as $pair ) {
		wp_delete_term( (int) $pair[0], $pair[1] );
	}
	delete_option( 'fc_demo_terms' );
	$menus = get_option( 'fc_demo_menus', array() );
	foreach ( $menus as $mid ) {
		wp_delete_nav_menu( (int) $mid );
	}
	delete_option( 'fc_demo_menus' );
}

/**
 * Create the demo content.
 */
function fc_demo_add() {
	$terms = array();
	$term  = static function ( $name, $tax, $meta = array(), $parent = 0 ) use ( &$terms ) {
		$r = wp_insert_term( $name, $tax, array( 'parent' => $parent ) );
		if ( is_wp_error( $r ) ) {
			$e = get_term_by( 'name', $name, $tax );
			return $e ? (int) $e->term_id : 0;
		}
		foreach ( $meta as $k => $v ) {
			update_term_meta( $r['term_id'], 'fc_' . $k, $v );
		}
		$terms[] = array( $r['term_id'], $tax );
		return (int) $r['term_id'];
	};
	$post  = static function ( $type, $title, $content, $meta = array(), $tax = array(), $order = 0 ) {
		$id = wp_insert_post(
			array(
				'post_type'    => $type,
				'post_title'   => $title,
				'post_content' => $content,
				'post_status'  => 'publish',
				'menu_order'   => $order,
			)
		);
		if ( ! $id || is_wp_error( $id ) ) {
			return 0;
		}
		update_post_meta( $id, '_fc_demo', '1' );
		foreach ( $meta as $k => $v ) {
			update_post_meta( $id, '_fc_' . $k, $v );
		}
		foreach ( $tax as $t => $ids ) {
			wp_set_object_terms( $id, (array) $ids, $t );
		}
		return $id;
	};

	// Areas.
	$sadar  = $term( 'ফরিদপুর সদর', 'fc_area' );
	$bhanga = $term( 'ভাঙ্গা', 'fc_area' );
	$boal   = $term( 'বোয়ালমারী', 'fc_area' );

	// Service categories.
	$c_health = $term( 'স্বাস্থ্যসেবা', 'fc_service_cat', array( 'icon' => 'health', 'home' => '1' ) );
	$c_edu    = $term( 'শিক্ষা', 'fc_service_cat', array( 'icon' => 'education', 'home' => '1' ) );
	$c_safety = $term( 'নিরাপত্তা ও জরুরি', 'fc_service_cat', array( 'icon' => 'police', 'home' => '1' ) );
	$c_util   = $term( 'বিদ্যুৎ ও পানি', 'fc_service_cat', array( 'icon' => 'electricity', 'home' => '1' ) );
	$c_gov    = $term( 'সরকারি অফিস', 'fc_service_cat', array( 'icon' => 'office', 'home' => '1' ) );
	$c_trans  = $term( 'যাতায়াত', 'fc_service_cat', array( 'icon' => 'transport', 'home' => '1' ) );

	$hours = array(
		'sat' => array( 'closed' => false, 'open' => '09:00', 'close' => '17:00' ),
		'sun' => array( 'closed' => false, 'open' => '09:00', 'close' => '17:00' ),
		'mon' => array( 'closed' => false, 'open' => '09:00', 'close' => '17:00' ),
		'tue' => array( 'closed' => false, 'open' => '09:00', 'close' => '17:00' ),
		'wed' => array( 'closed' => false, 'open' => '09:00', 'close' => '17:00' ),
		'thu' => array( 'closed' => false, 'open' => '09:00', 'close' => '14:00' ),
		'fri' => array( 'closed' => true, 'open' => '', 'close' => '' ),
	);

	// Services.
	$post( 'fc_service', 'জাতীয় জরুরি সেবা ৯৯৯', 'পুলিশ, ফায়ার সার্ভিস ও অ্যাম্বুলেন্সের জন্য যেকোনো সময় কল করুন।', array( 'phone' => '999', '24x7' => '1', 'emergency' => '1' ), array( 'fc_service_cat' => $c_safety, 'fc_area' => $sadar ), 1 );
	$post( 'fc_service', 'ফায়ার সার্ভিস', 'অগ্নিকাণ্ড ও উদ্ধার কাজের জন্য।', array( 'phone' => '199', '24x7' => '1', 'emergency' => '1' ), array( 'fc_service_cat' => $c_safety, 'fc_area' => $sadar ), 2 );
	$post( 'fc_service', 'সদর হাসপাতাল (নমুনা)', 'জেলা পর্যায়ের সরকারি হাসপাতাল। এটি একটি ডেমো তথ্য।', array( 'phone' => "01700-000001\n01700-000002", 'address' => 'হাসপাতাল রোড, ফরিদপুর', 'emergency' => '1', 'hours' => $hours, 'verified' => wp_date( 'Y-m-d' ) ), array( 'fc_service_cat' => $c_health, 'fc_area' => $sadar ), 3 );
	$post( 'fc_service', 'জেলা প্রশাসকের কার্যালয় (নমুনা)', 'নাগরিক সেবা ও আবেদন সংক্রান্ত তথ্য।', array( 'phone' => '01700-000003', 'address' => 'কালেক্টরেট ভবন, ফরিদপুর', 'hours' => $hours ), array( 'fc_service_cat' => $c_gov, 'fc_area' => $sadar ), 4 );
	$post( 'fc_service', 'পল্লী বিদ্যুৎ অফিস (নমুনা)', 'বিদ্যুৎ সংযোগ ও অভিযোগ।', array( 'phone' => '01700-000004', 'hours' => $hours ), array( 'fc_service_cat' => $c_util, 'fc_area' => $bhanga ), 5 );
	$post( 'fc_service', 'সরকারি বালিকা বিদ্যালয় (নমুনা)', 'ভর্তি ও শিক্ষা সংক্রান্ত তথ্য।', array( 'phone' => '01700-000005', 'hours' => $hours ), array( 'fc_service_cat' => $c_edu, 'fc_area' => $boal ), 6 );
	$post( 'fc_service', 'বাস টার্মিনাল (নমুনা)', 'দূরপাল্লার বাস ও টিকিট কাউন্টার।', array( 'phone' => '01700-000006', '24x7' => '1' ), array( 'fc_service_cat' => $c_trans, 'fc_area' => $sadar ), 7 );

	// Business directory.
	$b_food  = $term( 'রেস্টুরেন্ট', 'fc_business_cat', array( 'icon' => 'food', 'home' => '1' ) );
	$b_shop  = $term( 'শপিং', 'fc_business_cat', array( 'icon' => 'shopping', 'home' => '1' ) );
	$b_bank  = $term( 'ব্যাংক', 'fc_business_cat', array( 'icon' => 'bank', 'home' => '1' ) );
	$b_hotel = $term( 'হোটেল', 'fc_business_cat', array( 'icon' => 'hotel', 'home' => '1' ) );
	$post( 'fc_business', 'পদ্মা রেস্টুরেন্ট (নমুনা)', 'দেশি খাবারের জনপ্রিয় রেস্টুরেন্ট।', array( 'phone' => '01800-000001', 'address' => 'নিউ মার্কেট, ফরিদপুর', 'featured' => '1', 'hours' => $hours ), array( 'fc_business_cat' => $b_food, 'fc_area' => $sadar ) );
	$post( 'fc_business', 'ফরিদপুর শপিং সেন্টার (নমুনা)', 'পোশাক ও দৈনন্দিন পণ্য।', array( 'phone' => '01800-000002', 'address' => 'টেপাখোলা, ফরিদপুর', 'featured' => '1', 'hours' => $hours ), array( 'fc_business_cat' => $b_shop, 'fc_area' => $sadar ) );
	$post( 'fc_business', 'নদীর ধারে হোটেল (নমুনা)', 'থাকা ও খাওয়ার ব্যবস্থা।', array( 'phone' => '01800-000003', 'address' => 'কমলাপুর, ফরিদপুর', '24x7' => '1' ), array( 'fc_business_cat' => $b_hotel, 'fc_area' => $sadar ) );
	$post( 'fc_business', 'জনতা ব্যাংক শাখা (নমুনা)', 'সাধারণ ব্যাংকিং সেবা।', array( 'phone' => '01800-000004', 'hours' => $hours ), array( 'fc_business_cat' => $b_bank, 'fc_area' => $boal ) );

	// Places.
	$p_hist = $term( 'ঐতিহাসিক স্থান', 'fc_place_type' );
	$p_nat  = $term( 'প্রকৃতি', 'fc_place_type' );
	$place1 = $post( 'fc_place', 'পদ্মা নদীর তীর (নমুনা)', 'বিকেলে হাঁটা ও সূর্যাস্ত দেখার জনপ্রিয় জায়গা।', array( 'visiting_hours' => 'সারাদিন', 'entry_fee' => 'বিনামূল্যে', 'address' => 'ফরিদপুর সদর' ), array( 'fc_place_type' => $p_nat, 'fc_area' => $sadar ) );
	$post( 'fc_place', 'পুরনো জমিদার বাড়ি (নমুনা)', 'ইতিহাসপ্রেমীদের জন্য দর্শনীয় ভবন।', array( 'visiting_hours' => 'সকাল ১০টা – বিকেল ৫টা', 'entry_fee' => '২০ টাকা', 'address' => 'ভাঙ্গা' ), array( 'fc_place_type' => $p_hist, 'fc_area' => $bhanga ) );

	// Events.
	$post( 'fc_event', 'বইমেলা (নমুনা)', 'স্থানীয় লেখক ও প্রকাশকদের অংশগ্রহণে বইমেলা।', array( 'start' => wp_date( 'Y-m-d', strtotime( '+5 days' ) ) . 'T10:00', 'end' => wp_date( 'Y-m-d', strtotime( '+7 days' ) ) . 'T20:00', 'venue_place' => $place1, 'organizer' => 'জেলা লাইব্রেরি', 'status' => 'scheduled' ), array( 'fc_area' => $sadar ) );
	$post( 'fc_event', 'স্বাস্থ্য সচেতনতা শিবির (নমুনা)', 'বিনামূল্যে স্বাস্থ্য পরামর্শ।', array( 'start' => wp_date( 'Y-m-d', strtotime( '+12 days' ) ) . 'T09:00', 'venue_text' => 'টাউন হল', 'organizer' => 'স্বাস্থ্য বিভাগ', 'status' => 'scheduled' ), array( 'fc_area' => $sadar ) );

	// Notices.
	$post( 'fc_notice', 'জরুরি: এলাকাভিত্তিক বিদ্যুৎ বন্ধ (নমুনা)', 'রক্ষণাবেক্ষণের জন্য কাল সকাল ৯টা থেকে দুপুর ২টা পর্যন্ত বিদ্যুৎ বন্ধ থাকবে।', array( 'priority' => 'urgent', 'issue_date' => wp_date( 'Y-m-d' ), 'office' => 'পল্লী বিদ্যুৎ সমিতি' ) );
	$post( 'fc_notice', 'পৌর কর পরিশোধের সময়সীমা (নমুনা)', 'চলতি অর্থবছরের পৌর কর পরিশোধের শেষ তারিখ মাসের শেষ দিন।', array( 'priority' => 'normal', 'issue_date' => wp_date( 'Y-m-d' ), 'office' => 'পৌরসভা' ) );

	// Banners.
	$post( 'fc_banner', 'স্বাগতম ফরিদপুর সিটিতে', '', array( 'label' => 'শহরের তথ্য এক জায়গায়', 'subtitle' => 'সেবা, ব্যবসা, স্থান ও অনুষ্ঠানের তথ্য খুঁজে নিন।', 'btn_label' => 'সেবা দেখুন', 'btn_url' => (string) get_post_type_archive_link( 'fc_service' ) ), array(), 1 );
	$post( 'fc_banner', 'জরুরি নম্বর হাতের কাছে', '', array( 'label' => 'প্রস্তুত থাকুন', 'subtitle' => 'পুলিশ, ফায়ার ও হাসপাতালের নম্বর।', 'btn_label' => 'জরুরি সেবা', 'btn_url' => (string) get_post_type_archive_link( 'fc_service' ) ), array(), 2 );

	// News.
	$post( 'post', 'শহরে নতুন পাঠাগারের উদ্বোধন (নমুনা)', '<p>ফরিদপুর শহরে একটি নতুন পাঠাগার সবার জন্য খুলে দেওয়া হয়েছে। এটি একটি ডেমো সংবাদ।</p>', array( 'source' => 'স্থানীয় প্রতিনিধি' ) );
	$post( 'post', 'বর্ষা মৌসুমের প্রস্তুতি (নমুনা)', '<p>জলাবদ্ধতা এড়াতে পৌরসভা ড্রেন পরিষ্কারের কাজ শুরু করেছে। এটি একটি ডেমো সংবাদ।</p>', array( 'source' => 'পৌরসভা' ) );

	update_option( 'fc_demo_terms', $terms, false );

	// Menus.
	$menus  = array();
	$main   = wp_create_nav_menu( 'ডেমো প্রধান মেনু' );
	$links  = wp_create_nav_menu( 'ডেমো দরকারি লিংক' );
	$assign = get_theme_mod( 'nav_menu_locations', array() );
	if ( ! is_wp_error( $main ) ) {
		$menus[] = $main;
		$items   = array(
			array( 'হোম', home_url( '/' ) ),
			array( 'নাগরিক সেবা', get_post_type_archive_link( 'fc_service' ) ),
			array( 'ডিরেক্টরি', get_post_type_archive_link( 'fc_business' ) ),
			array( 'স্থান', get_post_type_archive_link( 'fc_place' ) ),
			array( 'অনুষ্ঠান', get_post_type_archive_link( 'fc_event' ) ),
			array( 'বিজ্ঞপ্তি', get_post_type_archive_link( 'fc_notice' ) ),
		);
		foreach ( $items as $it ) {
			wp_update_nav_menu_item(
				$main,
				0,
				array(
					'menu-item-title'  => $it[0],
					'menu-item-url'    => (string) $it[1],
					'menu-item-status' => 'publish',
					'menu-item-type'   => 'custom',
				)
			);
		}
		$assign['primary'] = $main;
	}
	if ( ! is_wp_error( $links ) ) {
		$menus[] = $links;
		foreach ( array( array( 'জাতীয় তথ্য বাতায়ন', 'https://www.bangladesh.gov.bd' ), array( 'ফরিদপুর জেলা ওয়েবসাইট', 'https://www.faridpur.gov.bd' ) ) as $it ) {
			wp_update_nav_menu_item(
				$links,
				0,
				array(
					'menu-item-title'  => $it[0],
					'menu-item-url'    => $it[1],
					'menu-item-status' => 'publish',
					'menu-item-type'   => 'custom',
				)
			);
		}
		$assign['useful_links'] = $links;
	}
	set_theme_mod( 'nav_menu_locations', $assign );
	update_option( 'fc_demo_menus', $menus, false );
	flush_rewrite_rules( false );
}
