@php
    $status_class = \App\Http\Controllers\Fend\UserPanelController::STATUS_CLASS[(int) $app->status] ?? 'pending';
    $resume = route(1 == $app->loan_type_id ? '_personalServiceStart' : '_businessServiceStart');
    $in_progress = 1 == (int) $app->status && 1 != (int) $app->payment_status;
@endphp
<div class="up-app">
    <div>
        <div class="up-app__head">
            <span class="up-app__type">{{ $app->loan_type }}</span>
            <span class="up-badge up-badge--{{ $status_class }}">{{ $app->status_label }}</span>
            @if (1 == (int) $app->payment_status)
                <span class="up-badge up-badge--success">Fee paid</span>
            @endif
        </div>
        <div class="up-app__no">{{ $app->application_no }} &middot; {{ date('d M Y', strtotime($app->applied_at)) }}</div>
        <div class="up-app__meta" style="margin-top:10px;">
            <span>Amount <strong>&#8377; {{ number_format((float) ($app->eligible_amount ?? 0)) }}</strong></span>
            <span>Tenure <strong>{{ $app->tenure_months ? $app->tenure_months . ' months' : '—' }}</strong></span>
            <span>EMI <strong>{{ $app->emi_amount ? '&#8377; ' . number_format((float) $app->emi_amount) : '—' }}</strong></span>
            <span>Process <strong>{{ 'consultant' === $app->login_type ? 'Hire Agent' : ('self' === $app->login_type ? 'Self Login' : '—') }}</strong></span>
        </div>
    </div>
    <div class="up-app__actions">
        @if ($in_progress)
            <a class="fl-btn fl-btn--sm" href="{{ $resume }}">Continue <i class="bi bi-arrow-right"></i></a>
        @else
            <a class="fl-btn fl-btn--sm" href="{{ route('_userApplicationShow', $app->application_no) }}">View details</a>
        @endif
        <a class="fl-link" href="{{ route('_userDocuments', $app->application_no) }}">Documents</a>
    </div>
</div>
