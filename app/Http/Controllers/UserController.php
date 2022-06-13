<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Master_friend_request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function request(Request $req){
      try {
        $data = [
          'requestor' => $req->email_requestor,
          'to'        => $req->email_receiver
        ];

        $check = Master_friend_request::where([
    			['email_requestor',$req->email_requestor],
    			['email_receiver',$req->email_receiver]
        ])
        ->first();

        if ($check) {
          if ($check->status == 'blocked') {
            return response()->json(['message' => 'Access denied']);
          }
          return response()->json(['message' => 'Data already exists']);
        }
        if ($req->email_requestor == $req->email_receiver) {
          return response()->json(['success' => false, 'data' => $data]);
        }

        $save = Master_friend_request::insert([
            'email_requestor' => $req->email_requestor,
            'email_receiver'  => $req->email_receiver,
            'status'          => 'pending'
        ]);

        if ($save) {
          return response()->json(['success' => true, 'message' => 'Friend successfully requested', 'data' => $data]);
        }

      }catch (\Exception $e) {
        DB::rollback();
        dd("Error. " . $e->getMessage());
        return false;
      }

    }

    public function status_action(Request $req){
      try {
        $status = $req->action;
        $data = [
          'requestor' => $req->email_requestor ?? '',
          'to'        => $req->email_receiver ?? ''
        ];

        $save = Master_friend_request::where('email_requestor', $req->email_requestor ?? '')->where('email_receiver', $req->email_receiver ?? '')->first();

        if ($save) {
          $save->status = $status;

          if ($save->save()) {
            return response()->json(['success' => true, 'message' => $status]);
          }
        }else{
          return response()->json(['message' => false]);
        }

      }catch (\Exception $e) {
        DB::rollback();
        dd("Error. " . $e->getMessage());
        return false;
      }
    }

    public function get_request(Request $req){
      try {
        $email = $req->email;

        $get_data = Master_friend_request::select('email_requestor as requestor','status')->where('email_receiver', $email)->get();

        if (count($get_data) > 0) {
          $data = [
            'requests' => $get_data
          ];
        }else{
          $data = 'Data not found';
        }

        return response()->json($data);

      }catch (\Exception $e) {
        DB::rollback();
        dd("Error. " . $e->getMessage());
        return false;
      }
    }

    public function get_friends(Request $req){
      try {
        $email = $req->email_requestor;

        $get_data = Master_friend_request::where('email_requestor',$email)->where('status' , 'accepted')->get();

        $datas = [];
        if (count($get_data) > 0) {
          foreach ($get_data as $value) {
            $email_receiver = $value->email_receiver;
            $datas[] = $email_receiver;
          }

          $data = [
            'friends' => $datas
          ];
        }else{
          $data = 'Data not found';
        }

        return response()->json($data);

      }catch (\Exception $e) {
        DB::rollback();
        dd("Error. " . $e->getMessage());
        return false;
      }
    }

    public function get_all_friend(Request $req){
      try {
        $data = Master_friend_request::where('status' , 'accepted')
        ->where('email_requestor',$req->friends[0])
        ->orWhere('email_requestor', $req->friends[1])
        ->groupBy('email_receiver')
        ->having(DB::raw('count(email_receiver)'), '>', 1)
        ->pluck('email_receiver');

        return response()->json(['success'=>true, 'friends' => $data, "count" => count($data)]);

      }catch (\Exception $e) {
        DB::rollback();
        dd("Error. " . $e->getMessage());
        return false;
      }
    }

}
