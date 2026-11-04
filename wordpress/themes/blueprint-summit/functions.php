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

add_filter('redirect_canonical', function ($redirect_url) {
    if (isset($_GET['elementor-preview'])) {
        return false;
    }

    return $redirect_url;
});

// Rebuild the static Vercel site after an Elementor publish/update. The hook
// URL is stored in WordPress, not source control.
function blueprint_summit_trigger_vercel_deploy($post_id) {
    if ((int) $post_id !== (int) get_option('page_on_front')) {
        return;
    }

    $hook = get_option('blueprint_summit_vercel_deploy_hook');
    if (!$hook || get_transient('blueprint_summit_deploy_pending')) {
        return;
    }

    set_transient('blueprint_summit_deploy_pending', 1, 20);
    wp_remote_post($hook, [
        'timeout' => 5,
        'blocking' => false,
        'headers' => ['User-Agent' => 'BlueprintSummitWordPressPublisher/1.0'],
    ]);
}

add_action('elementor/editor/after_save', function ($post_id) {
    blueprint_summit_trigger_vercel_deploy($post_id);
}, 10, 1);

