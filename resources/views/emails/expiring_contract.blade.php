@component('mail::message')
    # Contract Expiring Soon

    Dear User,

    The following contract is expiring soon:

    - **Contract ID:** {{ $contract->id }}
    - **Material:** {{ $contract->material }}
    - **Description:** {{ $contract->description }}
    - **Valid Until:** {{ \Carbon\Carbon::parse($contract->validitas_akhir)->toFormattedDateString() }}

    Please take necessary actions before the contract expires.

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
