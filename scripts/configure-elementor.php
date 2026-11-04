<?php
/** Configure the editable Elementor landing page. */

$event_url = 'https://www.eventbrite.com/e/the-blueprint-summit-tickets-2003832748693';
$artwork = get_template_directory_uri() . '/assets/images/blueprint-summit-2027.jpg';

function bs_widget($id, $type, $settings) {
    return [
        'id' => $id,
        'elType' => 'widget',
        'widgetType' => $type,
        'settings' => $settings,
        'elements' => [],
    ];
}

function bs_container($id, $settings, $elements) {
    return [
        'id' => $id,
        'elType' => 'container',
        'settings' => $settings,
        'elements' => $elements,
    ];
}

$hero_copy = bs_container('heroTxt', [
    'content_width' => 'full',
    'width' => ['unit' => '%', 'size' => 48],
    'justify_content' => 'center',
    'padding' => ['unit' => 'px', 'top' => 72, 'right' => 34, 'bottom' => 72, 'left' => 34, 'isLinked' => false],
], [
    bs_widget('heroKik', 'heading', [
        'title' => 'LIVE IN ONTARIO, CALIFORNIA',
        'header_size' => 'h4',
        'css_classes' => 'summit-kicker',
        'title_color' => '#70d8ff',
    ]),
    bs_widget('heroHed', 'heading', [
        'title' => 'The Blueprint Summit',
        'header_size' => 'h1',
        'title_color' => '#f7f3e9',
        'typography_typography' => 'custom',
        'typography_font_size' => ['unit' => 'px', 'size' => 84],
        'typography_font_weight' => '800',
        'typography_line_height' => ['unit' => 'em', 'size' => .92],
    ]),
    bs_widget('heroSub', 'text-editor', [
        'editor' => '<p>Unlock your next big move with fresh ideas, live teaching, meaningful networking, and an in-person experience led by Ray Reynolds.</p>',
        'text_color' => '#d6e4ef',
        'typography_typography' => 'custom',
        'typography_font_size' => ['unit' => 'px', 'size' => 22],
        'typography_line_height' => ['unit' => 'em', 'size' => 1.55],
    ]),
    bs_widget('heroMeta', 'text-editor', [
        'editor' => '<p><strong>January 15–17, 2027</strong> &nbsp;•&nbsp; 7 AM–2 PM<br>DoubleTree by Hilton Hotel Ontario Airport</p>',
        'text_color' => '#f1bf4a',
    ]),
    bs_widget('heroBtn', 'button', [
        'text' => 'Get Tickets on Eventbrite',
        'link' => ['url' => $event_url, 'is_external' => true, 'nofollow' => false],
        'size' => 'lg',
        'button_background_color' => '#70d8ff',
        'button_text_color' => '#061628',
        'border_radius' => ['unit' => 'px', 'top' => 999, 'right' => 999, 'bottom' => 999, 'left' => 999, 'isLinked' => true],
    ]),
]);

$hero_visual = bs_container('heroImg', [
    'content_width' => 'full',
    'width' => ['unit' => '%', 'size' => 52],
    'justify_content' => 'center',
    'padding' => ['unit' => 'px', 'top' => 55, 'right' => 20, 'bottom' => 55, 'left' => 20, 'isLinked' => false],
], [
    bs_widget('eventPic', 'image', [
        'image' => ['url' => $artwork, 'id' => ''],
        'image_size' => 'full',
        'css_classes' => 'summit-card',
        'border_radius' => ['unit' => 'px', 'top' => 28, 'right' => 28, 'bottom' => 28, 'left' => 28, 'isLinked' => true],
    ]),
]);

$hero = bs_container('heroSec', [
    'content_width' => 'boxed',
    'boxed_width' => ['unit' => 'px', 'size' => 1380],
    'flex_direction' => 'row',
    'flex_direction_tablet' => 'column',
    'min_height' => ['unit' => 'px', 'size' => 820],
    'css_classes' => 'summit-hero',
], [$hero_copy, $hero_visual]);

