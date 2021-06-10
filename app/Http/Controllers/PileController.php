<?php

namespace App\Http\Controllers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function index()
    {
        return view('piles.index');
    }

    public function settings(Request $request){
        return view('piles.settings',$this->getSData($request));
    }

    public function uploadboard(Request $request){

        $uuid = (String)Str::uuid();
        $this->upload($uuid, $request->get('name'));
        DB::table('items')->insert([
                'name' => $request->input('name'),
                'topsidelocation' => $uuid,
            ]
        );
        DB::table('games')->where('id',$request->get('id'))->update(['board'=>$uuid]);

        return view('piles.index',$this->getData($request));
    }

    public function setndice(Request $request){

        DB::table('ndice')->insert([
                'GameId' => $request->input('id'),
                'n' => $request->input('dxnormal'),
            ]
        );
        return view('piles.settings',$this->getsData($request));
    }

    public function setcdice(Request $request){

        DB::table('cdice')->insert([
                'GameId' => $request->input('id'),
                'name' => $request->input('name'),
                'weight' => $request->input('weight'),
                'diceId'=> $request->input('diceId'),

            ]
        );
        return view('piles.settings',$this->getsData($request));
    }

    public function destroydice(Request $request){
        DB::table('cdice')->where('GameId',$request->get('id'))->delete();
        DB::table('ndice')->where('GameId',$request->get('id'))->delete();
    }


    public function extractimg($originalFile,$n){
        $response = Http::attach('image', file_get_contents('storage/' . $originalFile), $originalFile)->attach('n', $n)
            ->post('http://127.0.0.1:5000/img');
        $image = str_replace('data:image/jpeg;base64,', '', $response);
        $image = str_replace(' ', '+', $image);
        $fn = strval($n) . $originalFile;

        File::put('uploads/' . $fn, base64_decode($image));
        return $fn;
    }

    public function createpiles(Request $request)
    {
        if($request->hasfile('images')) {
            foreach ($request->file('images') as $file) {
                $originalFile = $file->getClientOriginalName();
                $file->move('storage/', $originalFile);

                $this->extractimg($originalFile,64);
                $this->extractimg($originalFile,128);
            }
        }
        return view('piles.index',$this->getData($request));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $id)
    {
        dd($id, $request);
        return view('piles.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function store(Request $request)
    {
        DB::table('piles')->insert([
            'GameId' => $request->get('id'),
            'name' => $request->input('name'),
            'private' => $request->get('type'),
            'visibility' => $request->get('vis'),
        ]);


        return view('piles.index',$this->getData($request));

    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return void
     */
    public function show(Request $request)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function update(Request $request,int $id)
    {
        $uuid = (String)Str::uuid();
        $this->upload($uuid, $request->get('name'));
        DB::table('items')->insert([
                'name' => $request->input('name'),
                'topsidelocation' => $uuid,
            ]
        );
        DB::table('piles')->where('id',$request->get('cId'))->update(['image'=>$uuid]);

        return view('piles.index',$this->getData($request));
    }

    public function addowner(Request $request)
    {
        DB::table('piles')->where('id',$request->get('pileid'))->update(['owner'=>$request->get('owner')]);

        File::cleanDirectory('storage');
        File::cleanDirectory('uploads');

        return response()->json(['success'=>true,'url'=> route('games')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function destroy(Request $request)
    {

        $itemNames = DB::table('piles_items')
            ->join('items', 'piles_items.ItemId', '=', 'items.id')
            ->where('piles_items.PileId',$request->get('pileId'))
            ->select('topsidelocation')
            ->get();
        foreach ($itemNames as $itemName){
            $this->delete($itemName->topsidelocation);
        }

        DB::table('piles')->where('id',$request ->get('pileId'))->delete();


        return view('piles.index',$this->getData($request));
    }
}
