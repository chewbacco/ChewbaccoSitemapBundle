<?php
namespace Chewbakka\SitemapBundle\Generator;

class XmlSitemap
{
    private $dom;
    private $itemsSet;

    public function __construct($itemsSetTagName = 'urlset')
    {
        // Create dom object
        $this->dom = new \DOMDocument('1.0', 'UTF-8');
        $this->dom->formatOutput = true;
        $this->dom->substituteEntities = false;

        // Create <itemsSet> root tag
        $this->itemsSet = $this->dom->createElement($itemsSetTagName);

        // Add attribute of itemsSet
        $xmlns = $this->dom->createAttribute('xmlns');
        $itemsSetText = $this->dom->createTextNode('http://www.sitemaps.org/schemas/sitemap/0.9');
        $this->itemsSet->appendChild($xmlns);
        $xmlns->appendChild($itemsSetText);
    }

    public function addItem(array $tags, $itemTagName = 'url')
    {
        $item = $this->dom->createElement($itemTagName);
        foreach ($tags as $tag => $value) {
                $text = $this->dom->createTextNode($value);
                $elem = $this->dom->createElement($tag);
                $elem->appendChild($text);
                $item->appendChild($elem);
        }
        $this->itemsSet->appendChild($item);

        return $item->ownerDocument->saveXml($item);
    }

    public function save($path)
    {
        $this->dom->appendChild($this->itemsSet);

        return $this->dom->save($path);
    }
}
