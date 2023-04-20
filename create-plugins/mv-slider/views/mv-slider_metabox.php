<?php 
    $text = get_post_meta($post->ID, 'mv_slider_link_text', true);
    $url = get_post_meta($post->ID, 'mv_slider_link_url', true);

    if (!isset($text)) {
        $text = "";
    }

    if (!isset($url)) {
        $url = "";
    }

    $text = esc_html($text);
    $url = esc_url($url);
?>

<table class="form-table mv-slider-metabox">
    <input type="hidden" name="mv_slider_nonce" value="<?= wp_create_nonce("mv_slider_nonce") ?>">
    <tr>
        <th>
            <label for="mv_slider_link_text"><?= esc_html_e('Link Text', 'mv-slider') ?></label>
        </th>
        <td>
            <input type="text" name="mv_slider_link_text" id="mv_slider_link_text" class="regular-text link-text"
                value="<?= $text ?>" required>
        </td>
    </tr>

    <tr>
        <th>
            <label for="mv_slider_link_url"><?= esc_html_e('Link URL', 'mv-slider') ?></label>
        </th>
        <td>
            <input type="url" name="mv_slider_link_url" id="mv_slider_link_url" class="regular-text link-url"
                value="<?= $url ?>" required>
        </td>
    </tr>
</table>