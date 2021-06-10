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

    public function getimages(Request $request){
        $pileItems = DB::table('piles_items')
            ->join('piles', 'piles_items.PileId', '=', 'piles.id')
            ->join('items', 'piles_items.ItemId', '=', 'items.id')
            ->where('GameId', $request->get('id'))
            ->get();
        return $pileItems;

    }

    public function join(Request $request)
    {
        $latestgame = DB::table('gamesession')->where('GameId',$request->get('id'))->first();
        $friends = DB::table('friends')->where('userId',$latestgame->UserId)
            ->where('name',Auth::user()->name.'#'.Auth::id())
            ->first();
        if ($friends == null ||$friends->state == 1){
            return redirect('dashboard');
        }

        $id = $request->get('id');
        $game = DB::table('games')->where('id',$id)->first();
        if($game->board != null) {
            $this->download($game->board);
        }
        $piles = DB::table('piles')->where('GameId', $id)->get();
        $pileItems = DB::table('piles_items')
            ->join('piles', 'piles_items.PileId', '=', 'piles.id')
            ->join('items', 'piles_items.ItemId', '=', 'items.id')
            ->where('GameId', $id)
            ->get();

        $owners = DB::table('piles')->where('GameId',$id)->distinct('owner')->get('owner');
        $cdice = DB::table('cdice')->where('GameId',$id)->orderByDesc('diceId')->get();
        $ndice = DB::table('ndice')->where('GameId',$id)->get();
        foreach ($pileItems as $image){
            $this->download($image->topsidelocation);
        }
        return view('session.index',compact('friends','pileItems','game','owners','piles','cdice','ndice'));
    }




    public function start(int $id)
    {
        DB::table('gamesession')->insert([
                'GameId' => $id,
                'UserId' => Auth::id(),
            ]
        );
        $game = DB::table('games')->where('id',$id)->first();
        if($game->board != null) {
            $this->download($game->board);
        }
        $piles = DB::table('piles')->where('GameId', $id)->get();
        $pileItems = DB::table('piles_items')
            ->join('piles', 'piles_items.PileId', '=', 'piles.id')
            ->join('items', 'piles_items.ItemId', '=', 'items.id')
            ->where('GameId', $id)
            ->get();

        $owners = DB::table('piles')->where('GameId',$id)->distinct('owner')->get('owner');
        $friends = DB::table('friends')->where('userId',Auth::id())->get();
        $cdice = DB::table('cdice')->where('GameId',$id)->orderByDesc('diceId')->get();
        $ndice = DB::table('ndice')->where('GameId',$id)->get();
        foreach ($pileItems as $image){
            $this->download($image->topsidelocation);
        }
        return view('session.index',compact('friends','pileItems','game','owners','piles','cdice','ndice'));
    }


}
