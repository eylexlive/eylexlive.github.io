@extends($template . '.partials.app')

@section('title', 'Ölüm Detayları')

@section('breadcrumb', [
	[route('users'), 'Oyuncular'],
	[route('profile', ['player' => $user->username]), $user->getDisplayName()]
])

@section('container')
	<h3 class="m-b-1">{{ TurkishGrammar::get($user->getDisplayName(), 'i') }} öldürenler</h3>

	<div class="row">
		@if ( !$user->game()->playerDeaths('PLAYER', true) && !$from_monsters->count() && !$from_others->count() )
			<div class="col-lg-12">
				<div class="card">
					<div class="card-block">
						<span class="text-muted">
							Henüz {{ TurkishGrammar::get($user->getDisplayName('firstname'), 'i') }} öldürebilen kimse olmamış.<br>
						</span>
					</div>
				</div>
			</div>
		@endif

		@if ( $user->game()->playerDeaths('PLAYER', true) )
			<div class="col-lg-4">
				<div class="card">
					<div class="card-header">
						Oyuncular
						<small class="text-muted">{{ $user->game()->playerDeaths('PLAYER', true) }}</small>
					</div>
					<div class="card-block">
						<ul class="list-group-user">
							@foreach ( $from_players as $from_player )
								<li class="list-group-user-item clearfix">
									<div class="avatar">
										<img src="{{ $from_player->killer()->getAvatar(40) }}" alt="User Avatar">
									</div>
									<div class="content">
										<div class="title">
											<a href="{{ route('profile', ['player' => $from_player->killer()->username]) }}">
												<strong>{{ $from_player->killer()->getDisplayName() }}</strong>
											</a>
										</div>
										<div class="body">
											{{ $from_player->total }} kez
										</div>
									</div>
								</li>
							@endforeach
						</ul>
					</div>
				</div>
			</div>
		@endif
		
		@if ( $from_monsters->count() )
			<div class="col-lg-4">
				<div class="card">
					<div class="card-header">
						Yaratıklar
						<small class="text-muted">{{ $user->game()->playerDeaths('MONSTERS', true) }}</small>
					</div>
					<div class="card-block">
						<ul class="list-group-user">
							@foreach ( $from_monsters as $from_monster )
								<li class="list-group-user-item clearfix">
									<div class="avatar">
										<img src="{{ $from_monster->getMobAvatar(40) }}" alt="User Avatar">
									</div>
									<div class="content">
										<div class="title">
											<strong>@lang('minecraft.monsters.' . $from_monster->cause)</strong>
										</div>
										<div class="body">
											{{ $from_monster->value }} kez
										</div>
									</div>
								</li>
							@endforeach
						</ul>
					</div>
				</div>
			</div>
		@endif
		

		@if ( $from_others->count() )
			<div class="col-lg-4">
				<div class="card">
					<div class="card-header">
						Diğer Sebepler
						<small class="text-muted">{{ $user->game()->playerDeaths('OTHERS', true) }}</small>
					</div>
					<div class="card-block">
						<ul class="list-group-user">
							@foreach ( $from_others as $from_other )
								<li class="list-group-user-item clearfix">
									<div class="avatar">
										<img src="global/images/minecraft/{{ $from_other->cause }}.png" alt="User Avatar">
									</div>
									<div class="content">
										<div class="title">
											<strong>@lang('minecraft.others.' . $from_other->cause)</strong>
										</div>
										<div class="body">
											{{ $from_other->value }} kez
										</div>
									</div>
								</li>
							@endforeach
						</ul>
					</div>
				</div>
			</div>
		@endif
	</div>
@stop