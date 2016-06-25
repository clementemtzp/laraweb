<?php

use App\Validators\SiteMetaValidator;
use App\Http\Controllers\SiteMetaController;

class SiteMetaValidatorTest extends TestCase
{
    /**
     * Test successful update with valid data.
     */
    public function testUpdateSuccess()
    {
        $validator = new SiteMetaValidator(App::make('validator'));
        $this->assertTrue($validator->update(SiteMetaController::getSiteMeta()->getAttributes('id'))->with($this->getValidData())->passes());
    }

    /**
     * Test unsuccessful update with invalid data.
     */
    public function testUpdateFailure()
    {
        $validator = new SiteMetaValidator(App::make('validator'));
        $this->assertFalse($validator->update(SiteMetaController::getSiteMeta()->getAttributes('id'))->with($this->getInvalidData())->passes());
        $this->assertEquals(1, count($validator->errors()));
    }

    /**
     * Test image update with valid extension.
     */
    public function testSuccessfulImageUpload()
    {
        $validator = new SiteMetaValidator(App::make('validator'));
        $this->assertTrue($validator->update(SiteMetaController::getSiteMeta()->getAttributes('id'))->with($this->getValidImage())->passes());
        $this->assertEquals(0, count($validator->errors()));
    }

    /**
     * Test image update failure on upload invalid file.
     */
    public function testErrorOnInvalidImageFormat()
    {
        $validator = new SiteMetaValidator(App::make('validator'));
        $this->assertFalse($validator->update(SiteMetaController::getSiteMeta()->getAttributes('id'))->with($this->getInvalidFile())->passes());
        print_r($validator->errors());
        $this->assertEquals(1, count($validator->errors()));
    }

    /**
     * Returns valid data.
     *
     * @return array
     */
    private function getValidData()
    {
        return array(
            'title' => 'Site title updated',
            'subtitle' => 'Site subtitle',
            'description' => 'Site description',
        );
    }

    /**
     * Returns invalid data.
     *
     * @return array
     */
    private function getInvalidData()
    {
        return array(
            'title' => 'Site title updated',
            'subtitle' => null,
            'description' => 'Site description',
        );
    }

    /**
     * Returns valid data with an image.
     *
     * @return array
     */
    public function getValidImage()
    {
        $file = tempnam(sys_get_temp_dir(), 'upl'); // create file
        imagepng(imagecreatetruecolor(10, 10), $file); // create and write image/png to it
        $image = new \Symfony\Component\HttpFoundation\File\UploadedFile(
            $file,
            'image.png',
            'image/png',
            null,
            null,
            true
        );
        return array(
            'title' => 'Site title updated',
            'subtitle' => 'Site subtitle',
            'description' => 'Site description',
            'image' => $image,
        );
    }

    /**
     * Returns data with an invalid file.
     *
     * @return array
     */
    public function getInvalidFile()
    {
        $image = new \Symfony\Component\HttpFoundation\File\UploadedFile(
            $file = tempnam(sys_get_temp_dir(), 'upl'),
            'test-file.csv',
            'text/plain',
            446,
            null,
            true
        );
        return array(
            'title' => 'Site title updated',
            'subtitle' => 'Site subtitle',
            'description' => 'Site description',
            'image' => $image,
        );
    }

}