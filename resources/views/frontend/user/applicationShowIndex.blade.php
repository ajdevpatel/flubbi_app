@extends('frontend.user.layoutIndex')

@section('panelTitle', $loan->loan_type)
@section('panelCrumb', $loan->application_no)

@php
    $status_class = \App\Http\Controllers\Fend\UserPanelController::STATUS_CLASS[(int) $loan->status] ?? 'pending';
    $in_progress = 1 == (int) $loan->status && 1 != (int) $loan->payment_status;
    $resume = route(1 == $loan->loan_type_id ? '_personalServiceStart' : '_businessServiceStart');
    $uploaded = collect($docs)->flatten(1)->where('status', 'uploaded')->count();
    $total_docs = collect($docs)->flatten(1)->count();
@endphp

@section('panelBody')
    <div class="up-head">
        <div>
            <a class="fl-link" href="{{ route('_userApplications') }}"><i class="bi bi-arrow-left"></i> All applications</a>
            <h2 style="margin-top:8px;">{{ $loan->loan_type }} &middot; {{ $loan->application_no }}</h2>
            <p>Applied on {{ date('d M Y, h:i A', strtotime($loan->applied_at)) }}</p>
        </div>
        <div class="up-app__head">
            <span class="up-badge up-badge--{{ $status_class }}">{{ $loan->status_label }}</span>
            @if (1 == (int) $loan->payment_status)
                <span class="up-badge up-badge--success">Fee paid &#8377; {{ number_format((float) $loan->tz_amount, 2) }}</span>
            @elseif (2 == (int) $loan->payment_status)
                <span class="up-badge up-badge--failed">Payment failed</span>
            @endif
        </div>
    </div>

    <div class="up-card">
        <div class="up-card__title">
            <h4>Loan summary</h4>
            @if ($in_progress)
                <a class="fl-btn fl-btn--sm" href="{{ $resume }}">Continue application <i class="bi bi-arrow-right"></i></a>
            @endif
        </div>
        <div class="up-grid">
            <div class="up-kv"><span>Loan amount</span><strong>&#8377; {{ number_format((float) ($loan->eligible_amount ?? 0)) }}</strong></div>
            <div class="up-kv"><span>Tenure</span><strong>{{ $loan->tenure_months ? $loan->tenure_months . ' months' : '—' }}</strong></div>
            <div class="up-kv"><span>Interest rate</span><strong>{{ $loan->interest_rate ? rtrim(rtrim(number_format((float) $loan->interest_rate, 2), '0'), '.') . '% p.a.' : '—' }}</strong></div>
            <div class="up-kv"><span>Monthly EMI</span><strong>{{ $loan->emi_amount ? '&#8377; ' . number_format((float) $loan->emi_amount) : '—' }}</strong></div>
            <div class="up-kv"><span>Monthly income</span><strong>{{ $loan->monthly_income ? '&#8377; ' . number_format((float) $loan->monthly_income) : '—' }}</strong></div>
            <div class="up-kv"><span>Existing EMI</span><strong>&#8377; {{ number_format((float) ($loan->existing_emi ?? 0)) }}</strong></div>
            <div class="up-kv"><span>CIBIL range</span><strong>{{ $detail->cibil_label ?? '—' }}</strong></div>
            <div class="up-kv"><span>Employment</span><strong>{{ 'self_employed' === $loan->employment_type ? 'Self employed' : ($loan->employment_type ? 'Salaried' : '—') }}</strong></div>
            <div class="up-kv"><span>Purpose</span><strong>{{ $detail->purpose ?? '—' }}</strong></div>
            <div class="up-kv"><span>Process</span><strong>{{ 'consultant' === $loan->login_type ? 'Hire Agent' : ('self' === $loan->login_type ? 'Self Login' : '—') }}</strong></div>
            <div class="up-kv"><span>Application no</span><strong>{{ $loan->application_no }}</strong></div>
            <div class="up-kv"><span>Status</span><strong>{{ $loan->status_label }}</strong></div>
        </div>
    </div>

    @if ('self' === $loan->login_type && 1 == (int) $loan->payment_status)
        <div class="up-card">
            <div class="up-card__title"><h4>Lending partner</h4></div>
            @if ($loan->bank_name)
                <div class="up-bank">
                    <img src="{{ asset('banks/' . $loan->bank_logo) }}" alt="{{ $loan->bank_name }}">
                    <div>
                        <small>You selected</small>
                        <strong>{{ ucwords($loan->bank_name) }}</strong>
                    </div>
                    @if ($bank_link)
                        <a class="fl-btn fl-btn--sm" href="{{ $bank_link }}" target="_blank" rel="noopener">Open application <i class="bi bi-box-arrow-up-right"></i></a>
                    @endif
                </div>
            @else
                <div class="up-note"><i class="bi bi-bank"></i><div>Payment received. Pick your lending partner to continue.
                    <a class="fl-link" href="{{ route(1 == $loan->loan_type_id ? '_personalServiceStart' : '_businessServiceStart') }}">Choose bank</a></div></div>
            @endif
        </div>
    @elseif ('consultant' === $loan->login_type && 1 == (int) $loan->payment_status)
        <div class="up-card">
            <div class="up-card__title"><h4>Your Flubbi agent</h4></div>
            <div class="up-note"><i class="bi bi-headset"></i><div>A dedicated agent will call you within one working day, collect documents over WhatsApp and coordinate with the best-fit lender.</div></div>
        </div>
    @endif

    <div class="row">
        @if ($docs_allowed)
            <div class="col-12">
                <div class="up-card">
                    <div class="up-card__title">
                        <h4>Documents</h4>
                        <a class="fl-link" href="{{ route('_userDocuments', $loan->application_no) }}">Manage</a>
                    </div>
                    <p style="font-size:14px;color:var(--fl-muted);margin-bottom:10px;">{{ $uploaded }} of {{ $total_docs }} uploaded</p>
                    @foreach ($docs as $group => $items)
                        @foreach ($items as $d)
                            <div style="display:flex;justify-content:space-between;align-items:center;padding:7px 0;border-bottom:1px solid var(--fl-line);font-size:14px;">
                                <span>{{ $d['label'] }}</span>
                                <span class="up-badge up-badge--{{ $d['status'] }}">{{ ucfirst($d['status']) }}</span>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        @endif
        {{--
        <div class="col-lg-6">
            <div class="up-card">
                <div class="up-card__title"><h4>Timeline</h4></div>
                @if (count($history))
                    <ul class="up-timeline">
                        @foreach ($history as $h)
                            <li>
                                <strong>{{ $h->label ?? 'Update' }}</strong>
                                @if (!empty($h->remarks))<span>{{ $h->remarks }}</span>@endif
                                <small>{{ date('d M Y, h:i A', strtotime($h->created_at)) }}</small>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <ul class="up-timeline">
                        <li><strong>Application started</strong><small>{{ date('d M Y, h:i A', strtotime($loan->applied_at)) }}</small></li>
                        <li><strong>{{ $loan->status_label }}</strong><small>Current status</small></li>
                    </ul>
                @endif
            </div>
        </div>
        --}}
    </div>
@endsection
