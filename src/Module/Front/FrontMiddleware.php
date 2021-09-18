<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Module\Front;

use Lyrasoft\Luna\Script\FontAwesomeScript;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Unicorn\Script\UnicornScript;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\Html\HtmlFrame;
use Windwalker\Core\Middleware\AbstractLifecycleMiddleware;

/**
 * The FrontMiddleware class.
 */
class FrontMiddleware extends AbstractLifecycleMiddleware
{
    public function __construct(
        protected AppContext $app,
        protected AssetService $asset,
        protected HtmlFrame $htmlFrame,
        protected UnicornScript $unicornScript,
        protected FontAwesomeScript $fontAwesomeScript,
    ) {
    }

    /**
     * prepareExecute
     *
     * @param ServerRequestInterface $request
     *
     * @return  mixed
     */
    protected function preprocess(ServerRequestInterface $request): void
    {
        // Unicorn
        $this->unicornScript->init('js/front/main.js');

        // Font Awesome
        $this->fontAwesomeScript->cssFont(FontAwesomeScript::DEFAULT_SET);

        // Bootstrap
        $this->asset->css('vendor/bootstrap/dist/css/bootstrap.min.css');
        $this->asset->js('vendor/bootstrap/dist/js/bootstrap.bundle.min.js');

        // Main
        $this->asset->css('css/front/main.css');

        // Metadata
        $this->htmlFrame->setFavicon('images/favicon.png');
        $this->htmlFrame->setSiteName('Windwalker');
        $this->htmlFrame->setDescription('Windwalker Site Description.');
        // $this->htmlFrame->setCoverImages($this->asset->root('...'));
    }

    /**
     * postExecute
     *
     * @param ResponseInterface $response
     *
     * @return  mixed
     */
    protected function postProcess(ResponseInterface $response): void
    {
    }
}
