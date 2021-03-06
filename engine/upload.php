<?php
@ini_set('memory_limit', '300M');

$megaByte = 1024 * 1024;

if (!function_exists('str_split')) {
    function str_split($str, $nr = 1)
    {
        return array_slice(split('-l-', chunk_split($str, $nr, '-l-')), 0, -1);
    }
}

$phpsess = !empty($_GET['session_id']) ? $_GET['session_id'] : false;
if ($phpsess) {
    session_id($phpsess);
}
$settingsProperty = !empty($_GET['property']) ? $_GET['property'] : false;
$sectionName = !empty($_GET['section']) ? $_GET['section'] : false;
$entryId = !empty($_GET['entry']) ? $_GET['entry'] : false;
$mediaFolder = !empty($_GET['mediafolder']) ? $_GET['mediafolder'] : false;
$posterFor = !empty($_GET['poster_for']) ? $_GET['poster_for'] : false;
$uplosadType = !empty($_REQUEST['upload_type']) ? $_REQUEST['upload_type'] : 'fancy';
$sectionBackground = !empty($_GET['section_background']) ? $_GET['section_background'] : false;

$paramMinWidth = !empty($_REQUEST['min_width']) ? $_REQUEST['min_width'] : false;
$paramMinHeight = !empty($_REQUEST['min_height']) ? $_REQUEST['min_height'] : false;
$paramMaxWidth = !empty($_REQUEST['max_width']) ? $_REQUEST['max_width'] : false;
$paramMaxHeight = !empty($_REQUEST['max_height']) ? $_REQUEST['max_height'] : false;

$paramSmallWidth = !empty($_REQUEST['small_width']) ? $_REQUEST['small_width'] : false;
$paramSmallHeight = !empty($_REQUEST['small_height']) ? $_REQUEST['small_height'] : false;
$paramLargeWidth = !empty($_REQUEST['large_width']) ? $_REQUEST['large_width'] : false;
$paramLargeHeight = !empty($_REQUEST['large_height']) ? $_REQUEST['large_height'] : false;

define('AUTH_AUTHREQUIRED', true);
define('DO_UPLOAD', true);
define('BERTA_ENVIRONMENT', 'engine');
include_once 'inc.page.php';
include_once '_classes/Zend/Json.php';
include_once '_classes/class.bertaeditor.php';

/* Media size limits in Mega Bytes */
$constraints['max_img_size_mb'] = 3;
$constraints['max_gif_size_mb'] = 5;
$constraints['max_video_size_mb'] = 256;

// if image is being uploaded for a settings, then different constraints apply
if ($settingsProperty) {
    $settings = $berta->settings;
    $settingsPropertyTemplate = false;
    $settingsProperty = explode('/', $settingsProperty);
    if (count($settingsProperty) == 3) {	// if there is a template component
        $settingsPropertyTemplate = array_shift($settingsProperty);
        $settings = $berta->template->settings;
    }

    if (count($settingsProperty) != 2 || $settingsProperty[0] != 'siteTexts' && !$settings->definitionExists($settingsProperty[0], $settingsProperty[1])) {
        $settingsProperty = false;
    } else {
        $conProps = ['min_width', 'min_height', 'max_width', 'max_height'];
        foreach ($conProps as $cp) {
            $c = $settings->getDefinitionParam($settingsProperty[0], $settingsProperty[1], $cp);
            if ($c) {
                $constraints[$cp] = $c;
                if (substr($constraints[$cp], 0, 7) == 'setting') {
                    $s = explode(':', $constraints[$cp]);
                    $sInstance = $settings;
                    if ($s[1] == 'template') {
                        $sInstance = $berta->template->settings;
                    }
                    $constraints[$cp] = (int) $sInstance->get($s[2], $s[3]);
                }
            }
        }
    }
} elseif ($sectionName) {
    $sections = BertaContent::getSections();
    if (!empty($sections[$sectionName])) {
        $type = !empty($sections[$sectionName]['@attributes']['type']) ? $sections[$sectionName]['@attributes']['type'] : 'default';
    }
}

