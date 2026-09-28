@extends('backend.layoutIndex')


@section('bodyIndex')
    <div class="row">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
            <div class="d-flex flex-column justify-content-center">
                <h4 class="mb-1 mt-3">Remarketing Cycle</h4>
                <p class="text-muted mb-0">Each card is one cron bucket. The number is how many customers will get a
                    message when that bucket runs today, exactly as the cron counts them. Times are IST.</p>
            </div>
        </div>
    </div>

    @foreach ($channels as $channel)
        @php
            $color = 'whatsapp' === $channel['key'] ? 'success' : 'info';
            $icon = 'whatsapp' === $channel['key'] ? 'ti-brand-whatsapp' : 'ti-message-circle';
            $status = $channel['status'];
        @endphp
        <div class="card mb-4">
            <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
                <h5 class="card-title mb-0">
                    <span class="badge bg-label-{{ $color }}"><i class="ti {{ $icon }} me-1"></i>{{ $channel['label'] }}</span>
                    Remarketing
                </h5>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    @if ($status['live'])
                        <span class="badge bg-success">Cron ON</span>
                    @else
                        <span class="badge bg-danger">Cron OFF</span>
                        <small class="text-muted">{{ $status['reason'] }}</small>
                    @endif
                    @if ($status['test_numbers'] > 0)
                        <span class="badge bg-label-warning">+{{ $status['test_numbers'] }} test number(s) on every run</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($channel['buckets'] as $item)
                        <div class="col-xl-3 col-lg-4 col-sm-6 mb-3">
                            <div class="card card-border-shadow-{{ $color }} h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="avatar me-3">
                                            <span class="avatar-initial rounded bg-label-{{ $color }}"><i
                                                    class="icon-base ti ti-users icon-28px"></i></span>
                                        </div>
                                        <div>
                                            <h4 class="mb-0">{{ $item['customers'] }}</h4>
                                            <small class="text-muted">customers</small>
                                        </div>
                                    </div>
                                    <p class="mb-0">
                                        <span class="text-heading fw-medium me-2">Day <span
                                                class="badge bg-label-{{ $color }}">{{ $item['day'] }}</span></span>
                                        <span class="text-muted">applied {{ $item['udate'] }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
    <hr class="my-5" />
@endsection
