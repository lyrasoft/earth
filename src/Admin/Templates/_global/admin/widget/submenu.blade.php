<?php
/**
 * @var $helper \Windwalker\Core\View\Helper\Set\HelperSet
 * @var $router \Windwalker\Core\Router\PackageRouter
 */
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
        <a class="btn btn-default btn-outline-secondary btn-sm user-info-profile-button" href="@route('user', ['id' => $user->id])">
            Profile
        </a>
    </div>
</div>

<ul id="submenu" class="nav nav-stacked nav-pills flex-column">
    <li class="nav-item {{ $helper->menu->active('categories', ['type' => 'article']) }}">
        <a href="{{ $router->route('categories', ['type' => 'article']) }}" class="nav-link {{ $helper->menu->active('categories', ['type' => 'article']) }}">
            @translate($luna->langPrefix . 'categories.title')
        </a>
    </li>

    <li class="nav-item {{ $helper->menu->active('articles') }}">
        <a href="{{ $router->route('articles') }}" class="nav-link {{ $helper->menu->active('articles') }}">
            @translate($luna->langPrefix . 'articles.title')
        </a>
    </li>

    <li class="nav-item {{ $helper->menu->active('tags') }}">
        <a href="{{ $router->route('tags') }}" class="nav-link {{ $helper->menu->active('tags') }}">
            @translate($luna->langPrefix . 'tags.title')
        </a>
    </li>

    <li class="nav-item {{ $helper->menu->active('comments') }}">
        <a href="{{ $router->route('comments', ['type' => 'article']) }}" class="nav-link {{ $helper->menu->active('comments') }}">
            @translate($luna->langPrefix . 'comments.title')
        </a>
    </li>

    <li class="nav-item {{ $helper->menu->active('languages') }}">
        <a href="{{ $router->route('languages') }}" class="nav-link {{ $helper->menu->active('languages') }}">
            @translate($luna->langPrefix . 'languages.title')
        </a>
    </li>

    <li class="nav-item {{ $helper->menu->active('modules') }}">
        <a href="{{ $router->route('modules') }}" class="nav-link {{ $helper->menu->active('modules') }}">
            @translate($luna->langPrefix . 'modules.title')
        </a>
    </li>

    <li class="nav-item {{ $helper->menu->active('users') }}">
        <a href="{{ $router->route('users') }}" class="nav-link {{ $helper->menu->active('users') }}">
            @translate($warder->langPrefix . 'users.title')
        </a>
    </li>

    <li class="nav-item {{ $helper->menu->active('contacts') }}">
        <a href="{{ $router->route('contacts') }}" class="nav-link {{ $helper->menu->active('contacts') }}">
            @translate($luna->langPrefix . 'contacts.title')
        </a>
    </li>

    {{-- @muse-placeholder  submenu  Do not remove this line --}}
</ul>
