<?php
/*
Plugin Name: AspireDev Custom Widgets Elementor
Plugin URI: #
Description: A WordPress plugin to add a reusable video box Elementor widget. Developed by AspireDev.
Version: 2.1.8
Author: AspireDev
Author URI: #
License: GPL2
*/

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Check if Elementor is active
if (!function_exists('is_plugin_active')) {
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
}

if (!is_plugin_active('elementor/elementor.php')) {
    add_action('admin_notices', function() {
        ?>
        <div class="notice notice-error">
            <p><?php _e('AspireDev Custom Widgets Elementor requires Elementor to be installed and activated.', 'text-domain'); ?></p>
        </div>
        <?php
    });
    return;
}

// Register custom category
function add_elementor_widget_categories($elements_manager) {
    $elements_manager->add_category(
        'aspire-custom-category',
        [
            'title' => __('Aspire Custom Widgets', 'text-domain'),
            'icon' => 'fa fa-plug',
        ]
    );
}
add_action('elementor/elements/categories_registered', 'add_elementor_widget_categories');

// Define and register the widget
class Elementor_Video_Box_Widget extends \Elementor\Widget_Base {
    public function get_name() {
        return 'video_box';
    }

    public function get_title() {
        return __('Video Box', 'text-domain');
    }

    public function get_icon() {
        return 'eicon-play';
    }

    public function get_categories() {
        return ['aspire-custom-category'];
    }

