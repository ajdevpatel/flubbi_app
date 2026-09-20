@extends('frontend.user.layoutIndex')

@section('panelTitle', 'Notifications')
@section('panelCrumb', 'Notifications')

@section('panelBody')
    <form id="fl-notif-form" method="POST" action="{{ route('_userNotifications') }}" hidden>@csrf</form>

    <div class="up-head">
        <div>
            <h2>Notifications</h2>
            <p>{{ $unread }} unread</p>
        </div>
        @if ($unread)
            <button type="button" class="fl-btn fl-btn--ghost fl-btn--sm" data-fl-post="{{ route('_userNotifications') }}" data-fl-form="#fl-notif-form" data-fl-data='{"mark_all":1}'>
                <i class="bi bi-check2-all"></i> Mark all as read
            </button>
        @endif
    </div>

    <div class="up-card">
        @forelse ($notifications as $n)
            <div class="up-notif {{ $n->is_read ? '' : 'is-unread' }}">
                <i class="bi bi-bell"></i>
                <div style="flex:1;min-width:0;">
                    <strong>{{ $n->title }}</strong>
                    <p>{{ $n->message }}</p>
                    <small>{{ date('d M Y, h:i A', strtotime($n->created_at)) }}</small>
                </div>
                @unless ($n->is_read)
                    <button type="button" class="fl-btn fl-btn--link" style="align-self:flex-start;white-space:nowrap;" data-fl-post="{{ route('_userNotifications') }}" data-fl-form="#fl-notif-form" data-fl-data='{"notification_id":{{ (int) $n->id }}}'>Mark read</button>
                @endunless
            </div>
        @empty
            <div class="up-empty"><i class="bi bi-bell-slash"></i>Nothing here yet. We will notify you about status changes and offers.</div>
        @endforelse
    </div>
@endsection
