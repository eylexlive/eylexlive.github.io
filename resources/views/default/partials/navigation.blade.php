<div id="nav">
	<div class="logo">
		<a href="/">{{ MinecraftServer::name() }}</a>
	</div>

	<div class="widget">
		<div class="card user-card">
			<div class="card-block">
				<div class="avatar">
					<img src="{{ Auth::user()->getAvatar(60) }}" alt="User Avatar">
				</div>
				<div class="body">
					<span class="line">
						<strong>
							<a href="{{ route('account') }}">{{ Auth::user()->getDisplayName() }}</a>
						</strong>
					</span>
					
					@if ( !Auth::user()->game() || Auth::user()->game()->lastLogin() === 'firstlogin' )
						<span class="line text-muted">
							Sunucuya hiç girmediniz.
						</span>
					@elseif ( Auth::user()->game()->online() )
						<span class="line text-success">
							<i class="fa fa-circle"></i> çevrimiçi
						</span>							
					@else
						<span class="line text-muted">
							<i class="fa fa-circle"></i> {{ Auth::user()->game()->lastLogin() }}
						</span>
					@endif
				</div>
			</div>
			<div class="card-block footer text-muted">
				<div class="pull-left" data-toggle="tooltip" title="Türk Lirası">
					<i class="fa fa-turkish-lira"></i> {{ Auth::user()->getMoney() }}
				</div>
				<div class="pull-right" data-toggle="tooltip" title="Oyun Parası">
					<i class="fa fa-money"></i> {{ Auth::user()->game() && Auth::user()->game()->balance() ? Auth::user()->game()->balance()->format() : '0.00' }}
				</div>
			</div>
		</div>
	</div>

	<div class="widget" style="border-bottom: 0;">
		<button data-toggle="modal" data-target="#bugModal" class="btn btn-warning w-100 m-b-1">HATA BİLDİR</button>
		<ul class="navigation-menu">
			<li{!! Request::route()->getName() == 'home' ? ' class="active"' : '' !!}>
				<a href="{{ route('home') }}">Anasayfa</a>
			</li>
			<li{!! Request::route()->getName() == 'community.market' ? ' class="active"' : '' !!}>
				<a href="{{ route('community.market') }}">Topluluk Pazarı</a>
			</li>
			<li{!! Request::route()->getName() == 'account' ? ' class="active"' : '' !!}>
				<a href="{{ route('account') }}">Profilim</a>
			</li>
			<li{!! Request::route()->getName() == 'account.notifications' ? ' class="active"' : '' !!}>
				<a href="{{ route('account.notifications') }}">Bildirimlerim</a>
			</li>
			<li{!! Request::route()->getName() == 'users' ? ' class="active"' : '' !!}>
				<a href="{{ route('users') }}">Oyuncular</a>
			</li>
			<li{!! Request::route()->getName() == 'top.best' ? ' class="active"' : '' !!}>
				<a href="{{ route('top.best') }}">En İyiler</a>
			</li>
			<li{!! Request::route()->getName() == 'payment' ? ' class="active"' : '' !!}>
				<a href="{{ route('payment') }}">Kredi Yükle</a>
			</li>
			<li{!! Request::route()->getName() == 'upgrade' ? ' class="active"' : '' !!}>
				<a href="{{ route('upgrade') }}">Hesabımı Yükselt</a>
			</li>
			<li>
				<a href="{{ route('auth.signout') }}">Çıkış Yap</a>
			</li>
		</ul>
	</div>
</div>
