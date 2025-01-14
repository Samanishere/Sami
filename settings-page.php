<?php
$new_watermark_url = plugin_dir_url(__FILE__) . '../assets/images/watermark.png?' . time(); // Adding timestamp to prevent caching

if (isset($_POST['upload_watermark'])) {
    $watermark_path = plugin_dir_path(__FILE__) . '../assets/images/watermark.png';
    $watermark_exists = file_exists($watermark_path);

    if (!empty($_FILES['watermark_image']['tmp_name'])) {
        $uploaded_file = $_FILES['watermark_image'];
        $upload_dir = plugin_dir_path(__FILE__) . '../assets/images/';
        $target_file = $upload_dir . 'watermark.png';

        if (move_uploaded_file($uploaded_file['tmp_name'], $target_file)) {
            echo '<div class="updated"><p>Watermark image updated successfully!</p></div>';
        } else {
            echo '<div class="error"><p>Failed to upload watermark image.</p></div>';
        }
    } elseif (!$watermark_exists) {
        echo '<div class="error"><p>No file selected.</p></div>';
    }
}

if (isset($_POST['watermark_opacity']) && isset($_POST['watermark_rotation']) && isset($_POST['watermark_position'])) {
    update_option('watermark_opacity', intval($_POST['watermark_opacity']));
    update_option('watermark_rotation', intval($_POST['watermark_rotation']));
    update_option('watermark_position', sanitize_text_field($_POST['watermark_position']));
    echo '<div class="updated"><p>Settings updated successfully!</p></div>';
}

$current_opacity = get_option('watermark_opacity', 100);
$current_rotation = get_option('watermark_rotation', 0);
$current_position = get_option('watermark_position', 'center');

$watermark_file_url = plugin_dir_url(__FILE__) . '../assets/images/watermark.png';
$watermark_exists = file_exists(plugin_dir_path(__FILE__) . '../assets/images/watermark.png');
?>
<div class="wrap" style="max-width: 1280px;">
    <h1>Image Watermark</h1>
    <form method="post" enctype="multipart/form-data" id="watermark-form">
        <div style="display: flex;">
            <div style="flex: 1; margin-right: 20px;">
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Upload New Watermark</th>
                        <td>
                            <input type="file" name="watermark_image" id="watermark-image" accept="image/png, image/jpeg, image/gif, image/webp, image/avif" />
                            <p class="description">Upload a PNG, JPEG, GIF, WEBP, or AVIF file to use as the new watermark image.</p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Set Watermark Opacity</th>
                        <td>
                            <input type="number" name="watermark_opacity" id="watermark-opacity" min="0" max="100" value="<?php echo esc_attr($current_opacity); ?>" required />
                            <p class="description">Define the opacity level (0 to 100%) for the watermark.</p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Set Watermark Rotation</th>
                        <td>
                            <input type="number" name="watermark_rotation" id="watermark-rotation" min="0" max="360" value="<?php echo esc_attr($current_rotation); ?>" required />
                            <p class="description">Define the rotation angle (0 to 360 degrees) for the watermark.</p>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row">Set Watermark Position</th>
                        <td>
                            <select name="watermark_position" id="watermark-position" required>
                                <option value="top_left" <?php selected($current_position, 'top_left'); ?>>Top Left</option>
                                <option value="top_middle" <?php selected($current_position, 'top_middle'); ?>>Top Middle</option>
                                <option value="top_right" <?php selected($current_position, 'top_right'); ?>>Top Right</option>
                                <option value="middle_left" <?php selected($current_position, 'middle_left'); ?>>Middle Left</option>
                                <option value="center" <?php selected($current_position, 'center'); ?>>Center</option>
                                <option value="middle_right" <?php selected($current_position, 'middle_right'); ?>>Middle Right</option>
                                <option value="bottom_left" <?php selected($current_position, 'bottom_left'); ?>>Bottom Left</option>
                                <option value="bottom_middle" <?php selected($current_position, 'bottom_middle'); ?>>Bottom Middle</option>
                                <option value="bottom_right" <?php selected($current_position, 'bottom_right'); ?>>Bottom Right</option>
                                <option value="contain" <?php selected($current_position, 'contain'); ?>>Contain</option>
                                <option value="cover" <?php selected($current_position, 'cover'); ?>>Cover</option>
                            </select>
                            <p class="description">Choose the position of the watermark on the uploaded image.</p>
                        </td>
                    </tr>
                </table>
            </div>
            <div style="flex: 1;">
                <h2>Watermark Preview</h2>
                <div id="watermark-preview-container">
                    <?php if ($watermark_exists): ?>
                        <img src="<?php echo esc_url($new_watermark_url); ?>" id="watermark-preview" style="max-width: 100%; max-height: 100%;" alt="Watermark Preview">
                    <?php else: ?>
                        <p>No preview available</p>
                    <?php endif; ?>
                </div>
                <h2>Alignment Map</h2>
                <div id="alignment-map" style="background-image: url('<?php echo plugin_dir_url(__FILE__) . '../assets/images/alignment-background.jpeg'; ?>');">
                    <div class="region" data-region="top_left"></div>
                    <div class="region" data-region="top_middle"></div>
                    <div class="region" data-region="top_right"></div>
                    <div class="region" data-region="middle_left"></div>
                    <div class="region" data-region="center"></div>
                    <div class="region" data-region="middle_right"></div>
                    <div class="region" data-region="bottom_left"></div>
                    <div class="region" data-region="bottom_middle"></div>
                    <div class="region" data-region="bottom_right"></div>
                    <div class="region" data-region="contain"></div>
                </div>
                <input type="hidden" name="watermark_position" id="watermark-position-hidden" value="<?php echo esc_attr($current_position); ?>">
            </div>
        </div>
        <?php submit_button('Save Settings', 'primary', 'upload_watermark'); ?>
    </form>
</div>
<link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__) . '../assets/css/watermark-settings.css'; ?>">
<script src="<?php echo plugin_dir_url(__FILE__) . '../assets/js/watermark-settings.js'; ?>"></script>