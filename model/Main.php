<?php

class Main
{
    public $pagePath;
    public $bodyClassName;

    /**
     * @return string
     */
    public function bodyClass(): string
    {
        $pagePath = (isset($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : '';

        switch ($pagePath) {
            case '/home':
                $this->bodyClassName = 'page-home';
                break;
            case '/preventivni-prohlidky':
                $this->bodyClassName = 'page-preventivni-prohlidky';
                break;
            case '/pro-budouci-maminky':
                $this->bodyClassName = 'page-pro-budouci-maminky';
                break;
            case '/ockovani':
                $this->bodyClassName = 'page-ockovani';
                break;
            case '/nadstandardni-sluzby':
                $this->bodyClassName = 'page-nadstandardni-sluzby';
                break;
            case '/smluvni-pojistovny':
                $this->bodyClassName = 'page-pojistovny';
                break;
            case '/odkazy':
                $this->bodyClassName = 'page-odkazy';
                break;
            case '/kontakt':
                $this->bodyClassName = 'page-kontakt';
                break;
            case '/gallery':
                $this->bodyClassName = 'page-gallery';
                break;
            default:
                $this->bodyClassName = 'page-undefined';
        }

        return $this->bodyClassName;
    }
}
