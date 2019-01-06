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

$menu = $app->make(\Phoenix\Html\MenuHelper::class);
?>

<h3 class="visible-xs-block d-sm-block d-md-none">
    @translate('phoenix.title.submenu')
</h3>

<div id="user-info" class="text-center" style="padding-top: 50px; padding-bottom: 50px;">
    <div class="user-info-avatar-wrap text-center">
        <img class="img-circle rounded-circle" src="{{ $user->avatar }}" width="75" height="75" alt="Avatar">
    </div>
    <div class="user-info-detail">
        <h5 class="user-info-name my-3"><strong>{{ $user->name }}</strong></h5>
        <a class="btn btn-default btn-outline-secondary btn-sm user-info-profile-button"
           href="@route('user', ['id' => $user->id])">
            Profile
        </a>
    </div>
</div>

<ul id="submenu" class="nav nav-stacked nav-pills flex-column">
    <li class="nav-item {{ $menu->active('categories', ['type' => 'article']) }}">
        <a href="{{ $router->route('categories', ['type' => 'article']) }}"
           class="nav-link {{ $menu->active('categories', ['type' => 'article']) }}">
            @translate($luna->langPrefix . 'categories.title')
        </a>
    </li>

    <li class="nav-item {{ $menu->active('articles') }}">
        <a href="{{ $router->route('articles') }}" class="nav-link {{ $menu->active('articles') }}">
            @translate($luna->langPrefix . 'articles.title')
        </a>
    </li>

    <li class="nav-item {{ $menu->active('pages') }}">
        <a href="{{ $router->route('pages') }}" class="nav-link {{ $menu->active('pages') }}">
            @translate($luna->langPrefix . 'pages.title')
        </a>
    </li>

    <li class="nav-item {{ $menu->active('tags') }}">
        <a href="{{ $router->route('tags') }}" class="nav-link {{ $menu->active('tags') }}">
            @translate($luna->langPrefix . 'tags.title')
        </a>
    </li>

    <li class="nav-item {{ $menu->active('comments') }}">
        <a href="{{ $router->route('comments', ['type' => 'article']) }}"
           class="nav-link {{ $menu->active('comments') }}">
            @translate($luna->langPrefix . 'comments.title')
        </a>
    </li>

    <li class="nav-item {{ $menu->active('languages') }}">
        <a href="{{ $router->route('languages') }}" class="nav-link {{ $menu->active('languages') }}">
            @translate($luna->langPrefix . 'languages.title')
        </a>
    </li>

    <li class="nav-item {{ $menu->active('modules') }}">
        <a href="{{ $router->route('modules') }}" class="nav-link {{ $menu->active('modules') }}">
            @translate($luna->langPrefix . 'modules.title')
        </a>
    </li>

    <li class="nav-item {{ $menu->active('users') }}">
        <a href="{{ $router->route('users') }}" class="nav-link {{ $menu->active('users') }}">
            @translate($warder->langPrefix . 'users.title')
        </a>
    </li>

    <li class="nav-item {{ $menu->active('contacts') }}">
        <a href="{{ $router->route('contacts') }}" class="nav-link {{ $menu->active('contacts') }}">
            @translate($luna->langPrefix . 'contacts.title')
        </a>
    </li>

    <li class="nav-item {{ $menu->active('config', ['type' => 'core']) }}">
        <a href="{{ $router->route('config', ['type' => 'core']) }}" class="nav-link {{ $menu->active('config', ['type' => 'core']) }}">
            @translate($luna->langPrefix . 'config.title')
        </a>
    </li>

    {{-- @muse-placeholder  submenu  Do not remove this line --}}
</ul>
