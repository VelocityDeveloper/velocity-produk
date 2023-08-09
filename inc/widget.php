<?php
// Creating the widget
class velocity_listproduk_widget extends WP_Widget
{

    function __construct()
    {
        parent::__construct(

            // Base ID of your widget
            'velocity_listproduk_widget',

            // Widget name will appear in UI
            __('Velocity Produk', 'velocityproduk'),

            // Widget description
            array('description' => __('Menampilkan Produk Terbaru', 'velocityproduk'),)
        );
    }

    // Creating widget front-end

    public function widget($args, $instance)
    {
        $title      = apply_filters('widget_title', $instance['title']);
        $perpage    = isset($instance['perpage']) ? $instance['perpage'] : '-1';

        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if (!empty($title))
            echo $args['before_title'] . $title . $args['after_title'];

        // This is where you run the code and display the output
        // The Query.
        $args = array(
            'post_type'         => 'produk',
            'orderby'           => 'date',
            'order'             => 'desc',
            'posts_per_page'    => $perpage,
        );

        $the_query = new WP_Query($args);

        // The Loop.
        if ($the_query->have_posts()) {
            echo '<ul class="list-group list-group-flush">';
            while ($the_query->have_posts()) {
                $the_query->the_post();
                echo '<li class="list-group-item px-0">';
                echo '<div class="row align-items-center">';
                echo '<div class="col-3 pr-0 pe-0">';
                echo '<a href="' . get_the_permalink() . '">';
                echo '<div class="ratio-thumb-image">';
                if (has_post_thumbnail()) :
                    echo get_the_post_thumbnail(get_the_ID(), 'thumnbail', array('class' => 'card-img-top', 'loading' => 'lazy'));
                else :
                    echo '<svg viewBox="0 0 150 150" style="background-color: #ececec;" xmlns="http://www.w3.org/2000/svg"></svg>';
                endif;
                echo '</div>';
                echo '</a>';
                echo '</div>';
                echo '<div class="col-8">';
                echo '<a href="' . get_the_permalink() . '">' . esc_html(get_the_title()) . '</a>';

                echo '<div class="mt-1">';
                $velharga       = get_post_meta(get_the_ID(), 'ak_harga', true);
                $velhargadis    = get_post_meta(get_the_ID(), 'ak_harga_dis', true);
                echo $velhargadis ? '<small><s color: #c01a1a;> Rp ' : 'Rp ';
                echo $velharga ? number_format($velharga, 2) : '-';
                echo $velhargadis ? '</s></small> Rp ' . number_format($velhargadis, 2) : '';
                echo '</div>';

                echo '</div>';
                echo '</div>';
                echo '</li>';
            }
            echo '</ul>';
        } else {
            esc_html_e('produk kosong');
        }
        // Restore original Post Data.
        wp_reset_postdata();

        echo $args['after_widget'];
    }

    // Widget Backend
    public function form($instance)
    {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('Produk Terbaru', 'velocityproduk');
        }

        $perpage = isset($instance['perpage']) ? $instance['perpage'] : '5';
        // Widget admin form
?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('perpage'); ?>">
                <?php _e('Jumlah Post Ditampilkan (optional):'); ?>
            </label>
            <input id="<?php echo $this->get_field_id('perpage'); ?>" class="widefat" type="number" name="<?php echo $this->get_field_name('perpage'); ?>" value="<?php echo $perpage; ?>" />
        </p>
<?php
    }

    // Updating widget replacing old instances with new
    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['perpage'] = (!empty($new_instance['perpage'])) ? strip_tags($new_instance['perpage']) : '';
        return $instance;
    }

    // Class velocity_listproduk_widget ends here
}

// Register and load the widget
function wpb_load_widget()
{
    register_widget('velocity_listproduk_widget');
}
add_action('widgets_init', 'wpb_load_widget');
