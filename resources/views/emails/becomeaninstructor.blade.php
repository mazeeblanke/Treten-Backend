@component('mail::message')

Hello,

My names are {{ucfirst($first_name)}} {{ucfirst($other_name ?? '')}} {{ucfirst($last_name)}}.

I would love to be considered to be an instructor on Treten Academy.

I have the following qualifications: 
{{$qualifications}}

I believe i am a good fit because:
{{$consideration}}

Kindly find attached my curriculum vitae.

Thanks. 

Regards,
<br>
{{ucfirst($first_name)}} {{ucfirst($other_name ?? '')}} {{ucfirst($last_name)}}
<br>
{{$phone_number}}
@endcomponent
