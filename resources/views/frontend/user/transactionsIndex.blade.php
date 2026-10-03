@extends('frontend.user.layoutIndex')

@section('panelTitle', 'Transactions')
@section('panelCrumb', 'Transactions')

@section('panelBody')
    <div class="up-head">
        <div>
            <h2>Transactions</h2>
            <p>Platform fee payments made from this account.</p>
        </div>
    </div>

    <div class="up-card">
        @if (count($transactions))
            <div class="up-table-wrap">
                <table class="up-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Application</th>
                            <th>For</th>
                            <th>Amount</th>
                            <th>Payment ID</th>
                            <th>Status</th>
                            <th>Invoice</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $t)
                            <tr>
                                <td>{{ date('d M Y', strtotime($t->created_at)) }}<div class="muted">{{ date('h:i A', strtotime($t->created_at)) }}</div></td>
                                <td>{{ $t->application_no }}<div class="muted">{{ $t->loan_type }}</div></td>
                                <td>{{ 'consultant' === $t->login_type ? 'Hire Agent fee' : 'Self Login fee' }}</td>
                                <td><strong>&#8377; {{ number_format((float) $t->total_amount, 2) }}</strong><div class="muted">&#8377; {{ number_format((float) $t->base_amount) }} + GST &#8377; {{ number_format((float) $t->gst_amount, 2) }}</div></td>
                                <td><span class="muted">{{ $t->gateway_payment_id ?: '—' }}</span><div class="muted">{{ ucfirst($t->payment_gateway) }}</div></td>
                                <td><span class="up-badge up-badge--{{ 'success' === $t->status ? 'success' : ('failed' === $t->status ? 'failed' : 'pending') }}">{{ ucfirst($t->status) }}</span></td>
                                <td>
                                    @if ('success' === $t->status)
                                        <a class="fl-btn fl-btn--sm fl-btn--ghost" href="{{ route('_userTransactionInvoice', ['transaction' => $t->id]) }}"><i class="bi bi-download"></i> PDF</a>
                                    @else
                                        <span class="muted">&mdash;</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="up-empty"><i class="bi bi-receipt"></i>No payments yet.</div>
        @endif
    </div>
@endsection
