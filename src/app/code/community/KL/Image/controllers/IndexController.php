<?php
/**
 * Image Controller
 *
 * @author Erik Eng <erik@karlssonlord.com>
 *
 */
class KL_Image_IndexController extends Mage_Core_Controller_Front_Action
{
    const REQUEST_PATH_PATTERN = '/image\/index\/index\/(.*)/';

    public function indexAction()
    {
        $_req = $this->getRequest();
        $_res = $this->getResponse();

        if(preg_match(self::REQUEST_PATH_PATTERN, $_req->getPathInfo(), $match)) {

            // No tricks, ok?
            $_imagePath = str_replace('../', '', $match[1]);
            $_imageWidth = $this->_getImageWidth();

            $_mediaBaseDir = Mage::getBaseDir(Mage_Core_Model_Store::URL_TYPE_MEDIA);
            $_baseUrl  = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
            $_basePath = $_mediaBaseDir . DS . $_imagePath;
            $_newPath  = $_mediaBaseDir . DS . 'r' . DS . $_imageWidth . DS . $_imagePath;

            // TODO: Compress $_newPath widh some hash method, keeping the file name?
            //     Will that create too flat file hierarchy?

            $_origUrl  = $_baseUrl . $_imagePath;
            $_newUrl   = $_baseUrl . str_replace($_mediaBaseDir . '/', '', $_newPath);

            // Image available in requested size
            if(file_exists($_basePath) && file_exists($_newPath)) {
                $_imageUrl = $_newUrl;
            }

            // Scale and save new size
            if(!isset($_imageUrl) && file_exists($_basePath)) {
                $image  = new Varien_Image($_basePath);

                // Only resize if requested size is smaller then original
                if($_imageWidth < $image->getOriginalWidth()) {
                    $image->resize($_imageWidth);

                    if($_canOptimize = $this->_canOptimize()) {
                        $image->quality(100);
                    }

                    $image->save($_newPath);

                    // Optimize
                    if($_canOptimize) {
                        $this->_optimize($_newPath);
                    }

                    $_imageUrl = $_newUrl;
                }
                else {
                    $_imageUrl = $_origUrl;
                }
            }

            // Fall back to 404
            if(!isset($_imageUrl)) {
                return $this->norouteAction();
            }

            // Redirect or serve URL as JSON
            if($_req->isXmlHttpRequest()) {
                $_res->setHeader('Content-type', 'application/json')
                    ->setBody(Zend_Json::encode(array('url' => $_imageUrl)));
            }
            else {
                $_res->setRedirect($_imageUrl);
            }
        }

        // Fall back to 404
        if(!isset($_imageUrl)) {
            $this->norouteAction();
        }
    }

    private function _getImageWidth($step = 50)
    {
        return (int)ceil($this->getRequest()->getParam('w')/$step) * $step;
    }

    private function _canOptimize()
    {
        $output = shell_exec('which imgmin');
        return (empty($output) ? false : true);
    }

    private function _optimize($path)
    {
        exec("imgmin $path $path");
    }
}
