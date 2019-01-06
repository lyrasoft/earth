{{-- Part of phoenix project. --}}
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
?>

@php(\Phoenix\Script\BootstrapScript::tooltip())

@section('header')
    <div class="navbar navbar-default navbar-dark bg-dark navbar-fixed-top navbar-expand-lg navbar-light bg-light">
        <div class="navbar-header">
            <a class="navbar-brand" href="{{ $uri->path }}">EARTH</a>
            <button type="button" class="navbar-toggle navbar-toggler" data-toggle="collapse"
                    data-target="#top-navbar-content" aria-controls="#top-navbar-content" aria-expanded="false">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <div id="top-navbar-content" class="collapse navbar-collapse">
            <ul class="nav navbar-nav mr-auto">
                @section('nav')
                    @include('_global.admin.widget.mainmenu')
                @show
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="nav-item">
                    <a href="{{ $router->to('front@home')->mute() }}" target="_blank"
                       class="nav-link hasTooltip" title="Preview" data-placement="bottom">
                        <span class="glyphicon glyphicon-globe fa fa-globe"></span>
                    </a>
                </li>

                @if ($user->isMember())
                    <li class="nav-item">
                        <a href="{{ $router->to('logout')->mute() }}"
                           class="nav-link hasTooltip" title="Logout" data-placement="bottom">
                            <span class="glyphicon glyphicon-log-out fa fa-sign-out fa-sign-out-alt"></span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
        <!--/.nav-collapse -->
    </div>

@show
