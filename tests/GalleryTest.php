<?php
namespace LVAC\Test\Gallery;

class GalleryTest extends \PHPUnit_Framework_TestCase
{
    public function testGetAlbumList()
    {
        $gallery = new \LVAC\Gallery\Mapper();
        $result = $gallery->getAlbumList();

        $this->assertTrue(array_key_exists('title', $result[0]));
        $this->assertTrue(array_key_exists('farm', $result[0]));
        $this->assertTrue(array_key_exists('server', $result[0]));
        $this->assertTrue(array_key_exists('id', $result[0]));
        $this->assertTrue(array_key_exists('primary', $result[0]));
        $this->assertTrue(array_key_exists('secret', $result[0]));
    }
}
