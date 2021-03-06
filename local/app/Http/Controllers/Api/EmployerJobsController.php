<?php

namespace Responsive\Http\Controllers\Api;

use Responsive\Feedback;
use Responsive\Http\Traits\JobsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Responsive\Http\Controllers\Controller;
use Responsive\Job;
use Responsive\JobApplication;
use Responsive\Ticket;
use Responsive\TicketMessage;
use Responsive\Transaction;
use Responsive\User;
use Responsive\Businesscategory;
use Responsive\SecurityCategory;
use Responsive\Events\JobHiredApplicationMarkedAsComplete;
use Responsive\Events\AwardJob;
use Carbon\Carbon;

class EmployerJobsController extends Controller {


	/**
	 * @param $id
	 *$id= job id
	 *
	 * @return mixed
	 */
	public function JobApplications( $id ) {


		$job = auth()->user()->jobs()->where( 'id', $id )->get();
		if ( count( $job ) == 0 ) {
			return response()->json( 403 );
		}

		$applications = DB::table( 'job_applications' )
		                  ->where( 'job_id', $id )
		                  ->leftJoin( 'users', 'job_applications.applied_by', '=', 'users.id' )
		                  ->select( 'job_applications.id', 'job_applications.applied_by', 'users.name', 'users.photo', 'job_applications.created_at', 'job_applications.is_hired', 'job_applications.completion_status', 'job_applications.application_description' )
		                  ->get();

		return response()->json( $applications, 200 );

	}

	public function awardedJobs() {
		if ( auth()->user()->admin != 0 ) {
			return response()->json( 403 );
		}
		$ID          = auth()->user()->id;
		$awardedJobs = DB::table( 'security_jobs' )
		                 ->where( 'created_by', $ID )
		                 ->Join( 'job_applications', 'security_jobs.id', '=', 'job_applications.job_id' )
		                 ->where( 'is_hired', 1 )
		                 ->rightJoin( 'transactions', 'security_jobs.id', '=', 'transactions.job_id' )
		                 ->where( 'transactions.credit_payment_status', '=', 'funded' )
		                 ->select( 'job_applications.id as application_id', 'job_applications.job_id', 'security_jobs.title', 'transactions.amount', 'job_applications.updated_at' )
		                 ->get();

		return response()->json( $awardedJobs, 200 );
	}


	public function JobDecline( $application_id ) {

		if ( auth()->user()->admin != 0 ) {
			return response()->json( 403 );
		}
		$ID         = auth()->user()->id;
		$aplication = JobApplication::find( $application_id );


		$jobauth = auth()
			->user()
			->jobs()
			->where( 'id', $aplication->job_id )
			->get();

		if ( count( $jobauth ) === 0 ) {
			return response()->json( 403 );
		}
		$aplication->is_hired = false;
		$aplication->save();

		return response()->json( [ 'cancel' => '200' ], 200 );
	}

