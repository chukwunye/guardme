<?php

namespace Responsive;

use Illuminate\Database\Eloquent\Model;
use DB;

class JobApplication extends Model {

	/*Fields definition
	id -> id of the application
	job_id -> id of the job against which application has been submitted
	applied_by -> id of the freelancer who applied on the job
	is_hired -> checks if user is hired or not. 1 means hired and 0 means not hired.
	application_description -> description of the submitted application
	completion_status -> tells us if hired application is complete or not (means done or not) 0 is the default value, 1 means complete and 2 means canceled.
	*/

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'job_applications';

	public function job() {
		return $this->belongsTo( Job::class, 'job_id' );
	}

	/**
	 * @param $job_id
	 *
	 * @return mixed
	 */
	public function is_applied( $job_id ) {
		$user_id = auth()->user()->id;
		$res     = DB::table( $this->table )
		             ->where( 'applied_by', $user_id )
		             ->where( 'job_id', $job_id )->get();

		return count( $res );
	}

	/**
	 * @param $job_id
	 *
	 * @return mixed
	 */
	public function is_hired( $job_id ) {
		$user_id = auth()->user()->id;
		$res     = DB::table( $this->table )
		             ->where( 'applied_by', $user_id )
		             ->where( 'is_hired', 1 )
		             ->where( 'job_id', $job_id )->get();

		return count( $res );
	}

	public function getJobApplications( $id ) {
		$user_id = auth()->user()->id;
		$res     = DB::table( $this->table . ' as ja' )
		             ->select( 'u.name as user_name',
			             'ja.application_description as description',
			             'ja.id',
			             'ja.is_hired',
			             'u.photo as photo',
			             'u.id as u_id',
			             'u.email as u_email',
			             'ja.created_at as applied_date',
			             'ja.applied_by',
			             'ja.completion_status'
		             )
		             ->join( 'security_jobs as sj', 'sj.id', '=', 'ja.job_id' )
		             ->join( 'users as u', 'u.id', '=', 'ja.applied_by' )
		             ->where( 'ja.job_id', $id )
		             ->where( 'sj.created_by', $user_id )->get();

		return $res;
	}

	public function getApplicationDetails( $application_id ) {
		$user_id = auth()->user()->id;
		$res     = DB::table( $this->table . ' as ja' )
		             ->select( 'u.name as user_name',
			             'ja.application_description as description',
			             'ja.id',
			             'sj.title as job_title',
			             'sj.description as job_description',
			             'ja.is_hired',
			             'ja.completion_status',
			             'ja.created_at as applied_date'

		             )
		             ->join( 'security_jobs as sj', 'sj.id', '=', 'ja.job_id' )
		             ->join( 'users as u', 'u.id', '=', 'ja.applied_by' )
		             ->where( 'ja.id', $application_id )
		             ->where( 'sj.created_by', $user_id )->get()->first();

		return $res;
	}


	public function getMyApplicationDetails( $application_id ) {
		$user_id = auth()->user()->id;
		$res     = DB::table( $this->table . ' as ja' )
		             ->select( 'u.name as user_name',
			             'ja.application_description as description',
			             'ja.id',
			             'sj.title as job_title',
			             'sj.description as job_description',
			             'ja.is_hired',
			             'ja.completion_status',
			             'ja.created_at as applied_date'
		             )
		             ->join( 'security_jobs as sj', 'sj.id', '=', 'ja.job_id' )
		             ->join( 'users as u', 'u.id', '=', 'ja.applied_by' )
		             ->where( 'ja.id', $application_id )
//		             ->where( 'ja.applied_by', $user_id )
                     ->first();

		return $res;
	}

	public function isEligibleToMarkHired( $application_id ) {
		$error_message                       = '';
		$status_code                         = 200;
		$ja_with_job                         = JobApplication::with( 'job' )
		                                                     ->where( 'id', $application_id )
		                                                     ->get()
		                                                     ->first();

		$job                                 = $ja_with_job->job;
		$total_number_of_freelancers         = $job->number_of_freelancers;
		$job_hired_applications              = JobApplication::where( 'is_hired', 1 )
		                                                     ->where( 'job_id', $job->id )
		                                                     ->get();
		$number_of_already_hired_freelancers = count( $job_hired_applications );
		$vacant_positions                    = $total_number_of_freelancers - $number_of_already_hired_freelancers;
		$user_id                             = auth()->user()->id;
		if ( ! intval( $vacant_positions ) ) {
			$status_code   = 500;
			$error_message = 'Sorry, All vacancies for this job are already filled.';
		}
		if ( $job->created_by != $user_id ) {
			$status_code   = 500;
			$error_message = 'You are not authorized to perform this action';
		}

		return [ 'error_message' => $error_message, 'status_code' => $status_code ];
	}

