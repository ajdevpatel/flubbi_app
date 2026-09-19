@extends('backend.layoutIndex')


@section('bodyIndex')
    <h4 class="py-3 mb-4">Today's Statistics - {{ date('d M, Y') }}</h4>

    <div class="row g-6 mb-4">
        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-warning h-100 cursor-pointer"
                onclick="window.location.href='{{ route('_personalLoanIndex', ['type' => 'personal', 'login_type' => 'self']) }}'">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="icon-base ti ti-user icon-28px"></i></span>
                        </div>
                        <h4 class="mb-0">{{ $plApplicationCountSelf }}</h4>
                    </div>
                    <p class="mb-1">PL Applications - Self</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-warning h-100 cursor-pointer"
                onclick="window.location.href='{{ route('_personalLoanIndex', ['type' => 'personal', 'login_type' => 'consultant']) }}'">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-warning">
                                <i class="icon-base ti ti-user icon-28px"></i></span>
                        </div>
                        <h4 class="mb-0">{{ $plApplicationCountHireAgent }}</h4>
                    </div>
                    <p class="mb-1">PL Applications - Hire Agent</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-danger h-100 cursor-pointer"
                onclick="window.location.href='{{ route('_businessLoanIndex', ['type' => 'business', 'login_type' => 'self']) }}'">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-danger">
                                <i class="icon-base ti ti-building-bank icon-28px"></i></span>
                        </div>
                        <h4 class="mb-0">{{ $blApplicationCountSelf }}</h4>
                    </div>
                    <p class="mb-1">BL Applications - Self</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-danger h-100 cursor-pointer"
                onclick="window.location.href='{{ route('_businessLoanIndex', ['type' => 'business', 'login_type' => 'consultant']) }}'">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-danger">
                                <i class="icon-base ti ti-building-bank icon-28px"></i></span>
                        </div>
                        <h4 class="mb-0">{{ $blApplicationCountHireAgent }}</h4>
                    </div>
                    <p class="mb-1">BL Applications - Hire Agent</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6 mt-4">
            <div class="card card-border-shadow-info h-100 cursor-pointer"
                onclick="window.location.href='{{ route('_creditCardIndex') }}'">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="icon-base ti ti-credit-card icon-28px"></i></span>
                        </div>
                        <h4 class="mb-0">{{ $creditCardApplicationCount }}</h4>
                    </div>
                    <p class="mb-1">Credit Card Application</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6 mt-4">
            <div class="card card-border-shadow-info h-100 cursor-pointer"
                onclick="window.location.href='{{ route('_customersIndex', ['type' => 'all']) }}'">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="icon-base ti ti-users icon-28px"></i></span>
                        </div>
                        <h4 class="mb-0">{{ $allCustomersCount }}</h4>
                    </div>
                    <p class="mb-1">All Customers</p>
                </div>
            </div>
        </div>

    </div>

    <div class="row g-6 mb-4">
        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-primary h-100 cursor-pointer"
                onclick="window.location.href='{{ route('_transactionsIndex') }}'">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="icon-base ti ti-currency-rupee icon-28px"></i>
                            </span>
                        </div>
                        <h4 class="mb-0">{{ $transactionCount }}</h4>
                    </div>
                    <p class="mb-1">Transactions</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-primary h-100 cursor-pointer"
                onclick="window.location.href='{{ route('_otpLogsIndex') }}'">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-primary">
                                <i class="icon-base ti ti-device-mobile-message icon-28px"></i>
                            </span>
                        </div>
                        <h4 class="mb-0">{{ $otpCount }}</h4>
                    </div>
                    <p class="mb-1">Today's OTP</p>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card card-border-shadow-info h-100 cursor-pointer"
                onclick="window.location.href='{{ route('_supportPostIndex') }}'">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <div class="avatar me-4">
                            <span class="avatar-initial rounded bg-label-info">
                                <i class="icon-base ti ti-user-question icon-28px"></i></span>
                        </div>
                        <h4 class="mb-0">{{ $supportRequestCount ?? 0 }}</h4>
                    </div>
                    <p class="mb-1">Support Request</p>
                </div>
            </div>
        </div>
    </div>
@endsection
