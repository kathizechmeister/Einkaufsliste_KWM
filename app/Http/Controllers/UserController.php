<?php

namespace App\Http\Controllers;
use App\Comment;
use App\Item;
use App\Shoppinglist;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(){
        $shoppinglists = Shoppinglist::with(['items', 'user','helper', 'comments'])->get();
        return $shoppinglists;
        /*$shoppinglists = Shoppinglist:: all ();
        return view( 'shoppinglists.index' , compact ( 'shoppinglists' ));*/
    }
    public function show($shoppinglist){
        $shoppinglist = Shoppinglist::find($shoppinglist);
        return $shoppinglist;
       // return view( 'shoppinglists.show' , compact ( 'shoppinglist' ));
    }

    public function save(Request $request):JsonResponse{
        $request = $this->parseRequest($request);

        DB::beginTransaction();
        try {
            $shoppinglist = Shoppinglist::create($request->all());
            if (isset($request['items']) && is_array($request['items'])){
                foreach ($request['items'] as $itm){
                    $item = Item::firstOrNew(['description'=>$itm['description'],
                        'amount'=>$itm['amount'], 'maxprice'=>$itm['maxprice']]);
                    $shoppinglist->items()->save($item);
                }

            }
           if (isset($request['comments']) && is_array($request['comments'])){
                foreach ($request['comments'] as $comm){
                    $comment = Comment::firstOrNew(['commenttext'=>$comm['commenttext']]);
                    $shoppinglist->comments()->save($comment);
                }
            }
            DB::commit();
           return response()->json($shoppinglist,201);

        }
        catch (\Exception $e){
            DB::rollBack();
            return response()->json("saving shoppinglist failed: ". $e->getMessage(), 402);
        }
    }

    private function parseRequest(Request $request):Request
    {
        $date = new \DateTime($request->published);
        $request['deadline'] = $date;
        return $request;
    }

    public function findBySearchTerm ( string $searchTerm ) {
        $shoppinglist = Shoppinglist::with(['items', 'user', 'helper', 'comments'])
            -> where ( 'title' , 'LIKE' , '%' . $searchTerm . '%')
            -> orWhere ( 'costs' , 'LIKE' , '%' . $searchTerm . '%')
            -> orWhere ( 'deadline' , 'LIKE' , '%' . $searchTerm . '%')
            /* search term in authors name */
            -> orWhereHas ( 'items' , function ( $query ) use ( $searchTerm ) {
                $query -> where ( 'description' , 'LIKE' , '%' . $searchTerm . '%');
            })-> get ();
        return $shoppinglist;
    }

    public function findByUserId(number $id)
    {
        $shoppinglists = Shoppinglist::where('user_id', $id)->with(['items', 'user', 'helper', 'comments'])::all();

        if ($shoppinglists != Null) {
            return $shoppinglists;
        } else {
            echo 'Kein Listen gefunden';
            return null;
        }
    }

    public function findByShoppinglistId($id)
    {
        $shoppinglist = Shoppinglist::where('id', $id)
            ->with(['items', 'user', 'helper', 'comments'])
            ->first();
        if ($shoppinglist) {
            return $shoppinglist;
        } else {
            echo 'Keine Liste gefunden';
            return null;
        }
    }
}