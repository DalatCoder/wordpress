<?php 
    $active_tab = 'main_options';

    if (isset($_GET['tab'])) {
        $active_tab = $_GET['tab'];
    }
?>

<div class="wrap">
    <h1><?= esc_html(get_admin_page_title()) ?></h1>
    <h2 class="nav-tab-wrapper">
        <a href="?page=mv_slider_admin&tab=main_options" class="nav-tab <?= $active_tab == 'main_options' ? 'nav-tab-active' : '' ?>"><?= esc_html_e('Main Options', 'mv-slider') ?></a>
        <a href="?page=mv_slider_admin&tab=additional_options" class="nav-tab <?= $active_tab == 'additional_options' ? 'nav-tab-active' : '' ?>"><?= esc_html_e('Additional Options', 'mv-slider') ?></a>
    </h2>
    <form action="options.php" method="post">
        <?php
            settings_fields('mv_slider_group');

            if ($active_tab == 'main_options') {
                do_settings_sections('mv_slider_settings_page1');
            } else if ($active_tab == 'additional_options') {
                do_settings_sections('mv_slider_settings_page2');
            }

            submit_button(esc_html__('Save settings', 'mv-slider'));
        ?>
    </form>
</div>