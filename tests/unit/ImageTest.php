<?php


class ImageTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected $path;

    protected function _before()
    {
    }

    protected function _after()
    {
        Mockery::close();
        $this->tester->cleanDir('src/media/r/');
    }

    // tests
    public function test_it_talks_to_a_detector_and_an_optimizer()
    {
        $detector = Mockery::mock('KL_Image_Model_Optimizer_Detect');
        $detector->shouldReceive('getInstalledOptimizer')->once()->andReturn('FooDetector');
        $image = new KL_Image_Model_Image($detector);

        $this->path = $image->optimize('mjau.jpg', 99);

        // Assert that the new image width is as expected
        $image = new Varien_Image($image->getOptimizedImagePath());
        $this->assertEquals(100, $image->getOriginalWidth());

        // Assert that the new image is correctly located
        $this->assertEquals('http://localhost:8081/media/r/100/mjau.jpg', $this->path);
    }

}