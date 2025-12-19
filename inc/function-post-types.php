<?php
create_post_type('service', array(
	'name' => 'Dịch vụ',
	'slug' => 'dich-vu',
	'icon' => 'dashicons-hammer',
	'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
	'has_archive' => false,
));

create_post_type('product', array(
	'name' => 'Sản phẩm',
	'slug' => 'san-pham',
	'icon' => 'dashicons-products',
	'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
	'has_archive' => false,
));

create_post_type('customer', array(
	'name' => 'Khách hàng',
	'slug' => 'khach-hang',
	'icon' => 'dashicons-groups',
	'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
	'has_archive' => false,
));

create_taxonomy('product_cat', array(
	'name' => 'Danh mục sản phẩm',
	'object_type' => array('product'),
	'slug' => 'danh-muc-san-pham',
));