	public function isHired( $application_id ) {
		$results = DB::table( $this->table )
		             ->where( 'id', $application_id )
		             ->where( 'is_hired', 1 )->get();

		return count( $results );
	}

	public function getMyProposals() {
		$user_id = auth()->user()->id;
//		$res     = DB::table( $this->table . ' as ja' )
//		             ->select('sj.title','ja.id','sj.created_by',
//			             'ja.application_description as description',
//			             'ja.is_hired',
//			             'ja.id as application_id',
//			             'u.photo as photo',
//			             'sj.title as job_title',
//			             'sj.description as job_description',
//			             'sj.id as job_id',
//			             'ja.created_at as applied_date',
//			             'u.id as u_id',
//			             'u.name as user_name',
//			             'shp.shop_name',
//			             'shp.profile_photo',
//			             'transactions.amount',
//			             'sj.number_of_freelancers'
//		             )
//		             ->join( 'security_jobs as sj', 'sj.id', '=', 'ja.job_id' )
//		             ->join( 'users as u', 'u.id', '=', 'ja.applied_by' )
//		             ->join( 'shop as shp', 'sj.created_by', '=', 'shp.user_id' )
//		             ->leftJoin( 'transactions', 'ja.job_id', '=', 'transactions.job_id' )
//		             ->where( 'transactions.credit_payment_status', '=', 'funded' )
//		             ->where( 'ja.applied_by', $user_id )
//		             ->where( 'ja.completion_status', '!=', 2 )
//		             ->get()
//		             ->map( function ( $item, $key ) {
//			             if ( $item->amount ) {
//				             $item->amount = $item->amount / $item->number_of_freelancers;
//			             }
//
//			             return $item;
//		             } );

		$res = DB::table( $this->table . ' as ja' )
		         ->select( 'sj.title', 'ja.id', 'sj.created_by',
			         'ja.application_description as description',
			         'ja.is_hired',
			         'ja.id as application_id',
//			             'u.photo as photo',
			         'sj.title as job_title',
			         'sj.description as job_description',
			         'sj.id as job_id',
			         'ja.created_at as applied_date',
//			             'u.id as u_id',
//			             'u.name as user_name',
			         'shp.shop_name',
			         'shp.profile_photo',
			         'transactions.amount',
			         'sj.number_of_freelancers'
		         )
		         ->join( 'security_jobs as sj', 'sj.id', '=', 'ja.job_id' )
//		             ->join( 'users as u', 'u.id', '=', 'ja.applied_by' )
                 ->join( 'shop as shp', 'sj.created_by', '=', 'shp.user_id' )
		         ->leftJoin( 'transactions', 'ja.job_id', '=', 'transactions.job_id' )
		         ->where( 'transactions.type', '=', 'add_money' )
		         ->where( 'ja.applied_by', $user_id )
		         ->where( 'ja.completion_status', '!=', 2 )
		         ->get()
		         ->map( function ( $item, $key ) {
			         if ( $item->amount ) {
				         $item->amount = round( $item->amount * .74 );
				         $item->amount = $item->amount / $item->number_of_freelancers;
			         }

			         return $item;
		         } );


		return $res;
	}

