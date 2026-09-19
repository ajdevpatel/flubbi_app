@extends("frontend.layoutIndex")
 

@section("bodyIndex")

    <!-- Banner Start -->
    <section class="banner">
        <div class="container ">
            <div class="row gy-4 gy-sm-0 align-items-center">
                <div class="col-12 col-sm-6">
                    <div class="banner__content">
                        <h1 class="banner__title display-4 wow fadeInLeft" data-wow-duration="0.8s">License Agreement</h1>
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb wow fadeInRight" data-wow-duration="0.8s">
                                <li class="breadcrumb-item"><a href="{{ route('_homeIndex') }}">Home</a></li>
                                <li class="breadcrumb-item">User</li>
                                <li class="breadcrumb-item active" aria-current="page">License Agreement</li>
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
                                <h2 class="section__content-title">License Agreement</h2>
                                <p class="section__content-text">Kindly review the licence terms before using the portal.</p>
                                <hr/>
                                <ol>
                                    <li>Under any circumstances, the Subscription fees won&rsquo;t be refunded.</li>
                                    <li>Only the charge of the Subscription will be taken by the company; no other payment is charged by the company.</li>
                                    <li>It must be noted that the Subscription provided by the company is not an ATM, DEBIT, or CREDIT CARD. This card must be used for loan purposes and certain other benefits provided by the company.</li>
                                    <li>The customer or the referral person can use this Subscription.</li>
                                    <li>If a customer reference makes a payment via the customer&rsquo;s own referral link which is provided by the company &ndash; &nbsp;and if it reflects in the customer&#39;s portal then only the company will release the payout of that customer.</li>
                                    <li>In case a customer&rsquo;s loan is approved in our company but the customer denies taking the loan &ndash; still the Subscription fees won&rsquo;t be refunded.</li>
                                    <li>The login department&rsquo;s decision would be final for your loan process.</li>
                                    <li>The OTP asked by the company&rsquo;s employee is for loan purposes &ndash; if any issue/problem arises in future, the company won&rsquo;t be responsible for the same.</li>
                                    <li>If you do not share the OTP (for loan purposes) with the company&rsquo;s employee, your file will be rejected. As per the criteria, if your file matched without OTP, the loan will be processed further.</li>
                                    <li>If the company executive asks you to share any transaction OTP, kindly do not share it.</li>
                                    <li>In case any customer has any questions/queries regarding the loan process, they have to contact that department where their file is being processed.</li>
                                    <li>We will verify your documents in multiple banks; so whatever documents are submitted by customers in our company that will match with any bank criteria, in that bank only we will proceed with the loan process. (For instance, if your file will match in 3 banks then our company&rsquo;s login department will log in your document only on that 3 banks. The verification is done by the company&rsquo;s employee. The company or bank won&rsquo;t provide any proof for that.</li>
                                    <li>Your loan documents will be verified by the company in multiple banks. If the documents match the bank criteria, then the login process will be done in that bank. In case it doesn&rsquo;t match, the company will provide a solution which you can consider and reapply.</li>
                                    <li>It is not fixed that the customer file will be logged in only certain banks. It may be verified in other banks also, depending on the customer file.</li>
                                    <li>The company doesn&rsquo;t charge anything extra other than the Subscription fees. If any other external person or third-party charges you then our company won&rsquo;t be responsible for the same.</li>
                                    <li>The company doesn&rsquo;t charge for processing or file charges for customer loan approval. Only the Subscription fee is charged by the company.</li>
                                    <li>If a customer file is logged in uncoded banks, then the customer has to pay charges separately.</li>
                                    <li>The customer files will be logged in by the company according to the customer&rsquo;s requirements. For instance, If the customer requirement is INR 2 lakhs and if some bank criteria is up to INR 1 Lakh then we will not log in customer file in that bank.</li>
                                    <li>If the customer shares their profile and asks us whether the loan will be approved or not; so, the answer to this question would be &ndash; the loan approval estimated ratio is 60% and 40%. It means that there are 60% chances of approval and 40% chances of rejection. So, If your file gets rejected in our company then the customer shouldn&rsquo;t argue for that. Because the company is not taking any guarantee for the loan.</li>
                                    <li>Even if the customer file is rejected in our company, any bank can reopen that file for processing. But code activation time is dependent on various banks, ranging from 3 to 4 months. If approval will come from that bank within that code activation time then only company reference would be applicable. So, the customer would not have any doubt that the loan has been taken from his/her own or someone else&rsquo;s reference. After a code deactivation, if the loan is approved by that bank then there is no responsibility of our company.</li>
                                    <li>Wherever the customer file is logged in by the company, such information and details will not be given to any customer in written or digital form.</li>
                                    <li>All the documents submitted by the customer are safe and secure in our company. We are using those documents only for loan purposes. In case the documents are misused by any other sources, then the company won&rsquo;t be responsible for the same.</li>
                                    <li>Loan offers and pre-approval loans process is dependent only on the bank&#39;s rules and that type of loan will be given only on customer behaviour. So, there exists some difference between that type of process and the company&rsquo;s process. In case that loan is approved by another company then the customer can&rsquo;t blame our company.</li>
                                    <li>Loan approval depends on your profile so if your documents are perfect then you will get a loan from our company.</li>
                                    <li>Any information regarding the loan is only provided to that person who has applied for the loan.</li>
                                    <li>During the loan processing time, if any customer would not be in contact with us for 3 days, then that file will be rejected by our company.</li>
                                    <li>In case your file is rejected in our company, then the customer has to ascertain that they re-submit their documents with the solution in our company after a period of 6 months.</li>
                                    <li>The company won&rsquo;t be responsible in case the customer loan is rejected by queries.</li>
                                    <li>In case your file gets rejected in our company, then also the Subscription payment remains non-refundable.</li>
                                    <li>After processing, in case the customer cancels the file, then also the Subscription payment remains non-refundable.</li>
                                    <li>If the customer will apply for the first time but his/her loan is rejected in our company then the company will give them reason and solution for that. So at re-applying time, if the customer will not re-submit the file with a solution then the file will again face rejection in our company for the same reason.</li>
                                    <li>The company will give only the reason for rejection to the customer and it would not be provided in hard or soft copy. Banks only provide general reasons, they don&rsquo;t give us the specific reason &ndash; so the customer should not complain about that.</li>
                                    <li>The customer must give correct information about their CIBIL SCORE and PROFILE. If the customer will provide the wrong information, then the company holds no responsibility for loan rejection.</li>
                                    <li>Under any situation, the company will not be providing any CIBIL REPORT or VALUATION REPORT in digital or hard copy to any customer.</li>
                                    <li>Bank charges are applied according to the banks&#39; rules and regulations.</li>
                                    <li>In case a customer submits fake documents, the company will take legal action against that customer.</li>
                                    <li>After logging in your file, the customer has to contact only the login department &ndash; and not any telecaller or other department.</li>
                                    <li>The person who has already applied for a loan in the same bank, then our company will not apply in that bank.</li>
                                    <li>During the loan process if the rules of any bank change, then the company will have to follow those new rules.</li>
                                    <li>Only the person who needs a loan has to apply for the loan process.</li>
                                    <li>The customer has to give their registered phone number so that the login department can contact the customer.</li>
                                    <li>Once the loan process is completed, the customer has to cancel their cheque by visiting the concerned bank only.</li>
                                    <li>At the time of loan processing, if the company gets any queries and it is not solving that in the given time, then the company can take more time for that. So, the customer must not argue or complain about this.</li>
                                    <li>In case the customer wants to reapply in our company after file rejection or approval, then the customer has to re-submit their documents in the customer login menu.</li>
                                    <li>When you are applying for a loan on our website, we are showing you only your Eligibility for the loan. So whatever details you enter on the website are accepted by software only, and that only shows your pre-approval and not your final loan approval. The final loan approval depends on your documents. We are not giving you any guarantee for the final loan approval.</li>
                                    <li>The Subscription is shown on our websites during the processing time, which are only for demo purposes. So that&rsquo;s not your real Subscription. In that, whatever details are entered by the customer are accepted by the software and the system shows you the pre-approval depending on the customer details. The customer will get a card after completing the entire process of the loan on our website.</li>
                                    <li>The company&rsquo;s privacy policy, terms and conditions are also applicable to marketing and advertising.</li>
                                    <li>Our marketing contains advertisements, page updates, posts, videos, SMS, E-mails, banners, social media updates or any type of content.</li>
                                    <li>The third-party payment sources execute the customer&rsquo;s payment. So, whenever payment would be received by the company then only the Subscription will be generated. If a customer&#39;s payment would be debited from their account but the company doesn&rsquo;t receive any payment in the company&#39;s account then the company holds no responsibility for the same.</li>
                                    <li>Account verification is compulsory for any customer&#39;s payout. If your account is not verified in our company and once payment is credited from the company&#39;s account then the company is not responsible for answering any of the questions/queries/doubts.</li>
                                    <li>Our company is a private limited company and we are tied up with banks so we are providing loans through banks only.</li>
                                    <li>Multiple banks&rsquo; logos are displayed on our portal &ndash; they are shown only for our company&rsquo;s marketing purposes. That banks&rsquo; logos only reflect that our company is tied up with those banks. That&rsquo;s not any bank&rsquo;s advertisement.</li>
                                    <li>In case a person takes any legal action against the company, only the company&rsquo;s legal advisor would be dealing with that; and Surat, Gujarat will only remain the junction for any legal procedure. No one would be able to contact any employee or director of our company.</li>
                                    <li>A verbal/vocal statement won&rsquo;t be accepted. Only the signed agreements would be acceptable for any customer.</li>
                                    <li>The detailed terms and conditions are with the head office which is the core baseline to all the above-stated terms and conditions.</li>
                                    <li>For purchasing a Subscription, there are no age eligibility criteria. Still, everyone has to follow the Banks and NBFCs&rsquo; age criteria.</li>
                                    <li>Regarding the referral payout, the customer has to complete the payout agreement with the company. No payout will be given to anyone without an agreement.</li>
                                </ol>
                            </div>                         
                            <form action="{{ route('_licenseAgreementIndex') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <hr>
                                <small>If you accept the terms of the ageement, click "I Agree" to continue.</small>
                                <hr>
                                <label><input type="checkbox" name="i_agree" required value="1"><b> &nbsp;&nbsp;I accept the terms in the License Agreement.</b></label>
                                <hr>
                                <button type="submit" class="btn_theme btn_theme_active" name="submit"> I Agree <i class="bi bi-arrow-up-right"></i></button>
                            </form>
                            
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </section> 

@endsection
