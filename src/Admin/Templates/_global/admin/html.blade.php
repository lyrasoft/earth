<?php
/**
 * Global variables
 * --------------------------------------------------------------
 * @var $app      \Windwalker\Web\Application                 Global Application
 * @var $package  \Windwalker\Core\Package\AbstractPackage    Package object.
 * @var $view     \Windwalker\Data\Data                       Some information of this view.
 * @var $uri      \Windwalker\Uri\UriData                     Uri information, example: $uri->path
 * @var $datetime \DateTime                                   PHP DateTime object of current time.
 * @var $helper   \Windwalker\Core\View\Helper\Set\HelperSet  The Windwalker HelperSet object.
 * @var $route    \Windwalker\Core\Router\PackageRouter       Route builder object.
 * @var $asset    \Windwalker\Core\Asset\AssetManager         The Asset manager.
 */
?><!DOCTYPE html>
<html lang="{{ $app->get('language.locale') ? : $app->get('language.default', 'en-GB') }}">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0">

    <title>{{ \Phoenix\Html\HtmlHeader::getPageTitle() }}</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ $asset->path('images/favicon.ico') }}"/>
    <meta name="generator" content="Windwalker Framework"/>
    {!! \Phoenix\Html\HtmlHeader::renderMetadata() !!}
    @yield('meta')

    {!! $asset->renderStyles(true) !!}
    @yield('style')
    @stack('style')
    {!! \Phoenix\Html\HtmlHeader::renderCustomTags() !!}
</head>
<body class="{{ $package->getName() }}-admin-body phoenix-admin package-{{ $package->getName() }}
    view-{{ strtolower($view->getName()) }} layout-{{ $view->getLayout() }} {{ $helper->getView() instanceof \Phoenix\View\EditView ? 'sidebar-hide' : null }}
    bootstrap-{{ \Phoenix\Script\BootstrapScript::$currentVersion }}">
@section('superbody')

    @yield('body', 'Body Section')

@show
{!! $asset->getTemplate()->renderTemplates() !!}
{!! $asset->renderScripts(true) !!}
@yield('script')
@stack('script')
</body>
</html>
