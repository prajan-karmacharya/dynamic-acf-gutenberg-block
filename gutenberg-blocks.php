<?php

/**
 * blocks
 */


if (!function_exists('eweb_wp_register_block')) {
    function eweb_wp_register_block($name, $title, $img)
    {
        if (function_exists('acf_register_block')) {
            acf_register_block(array(
                'name'                => $name,
                'title'                => __($title),
                'description'        => __($title),
                'render_callback'    => 'eweb_block_render_callback',
                'category'            => 'eweb-web-platform',
                'supports' => array(
                    'className'  => true,
                    'anchor' => true,
                ),
                'mode' => 'edit',
                'align' => false,
                'icon'                => 'format-image',
                'keywords'            => array($name, $title, 'eweb'),
                'example'           => array(
                    'attributes' => array(
                        'mode' => 'preview',
                        'data' => array(
                            'is_preview'    => true,
                            'gutenberg_preview' => __('<img src="' . $img . '">'),
                        )
                    )
                )
            ));
        }
    }
}

add_action('acf/init', 'my_acf_init');
function my_acf_init()
{
    $path = get_theme_root() . '/eweb/blocks/';
    $get_dir = eweb_wp_dir_to_array($path);
    $block_name = array();
    
    //Block load from child theme
    if (get_template_directory() !== get_stylesheet_directory()) {
        $path_child = get_stylesheet_directory() . '/blocks/';
        $get_dir_child = eweb_wp_dir_to_array($path_child);
        
        if ($get_dir_child) {
            foreach ($get_dir_child as $k => $dir) {
                if (is_array($dir)) {
                    eweb_wp_register_init_child($k, $dir, 'child');
                }
            }
        }
    
        //Block load from parent theme
        $path = get_template_directory() . '/blocks/';
        $get_dir = eweb_wp_dir_to_array($path);
        if ($get_dir) {
            foreach ($get_dir as $k => $dir) {
                if (is_array($dir)) {
                    if (!array_key_exists($k, $get_dir_child) && !empty($dir)) {
                        eweb_wp_register_init_child($k, $dir, 'parent');
                    }
                }
            }
        }
    } else {
        if ($get_dir) {
            foreach ($get_dir as $k => $dir) {
                $title = ucwords(str_replace('-', ' ', $k));
                $img = get_template_directory_uri() . '/inc/admin/images/eweb-wp.png';
                if (is_array($dir) && in_array('preview.png', $dir) & in_array('template.php', $dir)) {
                    $img = get_template_directory_uri() . '/blocks/' . $k . '/preview.png';
                    eweb_wp_register_block($k, $title, $img);
                }
            }
        }
    }
}
function eweb_wp_register_init_child($key, $dir, $theme){
    if ( ! is_array($dir)) {
        return;
    }
    $title = ucwords(str_replace('-', ' ', $key));
    $img = get_template_directory_uri() . '/inc/admin/images/eweb-wp.png';
    if (is_array($dir) && in_array('preview.png', $dir) && in_array('template.php', $dir)) {
        if( $theme == 'child' ){
            $img = get_stylesheet_directory_uri() . '/blocks/' . $key . '/preview.png';
        }else{
            $img = get_template_directory_uri() . '/blocks/' . $key . '/preview.png';
        }
        eweb_wp_register_block($key, $title, $img);
    }
}
function eweb_block_render_callback($block)
{
    $slug = str_replace('acf/', '', $block['name']);
    if (get_template_directory() !== get_stylesheet_directory()) {
        if (file_exists(get_stylesheet_directory() . '/blocks/'. $slug  .'/template.php')) {
            require get_stylesheet_directory() . '/blocks/'. $slug  .'/template.php';
        } else {
            require get_template_directory() . '/blocks/'. $slug  .'/template.php';
        }
    } else {
        if (file_exists(get_theme_file_path("/blocks/{$slug}/template.php"))) {
            include(get_theme_file_path("/blocks/{$slug}/template.php"));
        }
    }
}

add_filter('allowed_block_types', 'eweb_wp_allowed_block_types');
function eweb_wp_allowed_block_types($allowed_blocks)
{
    $path = get_theme_root() . '/eweb/blocks/';
    $get_dir = eweb_wp_dir_to_array($path);
    $block_name = array();
    if ($get_dir) {
        foreach ($get_dir as $k => $dir) {
            $block_name[] = 'acf/' . $k;
        }
    }
    if (get_template_directory() !== get_stylesheet_directory()) {
        //Load block from child theme
        if (get_template_directory() !== get_stylesheet_directory()) {
            $path_child = get_stylesheet_directory() . '/blocks/';
            $get_dir_child = eweb_wp_dir_to_array($path_child);
            if ($get_dir_child) {
                foreach ($get_dir_child as $k => $dir) {
                    if (!array_key_exists($k, $get_dir) && is_array($dir) && !empty($dir)) {
                        $block_name[] = 'acf/' . $k;
                    }
                }
            }
        }
    }

    return $block_name;
}

if (!function_exists('eweb_wp_dir_to_array')) {
    function eweb_wp_dir_to_array($dir)
    {
        $result = array();
        if( file_exists($dir) ){
            $cdir = scandir($dir);
            foreach ($cdir as $key => $value) {
                if (!in_array($value, array(".", ".."))) {
                    if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                        $result[$value] = eweb_wp_dir_to_array($dir . DIRECTORY_SEPARATOR . $value);
                    } else {
                        $result[] = $value;
                    }
                }
            }
        }

        return $result;
    }
}