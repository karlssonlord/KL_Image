<?php

/**
 * @package KL_Image
 */
class KL_Image_Model_Optimizer_Detect
{
    /**
     *
     */
    const IMGMIN = 'imgmin';

    /**
     * @return bool|string
     */
    public function getInstalledOptimizer()
    {
        $imgmin = shell_exec('which imgmin');

        if ($imgmin) return $this->getClassName(self::IMGMIN);

        return false;
    }

    /**
     * @param $name
     * @return string
     */
    private function getClassName($name)
    {
        return 'KL_Image_Model_Optimizer_'.ucfirst($name);
    }

} 