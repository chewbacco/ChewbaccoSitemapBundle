<?php
namespace Chewbacco\SitemapBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SitemapsGenerateCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        parent::configure();

        $this->setName('chewbacco:sitemap:generate')
             ->setDescription('Generate sitemaps files.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $host = $this->getContainer()->getParameter('host');
        $generator = $this->getContainer()->get('chewbacco_sitemap.generator_chain');
        $generator->getRouter()->getContext()->setHost($host);
        $generator->generate();
        $generator->generateIndex();
        $this->sitemapReport($host);
    }

    protected function sitemapReport($host)
    {
        $this->logSection(date('Y-m-d H:i:s'), 'reporting about new sitemap on '.$host);
        $target_url = 'http://google.com/webmasters/sitemaps/ping?sitemap=http://'.$host.'/sitemaps.xml';
        curl_setopt($ch, CURLOPT_USERAGENT, 'ChewbaccoSitemapBundle');
        curl_setopt($ch, CURLOPT_URL, $target_url);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 80);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 6);

        return curl_exec($ch);
    }
}
