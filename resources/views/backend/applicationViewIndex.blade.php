@extends('backend.layoutIndex')



@section('bodyIndex')
    <div class="row">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center ">
            <div class="d-flex flex-column justify-content-center">
                <h4 class="mb-1 mt-3"> Application Details </h4>
                <div class="badge bg-{{ $data->status_class }} border border-danger m-0 display-6" role="alert"><b> Status :
                    </b> {{ $data->status_label }}</div>
                <?php /* <p class="text-muted"></p> */ ?>
            </div>
            <div class="d-flex align-content-center flex-wrap gap-3">
                <button type="submit" class="btn btn-secondary waves-effect waves-light" data-bs-target="#addNewPopupModal"
                    data-bs-toggle="offcanvas" tabindex="0">
                    <span class="ti-xs ti ti-square-plus me-2"></span>
                    <span class="d-none d-sm-inline-block"> Add Status </span>
                </button>
                <a href="javascript:history.back()">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        <span class="ti-xs ti ti-chevrons-left me-2"></span>
                        <span class="d-none d-sm-inline-block"> Back </span>
                    </button>
                </a>
            </div>
        </div>
    </div>

    <div class="row my-4">

        <div class="col-12 col-md-4">
            <div class="card border border-primary">
                <h5 class="card-header">User Info</h5>
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <tbody class="table-border-bottom-0">
                            <tr>
                                <td width="30%">Rec. Date</td>
                                <td width="5%">:</td>
                                <td>{{ $data->rec_date }}</td>
                            </tr>
                            <tr>
                                <td width="30%">Name</td>
                                <td width="5%">:</td>
                                <td>{{ $data->u_name }}</td>
                            </tr>
                            <tr>
                                <td width="20%">Mobile</td>
                                <td width="5%">:</td>
                                <td>{{ $data->u_phone }}</td>
                            </tr>
                            <tr>
                                <td width="20%">Email</td>
                                <td width="5%">:</td>
                                <td>{{ $data->u_email }}</td>
                            </tr>
                            <tr>
                                <td width="20%">City</td>
                                <td width="5%">:</td>
                                <td>{{ $data->u_city }}</td>
                            </tr>
                            <tr>
                                <td width="20%">State</td>
                                <td width="5%">:</td>
                                <td>{{ $data->u_state }}</td>
                            </tr>
                            <tr>
                                <td width="30%">Created Date</td>
                                <td width="5%">:</td>
                                <td>{{ $data->u_created_at }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border border-primary">
                <h5 class="card-header">Loan Info</h5>
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <tbody class="table-border-bottom-0">
                            <tr>
                                <td width="30%">Rec. Date</td>
                                <td width="5%">:</td>
                                <td>{{ $data->rec_date }}</td>
                            </tr>
                            <tr>
                                <td width="20%">Type</td>
                                <td width="5%">:</td>
                                <td>{{ $data->loan_types }}</td>
                            </tr>
                            <tr>
                                <td width="20%">Amount</td>
                                <td width="5%">:</td>
                                <td>{{ $data->loan_amount }}</td>
                            </tr>
                            <tr>
                                <td width="20%">Tenure</td>
                                <td width="5%">:</td>
                                <td>{{ $data->loantenure }}</td>
                            </tr>
                            <tr>
                                <td width="20%">Purpose</td>
                                <td width="5%">:</td>
                                <td>{{ $data->loan_purposes }}</td>
                            </tr>
                            <tr>
                                <td width="30%">Created Date</td>
                                <td width="5%">:</td>
                                <td>{{ $data->created_at }}</td>
                            </tr>
                            <tr>
                                <td width="30%">Updated Date</td>
                                <td width="5%">:</td>
                                <td>{{ $data->updated_at }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border border-primary">
                <h5 class="card-header"> Cibil Score & Income </h5>
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <tbody class="table-border-bottom-0">
                            <tr>
                                <td width="30%">User Type</td>
                                <td width="5%">:</td>
                                <td>{{ $data->user_type_label }}</td>
                            </tr>
                            <tr>
                                <td width="30%">Cibil Score</td>
                                <td width="5%">:</td>
                                <td>{{ $data->cibil_scores }}</td>
                            </tr>
                            <tr>
                                <td width="20%">Income</td>
                                <td width="5%">:</td>
                                <td>{{ $data->income }}</td>
                            </tr>
                            <tr>
                                <td width="20%">Current EMI</td>
                                <td width="5%">:</td>
                                <td>{{ $data->emi_paying }}</td>
                            </tr>
                            <tr>
                                <td width="20%">EMI Bounce</td>
                                <td width="5%">:</td>
                                <td>{{ $data->emi_bounce }}</td>
                            </tr>

                            <tr>
                                <td width="20%">Credit Card Usage</td>
                                <td width="5%">:</td>
                                <td>{{ $data->credit_card_usage }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-5 mt-4">
            <div class="card border border-primary">
                <h5 class="card-header"> Documents </h5>
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <tbody class="table-border-bottom-0">
                            @if ($data->loan_type_id == 1)
                                <tr>
                                    <td width="30%">Aadhar Number</td>
                                    <td width="5%">:</td>
                                    <td>{{ $data->aadhar_number }}</td>
                                </tr>
                                <tr>
                                    <td width="30%">Aadhar Front</td>
                                    <td width="5%">:</td>
                                    <td>
                                        @if (!empty($data->aadhar_front))
                                            <a href="{{ asset('uploads/' . $data->aadhar_front) }}" target="_blank">
                                                <img src="{{ asset('uploads/' . $data->aadhar_front) }}" alt="Aadhar Front"
                                                    width="100">
                                            </a>
                                            <a href="javascript:void(0);"
                                                onclick="removeDocument('aadhar_front', '{{ $data->app_id }}')"
                                                class="text-danger ms-3" title="Remove Document"><i
                                                    class="ti ti-trash ti-sm"></i></a>
                                        @else
                                            Not Uploaded
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">Aadhar Back</td>
                                    <td width="5%">:</td>
                                    <td>
                                        @if (!empty($data->aadhar_back))
                                            <a href="{{ asset('uploads/' . $data->aadhar_back) }}" target="_blank">
                                                <img src="{{ asset('uploads/' . $data->aadhar_back) }}" alt="Aadhar Back"
                                                    width="100">
                                            </a>
                                            <a href="javascript:void(0);"
                                                onclick="removeDocument('aadhar_back', '{{ $data->app_id }}')"
                                                class="text-danger ms-3" title="Remove Document"><i
                                                    class="ti ti-trash ti-sm"></i></a>
                                        @else
                                            Not Uploaded
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">Pan Number</td>
                                    <td width="5%">:</td>
                                    <td>{{ $data->pan_number }}</td>
                                </tr>
                                <tr>
                                    <td width="30%">Pan Front</td>
                                    <td width="5%">:</td>
                                    <td>
                                        @if (!empty($data->pan_front))
                                            <a href="{{ asset('uploads/' . $data->pan_front) }}" target="_blank">
                                                <img src="{{ asset('uploads/' . $data->pan_front) }}" alt="Pan Front"
                                                    width="100">
                                            </a>
                                            <a href="javascript:void(0);"
                                                onclick="removeDocument('pan_front', '{{ $data->app_id }}')"
                                                class="text-danger ms-3" title="Remove Document"><i
                                                    class="ti ti-trash ti-sm"></i></a>
                                        @else
                                            Not Uploaded
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">Selfie</td>
                                    <td width="5%">:</td>
                                    <td>
                                        @if (!empty($data->selfie))
                                            <a href="{{ asset('uploads/' . $data->selfie) }}" target="_blank">
                                                <img src="{{ asset('uploads/' . $data->selfie) }}" alt="Selfie"
                                                    width="100">
                                            </a>
                                            <a href="javascript:void(0);"
                                                onclick="removeDocument('selfie', '{{ $data->app_id }}')"
                                                class="text-danger ms-3" title="Remove Document"><i
                                                    class="ti ti-trash ti-sm"></i></a>
                                        @else
                                            Not Uploaded
                                        @endif
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td width="30%">Business Type</td>
                                    <td width="5%">:</td>
                                    <td>{{ $data->business_type }}</td>
                                </tr>
                                <tr>
                                    <td width="30%">Business Age</td>
                                    <td width="5%">:</td>
                                    <td>{{ $data->business_age }}</td>
                                </tr>
                                <tr>
                                    <td width="30%">Business Identity Proof</td>
                                    <td width="5%">:</td>
                                    <td>
                                        @if (!empty($data->business_identity_proof))
                                            <a href="{{ asset('uploads/' . $data->business_identity_proof) }}"
                                                target="_blank">
                                                <img src="{{ asset('uploads/' . $data->business_identity_proof) }}"
                                                    alt="Business Identity Proof" width="100">
                                            </a>
                                            <a href="javascript:void(0);"
                                                onclick="removeDocument('business_identity_proof', '{{ $data->app_id }}')"
                                                class="text-danger ms-3" title="Remove Document"><i
                                                    class="ti ti-trash ti-sm"></i></a>
                                        @else
                                            Not Uploaded
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td width="30%">Bank Account</td>
                                    <td width="5%">:</td>
                                    <td>{{ $data->bank_account }}</td>
                                </tr>
                                <tr>
                                    <td width="30%">Annual Business Turnover</td>
                                    <td width="5%">:</td>
                                    <td>{{ $data->annual_business_turnover }}</td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-7 mt-4">
            <div class="card border border-primary">
                <h5 class="card-header"> Remarks History </h5>
                <div class="card-datatable text-nowrap">
                    <table class="datatables-ajax table ajax_table ">
                        <thead>
                            <tr>
                                <th>Index</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <body>
                        @if ([] != $application_history)
                            @php
                                $x = 1;
                            @endphp
                            @foreach ($application_history as $k => $v)
                                <tr>
                                    <td>{{ $x }}</td>
                                    <td>{{ $v->created_at }}</td>
                                    <td>{{ $v->label }}</td>
                                    <td><textarea readonly>{{ $v->remarks }}</textarea></td>
                                </tr>
                                @php
                                    $x++;
                                @endphp
                            @endforeach
                        @endif
                        </body>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <hr class="my-5" />
@endsection


@section('addNewPopupModalIndex')
    <form action="{{ route('_applicationAddStatus', ['key' => $data->uuid]) }}" class="add-new-user pt-0 row" id="_addStatusModule" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" value="{{ $data->app_id }}" name="id">
        <input type="hidden" value="{{ $data->user_id }}" name="u_id">

        <div class="md-3 mb-2">
            <label class="form-label" for="type">Status </label>
            <select name="status" class="form-select" data-allow-clear="true">
                @if ([] != $loan_status)
                    @foreach ($loan_status as $k => $v)
                        @if ($v->id == $data->status)
                            <option value="{{ $v->id }}" selected>{{ ucfirst($v->label); }}</option>                        
                        @else
                            <option value="{{ $v->id }}" >{{ ucfirst($v->label); }}</option>
                        @endif
                    @endforeach
                @endif
            </select>
        </div>

        <div class="md-3 mb-2">
            <label class="form-label" for="type">Remarks Message </label>
            <select name="pre_msg" class="form-select" id="pre_msg" >
                <option data-msg="sss" value="aaaa" >No</option>
                <option data-msg="xxx" value="cccc">Yes</option>
                <option data-msg="rrrr" value="cccddd">dddd</option>
            </select>
        </div>

        <div class="mb-3 mb-2">
            <label class="form-label" for="remarks">Remarks </label>
            <textarea class="form-control " name="remarks" id="remarks" placeholder="Remarks message" rows="5"></textarea>
        </div>
        <div class="divider divider-dashed">
            <div class="divider-text"></div>
        </div>
        <div class="md-3 mb-2 col-12">
            <button type="submit" class="btn btn-label-linkedin me-sm-3 ">
                <span class="ti-xs ti ti-square-plus me-2"></span>Submit
            </button>
            <button type="reset" class="btn btn-label-pinterest me-sm-3" data-bs-dismiss="offcanvas">
                <span class="ti-xs ti ti-square-x me-2"></span>Close
            </button>
        </div>
    </form>
@endsection


@section('styleIndex')
    <link rel="stylesheet" href="{{ asset('appassets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
@endsection

@section('jsIndex')
    <script src="{{ asset('appassets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('appassets/layout/applicationsIndex.js') }}"></script>
    <script>

        $(document).on("change", "#pre_msg", function () {
            $("textarea[name='remarks']").val(
                $(this).find("option:selected").data("msg")
            );
        });
        
        $("#_addStatusModule").validate({
            rules: {
                status: {
                    required: true,
                }
            },
            messages: {},
            submitHandler: function (form) {
                return true;
            },
        });

        $(document).on("submit", "#_addStatusModule", function (e) {
            e.preventDefault();
            if (
                $("#_addStatusModule").valid() === true &&
                $(this).attr("action")
            ) {
                loadLoader("on");
                $.ajax({
                    type: "POST",
                    url: $(this).attr("action"),
                    data: new FormData(this),
                    dataType: "json",
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (xhr) {
                        if (xhr.message) {
                            Notify(xhr.message, "success");
                        }
                        location.reload();
                    },
                    error: function (xhr) {
                        ajaxResponseFailure(xhr);
                    },
                });
            }
        });

        
        function removeDocument(field, id) {
            if (confirm('Are you sure you want to remove this document?')) {
                $.ajax({
                    url: '{{ route('_removeApplicationDocument') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        document_field: field,
                        id: id
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function(xhr) {
                        alert(xhr.responseJSON?.message || 'Something went wrong. Please try again.');
                    }
                });
            }
        }
    </script>
@endsection
