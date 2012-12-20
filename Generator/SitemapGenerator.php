<?php
namespace Chewbakka\SitemapBundle\Generator;
use Symfony\Component\Routing\RouterInterface;
use Chewbakka\SitemapBundle\Generator\XmlSitemap;

abstract class SitemapGenerator implements SitemapGeneratorInterface
{
  private $router;

  private $config;

  public function __construct(array $config)
  {

      $this->config = $config;
  }

  public function setRouter(RouterInterface $router)
  {
      $this->router = $router;
  }

  public function getRouter()
  {
      return $this->router;
  }

  private function getFilePath()
  {
      return $this->config['path'].$this->config['file_name'];
  }

  public function getWebFilePath()
  {
      return 'http://'.$this->getRouter()->getContext()->getHost().$this->config['web_path'].$this->config['file_name'];
  }

  public function generate($returnString = false)
    {
        $sitemap = new XmlSitemap();
        $tagNames = array('loc', 'lastmod', 'priority');
        $entities = $this->getEntities();
        $item = array();
        foreach ($entities as $entity) {
            foreach ($tagNames as $tag) {
                $item[$tag] = $this->getTagValue($entity, $tag);
            }
            $sitemap->addItem($item);
        }

        if ($returnString == false)
            return $sitemap->save($this->getFilePath());
        return $sitemap->saveXML();
    }

    /**
     *
     * @param  Entity $entity
     * @param  string $tag
     * @return string
     */
        protected function getTagValue($entity, $tag)
    {
        $method = 'get' . ucfirst($tag).'TagValue';
        if (method_exists($this, $method)) {
            return $this->$method($entity);
        } else {
            return $this->config[$tag];
        }
    }

    protected function getPriorityTagValue($entity)
    {
        if (is_numeric($this->config['priority'])) {
            $priority = $this->config['priority'];
        } else {
            $priority = $this->stringToMethodCall($entity, $this->config['priority']);
        }

        return $priority;
    }

    protected function getLocTagValue($entity)
    {
        extract($this->config['loc']);
        foreach ($params as $key => $param) {
            if (is_array($param)) {
                $value        = $this->stringToMethodCall($entity, $param['field']);
                $object       = new $param['class'];
                $params[$key] = $object->{$param['method']}($value);
            } else {
                $value        = $this->stringToMethodCall($entity, $param);
                $params[$key] = $value;
            }
        }

        return $this->router->generate($route, $params, true);
    }

    protected function getLastmodTagValue($entity)
    {
        if (isset($this->config['lastmod'])) {
            $value = $this->stringToMethodCall($entity, $this->config['lastmod']);
        } else {
            $value = new \DateTime();
        }
        if ($value instanceof \DateTime) {
            $value = $value->format('Y-m-d');
        }

        return $value;
    }

    private function stringToMethodCall($entity, $call)
    {
        $keys = explode(" ", $call);
        $value = $entity;
        if (is_object($value)) {
            foreach ($keys as $method) {
                $method = 'get' . ucfirst($method);
                if (method_exists($value, $method)) {
                    $value = $value->$method();
                }
            }
            if (method_exists($entity, $method)) {
                $value = $entity->$method();
            }
        } elseif (is_array($value)) {
            foreach ($keys as $key) {
                if (isset($value[$key])) {
                    $value = $value[$key];
                }
            }
            if (isset($entity[$key])) {
                $value = $entity[$key];
            }
        }

        return $value;
    }
}
