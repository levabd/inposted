<?php
/**
 * @author Yura Fedoriv <yurko.fedoriv@gmail.com>
 */
namespace shared\components;
use shared\models\User;

class AvatarStorage extends \CApplicationComponent
{
    const ORIGINAL_FILE_NAME = 'original.jpg';

    public $appId;
    public $basePath;
    public $baseUrl;

    public $sizes = [64];

    /**
     * @param User $user
     * @param null $size
     *
     * @return string
     */
    public function getAvatarUrl(User $user, $size = null) {
        if ($user->avatar) {
            if (!$size) {
                $size = max($this->sizes);
            }

            if (is_file($this->getFileName($user, $size)) || $this->createResizedAvatar($user, $size)) {
                return Yii()->urlManager->getBaseUrl($this->appId) . "/$this->baseUrl/{$this->formPath($user)}/{$this->formFileName($size)}";
            }
        }

        return Yii()->urlManager->getBaseUrl('site') . '/img/empty_avatar.jpg';
    }

    protected function formPath(User $user) {
        return implode('/', str_split($user->id, 2)) . '/' . $user->id;
    }

    protected function formFileName($size = null) {
        return ($size = intval($size)) ? "$size.jpg" : self::ORIGINAL_FILE_NAME;
    }

    protected function getFileName($user, $size = null) {
        return path($this->basePath, $this->formPath($user), $this->formFileName($size));
    }

    public function processAvatarUpload(User $user) {
        if ($user->avatarUpload) {
            $path = path($this->basePath, $this->formPath($user));
            Yii()->fs->remove($path);
            Yii()->fs->dir($path);

            $image = new \Imagick($user->avatarUpload->tempName);
            $background = new \Imagick();
            $background->newImage($image->getImageWidth(), $image->getImageHeight(), new \ImagickPixel( "white" ));
            $background->compositeimage($image, \Imagick::COMPOSITE_COPY, 0, 0);
            $background->flattenImages();
            $background->writeimage($this->getFileName($user));

            foreach ($this->sizes as $size) {
                $this->createResizedAvatar($user, $size);
            }

            $user->avatar = true;
            $user->update(['avatar']);
        }
    }

    protected function createResizedAvatar(User $user, $size) {
        if (($size = intval($size)) && is_file($originalFile = $this->getFileName($user))) {
            $background = new \Imagick();
            $background->newimage($size, $size, new \ImagickPixel('white'));

            $image = new \Imagick($originalFile);
            $image->scaleimage($size, $size, true);
            $x = $size == $image->getimagewidth() ? 0 : ($size - $image->getimagewidth())/2;
            $y = $size == $image->getimageheight() ? 0 : ($size - $image->getimageheight())/2;
            $background->compositeimage($image, \Imagick::COMPOSITE_COPY, $x, $y);
            return $background->writeimage($this->getFileName($user, $size));
        }
        return false;
    }
}
