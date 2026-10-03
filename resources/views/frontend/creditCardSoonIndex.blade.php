@extends('frontend.layoutIndex')

@section('styleIndex')
    <link rel="stylesheet" href="{{ asset('assets/app/loanService.css') }}">
    <style>
        .cs-hero { position: relative; overflow: hidden; color: #fff; padding: 84px 0 104px; background: radial-gradient(900px 480px at 88% 8%, rgba(205, 182, 118, 0.24), transparent 62%), radial-gradient(720px 440px at 0% 100%, rgba(111, 177, 255, 0.14), transparent 60%), linear-gradient(135deg, #050d1f 0%, #09162f 46%, #15274d 100%); }
        .cs-hero::before { content: ""; position: absolute; inset: 0; pointer-events: none; background-image: linear-gradient(rgba(255, 255, 255, 0.05) 1px, transparent 1px), linear-gradient(90deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px); background-size: 46px 46px; -webkit-mask-image: radial-gradient(ellipse at 72% 28%, #000 18%, transparent 72%); mask-image: radial-gradient(ellipse at 72% 28%, #000 18%, transparent 72%); }
        .cs-hero .container { position: relative; z-index: 1; }
        .cs-sign { position: relative; display: inline-block; padding-top: 36px; transform-origin: 50% 6px; animation: cs-swing 3.4s ease-in-out infinite; }
        .cs-sign::before, .cs-sign::after { content: ""; position: absolute; top: 6px; left: 50%; width: 2px; height: 60px; margin-left: -1px; background: linear-gradient(rgba(205, 182, 118, 0.95), rgba(205, 182, 118, 0.4)); transform-origin: top center; }
        .cs-sign::before { transform: rotate(60deg); }
        .cs-sign::after { transform: rotate(-60deg); }
        .cs-sign__nail { position: absolute; top: 0; left: 50%; z-index: 2; width: 12px; height: 12px; margin-left: -6px; border-radius: 50%; background: radial-gradient(circle at 35% 35%, #f6ecc6, #a8873c); box-shadow: 0 0 0 4px rgba(205, 182, 118, 0.18); }
        .cs-sign__board { position: relative; z-index: 1; display: inline-flex; align-items: center; gap: 14px; padding: 15px 30px; border-radius: 16px; border: 2px solid rgba(205, 182, 118, 0.58); background: linear-gradient(135deg, rgba(205, 182, 118, 0.24), rgba(205, 182, 118, 0.06)), #0b1a38; box-shadow: 0 20px 44px rgba(0, 0, 0, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.16); font-size: clamp(28px, 4.4vw, 48px); font-weight: 800; line-height: 1; letter-spacing: 4px; text-transform: uppercase; white-space: nowrap; }
        .cs-sign__text { background: linear-gradient(100deg, #a8873c 0%, #f6ecc6 25%, #cdb676 50%, #f6ecc6 75%, #a8873c 100%); background-size: 200% 100%; -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; color: var(--fl-accent); animation: cs-shimmer 3.6s linear infinite; }
        .cs-sign__dot { position: relative; flex: 0 0 auto; width: 12px; height: 12px; border-radius: 50%; background: var(--fl-accent); }
        .cs-sign__dot::after { content: ""; position: absolute; inset: -7px; border-radius: 50%; border: 1px solid var(--fl-accent); animation: cs-pulse 1.9s ease-out infinite; }
        .cs-title { margin: 28px 0 16px; font-size: clamp(34px, 5.2vw, 60px); line-height: 1.06; font-weight: 700; color: #fff; letter-spacing: -0.5px; }
        .cs-title span { background: linear-gradient(100deg, #f1e2b3 0%, #cdb676 45%, #a8873c 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; color: var(--fl-accent); }
        .cs-lead { max-width: 520px; margin: 0 0 26px; font-size: 17px; line-height: 1.7; color: #b9c4d8; }
        .cs-chips { display: flex; flex-wrap: wrap; gap: 10px; padding: 0; margin: 0 0 32px; list-style: none; }
        .cs-chips li { display: inline-flex; align-items: center; gap: 8px; padding: 9px 14px; border-radius: 10px; font-size: 14px; color: #e3e9f4; background: rgba(255, 255, 255, 0.06); border: 1px solid rgba(255, 255, 255, 0.1); }
        .cs-chips i { color: var(--fl-accent); font-size: 15px; }
        .cs-actions { display: flex; flex-wrap: wrap; gap: 12px; }
        .cs-btn { display: inline-flex; align-items: center; justify-content: center; gap: 9px; min-height: 52px; padding: 0 26px; border-radius: 12px; font-size: 15.5px; font-weight: 600; text-decoration: none; transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease, color 0.2s ease; }
        .cs-btn--gold { color: var(--fl-ink); background: linear-gradient(135deg, #ecdca8, #cdb676 55%, #b0914a); box-shadow: 0 12px 28px rgba(205, 182, 118, 0.3); }
        .cs-btn--gold:hover { color: var(--fl-ink); transform: translateY(-2px); box-shadow: 0 16px 34px rgba(205, 182, 118, 0.42); }
        .cs-btn--ghost { color: #fff; background: transparent; border: 1px solid rgba(255, 255, 255, 0.28); }
        .cs-btn--ghost:hover { color: var(--fl-ink); background: #fff; border-color: #fff; transform: translateY(-2px); }
        .cs-note { margin: 18px 0 0; font-size: 13.5px; color: #8f9db6; }
        .cs-stage { position: relative; height: 380px; perspective: 1300px; }
        .cs-card { position: absolute; top: 50%; left: 50%; width: 372px; max-width: 86%; aspect-ratio: 1.586; margin: 0; padding: 26px 28px; border-radius: 22px; overflow: hidden; display: flex; flex-direction: column; justify-content: space-between; }
        .cs-card--back { color: #dfe6f3; background: linear-gradient(135deg, #20376b 0%, #0d1c3d 100%); border: 1px solid rgba(205, 182, 118, 0.34); box-shadow: 0 26px 54px rgba(0, 0, 0, 0.45); transform: translate(-38%, -34%) rotateY(-18deg) rotateX(9deg) rotate(8deg); animation: cs-float-back 7s ease-in-out infinite; }
        .cs-card--front { color: var(--fl-ink); background: linear-gradient(135deg, #f3e6bb 0%, #cdb676 42%, #9a7b34 100%); box-shadow: 0 34px 70px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.6); transform: translate(-58%, -58%) rotateY(-18deg) rotateX(9deg) rotate(-5deg); animation: cs-float-front 6s ease-in-out infinite; }
        .cs-card--front::after { content: ""; position: absolute; top: -60%; left: -40%; width: 46%; height: 220%; background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.5), transparent); transform: rotate(24deg); animation: cs-shine 5.5s ease-in-out infinite; }
        .cs-card__top { display: flex; align-items: center; justify-content: space-between; font-size: 20px; font-weight: 700; letter-spacing: 3px; }
        .cs-card__top i { font-size: 26px; transform: rotate(90deg); opacity: 0.8; }
        .cs-card__chip { width: 50px; height: 38px; border-radius: 8px; background: linear-gradient(135deg, #fbf3d6, #b99a4f); box-shadow: inset 0 0 0 1px rgba(9, 22, 47, 0.25); position: relative; }
        .cs-card__chip::before, .cs-card__chip::after { content: ""; position: absolute; background: rgba(9, 22, 47, 0.28); }
        .cs-card__chip::before { left: 0; right: 0; top: 50%; height: 1px; }
        .cs-card__chip::after { top: 0; bottom: 0; left: 50%; width: 1px; }
        .cs-card--back .cs-card__chip { background: linear-gradient(135deg, #e9d79f, #a8873c); }
        .cs-card__number { font-size: 21px; font-weight: 600; letter-spacing: 3.4px; font-variant-numeric: tabular-nums; }
        .cs-card__foot { display: flex; align-items: flex-end; justify-content: space-between; font-size: 11px; letter-spacing: 1.4px; text-transform: uppercase; }
        .cs-card__foot strong { display: block; margin-top: 3px; font-size: 14px; letter-spacing: 1.6px; }
        .cs-glow { position: absolute; left: 50%; bottom: 6px; width: 62%; height: 34px; transform: translateX(-50%); border-radius: 50%; background: radial-gradient(ellipse, rgba(205, 182, 118, 0.42), transparent 70%); filter: blur(10px); }
        .cs-expect { padding: 78px 0 86px; background: #fbfbfc; }
        .cs-expect__head { max-width: 620px; margin: 0 auto 40px; text-align: center; }
        .cs-expect__head h2 { margin: 0 0 10px; font-size: clamp(24px, 3vw, 34px); font-weight: 700; color: var(--fl-ink); }
        .cs-expect__head p { margin: 0; font-size: 16px; color: var(--fl-muted); }
        .cs-tile { height: 100%; padding: 28px 26px; border-radius: var(--fl-radius); background: #fff; border: 1px solid var(--fl-line); transition: transform 0.22s ease, box-shadow 0.22s ease, border-color 0.22s ease; }
        .cs-tile:hover { transform: translateY(-4px); border-color: var(--fl-accent); box-shadow: 0 18px 40px rgba(9, 22, 47, 0.09); }
        .cs-tile__icon { display: inline-flex; align-items: center; justify-content: center; width: 52px; height: 52px; margin-bottom: 18px; border-radius: 14px; font-size: 23px; color: var(--fl-accent-dark); background: rgba(205, 182, 118, 0.18); }
        .cs-tile h3 { margin: 0 0 8px; font-size: 19px; font-weight: 600; color: var(--fl-ink); }
        .cs-tile p { margin: 0; font-size: 15px; line-height: 1.65; color: var(--fl-muted); }
        .cs-band { margin-top: 44px; padding: 30px 34px; border-radius: 18px; display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 20px; color: #fff; background: linear-gradient(120deg, #09162f, #1a2f5b); }
        .cs-band h3 { margin: 0 0 4px; font-size: 21px; font-weight: 600; color: #fff; }
        .cs-band p { margin: 0; font-size: 15px; color: #b9c4d8; }
        @keyframes cs-pulse { 0% { transform: scale(0.6); opacity: 0.9; } 100% { transform: scale(1.7); opacity: 0; } }
        @keyframes cs-swing { 0%, 100% { transform: rotate(6deg); } 50% { transform: rotate(-6deg); } }
        @keyframes cs-shimmer { 0% { background-position: 200% 0; } 100% { background-position: 0 0; } }
        @keyframes cs-float-front { 0%, 100% { transform: translate(-58%, -58%) rotateY(-18deg) rotateX(9deg) rotate(-5deg); } 50% { transform: translate(-58%, -63%) rotateY(-12deg) rotateX(6deg) rotate(-3deg); } }
        @keyframes cs-float-back { 0%, 100% { transform: translate(-38%, -34%) rotateY(-18deg) rotateX(9deg) rotate(8deg); } 50% { transform: translate(-38%, -30%) rotateY(-22deg) rotateX(11deg) rotate(10deg); } }
        @keyframes cs-shine { 0%, 62% { left: -40%; } 100% { left: 130%; } }
        @media (max-width: 991px) { .cs-hero { padding: 56px 0 44px; text-align: center; } .cs-lead { margin-left: auto; margin-right: auto; } .cs-chips, .cs-actions { justify-content: center; } .cs-stage { height: 320px; margin-top: 30px; } }
        @media (max-width: 575px) { .cs-hero { padding: 40px 0 30px; } .cs-lead { font-size: 15.5px; } .cs-btn { width: 100%; } .cs-stage { height: 250px; } .cs-card { padding: 18px 20px; border-radius: 18px; } .cs-card__top { font-size: 16px; } .cs-card__top i { font-size: 21px; } .cs-card__chip { width: 40px; height: 30px; } .cs-card__number { font-size: 16px; letter-spacing: 2.2px; } .cs-card__foot strong { font-size: 12px; } .cs-expect { padding: 50px 0 56px; } .cs-band { padding: 24px 22px; text-align: center; justify-content: center; } }
        @media (max-width: 575px) { .cs-sign { padding-top: 32px; } .cs-sign::before, .cs-sign::after { height: 52px; } .cs-sign__board { padding: 12px 20px; gap: 10px; letter-spacing: 3px; border-radius: 14px; } .cs-sign__dot { width: 10px; height: 10px; } .cs-title { margin-top: 24px; } }
        @media (prefers-reduced-motion: reduce) { .cs-card--front, .cs-card--back, .cs-card--front::after, .cs-sign, .cs-sign__text, .cs-sign__dot::after { animation: none; } }
    </style>
@endsection

@section('bodyIndex')
    <section class="cs-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="cs-sign" role="img" aria-label="Coming soon">
                        <span class="cs-sign__nail"></span>
                        <span class="cs-sign__board"><span class="cs-sign__dot"></span><span class="cs-sign__text">Coming Soon</span></span>
                    </div>
                    <h1 class="cs-title">Credit cards, <span>the Flubbi way.</span></h1>
                    <p class="cs-lead">We are building a simpler way to find the right credit card. Compare cards from
                        our partner banks, check where you stand, and apply online without the paperwork. It is
                        almost ready.</p>
                    <ul class="cs-chips">
                        <li><i class="bi bi-columns-gap"></i> Compare partner cards</li>
                        <li><i class="bi bi-lightning-charge"></i> Quick eligibility check</li>
                        <li><i class="bi bi-shield-check"></i> 100% online</li>
                    </ul>
                    <div class="cs-actions">
                        <a class="cs-btn cs-btn--gold" href="{{ route('_personalServiceIndex') }}">Explore Personal Loan <i
                                class="bi bi-arrow-right"></i></a>
                        <a class="cs-btn cs-btn--ghost" href="{{ route('_businessServiceIndex') }}">Business Loan</a>
                    </div>
                    <p class="cs-note">Need funds today? Our loan services are live right now.</p>
                </div>
                <div class="col-lg-6">
                    <div class="cs-stage" aria-hidden="true">
                        <div class="cs-card cs-card--back">
                            <div class="cs-card__top"><span>FLUBBI</span><i class="bi bi-wifi"></i></div>
                            <div class="cs-card__chip"></div>
                            <div class="cs-card__number">&bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; 0000</div>
                            <div class="cs-card__foot">
                                <div>Card holder<strong>Your name</strong></div>
                                <div>Valid<strong>&bull;&bull;/&bull;&bull;</strong></div>
                            </div>
                        </div>
                        <div class="cs-card cs-card--front">
                            <div class="cs-card__top"><span>FLUBBI</span><i class="bi bi-wifi"></i></div>
                            <div class="cs-card__chip"></div>
                            <div class="cs-card__number">&bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; {{ date('Y') }}</div>
                            <div class="cs-card__foot">
                                <div>Card holder<strong>Coming soon</strong></div>
                                <div>Status<strong>In progress</strong></div>
                            </div>
                        </div>
                        <div class="cs-glow"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cs-expect">
        <div class="container">
            <div class="cs-expect__head">
                <h2>What to expect</h2>
                <p>The same simple, guided experience as our loan services, built for credit cards.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="cs-tile">
                        <span class="cs-tile__icon"><i class="bi bi-columns-gap"></i></span>
                        <h3>Compare side by side</h3>
                        <p>See cards from our partner banks in one place, so you can pick what fits how you spend.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="cs-tile">
                        <span class="cs-tile__icon"><i class="bi bi-speedometer2"></i></span>
                        <h3>Know before you apply</h3>
                        <p>Share a few details and find out which cards you are likely to qualify for.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="cs-tile">
                        <span class="cs-tile__icon"><i class="bi bi-phone"></i></span>
                        <h3>Apply from your phone</h3>
                        <p>A short online journey with clear steps, and our team on hand if you get stuck.</p>
                    </div>
                </div>
            </div>
            <div class="cs-band">
                <div>
                    <h3>Have a question about cards?</h3>
                    <p>Tell us what you are looking for and we will get back to you.</p>
                </div>
                <a class="cs-btn cs-btn--gold" href="{{ route('_contactusPost') }}">Contact us <i
                        class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </section>
@endsection
