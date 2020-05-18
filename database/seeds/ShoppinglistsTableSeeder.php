<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShoppinglistsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $shoppinglist = new \App\Shoppinglist();
        $shoppinglist->title = "Meine erste Liste";
        $shoppinglist->deadline = new DateTime();
        $shoppinglist->costs = "23";

        $user = App\User:: all ()->first();
        $shoppinglist ->user()->associate( $user );
        $helpers = \App\User::all();
        $helper =$helpers->find(2);
        $shoppinglist->helper()->associate($helper);
        $shoppinglist->save();


        $item1 = new \App\Item();
        $item1->description = "Käse";
        $item1->amount = 2;
        $item1->maxprice = 4;

        $item2 = new \App\Item();
        $item2->description = "Brot";
        $item2->amount = 1;
        $item2->maxprice = 2;

        $shoppinglist->items()->saveMany([$item1,$item2]);


        $comment1 = new \App\Comment();
        $comment1->commenttext ="Ich bin ein Feedback";

        $comment1 ->user()->associate( $user );
        $comment1 ->shoppinglist()->associate( $shoppinglist );
        $comment1 ->save();


        $shoppinglist1 = new \App\Shoppinglist();
        $shoppinglist1->title = "Meine zweite Liste";
        $shoppinglist1->deadline = new DateTime();
        $shoppinglist1->costs = "10";
        $shoppinglist1->save();



        $user1 = App\User::all()->find(2);
        $shoppinglist1 ->user()->associate( $user );
        $shoppinglist1 ->save();

      /*  $helper2= $helpers->find(2);
        $shoppinglist1->helper()->associate($helper2);
        $shoppinglist1->save();*/


        $item3 = new \App\Item();
        $item3->description = "Nudeln";
        $item3->amount = 2;
        $item3->maxprice = 4;

        $item4 = new \App\Item();
        $item4->description = "Marmelade";
        $item4->amount = 1;
        $item4->maxprice = 2;

        $shoppinglist1->items()->saveMany([$item3,$item4]);


        $comment2 = new \App\Comment();
        $comment2->commenttext ="Ich bin ein Feedback";

        $comment2 ->user()->associate( $user );
        $comment2 ->shoppinglist()->associate( $shoppinglist );
        $comment2 ->save();

      /*  $comment2 = new \App\Comment();
        $comment2->commenttext ="Ich bin ein Feedback";

        $comment2 ->user()->associate( $user );
        $comment2 ->save();*/

        //$shoppinglist->comments()->saveMany([$comment1, $comment2]);
    }
}