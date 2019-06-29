{{-- Part of earth project. --}}
<?php
/**
 * Global variables
 * --------------------------------------------------------------
 * @var $app      \Windwalker\Web\Application                 Global Application
 * @var $package  \Windwalker\Core\Package\AbstractPackage    Package object.
 * @var $view     \Windwalker\Data\Data                       Some information of this view.
 * @var $uri      \Windwalker\Uri\UriData                     Uri information, example: $uri->path
 * @var $chronos  \Windwalker\Core\DateTime\Chronos           PHP DateTime object of current time.
 * @var $helper   \Windwalker\Core\View\Helper\Set\HelperSet  The Windwalker HelperSet object.
 * @var $router   \Windwalker\Core\Router\PackageRouter       Route builder object.
 * @var $asset    \Windwalker\Core\Asset\AssetManager         The Asset manager.
 *
 * ---------------------------------------------------------------
 * @var $form     \Windwalker\Form\Form
 */

$update = isset($update) ? $update : true;
$copy = isset($copy) ? $copy : true;
?>
<style>
    body.bootstrap-3 #batch-modal .close {
        position: relative;
        top: -25px;
    }
</style>
<div class="modal fade" id="batch-modal" tabindex="-1" role="dialog" aria-labelledby="batch-modal-title">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="batch-modal-title">
                    <span class="fa fa-sliders"></span> @lang('phoenix.batch.modal.title')
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p>
                    @lang('phoenix.batch.modal.desc')
                </p>
                <hr />
                <div class="form-horizontal">
                    {!! $form->renderFields(null, 'batch') !!}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" role="button" class="btn btn-default btn-outline-secondary" data-dismiss="modal">
                    <span class="fa fa-remove fa-times"></span>
                    @lang('phoenix.core.close')
                </button>
                @if ($update)
                    <button type="button" class="btn btn-info" onclick="Phoenix.Grid.hasChecked();Phoenix.patch()">
                        <span class="fa fa-check"></span>
                        @lang('phoenix.core.update')
                    </button>
                @endif
                @if ($copy)
                    <button type="button" class="btn btn-primary" onclick="Phoenix.Grid.hasChecked();Phoenix.post()">
                        <span class="fa fa-copy"></span>
                        @lang('phoenix.core.copy')
                    </button>
                @endif
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
