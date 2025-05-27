<?php
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

        // Video Settings Section
        $this->start_controls_section(
            'video_settings_section',
            [
                'label' => __('Video Settings', 'text-domain'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => __('Autoplay Video', 'text-domain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'text-domain'),
                'label_off' => __('No', 'text-domain'),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => __('Enable or disable autoplay when the video popup opens.', 'text-domain'),
            ]
        );

        $this->add_control(
            'show_controls',
            [
                'label' => __('Show Video Controls', 'text-domain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'text-domain'),
                'label_off' => __('No', 'text-domain'),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => __('Show or hide YouTube player controls.', 'text-domain'),
            ]
        );

        $this->add_control(
            'loop_video',
            [
                'label' => __('Loop Video', 'text-domain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'text-domain'),
                'label_off' => __('No', 'text-domain'),
                'return_value' => 'yes',
                'default' => 'no',
                'description' => __('Loop the video in the popup.', 'text-domain'),
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
            'alignment',
            [
                'label' => __('Alignment', 'text-domain'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'text-domain'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'text-domain'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'text-domain'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'selectors' => [
                    '{{WRAPPER}} .video-box' => 'margin-{{VALUE}}: 0;',
                ],
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
                'condition' => [
                    'show_play_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'show_play_icon',
            [
                'label' => __('Show Play Icon', 'text-domain'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'text-domain'),
                'label_off' => __('Hide', 'text-domain'),
                'return_value' => 'yes',
                'default' => 'yes',
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

        $this->add_responsive_control(
            'text_padding',
            [
                'label' => __('Text Padding', 'text-domain'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 5, 'max' => 50],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .video-text' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'hover_scale',
            [
                'label' => __('Hover Scale Intensity', 'text-domain'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 1.0, 'max' => 1.2, 'step' => 0.01],
                ],
                'default' => [
                    'size' => 1.01,
                ],
                'selectors' => [
                    '{{WRAPPER}} .video-box:hover' => 'transform: scale({{SIZE}});',
                ],
            ]
        );

        $this->add_control(
            'transition_duration',
            [
                'label' => __('Hover Transition Duration (ms)', 'text-domain'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 100, 'max' => 1000, 'step' => 50],
                ],
                'default' => [
                    'size' => 300,
                ],
                'selectors' => [
                    '{{WRAPPER}} .video-box' => 'transition-duration: {{SIZE}}ms;',
                ],
            ]
        );

        $this->add_control(
            'thumbnail_aspect_ratio',
            [
                'label' => __('Thumbnail Aspect Ratio', 'text-domain'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '16 / 9' => __('16:9', 'text-domain'),
                    '4 / 3' => __('4:3', 'text-domain'),
                    '1 / 1' => __('1:1', 'text-domain'),
                    '3 / 2' => __('3:2', 'text-domain'),
                ],
                'default' => '16 / 9',
                'selectors' => [
                    '{{WRAPPER}} .video-box' => 'aspect-ratio: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'label' => __('Box Shadow', 'text-domain'),
                'selector' => '{{WRAPPER}} .video-box',
            ]
        );

        $this->add_control(
            'thumbnail_opacity',
            [
                'label' => __('Thumbnail Opacity', 'text-domain'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 0.1, 'max' => 1, 'step' => 0.1],
                ],
                'default' => [
                    'size' => 1,
                ],
                'selectors' => [
                    '{{WRAPPER}} .video-box' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'label' => __('Border', 'text-domain'),
                'selector' => '{{WRAPPER}} .video-box',
            ]
        );

        $this->end_controls_section();

        // Color Section
        $this->start_controls_section(
            'color_section',
            [
                'label' => __('Colors', 'text-domain'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

      

        $this->add_control(
            'overlay_color_start',
            [
                'label' => __('Overlay Bottom Color (20%)', 'text-domain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgb(6, 117, 46)', 
                'selectors' => [
                    '{{WRAPPER}} .video-box::before' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'overlay_color_end',
            [
                'label' => __('Overlay Top Color (80%)', 'text-domain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgb(0, 0, 0)', 
                'selectors' => [
                    '{{WRAPPER}} .video-box::after' => 'background: {{VALUE}};',
                ],
            ]
        );

    $this->add_control(
            'play_icon_color',
            [
                'label' => __('Play Icon Color', 'text-domain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .play-icon' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_play_icon' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label' => __('Title Color', 'text-domain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .video-title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'description_color',
            [
                'label' => __('Description Color', 'text-domain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(255,255,255,0.85)',
                'selectors' => [
                    '{{WRAPPER}} .video-desc' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Text Styling Section
        $this->start_controls_section(
            'text_styling_section',
            [
                'label' => __('Text Styling', 'text-domain'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'title_font_size',
            [
                'label' => __('Title Font Size', 'text-domain'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vw'],
                'range' => [
                    'px' => ['min' => 12, 'max' => 40],
                    'vw' => ['min' => 1, 'max' => 5],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 20,
                ],
                'selectors' => [
                    '{{WRAPPER}} .video-title' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'title_font_weight',
            [
                'label' => __('Title Font Weight', 'text-domain'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'normal' => __('Normal', 'text-domain'),
                    'bold' => __('Bold', 'text-domain'),
                    '100' => __('100', 'text-domain'),
                    '200' => __('200', 'text-domain'),
                    '300' => __('300', 'text-domain'),
                    '400' => __('400', 'text-domain'),
                    '500' => __('500', 'text-domain'),
                    '600' => __('600', 'text-domain'),
                    '700' => __('700', 'text-domain'),
                    '800' => __('800', 'text-domain'),
                    '900' => __('900', 'text-domain'),
                ],
                'default' => 'bold',
                'selectors' => [
                    '{{WRAPPER}} .video-title' => 'font-weight: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'description_font_size',
            [
                'label' => __('Description Font Size', 'text-domain'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'vw'],
                'range' => [
                    'px' => ['min' => 10, 'max' => 30],
                    'vw' => ['min' => 1, 'max' => 4],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 14,
                ],
                'selectors' => [
                    '{{WRAPPER}} .video-desc' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'description_font_weight',
            [
                'label' => __('Description Font Weight', 'text-domain'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'normal' => __('Normal', 'text-domain'),
                    'bold' => __('Bold', 'text-domain'),
                    '100' => __('100', 'text-domain'),
                    '200' => __('200', 'text-domain'),
                    '300' => __('300', 'text-domain'),
                    '400' => __('400', 'text-domain'),
                    '500' => __('500', 'text-domain'),
                    '600' => __('600', 'text-domain'),
                    '700' => __('700', 'text-domain'),
                    '800' => __('800', 'text-domain'),
                    '900' => __('900', 'text-domain'),
                ],
                'default' => 'normal',
                'selectors' => [
                    '{{WRAPPER}} .video-desc' => 'font-weight: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        // Popup Style Section
        $this->start_controls_section(
            'popup_style_section',
            [
                'label' => __('Popup Style', 'text-domain'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'popup_background_color',
            [
                'label' => __('Popup Background Color', 'text-domain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => 'rgba(0, 0, 0, 0.85)',
                'selectors' => [
                    '{{WRAPPER}} .video-popup' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'close_button_color',
            [
                'label' => __('Close Button Color', 'text-domain'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .closeBtn' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'close_button_size',
            [
                'label' => __('Close Button Size', 'text-domain'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => ['min' => 20, 'max' => 60],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .closeBtn' => 'font-size: {{SIZE}}{{UNIT}};',
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
    $thumbnail_url = $settings['thumbnail']['url'];
    // Check if custom thumbnail is empty or set to default placeholder
    if (empty($thumbnail_url) || $thumbnail_url === 'https://via.placeholder.com/800x450.jpg?text=Video+Thumbnail') {
        // Use YouTube thumbnail if video ID is available
        $thumbnail_url = !empty($video_id) ? "https://img.youtube.com/vi/{$video_id}/hqdefault.jpg" : 'https://via.placeholder.com/800x450.jpg?text=Video+Thumbnail';
    }

    $youtube_params = [];
    if ($settings['autoplay'] === 'yes') {
        $youtube_params[] = 'autoplay=1';
    }
    if ($settings['show_controls'] !== 'yes') {
        $youtube_params[] = 'controls=0';
    }
    if ($settings['loop_video'] === 'yes') {
        $youtube_params[] = 'loop=1';
        $youtube_params[] = 'playlist=' . esc_attr($video_id); // Required for looping
    }
    $youtube_query = !empty($youtube_params) ? '?' . implode('&', $youtube_params) : '';

    ?>
    <style>
        /* Container Styles */
#<?php echo esc_attr($unique_id); ?> {
    position: relative;
    width: 100%;
    background-image: url('<?php echo esc_url($thumbnail_url); ?>');
    background-size: cover;
    background-position: center;
    cursor: pointer;
    overflow: hidden;
    display: flex;
    align-items: flex-end;
}
#<?php echo esc_attr($unique_id); ?>::before {
    content: "";
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 40%; /* Extended height for smoother gradient */
    background: linear-gradient(to top, <?php echo esc_attr($settings['overlay_color_start']); ?> 0%, transparent 60%);
    z-index: 1;
}
#<?php echo esc_attr($unique_id); ?> .play-icon {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 2;
    pointer-events: none;
    transition: opacity 0.3s ease;
    display: <?php echo $settings['show_play_icon'] === 'yes' ? 'block' : 'none'; ?>;
}
#<?php echo esc_attr($unique_id); ?>:hover .play-icon {
    opacity: 0.9;
}
#<?php echo esc_attr($unique_id); ?> .video-text {
    position: relative;
    z-index: 2;
    width: 100%;
    box-sizing: border-box;
}
#<?php echo esc_attr($unique_id); ?> .video-title {
    margin-bottom: 8px;
}
#<?php echo esc_attr($unique_id); ?>_popup {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
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
        <?php if ($settings['show_play_icon'] === 'yes'): ?>
            <div class="play-icon">
                <svg style="width:28px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                    
                    <path fill="white" d="M73 39c-14.8-9.1-33.4-9.4-48.5-.9S0 62.6 0 80L0 432c0 17.4 9.4 33.4 24.5 41.9s33.7 8.1 48.5-.9L361 297c14.3-8.7 23-24.2 23-41s-8.7-32.2-23-41L73 39z"/>
                </svg>
            </div>
        <?php endif; ?>
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
                document.getElementById('<?php echo esc_attr($unique_id); ?>_iframe').src = "https://www.youtube.com/embed/<?php echo esc_attr($video_id); ?><?php echo $youtube_query; ?>";
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