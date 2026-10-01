<?php

namespace Grav\Theme;

class Quark2Child extends Quark2
{

    public static function getSubscribedEvents()
    {
        return [
            'onPagesInitialized' => [['onPagesInitialized', 0]],
            'onPageInit' => [['onPageInit', 0]],
        ];
    }

    public function onPagesInitialized(): void
    {
        $request = $this->grav['request'];
        if (strtolower($request->getHeaderLine('Hx-Request')) !== 'true') {
            return;
        }

        $uri  = $this->grav['uri'];
        $page = $this->grav['pages']->find($uri->path(), true);

        if (!$page || !$page->isModule() || empty($page->header()->htmx)) {
            return;
        }

        /* 
         * SECURITY: Uncomment the block below for Production.
         * This ensures that only authenticated users with 'htmx.write' 
         * permissions can perform modification requests (PUT, POST, PATCH, DELETE).
         */
        /* 
        if (!in_array($uri->method(), ['GET', 'HEAD'], true)) {
            $login = $this->grav['login'] ?? null;
            if (!$login || !$login->isAuthenticated('htmx.write')) {
                return;
            }
        } 
        */

        if (isset($_GET['q'])) {
            $this->grav['twig']->twig_vars['q'] = $_GET['q'];
        }

        $page->routable(true);
    }

    /**
     * Inject is_htmx variable into Twig context when request is HTMX.
     *
     * @return void
     */
    public function onPageInit(): void
    {
        if (!empty($_SERVER['HTTP_HX_REQUEST'])) {
            $this->grav['twig']->twig_vars['is_htmx'] = true;
        }
    }
}
