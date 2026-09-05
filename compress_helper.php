<?php
/**
 * Smart Image Compressor Utility for Photo Booth
 * Compresses images while preserving transparency and sharp visual quality (no pixelation).
 */

function compressImageFile($filePath, $maxDimension = 2480, $jpegQuality = 88, $pngCompression = 6) {
    if (!file_exists($filePath) || filesize($filePath) === 0) {
        return false;
    }

    $info = @getimagesize($filePath);
    if (!$info) return false;

    $width = $info[0];
    $height = $info[1];
    $mime = $info['mime'];

    // If image is already very small (< 200KB) and within max dimensions, skip
    if (filesize($filePath) < 200 * 1024 && max($width, $height) <= $maxDimension) {
        return true;
    }

    $srcImg = null;
    switch ($mime) {
        case 'image/jpeg':
        case 'image/jpg':
            $srcImg = @imagecreatefromjpeg($filePath);
            break;
        case 'image/png':
            $srcImg = @imagecreatefrompng($filePath);
            break;
        case 'image/webp':
            $srcImg = @imagecreatefromwebp($filePath);
            break;
        default:
            return false;
    }

    if (!$srcImg) return false;

    // Calculate new dimensions if exceeds maxDimension
    $newWidth = $width;
    $newHeight = $height;
    if ($width > $maxDimension || $height > $maxDimension) {
        if ($width >= $height) {
            $newWidth = $maxDimension;
            $newHeight = (int)round(($height / $width) * $maxDimension);
        } else {
            $newHeight = $maxDimension;
            $newWidth = (int)round(($width / $height) * $maxDimension);
        }
    }

    // Create target canvas
    $dstImg = imagecreatetruecolor($newWidth, $newHeight);

    // Preserve transparency for PNG and WebP
    if ($mime === 'image/png' || $mime === 'image/webp') {
        imagealphablending($dstImg, false);
        imagesavealpha($dstImg, true);
        $transparent = imagecolorallocatealpha($dstImg, 255, 255, 255, 127);
        imagefilledrectangle($dstImg, 0, 0, $newWidth, $newHeight, $transparent);
    }

    // High quality bicubic resampling
    imagecopyresampled($dstImg, $srcImg, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    // Save with optimal compression
    $tempPath = $filePath . '.tmp';
    $saved = false;

    if ($mime === 'image/jpeg' || $mime === 'image/jpg') {
        $saved = imagejpeg($dstImg, $tempPath, $jpegQuality);
    } elseif ($mime === 'image/png') {
        $saved = imagepng($dstImg, $tempPath, $pngCompression);
    } elseif ($mime === 'image/webp') {
        $saved = imagewebp($dstImg, $tempPath, $jpegQuality);
    }

    imagedestroy($srcImg);
    imagedestroy($dstImg);

    if ($saved && file_exists($tempPath) && filesize($tempPath) > 0) {
        // Only replace if new size is smaller or if dimensions were resized
        if (filesize($tempPath) < filesize($filePath) || $newWidth !== $width) {
            @unlink($filePath);
            @rename($tempPath, $filePath);
        } else {
            @unlink($tempPath);
        }
        return true;
    }

    if (file_exists($tempPath)) @unlink($tempPath);
    return false;
}
