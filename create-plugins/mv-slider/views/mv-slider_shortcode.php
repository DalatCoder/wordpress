<h3><?= $slider_title ?></h3>

<div class="mv-slider flexslider <?= $slider_style ?>">
    <ul class="slides">
        <?php 
            if ($query->have_posts()):
                while($query->have_posts()):
                    $query->the_post();

                    $button_text = get_post_meta(get_the_ID(), 'mv_slider_link_text', true);
                    $button_url = get_post_meta(get_the_ID(), 'mv_slider_link_url', true);
        ?>
            <li>
                <?php 
                    if (has_post_thumbnail()) {
                        the_post_thumbnail('full', ['class' => 'img-fluid']);
                    } else {
                        echo "<img src='". $default_image_url ."' class='img-fluid wp-post-image' />";
                    }
                ?>
                <div class="mvs-container">
                    <div class="slider-details-container">
                        <div class="wrapper">
                            <div class="slider-title">
                                <h2><?php the_title(); ?></h2>
                            </div>
                            <div class="slider-description">
                                <div class="subtitle"><?php the_content(); ?></div>
                                <a class="link" href="<?= esc_attr($button_url) ?>"><?= esc_html($button_text) ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        <?php
            endwhile;
            wp_reset_postdata();
        endif;
        ?>
    </ul>
</div>