	/**
	 * @param $application_id
	 *
	 * @return array
	 */
	public function getApplicantWorkHistory( $application_id ) {
		$work_history    = [];
		$job_application = JobApplication::find( $application_id );
		$res             = DB::table( $this->table . ' as ja' )
		                     ->select(
			                     'sj.title',
			                     'sj.id as job_id',
			                     'fb.message',
			                     'fb.appearance',
			                     'fb.punctuality',
			                     'fb.customer_focused',
			                     'fb.security_conscious'
		                     )
		                     ->join( 'users as u', 'u.id', '=', 'ja.applied_by' )
		                     ->join( 'security_jobs as sj', 'sj.id', '=', 'ja.job_id' )
		                     ->join( 'feedback as fb', 'fb.application_id', '=', 'ja.id' )
		                     ->where( 'ja.applied_by', $job_application->applied_by )->get();
		$sec_res         = DB::table( 'security_jobs_schedule as sjs' )
		                     ->select( 'start', 'end', 'sjs.job_id' )
		                     ->join( $this->table . ' as ja', 'ja.job_id', '=', 'sjs.job_id' )
		                     ->join( 'security_jobs as sj', 'sj.id', '=', 'sjs.job_id' )
		                     ->where( 'ja.applied_by', $job_application->applied_by )
		                     ->where( 'ja.is_hired', 1 )->get();
		$job_schedule    = [];
		if ( ! empty( $sec_res ) ) {
			foreach ( $sec_res as $key => $sec_item ) {
				$job_schedule[ $sec_item->job_id ][] = [ 'start' => $sec_item->start, 'end' => $sec_item->end ];
			}
		}
		$super_aggregate = 0;
		foreach ( $res as $k => $item ) {
			$job_date_range     = '';
			$appearance         = $item->appearance;
			$punctuality        = $item->punctuality;
			$customer_focused   = $item->customer_focused;
			$security_conscious = $item->security_conscious;
			$rating_aggregate   = ( $appearance + $punctuality + $customer_focused + $security_conscious ) / 4;
			$super_aggregate    += $rating_aggregate;
			if ( ! empty( $job_schedule[ $item->job_id ] ) ) {
				$current_job_schedule_array      = $job_schedule[ $item->job_id ];
				$current_job_schedule_start_date = $current_job_schedule_array[0]['start'];
				$current_job_schedule_end_date   = $current_job_schedule_array[ count( $current_job_schedule_array ) - 1 ]['end'];
				$job_date_range                  = date( 'd, M Y', strtotime( $current_job_schedule_start_date ) ) . ' to ' . date( 'd, M Y', strtotime( $current_job_schedule_end_date ) );
			}
			$work_history['project_ratings'][] = [
				'job_id'           => $item->job_id,
				'star_rating'      => $rating_aggregate,
				'feedback_message' => $item->message,
				'job_title'        => $item->title,
				'date_range'       => $job_date_range
			];
		}
		$total_feedbacks                  = count( $res ) > 0 ? count( $res ) : 1;
		$super_aggregate                  = $super_aggregate / $total_feedbacks;
		$work_history['aggregate_rating'] = number_format( $super_aggregate, 2 );

		return $work_history;
	}


	/**
	 * @param $applied_by
	 *
	 * @return array
	 */
	public function getApplicantWorkHistory_appliedby( $applied_by ) {
		$work_history = [];
		$res          = DB::table( $this->table . ' as ja' )
		                  ->select(
			                  'sj.title',
			                  'sj.id as job_id',
			                  'fb.message',
			                  'fb.appearance',
			                  'fb.punctuality',
			                  'fb.customer_focused',
			                  'fb.security_conscious'
		                  )
		                  ->join( 'users as u', 'u.id', '=', 'ja.applied_by' )
		                  ->join( 'security_jobs as sj', 'sj.id', '=', 'ja.job_id' )
		                  ->join( 'feedback as fb', 'fb.application_id', '=', 'ja.id' )
		                  ->where( 'ja.applied_by', $applied_by )->get();
		$sec_res      = DB::table( 'security_jobs_schedule as sjs' )
		                  ->select( 'start', 'end', 'sjs.job_id' )
		                  ->join( $this->table . ' as ja', 'ja.job_id', '=', 'sjs.job_id' )
		                  ->join( 'security_jobs as sj', 'sj.id', '=', 'sjs.job_id' )
		                  ->where( 'ja.applied_by', $applied_by )
		                  ->where( 'ja.is_hired', 1 )->get();
		$job_schedule = [];
		if ( ! empty( $sec_res ) ) {
			foreach ( $sec_res as $key => $sec_item ) {
				$job_schedule[ $sec_item->job_id ][] = [ 'start' => $sec_item->start, 'end' => $sec_item->end ];
			}
		}
		$super_aggregate = 0;
		foreach ( $res as $k => $item ) {
			$job_date_range     = '';
			$appearance         = $item->appearance;
			$punctuality        = $item->punctuality;
			$customer_focused   = $item->customer_focused;
			$security_conscious = $item->security_conscious;
			$rating_aggregate   = ( $appearance + $punctuality + $customer_focused + $security_conscious ) / 4;
			$super_aggregate    += $rating_aggregate;
			if ( ! empty( $job_schedule[ $item->job_id ] ) ) {
				$current_job_schedule_array      = $job_schedule[ $item->job_id ];
				$current_job_schedule_start_date = $current_job_schedule_array[0]['start'];
				$current_job_schedule_end_date   = $current_job_schedule_array[ count( $current_job_schedule_array ) - 1 ]['end'];
				$job_date_range                  = date( 'd, M Y', strtotime( $current_job_schedule_start_date ) ) . ' to ' . date( 'd, M Y', strtotime( $current_job_schedule_end_date ) );
			}
			$work_history['project_ratings'][] = [
				'job_id'           => $item->job_id,
				'star_rating'      => $rating_aggregate,
				'feedback_message' => $item->message,
				'job_title'        => $item->title,
				'date_range'       => $job_date_range
			];
		}
		$total_feedbacks                  = count( $res ) > 0 ? count( $res ) : 1;
		$super_aggregate                  = $super_aggregate / $total_feedbacks;
		$work_history['aggregate_rating'] = number_format( $super_aggregate, 2 );

		return $work_history;
	}
}
