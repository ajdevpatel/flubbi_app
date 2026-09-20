@extends('frontend.user.layoutIndex')

@section('panelTitle', 'Documents')
@section('panelCrumb', 'Documents')

@section('panelBody')
    <div class="up-head">
        <div>
            <h2>Documents</h2>
            <p>JPG, PNG or PDF up to 10 MB. Clear, uncropped photos get verified faster.</p>
        </div>
    </div>

    @if (!$loan)
        <div class="up-card"><div class="up-empty"><i class="bi bi-cloud-arrow-up"></i>Start an application first, then upload your documents here.</div></div>
    @else
        @if ($applications->count() > 1)
            <div class="up-card" style="padding:12px 16px;">
                <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
                    <span style="font-size:13px;color:var(--fl-muted);font-weight:600;">Application:</span>
                    @foreach ($applications as $a)
                        <a class="fl-btn fl-btn--sm {{ $a->application_no === $loan->application_no ? '' : 'fl-btn--ghost' }}" href="{{ route('_userDocuments', $a->application_no) }}">
                            {{ $a->loan_type }} &middot; {{ $a->application_no }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <form action="{{ route('_userDocuments', $loan->application_no) }}" method="POST" enctype="multipart/form-data" class="js-fl-step" novalidate>
            @csrf
            <div class="up-card">
                @foreach ($docs as $group => $items)
                    <div class="up-group">{{ ['kyc' => 'KYC', 'salaried' => 'Income proof', 'self_employed' => 'Business & income proof', 'other' => 'Other documents'][$group] }}</div>
                    <div class="up-docs">
                        @foreach ($items as $d)
                            <div class="up-doc">
                                <div class="up-doc__thumb">
                                    @if ($d['url'] && !str_ends_with(strtolower($d['file']), '.pdf'))
                                        <img src="{{ $d['url'] }}" alt="{{ $d['label'] }}">
                                    @elseif ($d['url'])
                                        <i class="bi bi-file-earmark-pdf"></i>
                                    @else
                                        <i class="bi bi-file-earmark-arrow-up"></i>
                                    @endif
                                </div>
                                <div>
                                    <strong>{{ $d['label'] }}</strong>
                                    <span class="up-badge up-badge--{{ $d['status'] }}">{{ ['uploaded' => 'Uploaded', 'rejected' => 'Re-upload needed', 'missing' => 'Not uploaded'][$d['status']] }}</span>
                                </div>
                                <label class="fl-btn fl-btn--ghost up-doc__btn">
                                    {{ 'missing' === $d['status'] ? 'Upload' : 'Replace' }}
                                    <input type="file" name="{{ $d['key'] }}" accept=".jpg,.jpeg,.png,.pdf">
                                </label>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>

            <div class="up-note"><i class="bi bi-shield-check"></i><div>Your documents are encrypted and shared only with the lending partner handling your application.</div></div>
        </form>
    @endif
@endsection
