<?php

namespace Webcraft\Http\Controllers;

use Auth;
use Response;

use Webcraft\Models\User;
use Webcraft\Notifications\AddFriend;
use Webcraft\Notifications\AcceptFriend;

use Illuminate\Http\Request;

class FriendController extends Controller
{
	public function postAddFriend(Request $request)
	{
		$user = User::find($request->input('id'));

		if ( $user === null ) {
			return \Response::json(['error' => 'Arkadaş olarak eklemek istediğiniz oyuncuyu bulamadık.']);
		}

		if ( Auth::id() === $user->id ) {
			return Response::json(['error' => 'Kendinize istek gönderemezsiniz.']);
		}

		if ( Auth::user()->hasFriendRequestPending($user) || $user->hasFriendRequestPending(Auth::user()) ) {
			return Response::json(['error' => 'Arkadaşlık isteği zaten gönderilmişti.']);
		}

		if ( Auth::user()->isFriendsWith($user) ) {
			return Response::json(['error' => 'Siz zaten arkadaşsınız.']);
		}

		Auth::user()->addFriend($user);

		$user->notify(new AddFriend(Auth::user()));

		return Response::json(['success' => true]);
	}

	public function postAcceptFriend(Request $request)
	{
		$user = User::find($request->input('id'));

		if ( $user === null ) {
			return Response::json(['error' => 'Arkadaşlığını kabul etmek istediğiniz oyuncuyu bulamadık.']);
		}

		if ( !Auth::user()->hasFriendRequestReceived($user) ) {
			return Response::json(['error' => 'Bunu yapmayın.']);
		}

		Auth::user()->acceptFriendRequest($user);

		$user->notify(new AcceptFriend(Auth::user()));

		return Response::json(['success' => true]);
	}

	public function postDeleteFriend(Request $request)
	{
		$user = User::find($request->input('id'));

		if ( $user === null ) {
			return Response::json(['error' => 'Arkadaşlıktan çıkartmak istediğiniz oyuncuyu bulamadık.']);
		}

		Auth::user()->deleteFriend($user);

		return Response::json(['success' => true]);
	}
}