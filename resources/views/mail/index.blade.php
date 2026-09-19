@extends("mail.layoutIndex")

@section("bodyIndex")
    @php
        echo $in_data["body_text"];
    @endphp
@endsection

