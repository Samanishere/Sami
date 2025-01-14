<?php
if (!class_exists('Image_Watermark')) {
    class Image_Watermark {
        public static function add_custom_watermark($file) {
            $image_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/avif'];
            if (in_array($file['type'], $image_types)) {
                $source_image_path = $file['file'];
                $watermark_image_path = plugin_dir_path(__FILE__) . '../assets/images/watermark.png';

                if (file_exists($watermark_image_path)) {
                    // Handling image creation based on the file type
                    switch ($file['type']) {
                        case 'image/jpeg':
                            $source_image = imagecreatefromjpeg($source_image_path);
                            break;
                        case 'image/png':
                            $source_image = imagecreatefrompng($source_image_path);
                            break;
                        case 'image/gif':
                            $source_image = imagecreatefromgif($source_image_path);
                            break;
                        case 'image/webp':
                            $source_image = imagecreatefromwebp($source_image_path);
                            break;
                        case 'image/avif':
                            $source_image = imagecreatefromavif($source_image_path);
                            break;
                        default:
                            return $file;
                    }

                    $watermark_image = imagecreatefrompng($watermark_image_path);

                    $source_width = imagesx($source_image);
                    $source_height = imagesy($source_image);
                    $watermark_width = imagesx($watermark_image);
                    $watermark_height = imagesy($watermark_image);

                    $opacity = get_option('watermark_opacity', 100);
                    $rotation = get_option('watermark_rotation', 0);
                    $position = get_option('watermark_position', 'center');

                    switch ($position) {
                        case 'contain':
                            $scaled_width = $source_width * 0.8;
                            $scaled_height = $source_height * 0.8;
                            break;
                        case 'cover':
                            $scaled_width = $source_width;
                            $scaled_height = $source_height;
                            break;
                        default:
                            $scaled_width = $watermark_width;
                            $scaled_height = $watermark_height;
                            break;
                    }

                    $resized_watermark = imagecreatetruecolor($scaled_width, $scaled_height);
                    imagealphablending($resized_watermark, false);
                    imagesavealpha($resized_watermark, true);
                    imagecopyresampled($resized_watermark, $watermark_image, 0, 0, 0, 0, $scaled_width, $scaled_height, $watermark_width, $watermark_height);

                    if ($opacity < 100) {
                        imagefilter($resized_watermark, IMG_FILTER_COLORIZE, 0, 0, 0, 127 - (127 * ($opacity / 100)));
                    }

                    // Rotate the watermark
                    if ($rotation != 0) {
                        $resized_watermark = imagerotate($resized_watermark, $rotation, imageColorAllocateAlpha($resized_watermark, 0, 0, 0, 127));
                    }

                    // Determine the position of the watermark
                    switch ($position) {
                        case 'top_left':
                            $x = 0;
                            $y = 0;
                            break;
                        case 'top_middle':
                            $x = ($source_width - $scaled_width) / 2;
                            $y = 0;
                            break;
                        case 'top_right':
                            $x = $source_width - $scaled_width;
                            $y = 0;
                            break;
                        case 'middle_left':
                            $x = 0;
                            $y = ($source_height - $scaled_height) / 2;
                            break;
                        case 'center':
                            $x = ($source_width - $scaled_width) / 2;
                            $y = ($source_height - $scaled_height) / 2;
                            break;
                        case 'middle_right':
                            $x = $source_width - $scaled_width;
                            $y = ($source_height - $scaled_height) / 2;
                            break;
                        case 'bottom_left':
                            $x = 0;
                            $y = $source_height - $scaled_height;
                            break;
                        case 'bottom_middle':
                            $x = ($source_width - $scaled_width) / 2;
                            $y = $source_height - $scaled_height;
                            break;
                        case 'bottom_right':
                            $x = $source_width - $scaled_width;
                            $y = $source_height - $scaled_height;
                            break;
                        case 'top_row':
                            $x = 0;
                            $y = 0;
                            $scaled_width = $source_width;
                            break;
                        case 'middle_row':
                            $x = 0;
                            $y = ($source_height - $scaled_height) / 2;
                            $scaled_width = $source_width;
                            break;
                        case 'bottom_row':
                            $x = 0;
                            $y = $source_height - $scaled_height;
                            $scaled_width = $source_width;
                            break;
                        case 'contain':
                            $x = $source_width * 0.1;
                            $y = $source_height * 0.1;
                            $scaled_width = $source_width * 0.8;
                            $scaled_height = $source_height * 0.8;
                            break;
                        case 'cover':
                            $x = 0;
                            $y = 0;
                            $scaled_width = $source_width;
                            $scaled_height = $source_height;
                            break;
                        default:
                            $x = ($source_width - $scaled_width) / 2;
                            $y = ($source_height - $scaled_height) / 2;
                            break;
                    }

                    imagecopy($source_image, $resized_watermark, $x, $y, 0, 0, $scaled_width, $scaled_height);

                    switch ($file['type']) {
                        case 'image/jpeg':
                            imagejpeg($source_image, $source_image_path, 90);
                            break;
                        case 'image/png':
                            imagepng($source_image, $source_image_path);
                            break;
                        case 'image/gif':
                            imagegif($source_image, $source_image_path);
                            break;
                        case 'image/webp':
                            imagewebp($source_image, $source_image_path);
                            break;
                        case 'image/avif':
                            imageavif($source_image, $source_image_path);
                            break;
                    }

                    imagedestroy($source_image);
                    imagedestroy($watermark_image);
                    imagedestroy($resized_watermark);
                }
            }
            return $file;
        }
    }
}
?>