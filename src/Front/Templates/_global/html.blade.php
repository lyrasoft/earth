{{-- Part of Windwalker project. --}}
<!DOCTYPE html>
<html lang="{{ $app->get('language.locale') ? : $app->get('language.default', 'en-GB') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0">

    <title>{{ \Phoenix\Html\HtmlHeader::getPageTitle() }}</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ $asset->path }}/images/favicon.ico"/>
    <meta name="generator" content="Windwalker Framework"/>
    {!! \Phoenix\Html\HtmlHeader::renderMetadata() !!}
    @yield('meta')

    {!! $asset->renderStyles(true) !!}
    @yield('style')
    @stack('style')
    {!! \Phoenix\Html\HtmlHeader::renderCustomTags() !!}
</head>
<body class="package-{{ $package->name }} view-{{ strtolower($view->getName()) }} layout-{{ $view->getLayout() }}"
      style="padding-top: 56px">
@section('navbar')
    <div class="navbar navbar-default navbar-fixed-top fixed-top navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="{{ $router->route('home') }}">Windwalker</a>
            <button type="button" class="navbar-toggle navbar-toggler" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    @section('nav')
                        <li class="nav-item active">
                            <a href="{{ $router->to('home')->mute() }}" class="nav-link">Home</a>
                        </li>
                    @show

                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false">
                            Category
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            @foreach ($categories as $category)
                                <li>
                                    <a class="dropdown-item" href="{{ $router->to('article_category', array('path' => $category->path))->mute() }}">
                                        {{ str_repeat('-', $category->level - 1) }}
                                        {{ $category->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right ml-auto">
                    {{-- <li class="pull-right"><a href="{{ $uri->path }}/admin">Admin</a></li> --}}
                    @if (!$user->isMember())
                        <li class="nav-item">
                            <a class="nav-link" href="@route('login', ['return' => base64_encode($uri->full)])">
                                <span class="fa fa-sign-in fa-sign-in-alt"></span>
                                Login
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="@route('logout', ['return' => base64_encode($uri->full)])">
                                <span class="fa fa-sign-out fa-sign-out-alt"></span>
                                Logout
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
    </div>
@show

@section('message')
    @messages()
@show

@yield('content', 'Content')

@section('copyright')
    <div id="copyright">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">

                    <hr/>

                    <footer>
                        &copy; Windwalker {{ $datetime->format('Y') }}
                    </footer>
                </div>
            </div>
        </div>
    </div>
@show
{!! $asset->getTemplate()->renderTemplates() !!}
{!! $asset->renderScripts(true) !!}
@yield('script')
@stack('script')
</body>
</html>
