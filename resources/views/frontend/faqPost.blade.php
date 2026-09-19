@extends('frontend.layoutIndex')


@section('bodyIndex')
    <div class="breadcumb-area d-flex ">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 text-center">
                    <div class="breadcumb-content">
                        <div class="breadcumb-title style_two">
                            <h4>Frequently Asked Questions</h4>
                        </div>
                        <ul>
                            <li><a href="{{ route('_homeIndex') }}"><i class="bi bi-house-door-fill"></i> Home </a></li>
                            <li class="rotates"><i class="bi bi-slash-lg"></i>Frequently Asked Questions</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="portfolio_details">
        <div class="container">
            <div class="port_main">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="port_details_content">
                            <h2>Frequently Asked Questions</h2>

                            <!-- Start Accordion -->
                            <div class="tab_container">
                                <div id="tab1" class="tab_content">
                                    <ul class="accordion">

                                        @if (!empty(config('web.faq.faq_page')))
                                            @foreach (config('web.faq.faq_page') as $k => $v)
                                                <li class="{{ $k == 0 ? 'active' : '' }}">
                                                    <a>
                                                        <span>{{ $v['question'] }}</span>
                                                        <i class="bi bi-chevron-right"></i>
                                                    </a>
                                                    <p @if ($k != 0) style="display:none;" @endif>
                                                        {!! $v['answer'] !!}
                                                    </p>
                                                </li>
                                            @endforeach
                                        @endif

                                    </ul>
                                </div>
                            </div>
                            <!-- End Accordion -->



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
