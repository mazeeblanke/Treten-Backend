@component('mail::message')

Hello,

You have been invited to join Treten Academy as an {{ ucfirst($role) }}
<br>
<br>
Click on the button below to get started.

@component('mail::panel')
		@component('mail::button', ['url' => config('app.frontend_url').'/t/invitation/'.$token])
			Get Started
		@endcomponent
	@endcomponent
Regards,
<br>
Treten Academy
@endcomponent
