<?php

/**
 * @package KL_Image
 * @author David Wickström <david@karlssonlord.com>
 */
class KL_Image_Model_Image
{
    /**
     *  Folder for optimized images
     */
    const OPTIMIZED_ROOT = 'r';

    /**
     * @var string
     */
    private $baseDir;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var
     */
    private $basePath;

    /**
     * @var
     */
    private $optimizedImagePath;

    /**
     * @var
     */
    private $originalImageUrl;

    /**
     * @var
     */
    private $newImageUrl;

    /**
     * @var KL_Image_Model_Optimizer_Detect
     */
    private $detector;

    /**
     * @var
     */
    private $installedOptimizer;

    public function __construct(KL_Image_Model_Optimizer_Detect $detector = null)
    {
        $this->detector = $detector ? : new KL_Image_Model_Optimizer_Detect;
    }

    /**
     * @param $imagePath
     * @param $imageWidth
     * @return mixed
     */
    public function optimize($imagePath, $imageWidth)
    {
        $imagePath = $this->cleanImagePath($imagePath);
        $imageWidth = $this->calculateImageWidth($imageWidth);

        $this->preparePaths($this->cleanImagePath($imagePath), $this->calculateImageWidth($imageWidth));

        if ($this->imageAlreadyOptimized()) {
            // Look, we've been over this image already...
            return $this->originalImageUrl;
        }

        $image = new Varien_Image($this->basePath);

        // Only resize if requested size is smaller then original
        if ($imageWidth < $image->getOriginalWidth()) {

            // Scale and save new size
            $image->resize($imageWidth);
            $image->save($this->optimizedImagePath);

            // Optimize
            if ($this->machineHasMinifierInstalled()) {
                $this->getOptimizer()->optimize($this->optimizedImagePath);
            }

            // Return the URL for the newly created image
            return $this->newImageUrl;
        }

        // Oh well, never mind, here is the original
        return $this->originalImageUrl;
    }

    /**
     * @return mixed
     */
    public function getOptimizedImagePath()
    {
        return $this->optimizedImagePath;
    }

    /**
     * @return bool
     */
    private function machineHasMinifierInstalled()
    {
        $this->installedOptimizer = $this->detector->getInstalledOptimizer();

        return (!$this->installedOptimizer ? false : true);
    }

    /**
     * @param $width
     * @param int $step
     * @return int
     */
    private function calculateImageWidth($width, $step = 50)
    {
        return (int)ceil($width / $step) * $step;
    }

    /**
     * @param $imagePath
     * @return mixed
     */
    private function cleanImagePath($imagePath)
    {
        return str_replace('../', '', $imagePath);
    }

    /**
     * @param $imagePath
     * @param $imageWidth
     */
    private function preparePaths($imagePath, $imageWidth)
    {
        $this->baseDir = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA);
        $this->baseUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
        $this->basePath = $this->baseDir . DS . $imagePath;
        $this->optimizedImagePath = $this->baseDir . DS . self::OPTIMIZED_ROOT . DS . $imageWidth . DS . $imagePath;
        $this->originalImageUrl = $this->baseUrl . $imagePath;
        $this->newImageUrl = $this->baseUrl . str_replace($this->baseDir . '/', '', $this->optimizedImagePath);
    }

    /**
     * @return bool
     */
    private function imageAlreadyOptimized()
    {
        return file_exists($this->basePath) && file_exists($this->optimizedImagePath);
    }

    /**
     * @return mixed
     */
    private function getOptimizer()
    {
        return new $this->installedOptimizer;
    }

} 