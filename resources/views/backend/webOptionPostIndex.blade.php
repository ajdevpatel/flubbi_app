@extends("backend.layoutIndex")



@section("bodyIndex")

<div class="row">
	<div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
		<div class="d-flex flex-column justify-content-center">
			<h4 class="mb-1 mt-3"> Option List </h4>
			<?php /* <p class="text-muted"></p> */ ?>
		</div>
		<div class="d-flex align-content-center flex-wrap gap-3">
		</div>
	</div>
</div>

<div class="card mb-4">
	<div class="card-body border border-success"> 
		<div class="row">
			<div class="mb-3">
				<h5 class="text-danger mb-0 btn rounded-pill btn-outline-danger waves-effect"> Google & Facebook Key </h5>
			</div>
			<table class="table">
				@if ([] != $option_list)
					@foreach ($option_list as $k => $v)
						<form action="{{ route('_webOptionPostIndex') }}" method="post">
							@csrf
							<tr class="">
								<th>{{ $v->op_label }}<th>
								<td>
									<input type="hidden" name="keys" value="{{ $v->op_key }}" />
									<input type="text" name="{{ $v->op_key }}" class="form-control  border-1 border-success" value="{{ $v->op_value }}" />
								<td>
								<td>
									<button type="submit" class="btn btn-label-linkedin me-sm-3 waves-effect">
										<span class="ti-xs ti ti-square-plus me-2"></span> Update
									</button>
								</td>
							</tr> 
						</form>  
					@endforeach
				@endif
			</table>
		</div>
	</div>
</div>
<hr class="my-5" />
@endsection



@section("styleIndex")
	<link rel="stylesheet" href="{{ asset('appassets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
@endsection

@section("jsIndex")
	<script src="{{ asset('appassets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
	<script src="{{ asset('appassets/layout/customersIndex.js') }}"></script>
@endsection
