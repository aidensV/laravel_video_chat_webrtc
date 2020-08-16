<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VideoChatController extends Controller
{
    public function index(Request $request){
        $user = $request->user();
        $others = \App\User::where('id','!=',$user->id)->pluck('name','id');
        return view('video_chat.index')->with([
            'user'=>collect($request->user()->only(['id','name'])),'others' => $others
        ]);
    }

    public function auth(Request $request){
        $user = $request->user();
        $socket_id =$request->socket_id;
        $channer_name = $request->channel_name;
        $pusher = new Pusher(
            confiq('broadcasting.connections.pusher.key'),
            confiq('broadcasting.connections.pusher.secret'),
            confiq('broadcasting.connections.pusher.app_id'),
            [
                'cluster' => confiq('broadcasting.connections.pusher.options.cluster'),
                'encrypted' => true
            ]);

        return response($pusher->presence_auth($channer_name,$socket_id,$user->id));
    }
}
