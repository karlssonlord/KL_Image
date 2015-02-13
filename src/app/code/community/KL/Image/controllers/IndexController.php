<?php

/**
 * Image Controller
 *
 * @package KL_Image
 * @author Erik Eng <erik@karlssonlord.com>
 * @author David Wickström <david@karlssonlord.com>
 */
class KL_Image_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     *  The request valid path pattern that I, the controller itself, care about
     */
    const REQUEST_PATH_PATTERN = '/image\/index\/index\/(.*)/';

    protected $image;

    public function __construct(KL_Image_Model_Image $image)
    {
        $this->image = $image ? : new KL_Image_Model_Image;
    }


    /**
     * @return void|Zend_Controller_Response_Abstract
     */
    public function indexAction()
    {
        if (!$this->isRequestIsValid($this->getRequest())) {
            // Erroneous request performed - move along, nothing to see here
            return $this->norouteAction();
        }

        $requestPath = preg_grep(self::REQUEST_PATH_PATTERN, $this->getRequest()->getPathInfo());

        try {
            // The actual optimization work takes place right here!
            $imageUri = $this->image->optimize($requestPath, $this->getImageWidth());

            if ($this->getRequest()->isXmlHttpRequest()) {

                // If this was an Ajax request the serve json response
                // The response we are giving back is the path to the
                // optimized image
                return $this->getResponse()
                    ->setHeader('Content-type', 'application/json')
                    ->setBody(json_encode(array('url' => $imageUri)));
            }

            // Otherwise, give it a good old redirect
            $this->getResponse()->setRedirect($imageUri);

        } catch (Exception $e) {

            // Something went wrong, serve standard 404
            return $this->norouteAction();
        }
    }

    /**
     * @param $_req
     * @return int
     */
    private function isRequestIsValid($_req)
    {
        return preg_match(self::REQUEST_PATH_PATTERN, $_req->getPathInfo());
    }

}
