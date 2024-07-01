<?php declare(strict_types=1);

namespace Xoops\RegDom;

use PHPUnit\Framework\TestCase;

class PublicSuffixListTest extends TestCase
{
    /**
     * @var PublicSuffixList
     */
    protected PublicSuffixList $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->object = new PublicSuffixList();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
//        $this->object->clearDataDirectory();
    }

    public function testContracts()
    {
        $this->assertInstanceOf(PublicSuffixList::class, $this->object);
    }

    public function testGetSet()
    {
        $tree = $this->object->getTree();
        $this->assertIsArray($tree);
        $this->assertArrayHasKey('com', $tree);
    }

    public function testClearDataDirectory()
    {
        $this->object->clearDataDirectory();
        $tree = $this->object->getTree();
        $this->assertIsArray($tree);
        $this->assertArrayHasKey('com', $tree);
    }

    public function testClearDataDirectoryCacheOnly()
    {
        $this->object->clearDataDirectory(true);
        $tree = $this->object->getTree();
        $this->assertIsArray($tree);
        $this->assertArrayHasKey('com', $tree);
    }

    public function testSetURL()
    {
        $url = 'https://example.com';
        $this->object->setURL($url);

        // Use reflection to call the protected setFallbackURL method
        $reflection = new \ReflectionClass($this->object);
        // Check the URL property to verify the fallback URL is set correctly
        $property = $reflection->getProperty('url');
        $property->setAccessible(true);

        $this->assertSame($url, $property->getValue($this->object));

    }

    public function testFallbackURL()
    {
        // Set URL to null and trigger the fallback mechanism
        $this->object->setURL(null);

        // Use reflection to call the protected setFallbackURL method
        $reflection = new \ReflectionClass($this->object);
        $method = $reflection->getMethod('setFallbackURL');
        $method->setAccessible(true);
        $method->invoke($this->object);

        // Check the URL property to verify the fallback URL is set correctly
        $property = $reflection->getProperty('url');
        $property->setAccessible(true);

//        $expectedUrl = file_exists(dirname(__DIR__, 2) . '/data/public_suffix_list.dat') ? dirname(__DIR__, 2) . '/data/public_suffix_list.dat' : 'https://publicsuffix.org/list/public_suffix_list.dat';
        $expectedUrl = \\file_exists(\\dirname(__DIR__, 2) . '/data/public_suffix_list.dat') ?  '/../data/public_suffix_list.dat' : 'https://publicsuffix.org/list/public_suffix_list.dat';
        $this->assertSame($expectedUrl, $property->getValue($this->object));
    }
}
