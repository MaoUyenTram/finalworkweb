<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $games = DB::table('games')->where('UserId',Auth::id())->get();
        return view('games.index')->with('games',$games);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('games.upload');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        DB::table('games')->insert([
            'UserId' => Auth::id(),
            'name' => $request->input('name'),
            'settingText' => null,
            ]);

        $idselect = DB::table('games')->where('UserId',Auth::id())->orderBy('id','desc')->first();
        $id = $idselect->id;

        return view('piles.index')->with('id',$id);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function show(Game $game)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function edit(Game $game)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Game  $game
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Game $game)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $gameId
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $gameId)
    {
        DB::table('games')->where('id',$gameId)->delete();
        return redirect()->back()->with('succes', 'removed succesfully');
    }
}
