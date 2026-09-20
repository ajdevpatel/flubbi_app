@extends('frontend.user.layoutIndex')

@section('panelTitle', 'Support')
@section('panelCrumb', 'Support')

@section('panelBody')
    <div class="up-head">
        <div>
            <h2>Help &amp; support</h2>
            <p>Raise a ticket and we will get back within one working day.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <form action="{{ route('_userSupport') }}" method="POST" class="js-fl-step" autocomplete="off" novalidate>
                @csrf
                <div class="up-card">
                    <div class="up-card__title"><h4>New ticket</h4></div>
                    <div class="fl-field">
                        <label class="fl-field__label" for="reason_id">Reason <span class="fl-req">*</span></label>
                        <select class="fl-field__control" name="reason_id" id="reason_id" required>
                            <option value="">Select a reason</option>
                            @foreach ($reasons as $r)
                                <option value="{{ $r->id }}">{{ $r->label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="fl-field">
                        <label class="fl-field__label" for="message">Message <span class="fl-req">*</span></label>
                        <textarea class="fl-field__control" name="message" id="message" maxlength="1000" placeholder="Tell us what happened, with your application number if relevant" required></textarea>
                    </div>
                    <div class="fl-actions">
                        <button type="submit" class="fl-btn fl-btn--block">Submit ticket <i class="bi bi-send"></i></button>
                    </div>
                </div>
            </form>

            <div class="up-card">
                <div class="up-card__title"><h4>Contact us directly</h4></div>
                <div class="up-contact" style="flex-direction:column;gap:8px;">
                    <span><i class="bi bi-envelope"></i> <a href="mailto:{{ $support['mail'] }}">{{ $support['mail'] }}</a></span>
                    <span><i class="bi bi-telephone"></i> <a href="tel:{{ $support['phone'] }}">{{ $support['phone'] }}</a></span>
                    <span><i class="bi bi-clock"></i> Mon&ndash;Sat, 9:00 AM &ndash; 6:00 PM</span>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="up-card">
                <div class="up-card__title"><h4>Your tickets</h4></div>
                @forelse ($tickets as $t)
                    <div class="up-ticket">
                        <div class="up-ticket__head">
                            <strong>{{ $t->ticket_no }}</strong>
                            <span class="up-badge up-badge--{{ 'closed' === $t->status ? 'closed' : ('resolved' === $t->status ? 'approved' : 'review') }}">{{ ucfirst($t->status) }}</span>
                        </div>
                        <p><strong style="color:var(--fl-ink);">{{ $t->reason ?? 'General' }}</strong> &mdash; {{ $t->message }}</p>
                        <small>{{ date('d M Y, h:i A', strtotime($t->created_at)) }}</small>
                    </div>
                @empty
                    <div class="up-empty"><i class="bi bi-chat-square-text"></i>No tickets yet.</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
