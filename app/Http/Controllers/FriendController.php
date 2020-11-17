<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FriendController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $friends = DB::table('friends')->where('UserId', Auth::id())->where('state', true)->get('name');
        $bans = DB::table('friends')->where('UserId', Auth::id())->where('state', false)->get('name');
        return view('friends.index', compact('friends', 'bans'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|regex:/#/',
        ]);
        $name = explode("#", $request->input('name'));

        if (DB::table('users')->where('name', $name[0])->where('id',$name[1])->exists()) {
            if (DB::table('friends')->where('name',$request->input('name'))->where('UserId',Auth::id())->doesntExist()) {
                DB::table('friends')->insert([
                    'UserId' => Auth::id(),
                    'name' => $request->input('name'),
                    'state' => $request->input('friend'),
                ]);
                return redirect()->route('friends-and-bans')->with('succes', 'successfully added');
            }
            return redirect()->back()->with('error', 'this person is already in your friend or ban list');
        }
        return redirect()->route('friends-and-bans')->with('error', 'no such person');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Friend $friend
     * @return \Illuminate\Http\Response
     */
    public function show(Friend $friend)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $friend = Friend::where('name',$request->input('name'))->where('UserId',Auth::id())->first();

        $friend->state = $request->input('state');

        $friend->save();

        return redirect()->route('friends-and-bans')->with('succes', 'successfully moved');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $name = explode('#',$request->input('name'));

        if (DB::table('users')->where('name', $name[0])->where('id',$name[1])->exists()) {
            DB::table('friends')->where('name',$request->input('name'))->where('UserId',Auth::id())->delete();
        }
        return redirect()->back()->with('succes', 'removed succesfully');
    }
}
