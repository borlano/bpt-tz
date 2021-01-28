<?php


namespace App\Services;



use ColorThief\ColorThief;
use Exception;
use Throwable;

/**
 * Класс по обработке изображения
 * Определяем основной цвет изображения
 * и ставим в зависимости от него водяной знак
 * Class ImageService
 * @package App\Services
 */
class ImageService
{
    /** @var string Красный цвет */
    const RED_COLOR = 'red';
    /** @var string Зеленый цвет */
    const GREEN_COLOR = 'green';
    /** @var string Синий цвет */
    const BLUE_COLOR = 'blue';
    /** @var string Черный цвет */
    const BLACK_COLOR = 'black';
    /** @var string Желтый цвет */
    const YELLOW_COLOR = 'yellow';
    /** @var int Ширина водяного знака */
    const WATERMARK_SIZE = 50;
    /** @var false|resource Основное изображение */
    private $image;
    /** @var resource Водяной знак */
    private $watermark;
    /** @var string Путь к водяным знакам */
    private $watermark_path;

    /** @var int[] Счетчики цвета */
    private $counters = [
        self::RED_COLOR => 0,
        self::GREEN_COLOR => 0,
        self::BLUE_COLOR => 0,
    ];

    public function __construct(string $image)
    {
        $this->image = imagecreatefromstring($image);
        $this->watermark_path = storage_path('watermarks/');
    }

    /**
     * Определение водяного знака по
     * основному цвету изображения.
     *
     * @return bool|resource
     * @throws Exception
     */
    private function setWatermark()
    {
        switch ($this->getDominantColor()) {
            case self::RED_COLOR:
                $color = self::BLACK_COLOR;
                break;
            case self::BLUE_COLOR:
                $color = self::YELLOW_COLOR;
                break;
            case self::GREEN_COLOR:
                $color = self::RED_COLOR;
                break;
        }

        if (isset($color)) {
            $this->watermark_path .= $color . '.jpg';
            $this->watermark = imagecropauto(imagecreatefromjpeg($this->watermark_path), IMG_CROP_WHITE);
            $this->watermark = imagescale($this->watermark, self::WATERMARK_SIZE);
        } else {
            throw new Exception('Undefined dominant color');
        }

        return $this->watermark;
    }

    /**
     * Определение основного цвета изображения.
     *
     * @return string
     */
    private function getDominantColor(): string
    {
//        for ($x = 0; $x < imagesx($this->image); $x++) {
//            for ($y = 0; $y < imagesy($this->image); $y++) {
//                $rgb = imagecolorat($this->image, $x, $y);
//                $this->counters[self::RED_COLOR] += ($rgb >> 16) & 0xFF;
//                $this->counters[self::GREEN_COLOR] += ($rgb >> 8) & 0xFF;
//                $this->counters[self::BLUE_COLOR] += $rgb & 0xFF;
//            }
//        }

        [
            $this->counters[self::RED_COLOR],
            $this->counters[self::GREEN_COLOR],
            $this->counters[self::BLUE_COLOR]
        ] = ColorThief::getColor($this->image);

        return array_search(max($this->counters), $this->counters);
    }

    /**
     * Установка водяного знака на изображение.
     * @return $this
     */
    public function modifyImage()
    {
        try {
            $this->setWatermark();
        } catch (Throwable $exception) {
            throw new Exception($exception->getMessage());
        }

        $sy = imagesy($this->watermark);

        imagecopy($this->image,
            $this->watermark,
            0,
            imagesy($this->image) - $sy,
            0,
            0,
            imagesx($this->watermark),
            imagesy($this->watermark));

        return $this;
    }

    /**
     * Получение текущего изображения.
     *
     * @return false|resource
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Отрисовка изображения.
     *
     * @return bool
     */
    public function render()
    {
        header('Content-type: image/jpeg');

        return imagejpeg($this->image);
    }
}