	public function invoice( Request $request, $job_id ) {

		$id = $job_id;

		$user    = auth()->user();
		$user_id = auth()->user()->id;
		$balance = 0;

		$from             = array();
		$from             = $user;
		$from->date       = Carbon::now();
		$all_transactions = array();

		if ( ! empty( $user_id ) ) {
			if ( $user->admin == 2 ) {
				$all_transactions = DB::select( 'select security_jobs.title, transactions.id, sum(transactions.amount) as amount, transactions.created_at, security_jobs.number_of_freelancers, transactions.credit_payment_status as status from security_jobs, transactions where transactions.job_id = security_jobs.id and transactions.status = 1 and transactions.type = "job_fee" and security_jobs.id = ' . $id . ' group by transactions.type' );
			} else if ( $user->admin == 0 ) {

				$all_transactions = DB::select( 'select transactions.title, transactions.id, transactions.created_at, sum(transactions.amount) as amount, security_jobs.number_of_freelancers, transactions.credit_payment_status as status, transactions.type from security_jobs, transactions where transactions.job_id = security_jobs.id and transactions.credit_payment_status in ("paid", "funded") and security_jobs.id = ' . $id . ' group by transactions.type' );

				$applied_by = JobApplication::select( 'applied_by', 'users.name' )
				                            ->where( 'job_id', $id )
				                            ->join( 'users', 'applied_by', '=', 'users.id' )
				                            ->get();

				foreach ( $all_transactions as $key => $transactions ) {
					if ( $transactions->title == 'Job Fee' ) {
						$transactions->user_id = $applied_by;
					}
					$balance = $transactions->amount + $balance;
				}
			}
		}
		if ( ! empty( $all_transactions ) ) {
			if ( $user->admin == 2 ) {
				if ( $request->has( 'download' ) ) {
					$pdf = PDF::loadView( 'invoice-freelancer', compact( 'all_transactions', 'balance', 'from', 'id' ) );

					return $pdf->download( 'invoice.pdf' );
				}

				return view( 'invoice-freelancer', compact( 'all_transactions', 'balance', 'from', 'id' ) );
			} else if ( $user->admin == 0 ) {
				if ( $request->has( 'download' ) ) {
					$pdf = PDF::loadView( 'invoice-employer', compact( 'all_transactions', 'balance', 'from', 'id' ) );

					return $pdf->download( 'invoice.pdf' );
				}

				return response()->json( [
					'all_transactions' => $all_transactions,
					'balance'          => $balance,
					'from'             => $from,
					'job_id'           => $id
				] );
			}
		}

		return response()->json( [ $all_transactions, $balance, $from, $id ] );

//		return view( 'invoice-freelancer', compact( 'all_transactions', 'balance', 'from', 'id' ) );
	}

	public function awardTo( $application_id ) {
		$application = JobApplication::find( $application_id );

		$job = Job::where( 'id', $application->job_id )
		          ->where( 'created_by', auth()->user()->id )
		          ->get();
		if ( count( $job ) !== 1 ) {
			return response()->json( 403 );
		}
		$application->is_hired = true;
		$application->save();

		return response()->json( [ 'application_id' => $application_id, 'user_id' => $application->applied_by ], 200 );
	}

	public function awardedOpenJobs() {

		if ( auth()->user()->admin != 0 ) {
			return response()->json( 403 );
		}

		$ID          = auth()->user()->id;
		$awardedJobs = DB::table( 'security_jobs' )
		                 ->where( 'created_by', $ID )
		                 ->Join( 'job_applications', 'security_jobs.id', '=', 'job_applications.job_id' )
		                 ->where( 'is_hired', 1 )
		                 ->where( 'security_jobs.status', 1 )
//		                 ->rightJoin( 'transactions', 'security_jobs.id', '=', 'transactions.job_id' )
//		                 ->where( 'transactions.credit_payment_status', '=', 'funded' )
                         ->select( 'job_applications.id as application_id', 'job_applications.job_id', 'security_jobs.title as job_title' )
		                 ->get();

		return response()->json( $awardedJobs, 200 );
	}

	public function makeDisput( Request $request, $ja_id ) {
		// TODO : need to sanatize
		$DispuTApplication                    = JobApplication::find( $ja_id );
		$DispuTApplication->completion_status = 2;
		$DispuTApplication->save();


		$newTicket                 = new Ticket();
		$newTicket->user_id        = auth()->user()->id;
		$newTicket->responsible_id = 0;
		$newTicket->category_id    = 3;
		$newTicket->title          = $request->job_title;
		$newTicket->status         = 0;
		$newTicket->state          = 1;
		$newTicket->job_id         = $DispuTApplication->job_id;
		$newTicket->save();

		$newTicketMess                   = new TicketMessage();
		$newTicketMess->ticket_id        = $newTicket->id;
		$newTicketMess->user_id          = auth()->user()->id;
		$newTicketMess->message          = "Dispute for $request->job_title with $request->f_name";
		$newTicketMess->date_time_create = Carbon::now();
		$newTicketMess->save();

		return response()->json( '101', 200 );

	}
}
