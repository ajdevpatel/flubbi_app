
<div class="d-inline-block d-xl-none mb-4">
    <button class="sidebar_btn"> <i class="bi bi-layout-text-sidebar"></i> <span>User Menu</span></button>    
</div>
<div class="sidebar sidebar_fixed sidebar-xl-fixed cus_scrollbar">
    <div class="sidebar__part">
        <h4 class="sidebar__part-title">User Menu</h4>
        <ul class="advantage-list d-grid gap-xxl-4 gap-xl-3 gap-2">
            <li class="align-items-start">
                <a href="{{ route('_userProfileIndex') }}"> 
                    <img src="{{ asset('assets/images/section-icon4.png') }}" alt="icon" class="arrows-icon"> Profile 
                </a>
            </li>
            <li class="align-items-start">
                <a href="{{ route('_userApplicationIndex') }}"> 
                    <img src="{{ asset('assets/images/section-icon4.png') }}" alt="icon" class="arrows-icon"> Application List 
                </a>
            </li>
            <li class="align-items-start">
                <a href="{{ route('_userDocumentsIndex') }}"> 
                    <img src="{{ asset('assets/images/section-icon4.png') }}" alt="icon" class="arrows-icon"> Documents 
                </a>
            </li>
            <li class="align-items-start">
                <a href="{{ route('_userSubscriptionsIndex') }}"> 
                    <img src="{{ asset('assets/images/section-icon4.png') }}" alt="icon" class="arrows-icon"> Subscription 
                </a>
            </li>
            <li class="align-items-start">
                <a href="{{ route('_userRaiseRequestIndex') }}"> 
                    <img src="{{ asset('assets/images/section-icon4.png') }}" alt="icon" class="arrows-icon"> Help & Support 
                </a>
            </li>
            <li class="align-items-start">
                <a href="{{ route('_frontendLogout') }}"> 
                    <img src="{{ asset('assets/images/section-icon4.png') }}" alt="icon" class="arrows-icon"> Logout
                </a>
            </li>
        </ul>
    </div>
</div>