<?php

declare(strict_types=1);

namespace Minepic\Image\Sections;

use Minepic\Image\Exceptions\ImageResourceCreationFailedException;
use Minepic\Image\ImageSection;

class Skin extends ImageSection
{
    /**
     * @param string $type
     *
     * @return string
     */
    private function checkType(string $type): string
    {
        if ($type !== self::BACK) {
            $type = self::FRONT;
        }

        return $type;
    }

    /**
     * @param $skinHeight
     *
     * @return int
     */
    private function checkHeight($skinHeight): int
    {
        if ($skinHeight === 0 || $skinHeight < 0 || $skinHeight > (int) env('MAX_SKINS_SIZE')) {
            $skinHeight = (int) env('DEFAULT_SKIN_SIZE');
        }

        return $skinHeight;
    }

    /**
     * Create a PNG with raw texture.
     *
     * @throws \Minepic\Image\Exceptions\ImageCreateFromPngFailedException
     */
    public function prepareTextureDownload(): void
    {
        $this->imgResource = $this->createImageFromPng($this->skinPath);
        \imagealphablending($this->imgResource, true);
        \imagesavealpha($this->imgResource, true);
    }

    /**
     * Render skin.
     *
     * @param int
     * @param string
     *
     * @throws \Throwable
     */
    public function render(int $skin_height = 256, $type = self::FRONT): void
    {
        $type = $this->checkType($type);
        $skin_height = $this->checkHeight($skin_height);

        $image = $this->createImageFromPng($this->skinPath);
        $scale = $skin_height / 32;
        if ($scale === 0) {
            $scale = 1;
        }
        $this->imgResource = \imagecreatetruecolor(16 * $scale, 32 * $scale);
        if ($this->imgResource === false) {
            throw new ImageResourceCreationFailedException('imagecreatetruecolor() failed');
        }
        \imagealphablending($this->imgResource, false);
        \imagesavealpha($this->imgResource, true);
        $transparent = \imagecolorallocatealpha($this->imgResource, 255, 255, 255, 127);
        \imagefilledrectangle($this->imgResource, 0, 0, 16 * $scale, 32 * $scale, $transparent);

        $tmpAvatar = new Avatar($this->skinPath);
        $tmpAvatar->render(8, $type);
        // Front
        if ($type === self::FRONT) {
            // Head
            \imagecopyresized($this->imgResource, $tmpAvatar->getResource(), 4 * $scale, 0 * $scale, 0, 0, 8 * $scale, 8 * $scale, 8, 8);
            // Body Front
            \imagecopyresized($this->imgResource, $image, 4 * $scale, 8 * $scale, 20, 20, 8 * $scale, 12 * $scale, 8, 12);
            // Right Arm (left on img)
            $r_arm = \imagecreatetruecolor(4, 12);
            \imagecopy($r_arm, $image, 0, 0, 44, 20, 4, 12);
            \imagecopyresized($this->imgResource, $r_arm, 0 * $scale, 8 * $scale, 0, 0, 4 * $scale, 12 * $scale, 4, 12);
            // Right leg (left on img)
            $r_leg = \imagecreatetruecolor(4, 20);
            \imagecopy($r_leg, $image, 0, 0, 4, 20, 4, 12);
            \imagecopyresized($this->imgResource, $r_leg, 4 * $scale, 20 * $scale, 0, 0, 4 * $scale, 12 * $scale, 4, 12);
        } else {
            // Head
            \imagecopyresized($this->imgResource, $tmpAvatar->getResource(), 4 * $scale, 0 * $scale, 0, 0, 8 * $scale, 8 * $scale, 8, 8);
            // Body Back
            \imagecopyresized($this->imgResource, $image, 4 * $scale, 8 * $scale, 32, 20, 8 * $scale, 12 * $scale, 8, 12);
            // Right Arm Back (left on img)
            $r_arm = \imagecreatetruecolor(4, 12);
            \imagecopy($r_arm, $image, 0, 0, 52, 20, 4, 12);
            \imagecopyresized($this->imgResource, $r_arm, 0 * $scale, 8 * $scale, 0, 0, 4 * $scale, 12 * $scale, 4, 12);
            // Right leg Back (left on img)
            $r_leg = \imagecreatetruecolor(4, 20);
            \imagecopy($r_leg, $image, 0, 0, 12, 20, 4, 12);
            \imagecopyresized($this->imgResource, $r_leg, 4 * $scale, 20 * $scale, 0, 0, 4 * $scale, 12 * $scale, 4, 12);
        }

        // Left Arm (right flipped)
        $l_arm = \imagecreatetruecolor(4, 12);
        for ($x = 0; $x < 4; ++$x) {
            \imagecopy($l_arm, $r_arm, $x, 0, 4 - $x - 1, 0, 1, 12);
        }
        \imagecopyresized($this->imgResource, $l_arm, 12 * $scale, 8 * $scale, 0, 0, 4 * $scale, 12 * $scale, 4, 12);
        // Left leg (right flipped)
        $l_leg = \imagecreatetruecolor(4, 20);
        for ($x = 0; $x < 4; ++$x) {
            \imagecopy($l_leg, $r_leg, $x, 0, 4 - $x - 1, 0, 1, 20);
        }
        \imagecopyresized($this->imgResource, $l_leg, 8 * $scale, 20 * $scale, 0, 0, 4 * $scale, 12 * $scale, 4, 12);
    }
}
