<?php
namespace Xoops\RegDom;

use Xoops\RegDom\RegisteredDomain;
use PHPUnit\Framework\TestCase;
//use PHPUnit\Framework\Attributes\DataProvider; //PHP 8

include __DIR__ . '/../../src/RegisteredDomain.php';
include __DIR__ . '/../../src/PublicSuffixList.php';

/**
 * Class TestProtectedDecodePunycode used to test protected decodePunycode() method that is
 * only used if intl extension is not loaded.
 */
class TestProtectedDecodePunycode extends RegisteredDomain
{
    public function doDecodePunycode($string)
    {
        return $this->decodePunycode($string);
    }
}

class RegisteredDomainTest extends TestCase
{
    /**
     * @var RegisteredDomain
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        //$this->object = new RegisteredDomain();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
    }

    public function testContracts()
    {
        $object = new RegisteredDomain();
        $this->assertInstanceOf(RegisteredDomain::class, $object);
    }


//    #[dataProvider('domainsProvider')] //PHP 8
    /**
     * @dataProvider domainsProvider
     */
    public function testGetRegisteredDomain($url = '', $regdom = '')
    {
        $object = new RegisteredDomain();
        $this->assertEquals($regdom, $object->getRegisteredDomain($url));
    }

    /**
     * @return array
     */
    public static function domainsProvider()
    {
        $provider = [
            [null, null],
            // Mixed case.
            ['COM', null],
            ['example.COM', 'example.com'],
            ['WwW.example.COM', 'example.com'],
            // Leading dot.
            ['.com', null],
            ['.example', null],
            // Unlisted TLD.
            ['example', null],
            ['example.example', 'example.example'],
            ['b.example.example', 'example.example'],
            ['a.b.example.example', 'example.example'],
            // TLD with only 1 rule.
            ['biz', null],
            ['domain.biz', 'domain.biz'],
            ['b.domain.biz', 'domain.biz'],
            ['a.b.domain.biz', 'domain.biz'],
            // TLD with some 2-level rules.
            ['com', null],
            ['example.com', 'example.com'],
            ['b.example.com', 'example.com'],
            ['a.b.example.com', 'example.com'],
            ['uk.com', null],
            ['example.uk.com', 'example.uk.com'],
            ['b.example.uk.com', 'example.uk.com'],
            ['a.b.example.uk.com', 'example.uk.com'],
            ['test.ac', 'test.ac'],
            // TLD with only 1 (wildcard) rule.
            ['mm', null],
            ['c.mm', null],
            ['b.c.mm', 'b.c.mm'],
            ['a.b.c.mm', 'b.c.mm'],
            // More complex TLD.
            ['jp', null],
            ['test.jp', 'test.jp'],
            ['www.test.jp', 'test.jp'],
            ['ac.jp', null],
            ['test.ac.jp', 'test.ac.jp'],
            ['www.test.ac.jp', 'test.ac.jp'],
            ['kyoto.jp', null],
            ['test.kyoto.jp', 'test.kyoto.jp'],
            ['ide.kyoto.jp', null],
            ['b.ide.kyoto.jp', 'b.ide.kyoto.jp'],
            ['a.b.ide.kyoto.jp', 'b.ide.kyoto.jp'],
            ['c.kobe.jp', null],
            ['b.c.kobe.jp', 'b.c.kobe.jp'],
            ['a.b.c.kobe.jp', 'b.c.kobe.jp'],
            ['city.kobe.jp', 'city.kobe.jp'],
            ['www.city.kobe.jp', 'city.kobe.jp'],
            // TLD with a wildcard rule and exceptions.
            ['ck', null],
            ['test.ck', null],
            ['b.test.ck', 'b.test.ck'],
            ['a.b.test.ck', 'b.test.ck'],
            ['www.ck', 'www.ck'],
            ['www.www.ck', 'www.ck'],
            // US K12.
            ['us', null],
            ['test.us', 'test.us'],
            ['www.test.us', 'test.us'],
            ['ak.us', null],
            ['test.ak.us', 'test.ak.us'],
            ['www.test.ak.us', 'test.ak.us'],
            ['k12.ak.us', null],
            ['test.k12.ak.us', 'test.k12.ak.us'],
            ['www.test.k12.ak.us', 'test.k12.ak.us'],
            // IDN labels.
            ['食狮.com.cn', '食狮.com.cn'],
            ['食狮.公司.cn', '食狮.公司.cn'],
            ['www.食狮.公司.cn', '食狮.公司.cn'],
            ['shishi.公司.cn', 'shishi.公司.cn'],
            ['公司.cn', null],
            ['食狮.中国', '食狮.中国'],
            ['www.食狮.中国', '食狮.中国'],
            ['shishi.中国', 'shishi.中国'],
            ['中国', null],
            // Same as above, but punycoded.
            ['xn--85x722f.com.cn', '食狮.com.cn'],
            ['xn--85x722f.xn--55qx5d.cn', '食狮.公司.cn'],
            ['www.xn--85x722f.xn--55qx5d.cn', '食狮.公司.cn'],
            ['shishi.xn--55qx5d.cn', 'shishi.公司.cn'],
            ['xn--55qx5d.cn', null],
            ['xn--85x722f.xn--fiqs8s', '食狮.中国'],
            ['www.xn--85x722f.xn--fiqs8s', '食狮.中国'],
            ['shishi.xn--fiqs8s', 'shishi.中国'],
            ['xn--fiqs8s', null],
            // inspiration case
            ['rfu.in.ua', 'rfu.in.ua'],
            ['in.ua', null],
        ];
        return $provider;
    }

//    #[\PHPUnit\Framework\Attributes\DataProvider('punycodeProvider')] //PHP 8
    /**
     * @dataProvider punycodeProvider
     */
    public function testDecodePunycode($punycode, $decoded)
    {
        $object = new TestProtectedDecodePunycode();
        $this->assertEquals($decoded, $object->doDecodePunycode($punycode));
    }

    /**
     * @return array
     */
    public static function punycodeProvider()
    {
        $provider = [
            [null, null],
            // Mixed case.
            ['test', 'test'],
            // punycoded
            ['xn--85x722f', '食狮'],
            ['xn--55qx5d', '公司'],
            ['xn--fiqs8s', '中国'],
        ];
        return $provider;
    }
}
