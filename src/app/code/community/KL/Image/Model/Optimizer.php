<?php

/**
 * Interface KL_Image_Model_Optimizer
 */
interface KL_Image_Model_Optimizer
{
    /**
     * @param string $pathToImage
     * @return string
     */
    public function optimize($pathToImage);
} 