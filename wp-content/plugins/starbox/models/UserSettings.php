<?php

class ABH_Models_UserSettings {

    /**
     * Add the image for gravatar
     *
     * @param string $file
     * @param string $path
     * @return array [name (the name of the file), image (the path of the image), message (the returned message)]
     *
     */
    public function addImage($file, $path = ABSPATH) {
        $out = array();
        $out['name'] = strtolower(basename($file['name']));
        $out['gravatar'] = _ABH_GRAVATAR_DIR_ . strtolower(basename($file['name']));
        $out['message'] = '';
        $file_error = $file['error'];
        $img = new Model_ABH_Image();

        /* get the file extension */
        $file_name = explode('.', $file['name']);
        $file_type = strtolower($file_name[count($file_name) - 1]);

        /* if the file has a name */
        if (!empty($file['name'])) {

            /* Check the extension */
            $file_type = strtolower($file_type);
            $files = array('jpeg', 'jpg', 'gif', 'png');
            $key = in_array($file_type, $files);

            if (!$key) {
                ABH_Classes_Error::setError(__("File type error: Only JPEG, JPG, GIF or PNG files are allowed.", _ABH_PLUGIN_NAME_));
                return;
            }

            /* Check for error messages */
            $error_count = count($file_error);
            if (!empty($file_error) && $error_count > 0) {
                for ($i = 0; $i <= $error_count; ++$i) {
                    ABH_Classes_Error::setError($file['error'][$i]);
                    return;
                }
            } elseif (!$img->checkFunctions()) {

                ABH_Classes_Error::setError(__("GD error: The GD library must be installed on your server.", _ABH_PLUGIN_NAME_));
                return;
            } else {

                /* Delete the previous file if exists */
                if (is_file($out['gravatar'])) {
                    if (!unlink($out['gravatar'])) {
                        ABH_Classes_Error::setError(__("Delete error: Could not delete the old gravatar.", _ABH_PLUGIN_NAME_));
                        return;
                    }
                }

                /* Upload the file */
                if (!move_uploaded_file($file['tmp_name'], $out['gravatar'])) {
                    ABH_Classes_Error::setError(__("Upload error: Could not upload the gravatar.", _ABH_PLUGIN_NAME_));
                    return;
                }

                /* Change the permision */
                if (!chmod($out['gravatar'], 0755)) {
                    ABH_Classes_Error::setError(__("Permission error: Could not change the gravatar permissions.", _ABH_PLUGIN_NAME_));
                    return;
                }

                /* Transform the image into icon */
                $img->openImage($out['gravatar']);
                $img->resizeImage(80, 80);
                $img->saveImage();

                copy($img->image, $out['gravatar']);

                $out['message'] .= __("The gravatar has been updated.", _ABH_PLUGIN_NAME_);

                return $out;
            }
        }
    }

}

/**
 * Upload the image to the server
 */
class Model_ABH_Image {

    var $imageType;
    var $imgH;
    var $image;
    var $quality = 100;

    function openImage($image) {
        $this->image = $image;

        if (!file_exists($image))
            return false;

        $imageData = getimagesize($image);

        if (!$imageData) {
            return false;
        } else {
            $this->imageType = image_type_to_mime_type($imageData[2]);

            switch ($this->imageType) {
                case 'image/gif':
                    $this->imgH = imagecreatefromgif($image);
                    imagealphablending($this->imgH, true);
                    break;
                case 'image/png':
                    $this->imgH = imagecreatefrompng($image);
                    imagealphablending($this->imgH, true);
                    break;
                case 'image/jpg':
                case 'image/jpeg':
                    $this->imgH = imagecreatefromjpeg($image);
                    break;

                // CHANGED EXCEPTION TO RETURN FALSE
                default: return false; // throw new Exception('Unknown image format!');
            }
        }
    }

    function saveImage() {
        switch ($this->imageType) {
            case 'image/jpg':
            case 'image/jpeg':
                return @imagejpeg($this->imgH, $this->image, $this->quality);
                break;
            case 'image/gif':
                return @imagegif($this->imgH, $this->image);
                break;
            case 'image/png':
                return @imagepng($this->imgH, $this->image);
                break;
            default:
                return @imagejpeg($this->imgH, $this->image);
        }
        @imagedestroy($this->imgH);
    }

    function resizeImage($maxwidth, $maxheight, $preserveAspect = true) {
        $width = @imagesx($this->imgH);
        $height = @imagesy($this->imgH);

        if ($width > $maxwidth && $height > $maxheight) {
            $oldprop = round($width / $height, 2);
            $newprop = round($maxwidth / $maxheight, 2);
            $preserveAspectx = round($width / $maxwidth, 2);
            $preserveAspecty = round($height / $maxheight, 2);

            if ($preserveAspect) {
                if ($preserveAspectx < $preserveAspecty) {
                    $newwidth = $width / ($height / $maxheight);
                    $newheight = $maxheight;
                } else {
                    $newwidth = $maxwidth;
                    $newheight = $height / ($width / $maxwidth);
                }

                $dest = imagecreatetruecolor($newwidth, $newheight);
                $this->applyTransparency($dest);
                // CHANGED EXCEPTION TO RETURN FALSE
                if (imagecopyresampled($dest, $this->imgH, 0, 0, 0, 0, $newwidth, $newheight, $width, $height) == false)
                    return false; // throw new Exception('Couldn\'t resize image!');
            }else {
                $dest = imagecreatetruecolor($maxwidth, $maxheight);
                $this->applyTransparency($dest);
                // CHANGED EXCEPTION TO RETURN FALSE
                if (imagecopyresampled($dest, $this->imgH, 0, 0, 0, 0, $maxwidth, $maxheight, $width, $height) == false)
                    return false; // throw new Exception('Couldn\'t resize image!') ;
            }
            $this->imgH = $dest;
        }
    }

    function applyTransparency($imgH) {
        if ($this->imageType == 'image/png' || $this->imageType == 'image/gif') {
            imagealphablending($imgH, false);
            $col = imagecolorallocatealpha($imgH, 255, 255, 255, 127);
            imagefilledrectangle($imgH, 0, 0, 485, 500, $col);
            imagealphablending($imgH, true);
        }
    }

    function checkFunctions() {
        return function_exists('gd_info');
    }

}

?>