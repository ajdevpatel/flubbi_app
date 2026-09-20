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
        <div class="up-card">
            <div class="up-empty">
                <i class="bi bi-cloud-arrow-up"></i>
                @if ($has_applications)
                    Documents are collected only for <strong>Hire Agent</strong> applications once the platform fee is paid.
                    Self Login applications continue directly on the lender's website.
                @else
                    Start an application first, then upload your documents here.
                @endif
            </div>
        </div>
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

        <form action="{{ route('_userDocuments', $loan->application_no) }}" method="POST" enctype="multipart/form-data" id="fl-doc-form" novalidate>
            @csrf
            <div class="up-card">
                @foreach ($docs as $group => $items)
                    <div class="up-group">{{ ['kyc' => 'KYC', 'salaried' => 'Income proof', 'self_employed' => 'Business & income proof', 'other' => 'Other documents'][$group] }}</div>

                    @if ('kyc' === $group)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="fl-field">
                                    <label class="fl-field__label" for="aadhar_number">Aadhaar number</label>
                                    <input class="fl-field__control numeric" type="text" name="aadhar_number" id="aadhar_number" inputmode="numeric" maxlength="12" placeholder="12 digits" value="{{ $loan->aadhar_number }}">
                                    <span class="fl-field__hint">Needed when uploading Aadhaar front / back</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="fl-field">
                                    <label class="fl-field__label" for="pan_number">PAN number</label>
                                    <input class="fl-field__control" type="text" name="pan_number" id="pan_number" maxlength="10" placeholder="ABCDE1234F" value="{{ $loan->pan_number }}" style="text-transform:uppercase;">
                                    <span class="fl-field__hint">Needed when uploading the PAN card</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="up-docs">
                        @foreach ($items as $d)
                            <div class="up-doc">
                                @if ($d['url'])
                                    <a class="up-doc__thumb" href="{{ $d['url'] }}" target="_blank" rel="noopener" title="View {{ $d['label'] }}">
                                        @if (str_ends_with(strtolower($d['file']), '.pdf'))
                                            <i class="bi bi-file-earmark-pdf"></i>
                                        @else
                                            <img src="{{ $d['url'] }}" alt="{{ $d['label'] }}">
                                        @endif
                                    </a>
                                @else
                                    <div class="up-doc__thumb"><i class="bi bi-file-earmark-arrow-up"></i></div>
                                @endif
                                <div>
                                    <strong>{{ $d['label'] }}</strong>
                                    <span class="up-badge up-badge--{{ $d['status'] }}">{{ ['uploaded' => 'Uploaded', 'rejected' => 'Re-upload needed', 'missing' => 'Not uploaded'][$d['status']] }}</span>
                                </div>
                                <label class="fl-btn fl-btn--ghost up-doc__btn">
                                    {{ 'missing' === $d['status'] ? 'Upload' : 'Replace' }}
                                    <input type="file" data-doc="{{ $d['key'] }}" accept=".jpg,.jpeg,.png,.pdf">
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

@section('panelJs')
    <script>
        (function () {
            var $form = $("#fl-doc-form");
            if (!$form.length) {
                return;
            }

            $form.on("change", "input[type=file][data-doc]", function () {
                var input = this;
                if (!input.files || !input.files.length) {
                    return;
                }

                var $label = $(input).closest("label");
                var text = $label.text().trim();
                var data = new FormData();
                data.append("_token", $form.find("[name=_token]").val());
                data.append("doc_type", $(input).data("doc"));
                data.append("file", input.files[0]);
                data.append("aadhar_number", $("#aadhar_number").val() || "");
                data.append("pan_number", $("#pan_number").val() || "");

                $label.addClass("is-busy").html('<i class="fas fa-spinner fa-spin"></i> Uploading');

                $.ajax({
                    type: "POST",
                    url: $form.attr("action"),
                    data: data,
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    success: function (res) {
                        if (res.message && typeof Notify === "function") {
                            Notify(res.message, "success");
                        }
                        window.location.href = res.step || window.location.href;
                    },
                    error: function (xhr) {
                        if (typeof ajaxResponseFailure === "function") {
                            ajaxResponseFailure(xhr);
                        }
                        $label.removeClass("is-busy").html(text + '<input type="file" data-doc="' + $(input).data("doc") + '" accept=".jpg,.jpeg,.png,.pdf">');
                    }
                });
            });
        })();
    </script>
@endsection
