@component('mail::message')
    Dear {{$user->name}},
    Happy to have you join us!

    We are so excited to share with you the newest resources of knowledge.
    You can study about anything, at anywhere and at anytime!

    Let's start learning together!

    Your sincerely,
    {{ config('app.name') }}
@endcomponent
