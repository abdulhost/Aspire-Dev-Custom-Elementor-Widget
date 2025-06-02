<?php
class AspireDev_Courses_List_Widget extends \Elementor\Widget_Base {
    
    public function get_name() {
        return 'aspiredev-courses-list';
    }
    
    public function get_title() {
        return esc_html__('Duty Academy Courses List', 'aspiredev-elementor');
    }
    
    public function get_icon() {
        return 'eicon-post-list';
    }
    
    public function get_categories() {
        return ['aspire-custom-category'];
    }
    
    public function get_keywords() {
        return ['courses', 'learndash', 'list', 'grid', 'duty academy'];
    }
    
    protected function register_controls() {
        // Content Section
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content', 'aspiredev-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $this->add_control(
            'per_page',
            [
                'label' => esc_html__('Courses Per Page', 'aspiredev-elementor'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 6,
                'min' => 1,
                'max' => 20,
            ]
        );
        
        $this->add_control(
            'columns',
            [
                'label' => esc_html__('Desktop Columns', 'aspiredev-elementor'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '3',
                'options' => [
                    '1' => '1 Column',
                    '2' => '2 Columns',
                    '3' => '3 Columns',
                    '4' => '4 Columns',
                ],
            ]
        );
        
        $this->add_control(
            'show_progress',
            [
                'label' => esc_html__('Show Progress Bar', 'aspiredev-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_category_filter',
            [
                'label' => esc_html__('Show Category Filter', 'aspiredev-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        
        $this->add_control(
            'show_price',
            [
                'label' => esc_html__('Show Course Price', 'aspiredev-elementor'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );
        
        $this->end_controls_section();
        
        // Style Section
        $this->start_controls_section(
            'style_section',
            [
                'label' => esc_html__('Style', 'aspiredev-elementor'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        
        $this->add_control(
            'card_background',
            [
                'label' => esc_html__('Card Background Color', 'aspiredev-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#ffffff',
                'selectors' => [
                    '{{WRAPPER}} .aspiredev-course-card' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'button_color',
            [
                'label' => esc_html__('Button Color', 'aspiredev-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#06752E',
                'selectors' => [
                    '{{WRAPPER}} .aspiredev-course-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->add_control(
            'ribbon_color',
            [
                'label' => esc_html__('Status Ribbon Color', 'aspiredev-elementor'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#06752E',
                'selectors' => [
                    '{{WRAPPER}} .course-status-ribbon' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        
        $this->end_controls_section();
    }
    
    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $args = [
            'post_type' => 'sfwd-courses',
            'posts_per_page' => $settings['per_page'],
            'post_status' => 'publish',
            'orderby' => 'title',
            'order' => 'ASC',
        ];
        
        $query = new \WP_Query($args);
        
        ?>
        <div class="aspiredev-courses-container">
            <?php if ($settings['show_category_filter'] === 'yes') : ?>
                <div class="aspiredev-category-filter">
                    <select id="aspiredev-course-category" onchange="filterCourses(this.value)">
                        <option value="all"><?php esc_html_e('All Courses', 'aspiredev-elementor'); ?></option>
                        <?php
                        $categories = get_terms(['taxonomy' => 'ld_course_category', 'hide_empty' => true]);
                        foreach ($categories as $category) {
                            echo '<option value="' . esc_attr($category->slug) . '">' . esc_html($category->name) . '</option>';
                        }
                        ?>
                    </select>
                </div>
            <?php endif; ?>
            
            <div class="aspiredev-courses-list" style="grid-template-columns: repeat(<?php echo esc_attr($settings['columns']); ?>, 1fr);">
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                    <div class="aspiredev-course-card" data-category="<?php echo esc_attr(implode(' ', wp_list_pluck(get_the_terms(get_the_ID(), 'ld_course_category'), 'slug'))); ?>">
                        <?php if (is_user_logged_in() && function_exists('learndash_user_get_course_progress')) : 
                            $progress = learndash_user_get_course_progress(null, get_the_ID());
                            $status = $progress['status'] ?? 'not_enrolled';
                            ?>
                            <div class="course-status-ribbon <?php echo esc_attr($status); ?>">
                                <?php
                                if ($status === 'completed') {
                                    echo esc_html__('Completed', 'aspiredev-elementor');
                                } elseif ($status === 'in_progress') {
                                    echo esc_html__('In Progress', 'aspiredev-elementor');
                                } else {
                                    echo esc_html__('New', 'aspiredev-elementor');
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                        <div class="course-thumbnail">
                            <?php if (has_post_thumbnail()) : ?>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium', ['class' => 'course-image']); ?>
                                </a>
                            <?php else : ?>
                                <a href="<?php the_permalink(); ?>">
                                    <img src="<?php echo esc_url(plugins_url('classroom.png', __FILE__)); ?>" class="course-image" alt="<?php esc_attr_e('Course Placeholder', 'aspiredev-elementor'); ?>">
                                </a>
                            <?php endif; ?>
                        </div>
                        <div class="course-details">
                            <h3 class="course-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            <div class="course-excerpt">
                                <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                            </div>
                            <?php if ($settings['show_price'] === 'yes') : ?>
                                <div class="course-price">
                                    <?php
                                    $price = get_post_meta(get_the_ID(), '_sfwd-courses', true)['sfwd-courses_course_price'] ?? esc_html__('Free', 'aspiredev-elementor');
                                    echo esc_html($price);
                                    ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($settings['show_progress'] === 'yes' && function_exists('learndash_course_progress')) : ?>
                                <div class="course-progress">
                                    <?php echo do_shortcode('[learndash_course_progress course_id="' . get_the_ID() . '"]'); ?>
                                </div>
                            <?php endif; ?>
                            <a href="<?php the_permalink(); ?>" class="aspiredev-course-button"><?php esc_html_e('View Course', 'aspiredev-elementor'); ?></a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
        <?php wp_reset_postdata(); ?>
        
        <script>
            function filterCourses(category) {
                const cards = document.querySelectorAll('.aspiredev-course-card');
                cards.forEach(card => {
                    if (category === 'all' || card.dataset.category.split(' ').includes(category)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }
        </script>
        
        <style>
            .aspiredev-courses-container {
                max-width: 1280px;
                margin: 0 auto;
                padding: 2rem 1rem;
                box-sizing: border-box;
            }
            .aspiredev-category-filter {
                margin-bottom: 1.5rem;
                /* text-align: center; */
            }
            .aspiredev-category-filter select {
                padding: 0.75rem;
                font-size: 1rem;
                border-radius: 6px;
                border: 1px solid #ccc;
                width: 100%;
                max-width: 300px;
                background-color: #fff;
                cursor: pointer;
                transition: border-color 0.3s;
            }
            .aspiredev-category-filter select:focus {
                outline: none;
                border-color: #06752E;
            }
            .aspiredev-courses-list {
                display: grid;
                gap: 1.5rem;
            }
            .aspiredev-course-card {
                position: relative;
                border: 1px solid #e0e0e0;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                background-color: #fff;
            }
            .aspiredev-course-card:hover, .aspiredev-course-card:focus-within {
                transform: translateY(-5px);
                box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
            }
            .course-status-ribbon {
                position: absolute;
                top: 0.75rem;
                right: 0.75rem;
                padding: 0.4rem 0.8rem;
                color: #fff;
                font-size: 0.85rem;
                font-weight: 500;
                border-radius: 4px;
                z-index: 10;
            }
            .course-status-ribbon.completed {
                background-color: #28a745;
            }
            .course-status-ribbon.in_progress {
                background-color: #007bff;
            }
            .course-status-ribbon.not_enrolled {
                background-color: #6c757d;
            }
            .course-thumbnail {
                position: relative;
                overflow: hidden;
            }
            .course-thumbnail img {
                width: 100%;
                aspect-ratio: 16 / 9;
                object-fit: contain;
                display: block;
            }
            .course-details {
                padding: 1.5rem;
            }
            .course-title {
                font-size: clamp(1.1rem, 2.5vw, 1.3rem);
                margin: 0 0 0.75rem;
                color: #333;
                line-height: 1.4;
            }
            .course-excerpt {
                font-size: clamp(0.85rem, 2vw, 0.9rem);
                color: #666;
                margin-bottom: 1rem;
                line-height: 1.5;
            }
            .course-price {
                font-size: clamp(0.9rem, 2vw, 1rem);
                color: #06752E;
                font-weight: 600;
                margin-bottom: 0.75rem;
            }
            .course-progress {
                margin: 0.75rem 0;
            }
            .aspiredev-course-button {
                display: inline-block;
                padding: 0.75rem 1.5rem;
                color: #fff;
                text-decoration: none;
                border-radius: 6px;
                font-weight: 500;
                font-size: clamp(0.9rem, 2vw, 1rem);
                transition: opacity 0.3s, background-color 0.3s;
                text-align: center;
                width: 100%;
                box-sizing: border-box;
            }
            .aspiredev-course-button:hover, .aspiredev-course-button:focus {
                opacity: 0.9;
            }
            /* Responsive Breakpoints */
            @media (max-width: 1200px) {
                .aspiredev-courses-container {
                    max-width: 960px;
                }
                .aspiredev-courses-list {
                    grid-template-columns: repeat(<?php echo esc_attr(min(3, $settings['columns'])); ?>, 1fr);
                }
            }
            @media (max-width: 992px) {
                .aspiredev-courses-container {
                    max-width: 720px;
                }
                .aspiredev-courses-list {
                    grid-template-columns: repeat(2, 1fr)!important;
                }
                .course-details {
                    padding: 1.25rem;
                }
            }
            @media (max-width: 576px) {
                .aspiredev-courses-container {
                    padding: 1rem 0.5rem;
                }
                .aspiredev-courses-list {
                    grid-template-columns: 1fr !important;
                }
                .aspiredev-category-filter {
                text-align: center;
                }
                .aspiredev-category-filter select {
                    max-width: 100%;
                    font-size: 0.95rem;
                }
                .course-details {
                    padding: 1rem;
                }
                .course-title {
                    font-size: 1.1rem;
                }
                .course-excerpt {
                    font-size: 0.85rem;
                }
                .aspiredev-course-button {
                    padding: 0.6rem 1rem;
                    font-size: 0.9rem;
                }
            }
        </style>
    <?php }
}
?>