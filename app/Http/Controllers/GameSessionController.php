<?php

namespace App\Http\Controllers;

use App\Events\change;
use App\Events\message;
use App\Events\places;
use App\Events\setOwners;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class GameSessionController extends Controller
{
    //events
    public function setowners(Request $request){
        broadcast(new setOwners($request->get('msg'),$request->get('data'),$request->get('id')));

    }

    public function changes(Request $request){
        broadcast(new change($request->get('msg'),$request->get('data'),$request->get('id')));
    }

    public function message(Request $request){
        broadcast(new message($request->get('msg'),$request->get('data'),$request->get('id')));

    }

    public function places(Request $request){
        broadcast(new places($request->get('msg'),$request->get('data'),$request->get('id')));

    }

    public function getalldata(Request $request){
        $id = $request->get('id');
        $game = DB::table('games')->where('id',$id)->first();
        if($game->board != null) {
            $this->download($game->board);
        }
        $pileItems = DB::table('piles_items')
            ->join('piles', 'piles_items.PileId', '=', 'piles.id')
            ->join('items', 'piles_items.ItemId', '=', 'items.id')
            ->where('GameId', $id)
            ->get();

        $cdice = DB::table('cdice')->where('GameId',$id)->orderByDesc('diceId')->get();
        $ndice = DB::table('ndice')->where('GameId',$id)->pluck('n')->toArray();
        foreach ($pileItems as $image){
            $this->download($image->topsidelocation);
        }
        $name = Auth::user()->name;
        return compact('pileItems','game','cdice','ndice','name');

    }

    public function join(Request $request)
    {
        $id = $request->get('id');
        $latestgame = DB::table('gamesession')->where('GameId',$request->get('id'))->first();
        $friend = DB::table('friends')->where('userId',$latestgame->UserId)
            ->where('name',Auth::user()->name)
            ->first();
        if ($friend == null || $friend->state == 0){
            return redirect('dashboard');
        }
        $game = DB::table('games')->where('id',$id)->first();
        $friends = DB::table('friends')->where('userId',Auth::id())->get();
        $owners = DB::table('piles')->where('GameId',$request->get('id'))->distinct('owner')->get('owner');
        $piles = DB::table('piles')->where('GameId', $id)->get();
        return view('session.index',compact('game','friends','owners','piles'));
    }




    public function start(int $id)
    {
        DB::table('gamesession')->insert([
                'GameId' => $id,
                'UserId' => Auth::id(),
            ]
        );
        $game = DB::table('games')->where('id',$id)->first();
        $friends = DB::table('friends')->where('userId',Auth::id())->get();
        $owners = DB::table('piles')->where('GameId',$id)->distinct('owner')->get('owner');
        $piles = DB::table('piles')->where('GameId', $id)->get();
        $pileItems = DB::table('piles_items')
            ->join('piles', 'piles_items.PileId', '=', 'piles.id')
            ->join('items', 'piles_items.ItemId', '=', 'items.id')
            ->where('GameId', $id)
            ->get();

        return view('session.index',compact('friends','owners','game','piles'));
    }


}
