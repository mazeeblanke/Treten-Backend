@component('mail::message')

Hello Team,

A student, {{ $enrollment['user']['first_name'] }}  {{ $enrollment['user']['last_name'] }} just enrolled for {{ $enrollment['course']['title']  }}

Regards,

@endcomponent
