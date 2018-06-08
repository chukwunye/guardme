<?php

namespace Responsive\Http\Controllers;


use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Responsive\FreelancerSetting;


class SettingController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */


	public function show() {
		$visibles = array();
		$temparr = array();
		$userids = DB::select('select id from users');
		$visibles = DB::select('select user_id from freelancer_settings');
		$query = '';
		if (count($visibles) > 0) {
			foreach ($visibles as $value) {
				$temparr[$value->user_id] = $value->user_id;
			}
		}
		foreach ($userids as $userid) {
			if (count($temparr) > 0) {
				if (!isset($temparr[$userid->id])) {
					DB::insert('insert into freelancer_settings(user_id, visible, created_at, updated_at) values ('.$userid->id.', 0, "'.date("Y-m-d H:i:s").'", "'.date("Y-m-d H:i:s").'");');
				}
			}	else {
				DB::insert('insert into freelancer_settings(user_id, visible created_at, updated_at) values ('.$userid->id.', 0, "'.date("Y-m-d H:i:s").'", "'.date("Y-m-d H:i:s").'");');
			}
		}
		if ( ! Auth::Check() ) {
			return redirect( '/' );
		}
		$visible = auth()->user()->freelancerSettings->visible;
		return view( 'setting', compact('visible') );
	}

	public function visibality() {


		if ( ! Auth::Check() ) {
			return redirect( '/' );
		}
		if ( auth()->user()->freelancerSettings->visible == 1 ) {
			DB::table( 'freelancer_settings' )
			  ->where( 'user_id', auth()->user()->id )
			  ->update( [ 'visible' => 0, 'updated_at' => date("Y-m-d H:i:s") ] );

			return response()->json( '102', 200 );
		} else {
			DB::table( 'freelancer_settings' )
			  ->where( 'user_id', auth()->user()->id )
			  ->update( [ 'visible' => 1, 'updated_at' => date("Y-m-d H:i:s") ] );

			return response()->json( '101', 200 );
		}
	}

}
