<div class="sidebar-wrapper">
	<div>
		<div class="logo-wrapper">
			<a href="{{route('home')}}"><img class="img-fluid for-light" style="max-width:80%" src="{{asset('assets/images/header/HappyTexting.png')}}" alt=""><img class="img-fluid for-dark" style="max-width:80%" src="{{asset('assets/images/header/HappyTexting.png')}}" alt=""></a>
			<div class="back-btn"><i class="fa fa-angle-left"></i></div>
			{{-- <div class="toggle-sidebar"><i class="status_toggle middle sidebar-toggle" data-feather="grid"> </i></div> --}}
		</div>
		{{-- <div class="logo-icon-wrapper"><a href="{{route('/')}}"><img class="img-fluid" src="{{asset('assets/images/logo/logo-icon.png')}}" alt=""></a></div> --}}
		<nav class="sidebar-main">
			<div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
			<div id="sidebar-menu">
				<ul class="sidebar-links" id="simple-bar">
					<li class="back-btn">
						{{-- <a href="{{route('/')}}"><img class="img-fluid" src="{{asset('assets/images/logo/logo-icon.png')}}" alt=""></a> --}}
						<div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
					</li>
					<li class="sidebar-list">
						<a class="sidebar-link sidebar-title {{request()->route()->getPrefix() == '/dashboard' ? 'active' : '' }}" href="{{route('home')}}"><i data-feather="home"></i><span class="lan-3">Dashboard</span>
						</a>

					</li>
					 <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav {{ Route::currentRouteName()=='users.index' ? 'active' : '' }}" href="{{route('users.index')}}"><i data-feather="users"> </i><span>Admins</span></a></li>
					 <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav {{ Route::currentRouteName()=='clients.index' ? 'active' : '' }}" href="{{route('clients.index')}}"><i data-feather="users"> </i><span>Clients</span></a></li>
					 <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav {{ Route::currentRouteName()=='contacts.index' ? 'active' : '' }}" href="{{route('contacts.index')}}"><i data-feather="users"> </i><span>Contacts</span></a></li>
					 <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav {{ Route::currentRouteName()=='textwords.index' ? 'active' : '' }}" href="{{route('textwords.index')}}"><i data-feather="users"> </i><span>Textwords</span></a></li>
                     <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav {{ Route::currentRouteName()=='campaigns.index' ? 'active' : '' }}" href="{{route('campaigns.index')}}"><i data-feather="users"> </i><span>Sent Campaigns</span></a></li>
                     <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav {{ Route::currentRouteName()=='plans.index' ? 'active' : '' }}" href="{{route('plans.index')}}"><i data-feather="dollar-sign"> </i><span>Plans</span></a></li>
                     <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav {{ Route::currentRouteName()=='clients.subscribe.create' ? 'active' : '' }}" href="{{route('clients.subscribe.create')}}"><i data-feather="credit-card"> </i><span>Subscribe</span></a></li>
					 <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav {{ Route::currentRouteName()=='activity.index' ? 'active' : '' }}" href="{{route('activity.index')}}"><i data-feather="activity"> </i><span>Activity Log</span></a></li>
					 <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav {{ Route::currentRouteName()=='users.feedback' ? 'active' : '' }}" href="{{route('users.contact-us')}}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg><span>Contact Us</span></a></li>
					 <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav {{ Route::currentRouteName()=='phone-number.index' ? 'active' : '' }}" href="{{route('phone-number.index')}}"><i data-feather="phone"> </i><span>AWS Phones</span></a></li>
					 <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav {{ Route::currentRouteName()=='phone-number.twilo' ? 'active' : '' }}" href="{{route('phone-number.twilo')}}"><i data-feather="phone"> </i><span>Twilo Phones</span></a></li>
					 <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav {{ Route::currentRouteName()=='settings.edit' ? 'active' : '' }}" href="{{route('settings.edit')}}"><i data-feather="settings"> </i><span>Setting</span></a></li>
					 <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav {{ Route::currentRouteName()=='privacy.set' ? 'active' : '' }}" href="{{route('privacy.set')}}"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-alert-triangle"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg> </i><span>Privacy and Policy</span></a></li>
                     <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav {{ Route::currentRouteName()=='terms.index' ? 'active' : '' }}" href="{{route('terms.index')}}"><i data-feather="activity"> </i><span>Terms and Conditions</span></a></li>
                     <li class="sidebar-list"><a class="sidebar-link sidebar-title link-nav {{ Route::currentRouteName()=='about.index' ? 'active' : '' }}" href="{{route('about.index')}}"><i data-feather="activity"> </i><span>About</span></a></li>
                </ul>
			</div>
			<div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
		</nav>
	</div>
</div>
