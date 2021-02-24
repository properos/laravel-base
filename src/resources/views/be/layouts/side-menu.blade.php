<div class="main-menu menu-fixed menu-dark menu-accordion menu-shadow" data-scroll-to-active="true" style="z-index: 1100">
	<div class="main-menu-content">
		<ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
			<?php
				$files = glob(resource_path('views/be/layouts/menu/*.blade.php'));
				?>
				@foreach ($files as $file)
				@include('be.layouts.menu.'.basename($file, '.blade.php'))
			@endforeach
			<li id="public_page" class="nav-item"><a href="/"><i class="ft-corner-up-left"></i><span class="menu-title" data-i18n="">Go to Website</span></a>
			</li>
		</ul>
	</div>
</div>
<!-- /main menu content-->

{{-- //Badge EX --}} {{-- <span class="badge badge badge-primary badge-pill float-right mr-2">5</span> --}}