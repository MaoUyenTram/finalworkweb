<?php

namespace App\Http\Controllers;

use Hamcrest\Core\IsNull;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Kreait\Laravel\Firebase\Facades\Firebase;
use function PHPUnit\Framework\isNull;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getData(Request $request)
    {
        $files = File::files(public_path('uploads'));
        $imgs = [];
        $piles = DB::table('piles')->where('GameId', $request->get('id'))->get();
        $id = $request->get('id');
        $pileItems = DB::table('piles_items')
            ->join('piles', 'piles_items.PileId', '=', 'piles.id')
            ->join('items', 'piles_items.ItemId', '=', 'items.id')
            ->where('GameId', $request->get('id'))
            ->select('piles.id','items.id as itemId', 'items.name', 'piles_items.amount')
            ->get();

        foreach ($files as $file) {
            $imgs[] = $file->getRelativePathname();
        }


        $c = compact('id');
        if (count($imgs) > 0) {
            $c2 = compact('imgs');
            $c = array_merge($c2, $c);
        }
        if (!$piles->isEmpty()) {
            $c3 = compact('piles');
            $c = array_merge($c3, $c);
        }
        if(!$pileItems->isEmpty()) {
            $c = array_merge($c,compact('pileItems'));
        }

        return $c;
    }
    public function getSData(Request $request)
    {
        $piles = DB::table('piles')->where('GameId', $request->get('id'))->get();
        $id = $request->get('id');
        $ndice = DB::table('ndice')->where('GameId', $request->get('id'))->get()->count();
        $cdice = DB::table('cdice')->where('GameId', $request->get('id'))->orderByDesc('diceId')->first();

        $c = compact('id');

        if (!$piles->isEmpty()) {
            $c3 = compact('piles');
            $c = array_merge($c3, $c);
        }
            $c = array_merge($c,compact('ndice'));
        if($cdice != null) {
            $c = array_merge($c,compact('cdice'));
        }

        return $c;
    }

    public function upload($uuid,$orig)
    {
        $storage = Firebase::storage();
        $storageClient = $storage->getStorageClient();
        $bucket = $storage->getBucket();
        $bucket->upload(fopen(public_path('uploads/' . $orig), 'r'), ['name'=>$uuid.'.jpg']);
    }

    public function download($uuid)
    {
        $storage = Firebase::storage();
        $storageClient = $storage->getStorageClient();
        $bucket = $storage->getBucket();
        $object = $bucket->object($uuid . '.jpg');
        $stream = $object->downloadToFile(public_path('uploads/' . $uuid . '.jpg'));
    }

    public function delete($uuid){
        $storage = Firebase::storage();
        $storageClient = $storage->getStorageClient();
        $bucket = $storage->getBucket();
        $object = $bucket->object($uuid . '.jpg');
        $object->delete();
    }
}
