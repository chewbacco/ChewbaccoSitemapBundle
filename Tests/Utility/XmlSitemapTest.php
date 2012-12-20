<?php
namespace Chewbakka\SitemapBundle\Tests\Utility;

use Chewbakka\SitemapBundle\Generator\XmlSitemap;

class ProductsSitemapGeneratorTest extends \PHPUnit_Framework_TestCase
{

    private function getTestData()
    {
        $items = array();
        $urls = array('http://phpunit.de', 'https://github.com/Chewbakka/', 'http://php.net', 'http://google.com/', 'http://lacroco.ru', 'http://sex-prod.ru');
        foreach ($urls as $url) {
            $items[] = array(
                'loc' => $url,
                'lastmod' => date('Y-m-d', strtotime('-'.mt_rand(0,30).' days')),
                'priority' => rand(0, 10) * 0.1
            );
        }

        return $items;
    }

    public function testBaseConstruct()
    {
        $sitemap = new XmlSitemap();
        $this->assertObjectHasAttribute('dom', $sitemap);
        $this->assertObjectHasAttribute('itemsSet', $sitemap);
    }

    public function testAddItem()
    {
        $sitemap = new XmlSitemap();
        $items = $this->getTestData();
        foreach ($items as $item) {
            $xml = $sitemap->addItem($item);
            foreach ($item as $k => $v) {
                $matcher = array(
                    'tag' => $k,
                    'content' => (string) $v,
                    'parent' => array('tag' => 'url')
                );
                $this->assertTag($matcher, $xml, '', false);
            }
        }
    }
}
