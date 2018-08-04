<?php
	/**
	 * Author Name: Liton Arefin
	 * Author URL: https://jeweltheme.com
	 * Date: 6/23/18
	 */


	$sections[] = array(
		'title'      => esc_html__('Menu Socials', 'uamp'),
		'icon_class' => 'icon-small',
		'id'         => 'socials',
		'icon'       => 'el-icon-twitter',
		'fields' => array(

			$fields =

			array(
				'id' => 'uamp_twitter',
				'type' => 'text',
				'title' => esc_html__('Twitter', 'uamp'),
				'default' => "#",
			),

			array(
				'id' => 'uamp_facebook',
				'type' => 'text',
				'title' => esc_html__('Facebook', 'uamp'),
				'default' => "#",
			),

			array(
				'id' => 'uamp_instagram',
				'type' => 'text',
				'title' => esc_html__('Instagram', 'uamp'),
				'default' => "#",
			),

			array(
				'id' => 'uamp_pinterest',
				'type' => 'text',
				'title' => esc_html__('Pinterest', 'uamp'),
				'default' => "#",
			),



		)
	);