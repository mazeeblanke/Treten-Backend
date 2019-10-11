@component('mail::message')

Dear Admin,


{{ $message }}


Thanks,<br>
{{ $first_name." ".$last_name }}
@endcomponent
