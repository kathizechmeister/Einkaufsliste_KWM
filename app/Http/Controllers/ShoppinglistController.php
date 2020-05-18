<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Item;
use App\Shoppinglist;
use App\User;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShoppinglistController extends Controller
{
    public function index()
    {
        $shoppinglists = Shoppinglist::with(['items', 'user', 'helper', 'comments'])->get();
        return $shoppinglists;

    }

    public function show($shoppinglist)
    {
        $shoppinglist = Shoppinglist::find($shoppinglist);
        return $shoppinglist;
    }

    public function save(Request $request): JsonResponse
    {

        DB::beginTransaction();
        try {
            $shoppinglist = Shoppinglist::create([

                'title' => $request->title,
                'user_id' => $request->user_id,
                'costs' => $request->costs,
                'deadline' => $request->deadline,
                'helper_id' => $request->helper_id,

            ]);

            if (isset($request['items']) && is_array($request['items'])) {
                foreach ($request['items'] as $itm) {
                    $item = Item::firstOrNew(
                        ['description' => $itm['description'],
                            'amount' => $itm['amount'],
                            'maxprice' => $itm['maxprice']]);
                    $shoppinglist->items()->save($item);
                }

            }
            if (isset($request['comments']) && is_array($request['comments'])) {
                foreach ($request['comments'] as $comm) {
                    $c = new \App\Comment;
                    $c->commenttext = $comm["commenttext"];
                    $c->user_id = $comm["user_id"];
                    $shoppinglist->comments()->save($c);

                }
            }
            DB::commit();

            return response()->json($shoppinglist::with('items', 'comments')->where('id', $shoppinglist->id)->get(), 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json("saving shoppinglist failed: " . $e->getMessage(), 402);
        }
    }



    public function findBySearchTerm(string $searchTerm)
    {
        $shoppinglist = Shoppinglist::with(['items', 'user', 'helper', 'comments'])
            ->where('title', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('costs', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('deadline', 'LIKE', '%' . $searchTerm . '%')
            /* search term in authors name */
            ->orWhereHas('items', function ($query) use ($searchTerm) {
                $query->where('description', 'LIKE', '%' . $searchTerm . '%');
            })->get();
        return $shoppinglist;
    }

    /**
     * @param number $id
     * @return null
     * User finden
     */
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

    /**
     * @param $id
     * @return null
     * Liste finden
     */
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

    /**
     * @param $id
     * @return null
     * User zurückgeben
     */
    public function getUserById($id)
    {
        $user = User::where('id', $id)->first();

        if ($user) {
            return $user;
        } else {
            echo 'Kein User gefunden';
            return null;
        }
    }

    /**
     * @param string $id
     * @return JsonResponse
     * @throws \Exception
     * Liste löschen
     */
    public function delete(string $id): JsonResponse
    {
        $shoppinglist = Shoppinglist:: where('id', $id)->first();
        if ($shoppinglist != null) {
            $shoppinglist->delete();
        } else
            throw new \Exception ("shoppinglist couldn't be deleted - it does not exist");
        return response()->json('list (' . $id . ') successfully deleted', 200);
    }

    /**
     * @param $id
     * @return JsonResponse
     * @throws \Exception
     * Kommentar löschen
     */
    public function deleteComment($id): JsonResponse
    {
        $comment = Comment::where('id', $id)->first();
        if ($comment != null) {
            $comment->delete();
        } else
            throw new \Exception ("comment couldn't be deleted - it does not exist");
        return response()->json('comment(' . $id . ') successfully deleted', 200);
    }

    /**
     * @param Request $request
     * @param $id
     * @param $helperid
     * @return JsonResponse
     * Helper übernimmt Liste
     */
    public function accept(Request $request, $id, $helperid): JsonResponse
    {
        DB::beginTransaction();
        try {
            $shoppinglist = Shoppinglist::where('id', $id)->first();
            $shoppinglist->update(['helper_id' => $helperid]);
            $shoppinglist->save();
            DB::commit();
            return response()->json($shoppinglist, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json("accepting Shoppinglist failed: " . $e->getMessage(), 420);
        }
        //  return response()->json("accept");
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * Neuer Kommentar
     */

    public function addComment(Request $request, $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $comment = Comment::create($request->all());
            DB::commit();
            return response()->json($comment, 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json("accepting Shoppinglist failed: " . $e->getMessage(), 420);
        }
        //  return response()->json("accept");
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * Liste bearbeiten
     */
    public function update(Request $request, $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $shoppinglist = Shoppinglist::with(['user', 'helper', 'items', 'comments'])
                ->where('id', $id)->first();

            if ($shoppinglist != null) {
                $shoppinglist->update($request->all());

                //delete all old items
                $shoppinglist->items()->delete();

                // save items
                if (isset($request['items']) && is_array($request['items'])) {
                    foreach ($request['items'] as $itm) {
                        $i = new \App\Item;
                        $i->description = $itm["description"];
                        $i->amount = $itm["amount"];
                        $i->maxprice = $itm["maxprice"];

                        $shoppinglist->items()->save($i);

                        /*$item = Item::firstOrNew(['description' => $itm['description'],
                            'amount' => $itm['amount'], 'maxprice' => $itm['maxprice']]);
                        $shoppinglist->items()->save($item);*/
                    }

                }



                $shoppinglist->comments()->delete();
                if (isset($request['comments']) && is_array($request['comments'])) {



                    foreach ($request['comments'] as $comm) {

                        $c = new Comment;
                        $c->commenttext = $comm["commenttext"];
                        $c->user_id = $comm["user_id"];
                        // $comment = Comment::firstOrNew(['commenttext'=>$comm['commenttext']]);
                        $shoppinglist->comments()->save($c);
                    }
                }
                $shoppinglist->save();
            }
            DB::commit();
            $shoppinglist1 = Shoppinglist::with(['user', 'helper', 'comments','items'])
                ->where('id', $id)->first();

            // return a vaild http response
            return response()->json($shoppinglist1, 201);
        } catch (\Exception $e) {
            // rollback all queries
            DB::rollBack();
            return response()->json("updating Shoppinglist failed: " . $e->getMessage(), 420);
        }
    }

}