$why = bs_container('whySec', [
    'content_width' => 'boxed',
    'boxed_width' => ['unit' => 'px', 'size' => 1180],
    'padding' => ['unit' => 'px', 'top' => 110, 'right' => 28, 'bottom' => 110, 'left' => 28, 'isLinked' => false],
    'background_background' => 'classic',
    'background_color' => '#f7f3e9',
], [
    bs_widget('whyKik', 'heading', ['title' => 'MORE THAN A CONFERENCE', 'header_size' => 'h4', 'title_color' => '#168bc4', 'align' => 'center', 'css_classes' => 'summit-kicker']),
    bs_widget('whyHed', 'heading', ['title' => 'Build the plan. Meet the people. Make the move.', 'header_size' => 'h2', 'title_color' => '#061628', 'align' => 'center']),
    bs_widget('whyTxt', 'text-editor', ['editor' => '<p style="text-align:center">Three days of practical teaching, real conversations, and a room full of people committed to building stronger futures.</p>', 'text_color' => '#334b60']),
]);

$agenda = bs_container('agendaS', [
    'content_width' => 'boxed',
    'boxed_width' => ['unit' => 'px', 'size' => 1180],
    'padding' => ['unit' => 'px', 'top' => 100, 'right' => 28, 'bottom' => 100, 'left' => 28, 'isLinked' => false],
    'background_background' => 'classic',
    'background_color' => '#0a3159',
], [
    bs_widget('agendaK', 'heading', ['title' => 'THE WEEKEND BLUEPRINT', 'header_size' => 'h4', 'title_color' => '#70d8ff', 'css_classes' => 'summit-kicker']),
    bs_widget('agendaH', 'heading', ['title' => 'Teaching, networking, and next-step strategy.', 'header_size' => 'h2', 'title_color' => '#f7f3e9']),
    bs_widget('agendaT', 'text-editor', ['editor' => '<p><strong class="summit-gold">Friday:</strong> Welcome, foundation, and the opening blueprint.</p><p><strong class="summit-gold">Saturday:</strong> Live teaching, working sessions, and meaningful connections.</p><p><strong class="summit-gold">Sunday:</strong> Action planning, commitments, and the road forward.</p>', 'text_color' => '#d6e4ef']),
]);

$cta = bs_container('finalCt', [
    'content_width' => 'boxed',
    'boxed_width' => ['unit' => 'px', 'size' => 980],
    'align_items' => 'center',
    'padding' => ['unit' => 'px', 'top' => 110, 'right' => 28, 'bottom' => 110, 'left' => 28, 'isLinked' => false],
    'background_background' => 'classic',
    'background_color' => '#061628',
], [
    bs_widget('finalHd', 'heading', ['title' => 'Your next move starts in the room.', 'header_size' => 'h2', 'title_color' => '#f7f3e9', 'align' => 'center']),
    bs_widget('finalTx', 'text-editor', ['editor' => '<p style="text-align:center">Join Ray Reynolds in Ontario, California, January 15–17, 2027.</p>', 'text_color' => '#d6e4ef']),
    bs_widget('finalBt', 'button', ['text' => 'Reserve Your Seat', 'link' => ['url' => $event_url, 'is_external' => true], 'size' => 'lg', 'align' => 'center', 'button_background_color' => '#f1bf4a', 'button_text_color' => '#061628']),
]);

$page_id = (int) get_option('page_on_front');
if (!$page_id) {
    $page_id = wp_insert_post([
        'post_title' => 'The Blueprint Summit',
        'post_name' => 'home',
        'post_type' => 'page',
        'post_status' => 'publish',
    ]);
}

update_post_meta($page_id, '_elementor_edit_mode', 'builder');
update_post_meta($page_id, '_elementor_template_type', 'wp-page');
update_post_meta($page_id, '_elementor_version', defined('ELEMENTOR_VERSION') ? ELEMENTOR_VERSION : '3.0.0');
update_post_meta($page_id, '_elementor_page_settings', ['hide_title' => 'yes']);
update_post_meta($page_id, '_elementor_data', wp_slash(wp_json_encode([$hero, $why, $agenda, $cta])));

update_option('show_on_front', 'page');
update_option('page_on_front', $page_id);
update_option('blog_public', 1);
update_option('blogname', 'The Blueprint Summit');
update_option('blogdescription', 'The official Blueprint Summit event landing page.');

echo "Configured Elementor landing page {$page_id}.\n";

