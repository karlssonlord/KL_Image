<?php 

class KL_Image_Model_Optimizer_Imgmin implements KL_Image_Model_Optimizer
{

    /**
     * @param string $pathToImage
     * @return string
     */
    public function optimize($pathToImage)
    {
        exec("imgmin $pathToImage $pathToImage");
    }
}