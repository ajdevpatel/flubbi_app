@php
    $initials = collect(explode(' ', trim($user->name ?: 'U')))->filter()->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))->take(2)->implode('');
    $items = [
        ['_userDashboard', 'bi-grid-1x2', 'Dashboard', 0, ['_userDashboard']],
        ['_userApplications', 'bi-file-earmark-text', 'My Applications', 0, ['_userApplications', '_userApplicationShow']],
        ['_userDocuments', 'bi-cloud-arrow-up', 'Documents', 0, ['_userDocuments']],
        ['_userProfile', 'bi-person', 'Profile', 0, ['_userProfile']],
        ['_userSupport', 'bi-life-preserver', 'Support', 0, ['_userSupport']],
        ['_userNotifications', 'bi-bell', 'Notifications', $unread, ['_userNotifications']],
        ['_userTransactions', 'bi-receipt', 'Transactions', 0, ['_userTransactions']],
    ];
@endphp

<div class="up-user">
    <div class="up-avatar">
        @if (!empty($user->profile_pic))
            <img src="{{ asset('uploads/' . $user->profile_pic) }}" alt="{{ $user->name }}">
        @else
            {{ $initials }}
        @endif
    </div>
    <div>
        <strong>{{ $user->name ?: 'Flubbi user' }}</strong>
        <span>+91 {{ $user->phone }}</span>
    </div>
</div>

<ul class="up-nav">
    @foreach ($items as [$route, $icon, $label, $count, $active])
        <li>
            <a href="{{ route($route) }}" class="{{ request()->routeIs(...$active) ? 'is-active' : '' }}">
                <i class="bi {{ $icon }}"></i> {{ $label }}
                @if ($count)
                    <span class="up-count">{{ $count }}</span>
                @endif
            </a>
        </li>
    @endforeach
    <li class="up-nav__sep"></li>
    <li class="is-danger">
        <a href="{{ route('_userDeleteAccount') }}" class="{{ request()->routeIs('_userDeleteAccount') ? 'is-active' : '' }}">
            <i class="bi bi-person-x"></i> Delete account
        </a>
    </li>
    <li>
        <a href="{{ route('_userLogout') }}"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </li>
</ul>
