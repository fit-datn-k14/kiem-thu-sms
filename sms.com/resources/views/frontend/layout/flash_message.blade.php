@if (session()->has('flash_notification.message'))
    @if (session()->has('flash_notification.overlay'))
        @include('backend.layout.flash_modal', [
            'modalClass' => 'flash-modal',
            'title'      => session('flash_notification.title'),
            'body'       => session('flash_notification.message')
        ])
    @else
        <div class="alert callout callout-{{ session('flash_notification.level') }}">
            {{--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>--}}
            {!! session('flash_notification.message') !!}
        </div>
    @endif
@endif
