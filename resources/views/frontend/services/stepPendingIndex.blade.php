{{-- Scaffold shown for steps that are not implemented yet (removed phase by phase). --}}
@extends('frontend.services.stepLayoutIndex')

@section('stepSubtitle')
    This step is being set up.
@endsection

@section('stepBody')
    <div class="fl-note" style="margin-top:0;">
        <i class="bi bi-cone-striped"></i>
        <div>
            <strong>{{ $step_meta['label'] }}</strong> step is not live yet.
            The route, the progress bar and the resume logic are in place &mdash; the form comes next.
        </div>
    </div>

    <div class="fl-actions" style="margin-top:24px;">
        <a class="fl-btn fl-btn--ghost" href="{{ route('_' . $service['type'] . 'ServiceIndex') }}">
            <i class="bi bi-arrow-left"></i> Back to {{ $service['label'] }}
        </a>
    </div>
@endsection
