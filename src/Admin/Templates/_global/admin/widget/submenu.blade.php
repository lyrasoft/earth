<?php
/**
 * @var $helper \Windwalker\Core\View\Helper\Set\HelperSet
 * @var $router \Windwalker\Core\Router\PackageRouter
 */
?>

<h3 class="visible-xs-block">
    @translate('phoenix.title.submenu')
</h3>

<div id="user-info" class="text-center" style="padding-top: 50px; padding-bottom: 50px;">
    <div class="user-info-avatar-wrap text-center">
        <img class="img-circle" src="{{ $user->avatar }}" width="75" height="75" alt="Avatar">
    </div>
    <div class="user-info-detail">
        <h5 class="user-info-name"><strong>{{ $user->name }}</strong></h5>
        <a class="btn btn-default btn-xs user-info-profile-button" href="@route('user', ['id' => $user->id])">
            Profile
        </a>
    </div>
</div>

<ul id="submenu" class="nav nav-pills nav-stacked">
    <li class="{{ $helper->menu->active('categories', ['type' => 'article']) }}">
        <a href="{{ $router->route('categories', ['type' => 'article']) }}">
            @translate($luna->langPrefix . 'categories.title')
        </a>
    </li>

    <li class="{{ $helper->menu->active('articles') }}">
        <a href="{{ $router->route('articles') }}">
            @translate($luna->langPrefix . 'articles.title')
        </a>
    </li>

    <li class="{{ $helper->menu->active('tags') }}">
        <a href="{{ $router->route('tags') }}">
            @translate($luna->langPrefix . 'tags.title')
        </a>
    </li>

    <li class="{{ $helper->menu->active('comments') }}">
        <a href="{{ $router->route('comments', ['type' => 'article']) }}">
            @translate($luna->langPrefix . 'comments.title')
        </a>
    </li>

    <li class="{{ $helper->menu->active('languages') }}">
        <a href="{{ $router->route('languages') }}">
            @translate($luna->langPrefix . 'languages.title')
        </a>
    </li>

    <li class="{{ $helper->menu->active('modules') }}">
        <a href="{{ $router->route('modules') }}">
            @translate($luna->langPrefix . 'modules.title')
        </a>
    </li>

    <li class="{{ $helper->menu->active('users') }}">
        <a href="{{ $router->route('users') }}">
            @translate($warder->langPrefix . 'users.title')
        </a>
    </li>

    <li class="{{ $helper->menu->active('contacts') }}">
        <a href="{{ $router->route('contacts') }}">
            @translate($luna->langPrefix . 'contacts.title')
        </a>
    </li>

    {{-- @muse-placeholder  submenu  Do not remove this line --}}
</ul>
