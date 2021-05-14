<?php

/**
 * Global variables
 * --------------------------------------------------------------
 * @var $app       AppContext      Application context.
 * @var $view      CategoryEditView       The view modal object.
 * @var $uri       SystemUri       System Uri information.
 * @var $chronos   ChronosService  The chronos datetime service.
 * @var $nav       Navigator       Navigator object to build route.
 * @var $asset     AssetService    The Asset manage service.
 * @var $lang      LangService     The language translation service.
 */

declare(strict_types=1);

use App\Module\Admin\Category\CategoryEditView;
use Windwalker\Core\Application\AppContext;
use Windwalker\Core\Asset\AssetService;
use Windwalker\Core\DateTime\ChronosService;
use Windwalker\Core\Language\LangService;
use Windwalker\Core\Router\Navigator;
use Windwalker\Core\Router\SystemUri;

/**
 * @var $form \Windwalker\Form\Form
 */

$lang = $lang->extract('luna.');

$app->service(\Unicorn\Script\UnicornScript::class)->data('foo', 'Bar');

?>

@extends('admin.global.body')

@section('toolbar-buttons')
    @include('edit-toolbar')
@stop

@section('content')
<uni-form-validate scroll>
    <form name="admin-form" id="admin-form" novalidate
        action="{{ $nav->to('category_edit', ['type' => $type]) }}"
        method="POST" enctype="multipart/form-data">

        <x-title-bar :form="$form"></x-title-bar>

        <div class="row">
            <div class="col-md-7">
                <x-fieldset name="basic" :title="$lang('category.edit.fieldset.basic')"
                    :form="$form" class="mb-4">
                </x-fieldset>
                <x-fieldset name="text" :title="$lang('category.edit.fieldset.text')"
                    :form="$form" class="mb-4">
                </x-fieldset>
            </div>
            <div class="col-md-5">
                <x-fieldset name="meta" :title="$lang('category.edit.fieldset.meta')"
                    :form="$form" class="mb-4">
                </x-fieldset>
            </div>
        </div>

        <div class="hidden-inputs">
            @formToken
        </div>

    </form>
</uni-form-validate>
@stop