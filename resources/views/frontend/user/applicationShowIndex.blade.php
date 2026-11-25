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
            <div class="up-kv"><span>Monthly EMI</span><strong>{{ $loan->emi_amount ? '₹ ' . number_format((float) $loan->emi_amount) : '—' }}</strong></div>
            <div class="up-kv"><span>Monthly income</span><strong>{{ $loan->monthly_income ? '₹ ' . number_format((float) $loan->monthly_income) : '—' }}</strong></div>
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
            <div class="up-card__title"><h4>Lending partners</h4></div>
            <p style="font-size:14px;color:var(--fl-muted);margin-bottom:10px;">Apply to as many lenders as you like &mdash; tap a bank to open its application page. You can apply to more anytime.</p>
            @if (count($banks))
                <div class="fl-bank-grid" id="up-bank-grid"
                    data-url="{{ route('_userApplicationBankApply', $loan->application_no) }}">
                    @foreach ($banks as $bank)
                        @php $count = (int) ($applied[$bank->id] ?? 0); @endphp
                        <button type="button" class="fl-bank fl-bank--apply @if ($count > 0) is-applied @endif"
                            data-id="{{ $bank->id }}" data-link="{{ trim($bank->link) }}" title="{{ $bank->label }}">
                            <span class="fl-bank__badge" @if ($count < 1) hidden @endif>Applied<span class="fl-bank__count">@if ($count > 1) ({{ $count }}) @endif</span></span>
                            <span class="fl-bank__logo"><img src="{{ asset('banks/' . $bank->logo) }}" alt="{{ $bank->label }}" loading="lazy"></span>
                            <span class="fl-bank__name">{{ ucwords($bank->label) }}</span>
                            <span class="fl-bank__apply"><i class="bi bi-box-arrow-up-right"></i> Apply</span>
                        </button>
                    @endforeach
                </div>
            @else
                <div class="up-note"><i class="bi bi-bank"></i><div>No lending partners are available right now. Please check back soon.</div></div>
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

@section('panelJs')
    <script>
        (function () {
            var $grid = $("#up-bank-grid");
            if (!$grid.length) {
                return;
            }
            var url = $grid.data("url"), token = '{{ csrf_token() }}', busy = false;

            $grid.on("click", ".fl-bank--apply", function () {
                if (busy) {
                    return false;
                }
                var $b = $(this);
                busy = true;
                $b.addClass("is-loading");
                var tab = window.open("", "_blank");

                $.ajax({
                    type: "POST",
                    url: url,
                    data: { _token: token, bank_id: $b.data("id") },
                    dataType: "json",
                    success: function (res) {
                        busy = false;
                        $b.removeClass("is-loading");
                        if (res.open && tab) {
                            tab.location.href = res.open;
                        } else if (tab) {
                            tab.close();
                        }
                        $b.addClass("is-applied");
                        $b.find(".fl-bank__badge").prop("hidden", false);
                        if (res.apply_count && res.apply_count > 1) {
                            $b.find(".fl-bank__count").text(" (" + res.apply_count + ")");
                        }
                        if (res.message && typeof Notify === "function") {
                            Notify(res.message, "success");
                        }
                    },
                    error: function (xhr) {
                        busy = false;
                        $b.removeClass("is-loading");
                        if (tab) {
                            tab.close();
                        }
                        if (typeof ajaxResponseFailure === "function") {
                            ajaxResponseFailure(xhr);
                        }
                    }
                });
                return false;
            });
        })();
    </script>
@endsection
