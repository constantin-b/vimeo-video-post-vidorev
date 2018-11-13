<?php

class CVM_Vidorev_Actions_Compatibility {
	/**
	 * Theme name
	 * @var string
	 */
	private $theme_name;

	/**
	 * CVM_Vidorev_Actions_Compatibility constructor.
	 *
	 * @param string $theme_name
	 */
	public function __construct( $theme_name ) {
		$this->theme_name = $theme_name;
		add_filter( 'cvm_theme_support', array( $this, 'theme_support' ) );
		add_filter( 'cvm_post_insert', array( $this, 'add_post_meta' ), 10, 4 );
	}

	/**
	 * @param array $themes
	 *
	 * @return array
	 */
	public function theme_support( $themes ) {
		$theme_name = strtolower( $this->theme_name );
		$themes[ $theme_name ] = array(
			'post_type'    => 'post',
			'taxonomy'     => false,
			'tag_taxonomy' => 'post_tag',
			'post_meta'    => array(),
			'post_format'  => 'video',
			'theme_name'   => $this->theme_name,
			'url'          => 'https://themeforest.net/item/vidorev-video-wordpress-theme/21798615?ref=cboiangiu',
		);

		return $themes;
	}

	/**
	 * @param $post_id
	 * @param $video
	 * @param $theme_import
	 * @param $post_type
	 *
	 * @return string
	 */
	public function add_post_meta( $post_id, $video, $theme_import, $post_type ) {
		if ( ! $theme_import ) {
			return;
		}
		// set video URL
		$url = cvm_video_url( $video['video_id'] );
		update_post_meta( $post_id, 'vm_video_url', $url );
		// set auto fetch off
		update_post_meta( $post_id, 'vm_video_fetch', 'on' );

		update_post_meta( $post_id, 'vm_comment_count', $video['stats']['comments'] );
		update_post_meta( $post_id, 'vm_duration', $video['_duration'] );
		update_post_meta( $post_id, 'vm_duration_ts', $video['duration'] );
		update_post_meta( $post_id, 'vm_view_count', $video['stats']['views'] );
		update_post_meta( $post_id, 'vm_like_count', $video['stats']['likes'] );
	}
}