@section('nav')
    <li class="nav-item {{ $helper->menu->active('home') }}">
        <a class="nav-link" href="{{ $router->route('home') }}">
            @translate('phoenix.title.dashboard')
        </a>
    </li>
@stop
