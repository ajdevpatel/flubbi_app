@extends('frontend.user.layoutIndex')

@section('panelTitle', 'Dashboard')

@section('panelBody')
    <div class="up-head">
        <div>
            <h2>Welcome back, {{ explode(' ', trim($user->name ?: 'there'))[0] }}</h2>
            <p>Track your applications, upload documents and manage your account.</p>
        </div>
        <div class="fl-actions" style="margin:0;">
            <a class="fl-btn fl-btn--sm" href="{{ route('_personalServiceStart') }}"><i class="bi bi-plus-lg"></i> Personal Loan</a>
            <a class="fl-btn fl-btn--sm fl-btn--ghost" href="{{ route('_businessServiceStart') }}"><i class="bi bi-plus-lg"></i> Business Loan</a>
        </div>
    </div>

    <div class="up-stats">
        <div class="up-stat"><i class="bi bi-files"></i><div><strong>{{ $stats['total'] }}</strong><span>Applications</span></div></div>
        <div class="up-stat"><i class="bi bi-hourglass-split"></i><div><strong>{{ $stats['active'] }}</strong><span>In progress</span></div></div>
        <div class="up-stat"><i class="bi bi-credit-card-2-front"></i><div><strong>{{ $stats['paid'] }}</strong><span>Fee paid</span></div></div>
        <div class="up-stat"><i class="bi bi-cash-coin"></i><div><strong>{{ $stats['disbursed'] }}</strong><span>Disbursed</span></div></div>
    </div>

    <div class="up-card">
        <div class="up-card__title">
            <h4>Latest application</h4>
            <a class="fl-link" href="{{ route('_userApplications') }}">View all</a>
        </div>

        @if ($latest)
            @include('frontend.user.applicationCardIndex', ['app' => $latest])
        @else
            <div class="up-empty">
                <i class="bi bi-file-earmark-plus"></i>
                You have not applied yet. Check your eligibility in under 2 minutes.
            </div>
        @endif
    </div>

    <div class="row">
        {{--
        <div class="col-lg-7">
            <div class="up-card">
                <div class="up-card__title">
                    <h4>Notifications</h4>
                    <a class="fl-link" href="{{ route('_userNotifications') }}">See all</a>
                </div>
                @forelse ($notifications as $n)
                    <div class="up-notif {{ $n->is_read ? '' : 'is-unread' }}">
                        <i class="bi bi-bell"></i>
                        <div>
                            <strong>{{ $n->title }}</strong>
                            <p>{{ $n->message }}</p>
                            <small>{{ date('d M Y, h:i A', strtotime($n->created_at)) }}</small>
                        </div>
                    </div>
                @empty
                    <div class="up-empty"><i class="bi bi-bell-slash"></i>No notifications yet.</div>
                @endforelse
            </div>
        </div>
        --}}
        <div class="col-12">
            <div class="up-card">
                <div class="up-card__title"><h4>Need help?</h4></div>
                <p style="font-size:14px;color:var(--fl-muted);margin-bottom:12px;">Our team replies within one working day.</p>
                <div class="up-contact" style="flex-direction:column;gap:8px;">
                    <span><i class="bi bi-envelope"></i> <a href="mailto:{{ $support['mail'] }}">{{ $support['mail'] }}</a></span>
                    <span><i class="bi bi-telephone"></i> <a href="tel:{{ $support['phone'] }}">{{ $support['phone'] }}</a></span>
                </div>
                <div class="fl-actions" style="margin-top:16px;">
                    <a class="fl-btn fl-btn--ghost fl-btn--sm" href="{{ route('_userSupport') }}"><i class="bi bi-chat-dots"></i> Raise a ticket</a>
                </div>
            </div>
        </div>
    </div>
@endsection
