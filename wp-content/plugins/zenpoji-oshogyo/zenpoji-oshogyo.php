<?php
/**
 * Plugin Name: 善法寺 お聖教
 * Description: お聖教を「お知らせ（投稿）」とは別の投稿タイプとして管理・表示します。
 * Version: 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Lightning は widgets_init（init 優先度 1）で投稿タイプ別サイドバーを作るため、それより前に登録する。
add_action( 'init', 'zenpoji_oshogyo_register', 0 );
add_filter( 'get_calendar_args', 'zenpoji_oshogyo_calendar_args' );
add_filter( 'day_link', 'zenpoji_oshogyo_date_link' );
add_filter( 'month_link', 'zenpoji_oshogyo_date_link' );

register_activation_hook( __FILE__, 'zenpoji_oshogyo_activate' );
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );

function zenpoji_oshogyo_activate() {
	zenpoji_oshogyo_register();
	flush_rewrite_rules();
}

function zenpoji_oshogyo_is_context() {
	return is_singular( 'oshogyo' )
		|| is_post_type_archive( 'oshogyo' )
		|| is_tax( array( 'oshogyo_cat', 'oshogyo_tag' ) );
}

function zenpoji_oshogyo_calendar_args( $args ) {
	if ( zenpoji_oshogyo_is_context() ) {
		$args['post_type'] = 'oshogyo';
	}
	return $args;
}

function zenpoji_oshogyo_date_link( $link ) {
	if ( zenpoji_oshogyo_is_context() ) {
		$link = add_query_arg( 'post_type', 'oshogyo', $link );
	}
	return $link;
}

function zenpoji_oshogyo_register() {
	register_post_type(
		'oshogyo',
		array(
			'labels'        => array(
				'name'               => 'お聖教',
				'singular_name'      => 'お聖教',
				'menu_name'          => 'お聖教',
				'all_items'          => 'お聖教一覧',
				'add_new'            => '新規追加',
				'add_new_item'       => 'お聖教を追加',
				'edit_item'          => 'お聖教を編集',
				'new_item'           => '新しいお聖教',
				'view_item'          => 'お聖教を表示',
				'view_items'         => 'お聖教を表示',
				'search_items'       => 'お聖教を検索',
				'not_found'          => 'お聖教が見つかりませんでした。',
				'not_found_in_trash' => 'ゴミ箱にお聖教はありません。',
			),
			'public'        => true,
			'has_archive'   => true,
			'show_in_rest'  => true,
			'menu_position' => 5,
			'menu_icon'     => 'dashicons-book-alt',
			'supports'      => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions', 'custom-fields' ),
			'rewrite'       => array( 'slug' => 'oshogyo' ),
		)
	);

	register_taxonomy(
		'oshogyo_cat',
		'oshogyo',
		array(
			'labels'            => array(
				'name'          => 'お聖教の分類',
				'singular_name' => '分類',
				'menu_name'     => '分類',
				'all_items'     => 'すべての分類',
				'edit_item'     => '分類を編集',
				'add_new_item'  => '分類を追加',
			),
			'hierarchical'      => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'oshogyo-cat' ),
		)
	);

	register_taxonomy(
		'oshogyo_tag',
		'oshogyo',
		array(
			'labels'            => array(
				'name'          => 'お聖教のタグ',
				'singular_name' => 'タグ',
				'menu_name'     => 'タグ',
				'all_items'     => 'すべてのタグ',
				'edit_item'     => 'タグを編集',
				'add_new_item'  => 'タグを追加',
			),
			'hierarchical'      => false,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'oshogyo-tag' ),
		)
	);
}
