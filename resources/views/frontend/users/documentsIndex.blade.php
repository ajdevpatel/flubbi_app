@extends("frontend.layoutIndex")
 

@section("bodyIndex")

    <!-- Banner Start -->
    <section class="banner">
        <div class="container ">
            <div class="row gy-4 gy-sm-0 align-items-center">
                <div class="col-12 col-sm-6">
                    <div class="banner__content">
                        <h1 class="banner__title display-4 wow fadeInLeft" data-wow-duration="0.8s">Document List</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb wow fadeInRight" data-wow-duration="0.8s">
                                <li class="breadcrumb-item"><a href="{{ route('_homeIndex') }}">Home</a></li>
                                <li class="breadcrumb-item">User</li>
                                <li class="breadcrumb-item active" aria-current="page">Document List</li>
                            </ol>
                        </nav>
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div class="banner__thumb text-end">
                        <img src="{{ asset('assets/images/about_banner.png') }}" alt="image">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Banner End -->

    <section class="reviews-details section">
        <div class="container ">
            <div class="row"> 
                <div class="col-12 col-xl-3 btn_sticky advantage-boxes">
                    @include("frontend.users.menuIndex")
                </div>
                <div class="col-12 col-xl-9 order-1 order-xl-0">
                    <div class="reviews-details__area">
                        <div class="reviews-details__part">
                            <h4 class="average-reviews__title">User Documents</h4>
                            <div class="row"> 
                                <div class="col-12 col-xl-4">                                       
                                    <div class="contact-info-part">
                                        <ul class="list-group p-0">
                                            <li class="list-group-item">
                                                @if(!empty($data->pancard))
                                                    <i class="bi bi-check2-square text-success"></i>
                                                @else
                                                    <i class="bi bi-x-square text-danger"></i>
                                                @endif 
                                                &nbsp;<span>PAN Card</span>
                                            </li>
                                            <li class="list-group-item">
                                                @if(!empty($data->aadharcard))
                                                    <i class="bi bi-check2-square text-success"></i>
                                                @else
                                                    <i class="bi bi-x-square text-danger"></i>
                                                @endif 
                                                &nbsp;<span>Aadhar Card</span>
                                            </li>	
                                            <li class="list-group-item">
                                                @if(!empty($data->profilephoto))
                                                    <i class="bi bi-check2-square text-success"></i>
                                                @else
                                                    <i class="bi bi-x-square text-danger"></i>
                                                @endif
                                                &nbsp;<span>Profile Photo</span>
                                            </li>				
                                            <li class="list-group-item">
                                                @if(!empty($data->lightbill))
                                                    <i class="bi bi-check2-square text-success"></i>
                                                @else
                                                    <i class="bi bi-x-square text-danger"></i>
                                                @endif 
                                                &nbsp;<span>Light bill</span>
                                            </li>
                                            <li class="list-group-item">
                                                @if(!empty($data->addressproof))
                                                    <i class="bi bi-check2-square text-success"></i>
                                                @else
                                                    <i class="bi bi-x-square text-danger"></i>
                                                @endif 
                                                &nbsp;<span>Address Proof</span>
                                            </li>
                                            <li class="list-group-item">
                                                @if(!empty($data->businessproof))
                                                    <i class="bi bi-check2-square text-success"></i>
                                                @else
                                                    <i class="bi bi-x-square text-danger"></i>
                                                @endif 
                                                &nbsp;<span>Business Proof</span>
                                            </li>					
                                            <li class="list-group-item">
                                                @if(!empty($data->cancelcheque))
                                                    <i class="bi bi-check2-square text-success"></i>
                                                @else
                                                    <i class="bi bi-x-square text-danger"></i>
                                                @endif 
                                                &nbsp;<span>Cancel Cheque</span>
                                            </li>					
                                            <li class="list-group-item">
                                                @if(!empty($data->bankstatement))
                                                    <i class="bi bi-check2-square text-success"></i>
                                                @else
                                                    <i class="bi bi-x-square text-danger"></i>
                                                @endif 
                                                &nbsp;<span>Bank Statement - Last 6 months</span>
                                            </li>					
                                            <li class="list-group-item">
                                                @if(!empty($data->itreturn))
                                                    <i class="bi bi-check2-square text-success"></i>
                                                @else
                                                    <i class="bi bi-x-square text-danger"></i>
                                                @endif 
                                                &nbsp;<span>IT-Return</span>
                                            </li>					
                                            <li class="list-group-item">
                                                @if(!empty($data->formsixteen))
                                                    <i class="bi bi-check2-square text-success"></i>
                                                @else
                                                    <i class="bi bi-x-square text-danger"></i>
                                                @endif 
                                                &nbsp;<span>Form 16</span>
                                            </li>					
                                            <li class="list-group-item">
                                                @if(!empty($data->salaryslip))
                                                    <i class="bi bi-check2-square text-success"></i>
                                                @else
                                                    <i class="bi bi-x-square text-danger"></i>
                                                @endif 
                                                &nbsp;<span>Salary Slip</span>
                                            </li>									
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-12 col-xl-8">
                                    <div class="accordion faq-page" id="accordionOne">
                                        @if(empty($data->pancard))
                                        <div class="accordion-item">
                                            <h5 class="accordion-header">
                                                <button class="accordion-button " data-bs-toggle="collapse" data-bs-target="#collapseOne3">
                                                    Pancard
                                                </button>
                                            </h5>
                                            <div id="collapseOne3" class="accordion-collapse collapse show" data-bs-parent="#accordionOne">
                                                <div class="accordion-body mb-0 pb-0">
                                                    <form action="{{ route('_userDocumentsIndex') }}" class="row m-0 p-0 documentsModule" method="POST" enctype="multipart/form-data">
                                                        <input type="hidden" name="type" value="pancard" required>
                                                        @csrf 
                                                        <div class="form-group col-12m-0 p-0 mb-2">
                                                            <input type="text" name="pancard_number" class="form-control alpha-numeric" placeholder="Pancard number" >
                                                        </div> 
                                                        <div class="form-group col-md-6 m-0 p-0">
                                                            <input type="file" name="doc" class="form-control" >
                                                        </div> 
                                                        <div class="form-group col-md-6 m-0 p-0">
                                                            <button type="submit" class="btn_theme btn_theme_active p-2 m-1">
                                                                Upload <i class="bi bi-upload"></i><span></span>
                                                            </button> 
                                                        </div> 
                                                    </form>
                                                </div>
                                            </div>
                                        </div>	
                                        @endif
                                        @if(empty($data->aadharcard))
                                        <div class="accordion-item">
                                            <h5 class="accordion-header">
                                                <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseOne2">
                                                    Aadhar Card
                                                </button>
                                            </h5>
                                            <div id="collapseOne2" class="accordion-collapse collapse " data-bs-parent="#accordionOne">
                                                <div class="accordion-body mb-0 pb-0">
                                                    <form action="{{ route('_userDocumentsIndex') }}" class="row m-0 p-0 documentsModule" method="POST" enctype="multipart/form-data">
                                                        <input type="hidden" name="type" value="aadharcard" required>
                                                        @csrf 
                                                        <div class="form-group col-12m-0 p-0 mb-2">
                                                            <input type="text" name="aadharcard_number" class="form-control numeric" placeholder="aadharcard number" >
                                                        </div> 
                                                        <div class="form-group col-md-6 m-0 p-0">
                                                            <input type="file" name="doc" class="form-control" >
                                                        </div> 
                                                        <div class="form-group col-md-6 m-0 p-0">
                                                            <button type="submit" class="btn_theme btn_theme_active p-2 m-1">
                                                                Upload <i class="bi bi-upload"></i><span></span>
                                                            </button> 
                                                        </div> 
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @if(empty($data->profilephoto))
                                        <div class="accordion-item">
                                            <h5 class="accordion-header">
                                                <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseOne1">
                                                    Profile Photo
                                                </button>
                                            </h5>
                                            <div id="collapseOne1" class="accordion-collapse collapse " data-bs-parent="#accordionOne">
                                                <div class="accordion-body mb-0 pb-0">
                                                    <form action="{{ route('_userDocumentsIndex') }}" class="row m-0 p-0 documentsModule" method="POST" enctype="multipart/form-data">
                                                        <input type="hidden" name="type" value="profilephoto" required>
                                                        @csrf 
                                                        <div class="form-group col-md-6 m-0 p-0">
                                                            <input type="file" class="form-control" name="doc" >
                                                        </div> 
                                                        <div class="form-group col-md-6 m-0 p-0">
                                                            <button type="submit" class="btn_theme btn_theme_active p-2 m-1">
                                                                Upload <i class="bi bi-upload"></i><span></span>
                                                            </button> 
                                                        </div> 
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @if(empty($data->cancelcheque))							
                                        <div class="accordion-item">
                                            <h5 class="accordion-header">
                                                <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseOne4">
                                                    Cancel Cheque
                                                </button>
                                            </h5>
                                            <div id="collapseOne4" class="accordion-collapse collapse " data-bs-parent="#accordionOne">
                                                <div class="accordion-body mb-0 pb-0">
                                                    <form action="{{ route('_userDocumentsIndex') }}" class="row m-0 p-0 documentsModule" method="POST" enctype="multipart/form-data">
                                                        <input type="hidden" name="type" value="cancelcheque" required>
                                                        @csrf 
                                                        <div class="form-group col-md-6 m-0 p-0">
                                                            <input type="file" name="doc" class="form-control" >
                                                        </div> 
                                                        <div class="form-group col-md-6 m-0 p-0">
                                                            <button type="submit" class="btn_theme btn_theme_active p-2 m-1">
                                                                Upload <i class="bi bi-upload"></i><span></span>
                                                            </button> 
                                                        </div> 
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @if(empty($data->lightbill))
                                        <div class="accordion-item">
                                            <h5 class="accordion-header">
                                                <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseOne5">
                                                    Light bill 
                                                </button>
                                            </h5>
                                            <div id="collapseOne5" class="accordion-collapse collapse  " data-bs-parent="#accordionOne">
                                                <div class="accordion-body mb-0 pb-0">
                                                    <form action="{{ route('_userDocumentsIndex') }}" class="row m-0 p-0 documentsModule" method="POST" enctype="multipart/form-data">
                                                        <input type="hidden" name="type" value="lightbill" required>
                                                        @csrf 
                                                        <div class="form-group col-md-6 m-0 p-0">
                                                            <input type="file" name="doc" class="form-control" >
                                                        </div> 
                                                        <div class="form-group col-md-6 m-0 p-0">
                                                            <button type="submit" class="btn_theme btn_theme_active p-2 m-1">
                                                                Upload <i class="bi bi-upload"></i><span></span>
                                                            </button> 
                                                        </div> 
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @if(empty($data->bankstatement))
                                        <div class="accordion-item">
                                            <h5 class="accordion-header">
                                                <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseOne6">
                                                    Bank Statement - Last 6 months
                                                </button>
                                            </h5>
                                            <div id="collapseOne6" class="accordion-collapse collapse  " data-bs-parent="#accordionOne">
                                                <div class="accordion-body mb-0 pb-0">
                                                    <form action="{{ route('_userDocumentsIndex') }}" class="row m-0 p-0 documentsModule" method="POST" enctype="multipart/form-data">
                                                        <input type="hidden" name="type" value="bankstatement" required>
                                                        @csrf 
                                                        <div class="form-group col-md-6 m-0 p-0">
                                                            <input type="file" name="doc" class="form-control" >
                                                        </div> 
                                                        <div class="form-group col-md-6 m-0 p-0">
                                                            <button type="submit" class="btn_theme btn_theme_active p-2 m-1">
                                                                Upload <i class="bi bi-upload"></i><span></span>
                                                            </button> 
                                                        </div> 
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @if(empty($data->formsixteen))
                                        <div class="accordion-item">
                                            <h5 class="accordion-header">
                                                <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseOne7">
                                                    Form 16
                                                </button>
                                            </h5>
                                            <div id="collapseOne7" class="accordion-collapse collapse  " data-bs-parent="#accordionOne">
                                                <div class="accordion-body mb-0 pb-0">
                                                    <form action="{{ route('_userDocumentsIndex') }}" class="row m-0 p-0 documentsModule" method="POST" enctype="multipart/form-data">
                                                        <input type="hidden" name="type" value="formsixteen" required>
                                                        @csrf 
                                                        <div class="form-group col-md-6 m-0 p-0">
                                                            <input type="file" name="doc" class="form-control" >
                                                        </div> 
                                                        <div class="form-group col-md-6 m-0 p-0">
                                                            <button type="submit" class="btn_theme btn_theme_active p-2 m-1">
                                                                Upload <i class="bi bi-upload"></i><span></span>
                                                            </button> 
                                                        </div> 
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @if(empty($data->salaryslip))
                                        <div class="accordion-item">
                                            <h5 class="accordion-header">
                                                <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseOne8">
                                                    Salary Slip
                                                </button>
                                            </h5>
                                            <div id="collapseOne8" class="accordion-collapse collapse  " data-bs-parent="#accordionOne">
                                                <div class="accordion-body mb-0 pb-0">
                                                    <form action="{{ route('_userDocumentsIndex') }}" class="row m-0 p-0 documentsModule" method="POST" enctype="multipart/form-data">
                                                        <input type="hidden" name="type" value="salaryslip" required>
                                                        @csrf 
                                                        <div class="form-group col-md-6 m-0 p-0">
                                                            <input type="file" name="doc" class="form-control" >
                                                        </div> 
                                                        <div class="form-group col-md-6 m-0 p-0">
                                                            <button type="submit" class="btn_theme btn_theme_active p-2 m-1">
                                                                Upload <i class="bi bi-upload"></i><span></span>
                                                            </button> 
                                                        </div> 
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @if(empty($data->businessproof))
                                        <div class="accordion-item">
                                            <h5 class="accordion-header">
                                                <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseOne9">
                                                    Business Proof
                                                </button>
                                            </h5>
                                            <div id="collapseOne9" class="accordion-collapse collapse  " data-bs-parent="#accordionOne">
                                                <div class="accordion-body mb-0 pb-0">
                                                    <form action="{{ route('_userDocumentsIndex') }}" class="row m-0 p-0 documentsModule" method="POST" enctype="multipart/form-data">
                                                        <input type="hidden" name="type" value="businessproof" required>
                                                        @csrf 
                                                        <div class="form-group col-md-6 m-0 p-0">
                                                            <input type="file" name="doc" class="form-control" >
                                                        </div> 
                                                        <div class="form-group col-md-6 m-0 p-0">
                                                            <button type="submit" class="btn_theme btn_theme_active p-2 m-1">
                                                                Upload <i class="bi bi-upload"></i><span></span>
                                                            </button> 
                                                        </div> 
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @if(empty($data->itreturn))
                                        <div class="accordion-item">
                                            <h5 class="accordion-header">
                                                <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseOne10">
                                                    IT-Return
                                                </button>
                                            </h5>
                                            <div id="collapseOne10" class="accordion-collapse collapse  " data-bs-parent="#accordionOne">
                                                <div class="accordion-body mb-0 pb-0">
                                                    <form action="{{ route('_userDocumentsIndex') }}" class="row m-0 p-0 documentsModule" method="POST" enctype="multipart/form-data">
                                                        <input type="hidden" name="type" value="itreturn" required>
                                                        @csrf 
                                                        <div class="form-group col-md-6 m-0 p-0">
                                                            <input type="file" name="doc" class="form-control" >
                                                        </div> 
                                                        <div class="form-group col-md-6 m-0 p-0">
                                                            <button type="submit" class="btn_theme btn_theme_active p-2 m-1">
                                                                Upload <i class="bi bi-upload"></i><span></span>
                                                            </button> 
                                                        </div> 
                                                    </form>
                                                </div>
                                            </div>
                                        </div> 
                                        @endif
                                        @if(empty($data->addressproof))
                                        <div class="accordion-item">
                                            <h5 class="accordion-header">
                                                <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#collapseOne11">
                                                    Address Proof 
                                                </button>
                                            </h5>
                                            <div id="collapseOne11" class="accordion-collapse collapse  " data-bs-parent="#accordionOne">
                                                <div class="accordion-body mb-0 pb-0">
                                                    <form action="{{ route('_userDocumentsIndex') }}" class="row m-0 p-0 documentsModule" method="POST" enctype="multipart/form-data">
                                                        <input type="hidden" name="type" value="addressproof" required>
                                                        @csrf 
                                                        <div class="form-group col-md-6 m-0 p-0">
                                                            <input type="file" name="doc" class="form-control" >
                                                        </div> 
                                                        <div class="form-group col-md-6 m-0 p-0">
                                                            <button type="submit" class="btn_theme btn_theme_active p-2 m-1">
                                                                Upload <i class="bi bi-upload"></i><span></span>
                                                            </button> 
                                                        </div> 
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </section> 

@endsection

@section("jsIndex")
    <script src="{{ asset('assets/app/userPanelIndex.js') }}"></script>
@endsection
