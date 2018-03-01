{{-- Part of phoenix project. --}}

<aside id="admin-toolbar" class="">
    <button data-toggle="collapse" class="btn btn-default toolbar-toggle-button" data-target=".admin-toolbar-buttons">
        <span class="glyphicon glyphicon-wrench"></span>
        @translate('phoenix.toolbar.toggle')
    </button>
    <div class="admin-toolbar-buttons">
        <hr/>
        @yield('toolbar-buttons')
    </div>
</aside>