$badChars = str_split('#$%^&*\\/.,><~`@');
$badChars = array_push($badChars, '--');
$videoExtensions = ['flv', 'mp4'];
$iconExtensions = ['ico'];

$result = [];

if (($entryId && $mediaFolder || $settingsProperty || $sectionName && $mediaFolder) && isset($_FILES['Filedata'])) {
    $file = $_FILES['Filedata']['tmp_name'];
    $error = false;
    $imInfo = false;
    $videoExt = false;
    $fileExt = false;
    $fileType = '';

    if ((!is_uploaded_file($file) && $_FILES['Filedata']['error'] === UPLOAD_ERR_INI_SIZE) || ($_FILES['Filedata']['size'] > $constraints['max_video_size_mb'] * $megaByte)) {
        $error = "Please upload video files smaller than <strong>{$constraints['max_video_size_mb']}MB</strong>!";
    } elseif (!is_uploaded_file($file)) {
        $error = 'Failed to upload file!';
    }

    if (!$error) {
        $ext = strtolower(substr(strrchr($_FILES['Filedata']['name'], '.'), 1));
        if (in_array($ext, $videoExtensions)) {
            if ($sectionBackground) {
                $error = 'Videos not supported in background gallery!';
            }

            $videoExt = $ext;
            $fileType = 'video';
        } elseif (!$sectionBackground && in_array($ext, $iconExtensions)) {
            $fileExt = $ext;
            $fileType = 'icon';
        } else {
            if (!($imInfo = @getimagesize($file)) || (!$error && !in_array($imInfo[2], [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG]))) {
                if ($sectionBackground) {
                    $error = 'Only JPG, GIF, PNG images are supported.';
                } else {
                    $error = 'Only JPG, GIF, PNG images and MP4, FLV videos are supported.';
                }
            }

            if (!$error && $imInfo[2] == IMAGETYPE_GIF && filesize($file) > ($constraints['max_gif_size_mb'] * $megaByte)) {
                $error = 'Please upload GIF image smaller then <strong>' . $constraints['max_gif_size_mb'] . 'MB</strong>!';
            } elseif (!$error && filesize($file) > ($constraints['max_img_size_mb'] * $megaByte)) {
                $error = 'Please upload image smaller then <strong>' . $constraints['max_img_size_mb'] . 'MB</strong>!';
            }

            $fileType = 'image';
        }
    }

    $addr = gethostbyaddr($_SERVER['REMOTE_ADDR']);

    if ($error) {
        $result['status'] = 0;
        $result['error'] = $error;
    } else {
        // set and check (and create if needed) the containing folder
        $fileFolder = $options['MEDIA_ROOT'];
        $fileFolderUrl = $options['MEDIA_URL'];
        if ($mediaFolder) {
            $fileFolder = $options['MEDIA_ROOT'] . $mediaFolder;
            $fileFolderUrl = $options['MEDIA_URL'] . $mediaFolder;
            if (!file_exists($fileFolder) || !is_dir($fileFolder)) {
                if (!@mkdir($fileFolder, 0777)) {
                    $result['status'] = 0;
                    $result['error'] = 'Cannot create media folder! Check permissions of the storage folder.';
                }
            } elseif (!is_writable($fileFolder)) {
                if (!@chmod($fileFolder, 0777)) {
                    $result['status'] = 0;
                    $result['error'] = 'Media folder is not writable!';
                }
            }
            $fileFolder .= '/';
            $fileFolderUrl .= '/';
        }

        if (empty($result['error'])) {
            // set and check the file name, remove bad chars
            if ($videoExt) {
                $ext = $videoExt;
                $fName = strtolower(BertaUtils::canonizeString($_FILES['Filedata']['name'], '_', '\.-'));
                if (substr($fName, 0, 1) == '_') {
                    $fName = rand(1, 100) . $fName;
                } // only derivatives start with "_"
            } elseif ($fileExt) {
                $ext = $fileExt;
                $fName = strtolower(BertaUtils::canonizeString($_FILES['Filedata']['name'], '_', '\.-'));
                if (substr($fName, 0, 1) == '_') {
                    $fName = rand(1, 100) . $fName;
                } // only derivatives start with "_"
            } elseif ($posterFor) {
                // if this image is uploaded as a poster frame, then its name should be the same as the name of the video
                $fName = substr($posterFor, 0, strrpos($posterFor, '.')) . '.' . strtolower(substr(strrchr($_FILES['Filedata']['name'], '.'), 1));
                if (file_exists($fileFolder . $fName)) {
                    if (@unlink($fileFolder . $fName)) {
                        BertaEditor::images_deleteDerivatives($fileFolder, $fName);
                    } else {
                        $result['result'] = 'failed';
                        $result['error'] = 'Cannot delete the current poster frame file!';
                    }
                }
            } else {
                $fName = $fRealName = strtolower(BertaUtils::canonizeString($_FILES['Filedata']['name'], '_', '\.-')); //str_replace(' ', '', str_replace($badChars, '_', strtolower($_FILES['Filedata']['name'])));
                if (substr($fName, 0, 1) == '_') {
                    $fName = rand(1, 100) . $fName;
                } // only derivatives start with "_"
                $ext = strtolower(substr(strrchr($fName, '.'), 1));
            }

            if (empty($result['error'])) {
                // be sure that we won't overwrite anything
                while (file_exists($fileFolder . $fName)) {
                    $fName = substr($fName, 0, strlen($fName) - strlen($ext) - 1) . rand(1, 9) . '.' . $ext;
                }

                // UPLOAD!
                if (!move_uploaded_file($file, $fileFolder . $fName)) {
                    $result['result'] = 'error';
                    $result['error'] = 'Insufficient target permissions!';
                } else {
                    chmod($fileFolder . $fName, 0666);
                    BertaEditor::images_deleteDerivatives($fileFolder, $fName);

                    // in case of video, all is done
                    if ($videoExt || $fileExt) {
                        if ($settingsProperty) { // update setings value
                            if ($settings->get($settingsProperty[0], $settingsProperty[1])) {
                                $oldF = $settings->get($settingsProperty[0], $settingsProperty[1]);
                                @unlink($fileFolder . $oldF);
                                BertaEditor::images_deleteDerivatives($fileFolder, $oldF);
                            }
                            $settings->update($settingsProperty[0], $settingsProperty[1], $fName);
                            $settings->save();
                        } else { // update image cache
                            $blog = BertaEditor::loadBlog($sectionName);
                            BertaEditor::updateImageCacheFor($blog, $entryId);
                            BertaEditor::saveBlog($sectionName, $blog);
                        }

                        // write response
                        $result['status'] = 1;
                        $return['hash'] = md5_file($fileFolder . $fName);
                        $result['type'] = $fileType;
                        $result['smallthumb_path'] = $result['smallthumb_width'] = $result['smallthumb_height'] = null;
                        $result['path'] = $fileFolderUrl . $fName;
                        $result['filename'] = $fName;
                        $result['size'] = $_FILES['Filedata']['size'];
                        $result['width'] = null;
                        $result['height'] = null;
                    }

                    // in case of image - check the dimensions, create small thumb etc.
                    else {
                        $resampleOk = true;

                        // if image could not be resampled, it is not a valid image
                        if ($resampleOk) {
                            // create the small thumb
                            $smallThumbPath = BertaEditor::images_getSmallThumbFor($mediaFolder . (substr($mediaFolder, -1) == '/' ? '' : '/') . $fName);
                            $thumbName = basename($smallThumbPath);
                            $smallThumbInfo = getimagesize($fileFolder . $thumbName);

                            // if uploaded for background, create lighter image & create an image for grid
                            if ($sectionBackground) {
                                $bgImagePath = BertaEditor::images_getBgImageFor($fileFolder . $fName);
                                $bgImageInfo = getimagesize($bgImagePath);

                                $gridImageSrc = BertaGallery::images_getGridImageFor($fileFolder . $fName);
                                if ($gridImageSrc) {
                                    $gridImageInfo = getimagesize($fileFolder . $gridImageSrc);
                                }
                            }

                            if ($settingsProperty) { // update setings value
                                if ($settings->get($settingsProperty[0], $settingsProperty[1])) {
                                    $oldF = $settings->get($settingsProperty[0], $settingsProperty[1]);
                                    @unlink($fileFolder . $oldF);
                                    BertaEditor::images_deleteDerivatives($fileFolder, $oldF);
                                }

                                //generate 2x smaller image here, original will be for retina displays @2x
                                $imInfo[0] = round($imInfo[0] / 2);
                                $imInfo[1] = round($imInfo[1] / 2);
                                BertaGallery::getResizedSrc($fileFolder, $fName, $imInfo[0], $imInfo[1]);

                                $settings->update($settingsProperty[0], $settingsProperty[1], $fName);
                                $settings->update($settingsProperty[0], $settingsProperty[1] . '_width', $imInfo[0]);
                                $settings->update($settingsProperty[0], $settingsProperty[1] . '_height', $imInfo[1]);
                                $settings->save();
                            } elseif ($sectionBackground) {
                                $sectionsToEdit = BertaEditor::getSections();

                                if (empty($sectionsToEdit[$sectionName]['mediafolder'])) {
                                    $sectionsToEdit[$sectionName]['mediafolder'] = [];
                                }
                                if (empty($sectionsToEdit[$sectionName]['mediafolder']['value'])) {
                                    $sectionsToEdit[$sectionName]['mediafolder']['value'] = $mediaFolder;
                                }

                                BertaEditor::updateImageCacheForSection($sectionsToEdit[$sectionName]);
                                BertaEditor::saveSections($sectionsToEdit);
                            } else { // update image cache
                                $blog = BertaEditor::loadBlog($sectionName);

                                BertaEditor::updateImageCacheFor($blog, $entryId);
                                BertaEditor::saveBlog($sectionName, $blog);
                            }

                            // write response
                            $result['status'] = 1;
                            $result['hash'] = md5_file($fileFolder . $fName);
                            $result['type'] = $fileType; // image
                            $result['smallthumb_path'] = $smallThumbPath;
                            $result['smallthumb_width'] = $smallThumbInfo[0];
                            $result['smallthumb_height'] = $smallThumbInfo[1];
                            $result['path'] = $fileFolderUrl . $fName;
                            $result['path_orig'] = $fileFolderUrl . $fName; //$origVersionPath;
                            $result['filename'] = $fName;
                            $result['size'] = $_FILES['Filedata']['size'];
                            $result['width'] = $imInfo[0];
                            $result['height'] = $imInfo[1];
                            if ($sectionBackground) {
                                $result['bg_image_width'] = $bgImageInfo[0];
                                $result['bg_image_height'] = $bgImageInfo[1];
                                if ($gridImageSrc) {
                                    $result['grid_image_width'] = $gridImageInfo[0];
                                    $result['grid_image_height'] = $gridImageInfo[1];
                                }
                            }
                        } else {
                            $result['status'] = 0;
                            $result['error'] = 'Unsupported image dimensions!';
                        }
                    }
                }
            }
        }
    }
} else {
    $result['status'] = 0;
    $result['error'] = 'Wrong file type or size too big!';
}

if ($result['status'] == 0) {
    http_response_code(400);
}

if (isset($_REQUEST['response']) && $_REQUEST['response'] == 'xml') {
    // Really dirty, use DOM and CDATA section!
    echo '<response>';
    foreach ($result as $key => $value) {
        echo "<$key><![CDATA[$value]]></$key>";
    }
    echo '</response>';
} else {
    if ($uplosadType == 'fancy') {
        header('Content-type: application/json');
    }

    echo Zend_Json::encode($result);
}
