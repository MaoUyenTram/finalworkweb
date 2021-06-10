<?php

namespace App\Http\Controllers;

use App\Models\Pile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Kreait\Laravel\Firebase\Facades\Firebase;

class ItemController extends Controller
{


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function store(Request $request)
    {
        $check = DB::table('piles')->where('id', $request->get('cId'))->exists();
        if ($check) {
            Session::put('cVal',$request->get('cId'));
            $uuid = (String)Str::uuid();
            $this->upload($uuid, $request->get('originalname'));
            DB::table('items')->insert([
                    'name' => $request->input('name'),
                    'topsidelocation' => $uuid,
                ]
            );
            $itemId = DB::table('items')->where('name', $request->get('name'))->where('topsidelocation', $uuid)->first()->id;

            DB::table('piles_items')->insert([
                'PileId' => $request->get('cId'),
                'ItemId' => $itemId,
                'amount' => $request->get('amount'),
            ]);
        }
        return view('piles.index', $this->getData($request));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function destroy(Request $request)
    {

        $itemName = DB::table('items')
            ->where('id', $request->get('itemId'))
            ->first();
        $this->delete($itemName->topsidelocation);
        DB::table('items')->where('id',$request->get('itemId'))->delete();

        return view('piles.index',$this->getData($request));
    }

}
