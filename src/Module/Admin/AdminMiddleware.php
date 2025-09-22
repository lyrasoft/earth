<?php

declare(strict_types=1);

namespace App\Module\Admin;

use Lyrasoft\Luna\LunaPackage;
use Lyrasoft\Luna\Script\FontAwesomeScript;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Unicorn\Script\UnicornScript;
use Unicorn\UnicornPackage;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\Html\HtmlFrame;
use Windwalker\Core\Language\TranslatorTrait;
use Windwalker\Core\Middleware\AbstractLifecycleMiddleware;

class AdminMiddleware extends AbstractLifecycleMiddleware
{
    use TranslatorTrait;

    public function __construct(
        protected AppContext $app,
        protected AssetService $asset,
        protected UnicornScript $unicornScript,
        protected FontAwesomeScript $fontAwesomeScript,
        protected HtmlFrame $htmlFrame,
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
        $this->lang->loadAllFromVendor(UnicornPackage::class, 'ini');
        $this->lang->loadAllFromVendor(LunaPackage::class, 'ini');
        $this->lang->loadAll('ini');

        // Unicorn
        $this->unicornScript->init('@vite/src/admin/main.ts');

        // Font Awesome
        $this->fontAwesomeScript->cssFont(FontAwesomeScript::DEFAULT_SET);

        // Bootstrap
        $this->asset->css('vendor/bootstrap/dist/css/bootstrap.min.css');
        // $this->asset->js('vendor/bootstrap/dist/js/bootstrap.bundle.min.js');

        // Main
        $this->asset->css('@vite/scss/admin/main.scss');
        // $this->asset->css('css/admin/main.css');

        // HtmlFrame
        $this->htmlFrame->setFavicon($this->asset->path('images/admin/favicon.png'));
    }

    // protected function checkAccess(): bool
    // {
    //     $user = $this->app->service(UserService::class)->getUser();
    //
    //     return $user->isLogin();
    // }
    //
    // public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    // {
    //     $user = $this->app->service(UserService::class)->getUser();
    //
    //     if (!$user->isLogin()) {
    //         return $this->app->service(Navigator::class)->redirectTo('front::home');
    //     }
    //
    //     return parent::process($request, $handler);
    // }

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
