<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFriend;
use App\Models\Friend;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
     * @param StoreFriend $request
     * @return RedirectResponse
     */

    public function store(StoreFriend $request)
    {
        $request->validate([
            'name' => 'required|regex:/#/',
        ]);

        if (strpos($request->input('name'),' ')){
            return redirect()->back()->with('error', 'names may only contain letters, numbers, dashes and underscores');
        }
        $name = explode("#", $request->input('name'));
        if (!is_numeric($name[1])){
            return redirect()->back()->with('error', 'no such person');
        }

        if (DB::table('users')->where('name', $name)->where('id',$name[1])->exists()) {
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
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
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
     * @return RedirectResponse
     */
    public function destroy(Request $request)
    {
        $name = explode('#',$request->input('name'));

        if (DB::table('users')->where('name', $name)->where('id',$name[1])->exists()) {
            DB::table('friends')->where('name',$request->input('name'))->where('UserId',Auth::id())->delete();
        }
        return redirect()->back()->with('succes', 'removed succesfully');
    }
}
