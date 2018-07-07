<?php

//namespace Uamp\inc\admin\options;

$sections[] = array(
    'title'      => esc_html__('General Settings', 'uamp'),
    'icon_class' => 'icon-large',
    'id'         => 'global',
    'icon'       => 'el-icon-globe',
    'fields' => array(

        $fields =
                array(
                        'id' => 'admin_logo',
                        'type' => 'media',
                        'title' => esc_html__('Admin Logo', 'uamp'),
                        'default' => array("url" => esc_url( get_template_directory_uri() . "/images/logo.png" )),
                        'preview' => true,
                        "url" => true
                    ),
                array(
                        'id' => 'read_more',
                        'type' => 'text',
                        'title' => esc_html__('Read More', 'uamp'),
                        'default' => "Continue Reading",
                    ),
                array(
                        'id'       => 'layout',
                        'type'     => 'button_set',
                        'title'    => esc_html__( 'Choose Layout', 'uamp' ),
                        'subtitle' => esc_html__( 'Choose Layout Boxed or Full Width', 'uamp' ),
                        'options'  => array(
                            'boxed'     => esc_html__( 'Boxed', 'uamp'),
                            'fullwidth'    => esc_html__( 'Full Width', 'uamp'),
                        ),
                        'default'  => 'fullwidth'
                    ),

                array(
                    'id' => 'bodyimg',
                    'type' => 'media',
                    'title' => esc_html__('Body Background Image', 'uamp'),
                    'desc'        => __('Upload the image you have selected for your background image of your body.', 'squarecode'),
                    'preview' => true,
                    "url" => true
                ),

                array(
                    'id'       => 'bodyimg_repeat',
                    'type'     => 'button_set',
                    'title'    => esc_html__( 'Body Image Repeat', 'uamp' ),
                    'desc'        => __('Choose how to display your body background image. Repeat option will repeat your image both horizontally and vertically.  Repeat-x will ONLY repeat horizontally.  Repeat-y will ONLY repeat vertically. Cover option will make your image fill the full space retaining the aspect ratio.', 'squarecode'),
                    'options'  => array(
                        'repeat'     => esc_html__( 'Repeat', 'uamp'),
                        'repeat-x'     => esc_html__( 'Repeat-X', 'uamp'),
                        'repeat-y'     => esc_html__( 'Repeat-Y', 'uamp'),
                        'cover'     => esc_html__( 'Cover', 'uamp'),
                    ),
                    'default'  => 'repeat'
                ),

                array(
                    'id'       => 'license_type',
                    'type'     => 'button_set',
                    'title'    => esc_html__( 'License Type', 'uamp' ),
                    'desc'        => __('Choose how to display your body background image. Repeat option will repeat your image both horizontally and vertically.  Repeat-x will ONLY repeat horizontally.  Repeat-y will ONLY repeat vertically. Cover option will make your image fill the full space retaining the aspect ratio.', 'squarecode'),
                    'options'  => array(
                        'gpl'     => esc_html__( 'GPL ', 'uamp'),
                        'mit'     => esc_html__( 'MIT', 'uamp')
                    ),
                    'default'  => 'mit'
                ),
                array(
                        'id' => 'gpl_link',
                        'type' => 'text',
                        'title' => esc_html__('GPL License Link', 'uamp'),
                        'default' => "#",
                        'required' => array('license_type', '=', 'gpl')
                    ),
                array(
                        'id' => 'mit_link',
                        'type' => 'text',
                        'title' => esc_html__('MIT License Link', 'uamp'),
                        'default' => "#",
                        'required' => array('license_type', '=', 'mit')
                    ),

        )
); //global

