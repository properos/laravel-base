<!-- fixed-top-->
<style>
	::placeholder {
	/* Chrome, Firefox, Opera, Safari 10.1+ */
	color: #fff;
	opacity: 1;
	/* Firefox */
	}
	:-ms-input-placeholder {
	/* Internet Explorer 10-11 */
	color: #fff;
	}
	::-ms-input-placeholder {
	/* Microsoft Edge */
	color: #fff;
	}

	.main-menu.menu-dark .navigation>li>ul, .main-menu.menu-dark ul.menu-popout {
	background: #404e67b3!important;
	}
	.main-menu.menu-dark .navigation>li ul .active>a {
		color: #fff;
	}
	/* .main-menu.menu-dark .navigation>li ul li.active>a, .main-menu.menu-dark .navigation>li>a> ul li.hover>a, .main-menu.menu-dark .navigation>li ul li:hover>a {
	background-color: #11656c !important;
	} */
</style>
<nav class="header-navbar navbar-expand-md navbar navbar-with-menu fixed-top navbar-dark">
	<div class="navbar-wrapper">
		<div class="navbar-header">
			<ul class="nav navbar-nav flex-row">
				<li class="nav-item mobile-menu d-md-none mr-auto"><a class="nav-link nav-menu-main menu-toggle hidden-xs" href="#"><i class="ft-menu font-large-1"></i></a></li>
				<li class="nav-item mr-auto">
				<a class="navbar-brand" href="/admin/dashboard">
					<img class="brand-logo" alt="logo" src="/be/images/logo.png" style="width: 30px; height: auto">
					<span class="brand-text">Admin</span>
				</a>
				</li>
				<li class="nav-item d-none d-md-block float-right"><a class="nav-link modern-nav-toggle pr-0" data-toggle="collapse"><i class="toggle-icon ft-toggle-right font-medium-3 white" data-ticon="ft-toggle-right"></i></a></li>
				<li class="nav-item d-md-none"><a class="nav-link open-navbar-container" data-toggle="collapse" data-target="#navbar-mobile"><i class="fa fa-ellipsis-v"></i></a></li>
			</ul>
		</div>
		<div class="navbar-container content">
			<div id="navbar-mobile" class="navbar-collapse collapse" style="">
				<ul class="nav navbar-nav mr-auto float-left">
					<li class="nav-item d-none d-md-block"><a href="#" class="nav-link nav-menu-main menu-toggle hidden-xs"><i class="ft-menu"></i></a></li>
					<li class="nav-item d-none d-md-block"><a href="#" class="nav-link nav-link-expand"><i class="ficon ft-maximize"></i></a></li>
					<li class="nav-item nav-search"><a href="#" class="nav-link nav-link-search"><i class="ficon ft-search"></i></a>
						<div class="search-input">
							<input type="text" placeholder="Search..." class="input">
						</div>
					</li>
				</ul>
				<ul class="nav navbar-nav float-right">
					<li class="dropdown dropdown-notification nav-item">
						<a href="#" data-toggle="dropdown" class="nav-link nav-link-label"><i class="ficon ft-bell"></i>
						</a>
						<ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
							<li class="dropdown-menu-header text-center">
								<h6 class="dropdown-header m-0">
								<span class="grey darken-2">There are not notifications</span>
								</h6>
							</li>
						</ul>
					</li>
					<li class="dropdown dropdown-notification nav-item">
						<a href="#" data-toggle="dropdown" class="nav-link nav-link-label"><i class="ficon ft-mail"></i>
						</a>
						<ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
							<li class="dropdown-menu-header text-center">
								<h6 class="dropdown-header m-0">
									<span class="grey darken-2">There are not messages</span>
								</h6>
							</li>
						</ul>
					</li>
					<li class="dropdown dropdown-user nav-item">
						<a href="#" data-toggle="dropdown" class="dropdown-toggle nav-link dropdown-user-link" aria-expanded="false">
							<span class="avatar avatar-online">
								<img src="/be/app-assets/images/portrait/small/avatar-s-1.png" alt="avatar"><i></i></span>
							<span class="user-name">{{Auth::user()->firstname}} {{Auth::user()->lastname}}</span>
						</a>
						<div class="dropdown-menu dropdown-menu-right">
							<a class="dropdown-item" href="#" onclick="event.preventDefault();
								document.getElementById('logout-form').submit();">
								<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
								@csrf
								</form>
								<i class="ft-power"></i> {{ __('Logout') }}
							</a>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>
</nav>