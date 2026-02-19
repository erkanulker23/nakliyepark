<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

/**
 * Firma logosunu işler: şeffaf PNG'leri beyaz arka plana alır, boyut sınırı uygular.
 */
class CompanyLogoProcessor
{
    /** Maksimum genişlik/yükseklik (oran korunur) */
    public const MAX_SIDE = 800;

    public function process(string $storagePath): bool
    {
        $fullPath = Storage::disk('public')->path($storagePath);
        if (! is_file($fullPath) || ! is_readable($fullPath)) {
            return false;
        }

        $info = @getimagesize($fullPath);
        if ($info === false) {
            return false;
        }

        $mime = $info['mime'] ?? '';
        $width = (int) ($info[0] ?? 0);
        $height = (int) ($info[1] ?? 0);
        if ($width < 1 || $height < 1) {
            return false;
        }

        if (! extension_loaded('gd')) {
            return false;
        }

        $src = match ($mime) {
            'image/png' => @imagecreatefrompng($fullPath),
            'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($fullPath),
            'image/webp' => @imagecreatefromwebp($fullPath),
            default => null,
        };

        if ($src === false || $src === null) {
            return false;
        }

        $needsWhiteBackground = $mime === 'image/png' && $this->hasTransparency($src);
        $needsResize = $width > self::MAX_SIDE || $height > self::MAX_SIDE;

        if (! $needsWhiteBackground && ! $needsResize) {
            imagedestroy($src);
            return true;
        }

        $workW = $width;
        $workH = $height;
        $work = $src;

        if ($needsWhiteBackground) {
            $whiteBg = imagecreatetruecolor($width, $height);
            if ($whiteBg === false) {
                imagedestroy($src);
                return false;
            }
            $white = imagecolorallocate($whiteBg, 255, 255, 255);
            imagefill($whiteBg, 0, 0, $white);
            imagealphablending($whiteBg, true);
            imagesavealpha($src, true);
            imagecopy($whiteBg, $src, 0, 0, 0, 0, $width, $height);
            imagedestroy($src);
            $work = $whiteBg;
        }

        $targetW = $workW;
        $targetH = $workH;
        if ($needsResize) {
            if ($workW >= $workH) {
                $targetW = self::MAX_SIDE;
                $targetH = (int) round($workH * (self::MAX_SIDE / $workW));
            } else {
                $targetH = self::MAX_SIDE;
                $targetW = (int) round($workW * (self::MAX_SIDE / $workH));
            }
        }

        if ($needsResize) {
            $dest = imagecreatetruecolor($targetW, $targetH);
            if ($dest === false) {
                imagedestroy($work);
                return false;
            }
            $white = imagecolorallocate($dest, 255, 255, 255);
            imagefill($dest, 0, 0, $white);
            imagecopyresampled($dest, $work, 0, 0, 0, 0, $targetW, $targetH, $workW, $workH);
            imagedestroy($work);
        } else {
            $dest = $work;
        }

        $saved = false;
        if (str_ends_with(strtolower($fullPath), '.png')) {
            $saved = imagepng($dest, $fullPath, 6);
        } else {
            $saved = imagejpeg($dest, $fullPath, 90);
        }
        imagedestroy($dest);

        return $saved;
    }

    private function hasTransparency(\GdImage $img): bool
    {
        $w = imagesx($img);
        $h = imagesy($img);
        if ($w < 1 || $h < 1) {
            return false;
        }
        if (imagecolortransparent($img) >= 0) {
            return true;
        }
        for ($x = 0; $x < min($w, 20); $x += max(1, $w / 10)) {
            for ($y = 0; $y < min($h, 20); $y += max(1, $h / 10)) {
                $rgba = @imagecolorat($img, $x, $y);
                if ($rgba === false) {
                    continue;
                }
                $alpha = ($rgba >> 24) & 0x7F;
                if ($alpha > 0) {
                    return true;
                }
            }
        }
        return false;
    }
}
