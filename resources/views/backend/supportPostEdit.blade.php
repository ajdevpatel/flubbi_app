@extends('backend.layoutIndex')



@section('bodyIndex')
    <div class="row">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center ">
            <div class="d-flex flex-column justify-content-center">
                <h4 class="mb-1 mt-3"> Support Request Edit </h4>
                <?php /* <p class="text-muted"></p> */ ?>
            </div>
            <div class="d-flex align-content-center flex-wrap gap-3">
                <a href="javascript:history.back()">
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        <span class="ti-xs ti ti-chevrons-left me-2"></span>
                        <span class="d-none d-sm-inline-block"> Back </span>
                    </button>
                </a>
            </div>
        </div>
    </div>


    <form action="{{ route('_supportPostEdit', ['key' => $data->ticket_no]) }}" class="add-new-user pt-0"
        id="_supportUpdateModule" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{ $data->p_id }}" />
        <input type="hidden" name="type" value="log_update" />
        <div class="row my-4">
            <div class="col-12 col-md-6">
                <div class="card mb-4">
                    <div class="card-body border border-success">
                        <div class="row">
                            <div class="mb-3">
                                <h5 class="text-danger mb-0 btn rounded-pill btn-outline-danger waves-effect">
                                    Ticket is currently -
                                    @if ($data->status == 'open')
                                        Open
                                    @elseif($data->status == 'processing')
                                        Processing
                                    @elseif($data->status == 'close/no response')
                                        Close/No
                                        Response
                                    @elseif($data->status == 'hold')
                                        Hold
                                    @elseif($data->status == 'reopen')
                                        Reopen
                                    @elseif($data->status == 'solve')
                                        solve
                                    @endif
                                </h5>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="name">Ticket Number</label>
                                <input type="text" class="form-control" readonly value="{{ $data->ticket_no }}" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="name">Name</label>
                                <input type="text" class="form-control" readonly value="{{ $data->name }}" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="name">Mobile</label>
                                <input type="text" class="form-control" readonly value="{{ $data->phone }}" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="name">E-mail</label>
                                <input type="text" class="form-control" readonly value="{{ $data->email }}" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="name">Created At </label>
                                <input type="text" class="form-control" readonly value="{{ $data->created_at }}" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="name">Updated At </label>
                                <input type="text" class="form-control" readonly value="{{ $data->updated_at }}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="card mb-4">
                    <div class="card-body border border-Request Reason">
                        <div class="row">
                            <div class="mb-3">
                                <label class="form-label" for="name">Request Reason</label>
                                <textarea name="message" class="form-control" disabled rows="2" readonly placeholder="Description">{{ $data->reason_label }}</textarea>
                            </div>
                            <div class="mb-3">
                                <div class="alert alert-warning border border-warning">
                                    <strong>Customer : </strong> {{ $data->message }}
                                </div>
                                {{-- <hr class="">
                                @foreach ($logs as $k => $v)
                                    <div class="alert alert-success border border-success">
                                        <strong>Admin : <small>{{ $v->created_at }} </small> || status : <small>
                                                @if ($v->status == 'open')
                                                    Open
                                                @elseif($v->status == 'processing')
                                                    Processing
                                                @elseif($v->status == 'close/no response')
                                                    Close/No Response
                                                @elseif($v->status == 'hold')
                                                    Hold
                                                @elseif($v->status == 'reopen')
                                                    Re-Open
                                                @elseif($v->status == 'solve')
                                                    Solve
                                                @endif
                                            </small> </strong>
                                        <br> <b> Remarks : </b> {{ $v->message }}
                                    </div>
                                @endforeach --}}
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="type">Status</label>
                                <select name="status" class="form-select" data-allow-clear="true">
                                    <option @if ($data->status == 'open') selected @endif value="open">Open</option>
                                    <option @if ($data->status == 'processing') selected @endif value="processing">Processing
                                    </option>
                                    <option @if ($data->status == 'close/no response') selected @endif value="close/no response">
                                        Close/No
                                        Response</option>
                                    <option @if ($data->status == 'hold') selected @endif value="hold">Hold</option>
                                    <option @if ($data->status == 'reopen') selected @endif value="reopen">Re-open
                                    </option>
                                    <option @if ($data->status == 'solve') selected @endif value="solve">Solve</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="name">Remarks</label>
                                <textarea name="remarks" class="form-control" rows="6" placeholder="remarks" required>{{ $data->message }}</textarea>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-label-linkedin me-sm-3 ">
                                    <span class="ti-xs ti ti-square-plus me-2"></span> Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
    <hr class="my-5" />
@endsection


@section('jsIndex')
    <script src="{{ asset('appassets/layout/supportPostIndex.js') }}"></script>
@endsection
