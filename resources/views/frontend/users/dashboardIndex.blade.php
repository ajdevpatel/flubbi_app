@extends("frontend.layoutIndex")
 

@section("bodyIndex")

    <!-- Banner Start -->
    <section class="banner">
        <div class="container ">
            <div class="row gy-4 gy-sm-0 align-items-center">
                <div class="col-12 col-sm-6">
                    <div class="banner__content">
                        <h1 class="banner__title display-4 wow fadeInLeft" data-wow-duration="0.8s">Dashboard</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb wow fadeInRight" data-wow-duration="0.8s">
                                <li class="breadcrumb-item"><a href="{{ route('_homeIndex') }}">Home</a></li>
                                <li class="breadcrumb-item">User</li>
                                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
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
                            <div class="section__content">
                                <h2 class="section__content-title">The Benefits of Consolidating Your Student Loans</h2>
                                <p class="section__content-text">At FINVIEW, we provide comprehensive and unbiased loan reviews to help you navigate the complex world of financial lending. Our team of experts meticulously researches and analyzes various loan options to provide you with reliable and up-to-date information.</p>
                                <p class="section__content-text">When it comes to Loan, we've conducted a thorough review to provide you with all the essential details. We delve into the details of each loan, examining interest rates, repayment terms, eligibility criteria, and any associated fees.</p>
                            </div>
                            <div class="repayment section__content wow fadeInUp" data-wow-duration="0.8s">
                                <h3 class="section__content-title">Repayment Terms</h3>
                                <div class="section__content-inner-list">
                                    <ol class="number">
                                        <li>Loan Duration:
                                            <ol class="bullet">
                                                <li>Specify the length of time borrowers have to repay the loan. For example, it could be expressed in months or years.</li>
                                            </ol>
                                        </li>
                                        <li> Payment Frequency:
                                            <ol class="bullet">
                                                <li>Indicate how often borrowers are required to make payments. Common options include monthly, bi-monthly, bi-weekly, or weekly payments.</li>
                                            </ol>
                                        </li>
                                        <li> Amortization Type:
                                            <ol class="bullet">
                                                <li>Describe whether the loan follows an amortizing or interest-only repayment structure. In an amortizing loan, each payment includes both principal and interest, while an interest-only loan requires only interest payments during a specific period.</li>
                                            </ol>
                                        </li>
                                        <li>Payment Calculation:
                                            <ol class="bullet">
                                                <li>IExplain how the loan payment is calculated. This may include factors such as the loan amount, interest rate, and loan duration</li>
                                            </ol>
                                        </li>
                                        <li> Prepayment Penalties:
                                            <ol class="bullet">
                                                <li>If applicable, mention any penalties or fees associated with making early or additional payments towards the loan balance.</li>
                                            </ol>
                                        </li>
                                        <li> Grace Period:
                                            <ol class="bullet">
                                                <li>If there is a grace period before the borrower needs to start making repayments, specify the duration and any conditions that apply.</li>
                                            </ol>
                                        </li>
                                        
                                        <li> Late Payment Policy:
                                            <ol class="bullet">
                                                <li>Outline the consequences and potential fees for late payments, including any grace periods and the impact on credit score.</li>
                                            </ol>
                                        </li>
                                        <li> Payment Options:
                                            <ol class="bullet">
                                                <li>Provide information on the available payment methods, such as automatic bank transfers, online payments, or physical check payments.</li>
                                            </ol>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                            <div class="card pro__card wow fadeInUp" data-wow-duration="0.8s">
                                <div class="pro__part">
                                    <h4 class="gap-6 mb-4"><i class="bi bi-check-circle-fill"></i> pro</h4>
                                    <ul>
                                        <li><i class="bi bi-check2"></i>Competitive Interest Rate</li>
                                        <li><i class="bi bi-check2"></i>Flexible Repayment Options</li>
                                        <li><i class="bi bi-check2"></i>Quick Approval and Disbursement</li>
                                        <li><i class="bi bi-check2"></i>No Collateral Required</li>
                                        <li><i class="bi bi-check2"></i>Rewards or Benefits</li>
                                        <li><i class="bi bi-check2"></i>Favorable Terms and Conditions</li>
                                    </ul>
                                </div>
                                <div class="pro__part free">
                                    <h4 class="gap-6 mb-4"><i class="bi bi-x-circle-fill"></i> Cons</h4>
                                    <ul>
                                        <li><i class="bi bi-x-lg"></i>Competitive Interest Rate</li>
                                        <li><i class="bi bi-x-lg"></i>Flexible Repayment Options</li>
                                        <li><i class="bi bi-x-lg"></i>Quick Approval and Disbursement</li>
                                        <li><i class="bi bi-x-lg"></i>No Collateral Required</li>
                                        <li><i class="bi bi-x-lg"></i>Rewards or Benefits</li>
                                        <li><i class="bi bi-x-lg"></i>Favorable Terms and Conditions</li>
                                    </ul>
                                </div>
                            </div>
                            <p class="wow fadeInUp" data-wow-duration="0.8s">It's important to customize the repayment terms section for each loan you review, ensuring that you accurately represent the specific details of that loan. Additionally, consider using clear and concise language to help borrowers easily understand the repayment conditions associated with the loan.</p>
                        </div>
                        <div class="reviews-details__part wow fadeInUp" data-wow-duration="0.8s">
                            <div class="average-reviews">
                                <h4 class="average-reviews__title">Average Reviews</h4>
                                <div class="gap-9 flex-wrap flex-md-nowrap average-reviews__content">
                                    <div class="average-reviews__card">
                                        <p class="average-reviews__count"><span class="headingTwo">4.9</span>/5</p>
                                        <div class="star_review">
                                            <i class="bi bi-star-fill star-active"></i>
                                            <i class="bi bi-star-fill star-active"></i>
                                            <i class="bi bi-star-fill star-active"></i>
                                            <i class="bi bi-star-fill star-active"></i>
                                            <i class="bi bi-star-half star-active"></i>
                                        </div>
                                        <p>26 Rating</p>
                                    </div>
                                    <div class="progress-area">
                                        <div class="progress-area__part">
                                            <span class="gap-1">5 <i class="bi bi-star-fill star-active"></i></span>
                                            <div class="prog-bar">
                                                <div class="prog-percentage" style="max-width:90%"></div>
                                            </div>
                                            <span>90%</span>
                                        </div>
                                        <div class="progress-area__part">
                                            <span class="gap-1">4 <i class="bi bi-star-fill star-active"></i></span>
                                            <div class="prog-bar">
                                                <div class="prog-percentage" style="max-width:75%"></div>
                                            </div>
                                            <span>75%</span>
                                        </div>
                                        <div class="progress-area__part">
                                            <span class="gap-1">3 <i class="bi bi-star-fill star-active"></i></span>
                                            <div class="prog-bar">
                                                <div class="prog-percentage" style="max-width:67%"></div>
                                            </div>
                                            <span>67%</span>
                                        </div>
                                        <div class="progress-area__part">
                                            <span class="gap-1">2 <i class="bi bi-star-fill star-active"></i></span>
                                            <div class="prog-bar">
                                                <div class="prog-percentage" style="max-width:44%"></div>
                                            </div>
                                            <span>44%</span>
                                        </div>
                                        <div class="progress-area__part">
                                            <span class="gap-1">1 <i class="bi bi-star-fill star-active"></i></span>
                                            <div class="prog-bar">
                                                <div class="prog-percentage" style="max-width:21%"></div>
                                            </div>
                                            <span>21%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                            <div class="comments-area">
                                <div class="space_between">
                                    <h4>All Reviews</h4>
                                    <div class="gap-2 comments-title">
                                        <p class="sort_by">Sort By : </p>
                                        <select class="form-control cus-sel-active">
                                            <option data-display="new">new</option>
                                            <option value="1">recent</option>
                                            <option value="2">old</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="author__content wow fadeInUp" data-wow-duration="0.8s">
                                    <p class="author__submit-time">Mar 03, 2023 <i class="bi bi-dot"></i> 09:01 am</p>
                                    <div class="author__text">
                                        <div class="star_review">
                                            <i class="bi bi-star-fill star-active"></i>
                                            <i class="bi bi-star-fill star-active"></i>
                                            <i class="bi bi-star-fill star-active"></i>
                                            <i class="bi bi-star-fill star-active"></i>
                                            <i class="bi bi-star-half star-active"></i>
                                        </div>
                                        <p>Our satisfied clients have experienced success with our services and loan recommendations. Here are some of their testimonials highlighting their positive experiences and the value they received</p>  
                                    </div>
                                    <div class="gap-7">
                                        <div class="author__thumbs">
                                            <img src="assets/images/author.png" alt="image">
                                        </div>
                                        <div class="author__info">
                                            <h5 class="author__name">Darrell Steward</h5>
                                            <p>Software engineer</p>
                                        </div>
                                    </div>
                                    <div class="feedback">
                                        <div class="gap-9 feedback__content">
                                            <a href="javascript:void(0)" class="like">
                                                <i class="bi bi-hand-thumbs-up"></i>178
                                            </a>
                                            <a href="javascript:void(0)" class="reply">
                                                <i class="bi bi-chat-left-text"></i>Reply
                                            </a>
                                        </div>
                                        <div class="reply__content">
                                            <div class="gap-7">
                                                <div class="author__thumbs">
                                                    <img src="assets/images/author2.png" alt="Author">
                                                </div>
                                                <form method="POST" class="reply__form">
                                                    <div >
                                                        <input type="text" class="form-control" name="reply__text" placeholder="Join the discussion..." required>
                                                        <button type="submit" class="d-none" name="reply__submit">Submit</button>
                                                    </div>
                                                    <span id="reply__commentsMsg"></span> 
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="author__content wow fadeInUp" data-wow-duration="0.8s">
                                    <p class="author__submit-time">Mar 03, 2023 <i class="bi bi-dot"></i> 09:01 am</p>
                                    <div class="author__text">
                                        <div class="star_review">
                                            <i class="bi bi-star-fill star-active"></i>
                                            <i class="bi bi-star-fill star-active"></i>
                                            <i class="bi bi-star-fill star-active"></i>
                                            <i class="bi bi-star-fill star-active"></i>
                                            <i class="bi bi-star-half star-active"></i>
                                        </div>
                                        <p>Our satisfied clients have experienced success with our services and loan recommendations. Here are some of their testimonials highlighting their positive experiences and the value they received</p>  
                                    </div>
                                    <div class="gap-7">
                                        <div class="author__thumbs">
                                            <img src="assets/images/author3.png" alt="image">
                                        </div>
                                        <div class="author__info">
                                            <h5 class="author__name">Albert Flores</h5>
                                            <p>Customer success</p>
                                        </div>
                                    </div>
                                    <div class="feedback">
                                        <div class="gap-9 feedback__content">
                                            <a href="javascript:void(0)" class="like">
                                                <i class="bi bi-hand-thumbs-up"></i>178
                                            </a>
                                            <a href="javascript:void(0)" class="reply">
                                                <i class="bi bi-chat-left-text"></i>Reply
                                            </a>
                                        </div>
                                        <div class="reply__content">
                                            <div class="gap-7">
                                                <div class="author__thumbs">
                                                    <img src="assets/images/author2.png" alt="Author">
                                                </div>
                                                <form method="POST" class="reply__form">
                                                    <div >
                                                        <input type="text" class="form-control" name="reply__text" placeholder="Join the discussion..." required>
                                                        <button type="submit" class="d-none" name="reply__submit">Submit</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="author__content wow fadeInUp" data-wow-duration="0.8s">
                                    <p class="author__submit-time">Mar 03, 2023 <i class="bi bi-dot"></i> 09:01 am</p>
                                    <div class="author__text">
                                        <div class="star_review">
                                            <i class="bi bi-star-fill star-active"></i>
                                            <i class="bi bi-star-fill star-active"></i>
                                            <i class="bi bi-star-fill star-active"></i>
                                            <i class="bi bi-star-fill star-active"></i>
                                            <i class="bi bi-star-half star-active"></i>
                                        </div>
                                        <p>Our satisfied clients have experienced success with our services and loan recommendations. Here are some of their testimonials highlighting their positive experiences and the value they received</p>  
                                    </div>
                                    <div class="gap-7">
                                        <div class="author__thumbs">
                                            <img src="assets/images/author4.png" alt="image">
                                        </div>
                                        <div class="author__info">
                                            <h5 class="author__name">Annette Black</h5>
                                            <p>Personal assistant</p>
                                        </div>
                                    </div>
                                    <div class="feedback">
                                        <div class="gap-9 feedback__content">
                                            <a href="javascript:void(0)" class="like">
                                                <i class="bi bi-hand-thumbs-up"></i>178
                                            </a>
                                            <a href="javascript:void(0)" class="reply">
                                                <i class="bi bi-chat-left-text"></i>Reply
                                            </a>
                                        </div>
                                        <div class="reply__content">
                                            <div class="gap-7">
                                                <div class="author__thumbs">
                                                    <img src="assets/images/author2.png" alt="Author">
                                                </div>
                                                <form method="POST" class="reply__form">
                                                    <div >
                                                        <input type="text" class="form-control" name="reply__text" placeholder="Join the discussion..." required>
                                                        <button type="submit" class="d-none" name="reply__submit">Submit</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="section__cta text-start mt_40">
                                <a href="sign-in.html" class="btn_theme btn_theme_active">See All Reviews <i class="bi bi-arrow-up-right"></i><span></span></a>
                            </div>
                        </div>
                        <form class="reviews-details__part write-commnets wow fadeInUp" data-wow-duration="0.8s">
                            <h4 class="average-reviews__title">Write a Comments</h4>
                           <div class="d-grid gap-xxl-4 gap-3">
                                <div class="write-form">
                                    <label for="nmae">Name</label>
                                    <input type="text" placeholder="Enter Name...">
                                </div>
                                <div class="write-form">
                                    <label for="nmae">Email </label>
                                    <input type="email" placeholder="Enter Email...">
                                </div>
                                <div class="write-form">
                                    <label for="nmae">Ratting </label>
                                    <div class="d-flex align-items-center gap-1">
                                        <i class="bi bi-star rating"></i>
                                        <i class="bi bi-star rating"></i>
                                        <i class="bi bi-star rating"></i>
                                        <i class="bi bi-star rating"></i>
                                        <i class="bi bi-star rating"></i>
                                    </div>
                                </div>
                                <div class="write-form">
                                    <label for="nmae">Comments </label>
                                    <textarea name="comments" placeholder="Write you comments..."></textarea>
                                </div>
                                <div class="section__cta text-start mt-xl-3 mt-2">
                                    <button type="button" class="btn_theme btn_theme_active">Submit Comments <i class="bi bi-arrow-up-right"></i><span></span></button>
                                </div>
                           </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section> 

@endsection