    protected function register_controls() {
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Video Box Content', 'text-domain'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'youtube_url',
            [
                'label' => __('YouTube Video URL', 'text-domain'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'placeholder' => __('e.g., https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'text-domain'),
                'description' => __('Enter the full YouTube URL (e.g., https://www.youtube.com/watch?v=dQw4w9WgXcQ, https://youtu.be/dQw4w9WgXcQ, or https://www.youtube.com/embed/dQw4w9WgXcQ)', 'text-domain'),
                'default' => '',
                'label_block' => true,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __('Video Title', 'text-domain'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Sample Video', 'text-domain'),
                'placeholder' => __('Enter video title', 'text-domain'),
                'label_block' => true,
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __('Video Description', 'text-domain'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => __('A short description about this video.', 'text-domain'),
                'placeholder' => __('Enter video description', 'text-domain'),
                'rows' => 4,
            ]
        );

        $this->add_control(
            'thumbnail',
            [
                'label' => __('Thumbnail Image', 'text-domain'),
                'type' => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => 'https://via.placeholder.com/800x450.jpg?text=Video+Thumbnail',
                ],
                'description' => __('Select a thumbnail image (recommended: 800x450px for 16:9 aspect ratio).', 'text-domain'),
            ]
        );

        $this->end_controls_section();

        // Style Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => __('Video Box Style', 'text-domain'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'border_radius',
            [
                'label' => __('Border Radius', 'text-domain'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 50],
                    '%' => ['min' => 0, 'max' => 50],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 10,
                ],
                'selectors' => [
                    '{{WRAPPER}} .video-box' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'play_icon_size',
            [
                'label' => __('Play Icon Size', 'text-domain'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 20, 'max' => 100],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 60,
                ],
                'selectors' => [
                    '{{WRAPPER}} .play-icon' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'max_width',
            [
                'label' => __('Max Width', 'text-domain'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range' => [
                    'px' => ['min' => 200, 'max' => 1200],
                    '%' => ['min' => 20, 'max' => 100],
                    'vw' => ['min' => 20, 'max' => 100],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 600,
                ],
                'selectors' => [
                    '{{WRAPPER}} .video-box' => 'max-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $unique_id = uniqid('video_box_');

        // Parse YouTube URL to extract video ID
        $video_id = '';
        $youtube_url = $settings['youtube_url'];
        if (!empty($youtube_url)) {
            // Support multiple YouTube URL formats
            if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/|v\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $youtube_url, $matches)) {
                $video_id = $matches[1];
            }
        }

        ?>
        <style>
            /* Container Styles */
            #<?php echo esc_attr($unique_id); ?> {
                position: relative;
                width: 100%;
                aspect-ratio: 16 / 9;
                background-image: url('<?php echo esc_url($settings['thumbnail']['url']); ?>');
                background-size: cover;
                background-position: center;
                cursor: pointer;
                overflow: hidden;
                display: flex;
                align-items: flex-end;
                transition: transform 0.3s ease;
                margin: 0 auto;
            }
            #<?php echo esc_attr($unique_id); ?>:hover {
                transform: scale(1.02);
            }
            #<?php echo esc_attr($unique_id); ?>::before {
                content: "";
                position: absolute;
                inset: 0;
                background: linear-gradient(to top, rgba(0,128,0,0.6), rgba(0,0,0,0.3));
                z-index: 1;
            }
            #<?php echo esc_attr($unique_id); ?> .play-icon {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                color: white;
                z-index: 2;
                pointer-events: none;
                transition: opacity 0.3s ease;
            }
            #<?php echo esc_attr($unique_id); ?>:hover .play-icon {
                opacity: 0.9;
            }
            #<?php echo esc_attr($unique_id); ?> .video-text {
                position: relative;
                z-index: 2;
                color: white;
                padding: clamp(10px, 2vw, 20px);
                width: 100%;
                box-sizing: border-box;
            }
            #<?php echo esc_attr($unique_id); ?> .video-title {
                font-size: clamp(16px, 2.5vw, 20px);
                font-weight: bold;
                margin-bottom: 8px;
            }
            #<?php echo esc_attr($unique_id); ?> .video-desc {
                font-size: clamp(12px, 2vw, 14px);
                opacity: 0.85;
            }
            #<?php echo esc_attr($unique_id); ?>_popup {
                display: none;
                position: fixed;
                z-index: 9999;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.85);
                justify-content: center;
                align-items: center;
                padding: 20px;
                box-sizing: border-box;
            }
            #<?php echo esc_attr($unique_id); ?>_popup iframe {
                width: 100%;
                height: 100%;
                max-width: 800px;
                max-height: 450px;
                aspect-ratio: 16 / 9;
                border: none;
            }
            #<?php echo esc_attr($unique_id); ?>_popup .closeBtn {
                position: absolute;
                top: clamp(10px, 2vw, 20px);
                right: clamp(15px, 3vw, 30px);
                font-size: clamp(20px, 4vw, 30px);
                color: #fff;
                cursor: pointer;
                z-index: 1000;
            }
            /* Responsive Adjustments */
            @media (max-width: 1024px) {
                #<?php echo esc_attr($unique_id); ?>_popup iframe {
                    max-width: 90vw;
                    max-height: calc(90vw * 9 / 16);
                }
            }
            @media (max-width: 768px) {
                #<?php echo esc_attr($unique_id); ?> {
                    max-width: 100%;
                }
                #<?php echo esc_attr($unique_id); ?>_popup iframe {
                    max-width: 95vw;
                    max-height: calc(95vw * 9 / 16);
                }
            }
            @media (max-width: 480px) {
                #<?php echo esc_attr($unique_id); ?> .video-text {
                    padding: 10px;
                }
                #<?php echo esc_attr($unique_id); ?>_popup {
                    padding: 10px;
                }
            }
        </style>

        <div id="<?php echo esc_attr($unique_id); ?>" class="video-box">
            <div class="play-icon">▶</div>
            <div class="video-text">
                <div class="video-title"><?php echo esc_html($settings['title']); ?></div>
                <div class="video-desc"><?php echo esc_html($settings['description']); ?></div>
            </div>
        </div>

        <div id="<?php echo esc_attr($unique_id); ?>_popup" class="video-popup">
            <div class="closeBtn" onclick="closeVideo('<?php echo esc_attr($unique_id); ?>')">×</div>
            <iframe id="<?php echo esc_attr($unique_id); ?>_iframe" src="" allow="autoplay; encrypted-media" allowfullscreen></iframe>
        </div>

        <script>
            document.getElementById('<?php echo esc_attr($unique_id); ?>').onclick = function () {
                <?php if (!empty($video_id)) : ?>
                    document.getElementById('<?php echo esc_attr($unique_id); ?>_iframe').src = "https://www.youtube.com/embed/<?php echo esc_attr($video_id); ?>?autoplay=1";
                    document.getElementById('<?php echo esc_attr($unique_id); ?>_popup').style.display = "flex";
                <?php else: ?>
                    console.warn('Invalid or missing YouTube URL for video box: <?php echo esc_attr($unique_id); ?>');
                <?php endif; ?>
            };

            function closeVideo(id) {
                document.getElementById(id + '_popup').style.display = "none";
                document.getElementById(id + '_iframe').src = "";
            }

            window.onclick = function(e) {
                if (e.target === document.getElementById('<?php echo esc_attr($unique_id); ?>_popup')) {
                    closeVideo('<?php echo esc_attr($unique_id); ?>');
                }
            };
        </script>
        <?php
    }
}

// Register the widget
function register_video_box_widget($widgets_manager) {
    $widgets_manager->register(new Elementor_Video_Box_Widget());
}
add_action('elementor/widgets/register', 'register_video_box_widget');