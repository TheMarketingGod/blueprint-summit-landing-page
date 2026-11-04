<?php
add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('elementor');
});

add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'blueprint-summit',
        get_template_directory_uri() . '/assets/css/site.css',
        [],
        filemtime(get_template_directory() . '/assets/css/site.css')
    );
});

add_action('wp_head', function () {
    echo '<meta name="theme-color" content="#061628">';
    echo '<script defer src="/_vercel/insights/script.js"></script>';
}, 1);

// Keep Elementor's editor preview on a path that Vercel always proxies to
// WordPress. The public homepage itself is a static Vercel export, so the
// default `/?elementor-preview=...` URL would otherwise load that static file.
add_filter('elementor/document/urls/preview', function ($url) {
    $query = wp_parse_url($url, PHP_URL_QUERY);

    if (!$query) {
        return $url;
    }

    return home_url('/index.php?' . $query);
}, 10, 1);

