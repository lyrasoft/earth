{{-- Part of Admin project. --}}

@extends('_global.admin.admin')

@section('admin-area')
    <section id="admin-area">
        @messages

        @yield('admin-body', 'Admin Body')
    </section>
@stop
