<?php
declare(strict_types=1);

namespace Eightfold\Amos;

use Eightfold\Amos\Site;

class Environment
{
    private array $sites = [];

    public static function init(
        string $site,
        string $domain,
        string $contentRoot
    ): Environment {
        return new Environment($site, $domain, $contentRoot);
    }

    public function __construct(
        private string $site,
        private string $domain,
        private $contentRoot
    ) {
        $this->addTenant($site, $domain, $contentRoot);
    }

    public function addTenant(
        string $site,
        string $domain,
        string $contentRoot
    ): self {
        $this->sites[$site] = Site::init($domain, $contentRoot);
        return $this;
    }

    public function site(string $named): Site|false
    {
        if (array_key_exists($named, $this->sites)) {
            return $this->sites[$named];
        }
        return false;
    }

    public function schemeForSite(string $site): string
    {
        $d = $this->domainForSite($site);
        $parts = explode('://', $d);
        return $parts[0];
    }

    public function authorityForSite(string $site): string
    {
        $d = $this->domainForSite($site);
        $parts = explode('://', $d);
        return $parts[1];
    }

    public function contentRootForSite(string $site): string
    {
        if (array_key_exists($site, $this->domainsAndContentRoots())) {
            $d = $this->domainsAndContentRoots();
            $i = $d[$site];
            return $i[1];
        }
        return '';
    }

    public function domainForSite(string $site): string
    {
        if (array_key_exists($site, $this->domainsAndContentRoots())) {
            $d = $this->domainsAndContentRoots();
            $i = $d[$site];
            return $i[0];
        }
        return '';
    }

    private function domainsAndContentRoots(): array
    {
        return $this->domainsAndContentRoots;
    }
}
