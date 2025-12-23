<?php
// Custom field class for page
function add_field_custom_class_body()
{
	acf_add_local_field_group(array(
		'key' => 'class_body',
		'title' => 'Body: Add Class',
		'fields' => array(),
		'location' => array(
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'page',
				),
			),
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'post',
				),
			),
		),
	));
	acf_add_local_field(array(
		'key' => 'add_class_body',
		'label' => 'Add class body',
		'name' => 'Add class body',
		'type' => 'text',
		'parent' => 'class_body',
	));
}
add_action('acf/init', 'add_field_custom_class_body');

//

function add_field_select_banner()
{
	acf_add_local_field_group(array(
		'key' => 'select_banner',
		'title' => 'Banner: Select Page',
		'fields' => array(),
		'location' => array(
			// Pages
			array(
				array(
					'param' => 'post_type',
					'operator' => '==',
					'value' => 'page',
				),
			),
			// Category (bài viết) - cho phép thêm banner cho danh mục bài viết
			array(
				array(
					'param' => 'taxonomy',
					'operator' => '==',
					'value' => 'category',
				),
			),
			array(
				array(
					'param' => 'taxonomy',
					'operator' => '==',
					'value' => 'product_cat',
				),
			),
		),
	));

	acf_add_local_field_group([
	'key' => 'group_banner_content',
	'title' => 'Banner Content',
	'location' => [
		[
			[
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'banner',
			],
		],
	],
	'fields' => [
		[
			'key' => 'field_banner_type',
			'label' => 'Kiểu banner',
			'name' => 'banner_type',
			'type' => 'radio',
			'choices' => [
				'single' => 'Banner đơn',
				'slider' => 'Banner slider',
			],
			'default_value' => 'single',
			'layout' => 'horizontal',
		],

		// SINGLE
		[
			'key' => 'field_banner_single_image',
			'label' => 'Ảnh banner',
			'name' => 'banner_single_image',
			'type' => 'image',
			'return_format' => 'id',
			'conditional_logic' => [
				[
					[
						'field' => 'field_banner_type',
						'operator' => '==',
						'value' => 'single',
					],
				],
			],
		],
		[
			'key' => 'field_banner_single_title',
			'label' => 'Tiêu đề',
			'name' => 'banner_single_title',
			'type' => 'wysiwyg',
			'toolbar' => 'basic',
			'media_upload' => 0,
			'rows' => 2,
			'conditional_logic' => [
				[
					[
						'field' => 'field_banner_type',
						'operator' => '==',
						'value' => 'single',
					],
				],
			],
		],
		[
			'key' => 'field_banner_single_button',
			'label' => 'Nút',
			'name' => 'banner_single_button',
			'type' => 'link',
			'conditional_logic' => [
				[
					[
						'field' => 'field_banner_type',
						'operator' => '==',
						'value' => 'single',
					],
				],
			],
		],

		// SLIDER
		[
			'key' => 'field_banner_slides',
			'label' => 'Slides',
			'name' => 'banner_slides',
			'type' => 'repeater',
			'min' => 1,
			'layout' => 'block',
			'conditional_logic' => [
				[
					[
						'field' => 'field_banner_type',
						'operator' => '==',
						'value' => 'slider',
					],
				],
			],
			'sub_fields' => [
				[
					'key' => 'field_slide_image',
					'label' => 'Ảnh',
					'name' => 'image',
					'type' => 'image',
					'return_format' => 'id',
				],
				[
					'key' => 'field_slide_title',
					'label' => 'Tiêu đề',
					'name' => 'title',
					'type' => 'wysiwyg',
					'toolbar' => 'basic',
					'media_upload' => 0,
					'rows' => 2,
				],
				[
					'key' => 'field_slide_button',
					'label' => 'Nút',
					'name' => 'button',
					'type' => 'link',
				],
			],
		],
	],
]);

		acf_add_local_field([
		'key'        => 'banner_select_page',
		'label'      => 'Chọn banner hiển thị',
		'name'       => 'banner_select_page',
		'type'       => 'post_object',
		'post_type'  => ['banner'],
		'multiple'   => false, //
		'return_format' => 'id', //
		'parent'     => 'select_banner',
	]);
}
add_action('acf/init', 'add_field_select_banner');

function add_theme_config_options()
{
	// Add the field group
	acf_add_local_field_group(array(
		'key' => 'group_theme_config',
		'title' => 'Theme Configuration',
		'fields' => array(
			array(
				'key' => 'tab_config',
				'label' => 'Config',
				'name' => 'tab_config',
				'type' => 'tab',
				'placement' => 'top',
			),
			array(
				'key' => 'field_config_head',
				'label' => 'Config Head',
				'name' => 'config_head',
				'type' => 'textarea',
				'instructions' => 'Add custom code for header (CSS, meta tags, etc)',
				'required' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'maxlength' => '',
				'rows' => 4,
				'new_lines' => '',
			),
			array(
				'key' => 'field_config_body',
				'label' => 'Config Body',
				'name' => 'config_body',
				'type' => 'textarea',
				'instructions' => 'Add custom code for body (JS, tracking code, etc)',
				'required' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'maxlength' => '',
				'rows' => 4,
				'new_lines' => '',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'theme-settings',
				),
			),
		),
		'menu_order' => 999,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
	));
}
add_action('acf/init', 'add_theme_config_options');