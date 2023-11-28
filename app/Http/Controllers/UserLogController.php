<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class UserLogController extends Controller
{
    //
    public static function logCreate (string $id){
        $message = 'You have created new request';
        DB::insert('insert into user_logs (user_id, message, log_type, created_at)
        values(?, ?, ?, ?)',
        [$id, $message, 'success', Carbon::now('Asia/Ho_Chi_Minh')]);
    }
    public static function logCheck (string $id){
        $message = 'You have new request need to check';
        DB::insert('insert into user_logs (user_id, message, log_type, created_at)
        values(?, ?, ?, ?)',
        [$id, $message, 'warning', Carbon::now('Asia/Ho_Chi_Minh')]);
    }

    public static function logApprove (string $id){
        $message = 'You have approved a request';
        DB::insert('insert into user_logs (user_id, message, log_type, created_at)
        values(?, ?, ?, ?)',
        [$id, $message, 'success', Carbon::now('Asia/Ho_Chi_Minh')]);
    }
    public static function logComplete (string $id){
        $message = 'Your request has been completed';
        DB::insert('insert into user_logs (user_id, message, log_type, created_at)
        values(?, ?, ?, ?)',
        [$id, $message, 'success', Carbon::now('Asia/Ho_Chi_Minh')]);
    }

    public static function logRevise (string $id){
        $message = 'You had submitted a request for revision';
        DB::insert('insert into user_logs (user_id, message, log_type, created_at)
        values(?, ?, ?, ?)',
        [$id, $message, 'warning', Carbon::now('Asia/Ho_Chi_Minh')]);
    }
    public static function logRedo (string $id){
        $message = 'You have request need to revise';
        DB::insert('insert into user_logs (user_id, message, log_type, created_at)
        values(?, ?, ?, ?)',
        [$id, $message, 'warning', Carbon::now('Asia/Ho_Chi_Minh')]);
    }
    public static function logResend (string $id){
        $message = 'You have re-send a request';
        DB::insert('insert into user_logs (user_id, message, log_type, created_at)
        values(?, ?, ?, ?)',
        [$id, $message, 'success', Carbon::now('Asia/Ho_Chi_Minh')]);
    }

    public static function logReject (string $id){
        $message = 'You have Rejected a request';
        DB::insert('insert into user_logs (user_id, message, log_type, created_at)
        values(?, ?, ?, ?)',
        [$id, $message, 'warning', Carbon::now('Asia/Ho_Chi_Minh')]);
    }
    public static function logFail (string $id){
        $message = 'Your request has been Rejected';
        DB::insert('insert into user_logs (user_id, message, log_type, created_at)
        values(?, ?, ?, ?)',
        [$id, $message, 'warning', Carbon::now('Asia/Ho_Chi_Minh')]);
    }


    public static function logNewSys (string $id){
        
    }
}
