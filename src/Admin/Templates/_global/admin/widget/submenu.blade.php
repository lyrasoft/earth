<?php
/**
 * @var $helper \Admin\Helper\MenuHelper
 * @var $router \Windwalker\Core\Router\PackageRouter
 */
?>

<h3 class="visible-xs-block">
    @translate('phoenix.title.submenu')
</h3>

<div id="user-info" class="text-center" style="padding-top: 50px; padding-bottom: 50px; border-bottom: 1px solid #efefef">
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

<div id="submenu" class="list-group">
    <a href="{{ $router->route('categories', ['type' => 'article']) }}"
        class="list-group-item {{ $helper->menu->active('categories', ['type' => 'article']) }}">
        @translate($luna->langPrefix . 'categories.title')
    </a>

    <a href="{{ $router->route('articles') }}"
        class="list-group-item {{ $helper->menu->active('articles') }}">
            @translate($luna->langPrefix . 'articles.title')
    </a>

    <a href="{{ $router->route('tags') }}"
        class="list-group-item {{ $helper->menu->active('tags') }}">
        @translate($luna->langPrefix . 'tags.title')
    </a>

    <a href="{{ $router->route('comments', ['type' => 'article']) }}"
        class="list-group-item {{ $helper->menu->active('comments') }}">
        @translate($luna->langPrefix . 'comments.title')
    </a>

    <a href="{{ $router->route('languages') }}"
        class="list-group-item {{ $helper->menu->active('languages') }}">
        @translate($luna->langPrefix . 'languages.title')
    </a>

    <a href="{{ $router->route('modules') }}"
        class="list-group-item {{ $helper->menu->active('modules') }}">
        @translate($luna->langPrefix . 'modules.title')
    </a>

    <a href="{{ $router->route('users') }}"
        class="list-group-item {{ $helper->menu->active('users') }}">
        @translate($warder->langPrefix . 'users.title')
    </a>

    <a href="{{ $router->route('contacts') }}"
        class="list-group-item {{ $helper->menu->active('contacts') }}">
        @translate($luna->langPrefix . 'contacts.title')
    </a>

    {{-- @muse-placeholder  submenu  Do not remove this line --}}
</div>
