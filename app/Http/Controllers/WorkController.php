<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Work;
use App\Models\User;
use App\Models\Worker_wage;
use Illuminate\Http\Request;

class WorkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $works = Work::with('subscription')->where('status', 'open')->orWhere('status', 'running')->latest()->get();
        $workers = User::role('Worker')->get();
        return view('backend.work.index', compact('works', 'workers'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Work $work)
    {
        //only for super admin
        if(auth()->id() == 1){
            $work->user_id = $request->user_id;
            if ($request->user_id) {
                $work->status = "running";
            } else {
                $work->status = "open";
            }
            $work->save();
            return back()->with('update_success', 'Updated Successfully!');
        }
        if($work->user_id && $work->user_id != auth()->id()){
            abort(404);
        }else{
            $work->user_id = $request->user_id;
            if ($request->user_id) {
                $work->status = "running";
            } else {
                $work->status = "open";
            }
            $work->save();
            return back()->with('update_success', 'Updated Successfully!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    //custom routes
    public function worker_wage()
    {
        $workers = User::with('worker_wage')->role('Worker')->get();
        return view('backend.misc.worker_wage', compact('workers'));
    }
    public function worker_wage_post (Request $request)
    {
        $request->validate([
            'wage' => 'required'
        ]);
        Worker_wage::updateOrCreate(
            ['user_id' => $request->user_id],
            ['wage' => $request->wage]
        );
        return back()->with('update_success', 'Updated successfully!');
    }
    public function add_wallet_post (Request $request)
    {
        Worker_wage::where('user_id', $request->user_id)->increment('wallet', $request->wallet);
        return back()->with('update_success', 'Updated successfully!');
    }
    public function delivered_work ()
    {
        $works = Work::with('user', 'subscription')->where('status', 'delivered')->get();
        $done_works = Work::with('user', 'subscription')->where('status', 'done')->orderByDesc('updated_at')->get();
        return view('backend.misc.delivered_work', compact('works', 'done_works'));
    }
    public function work_mark_as_done (Work $work, Request $request)
    {
        Worker_wage::where('user_id', $work->user_id)->decrement('wallet', $request->worker_wage);
        $work->status = 'done';
        $work->save();
        return back()->with('update_success', 'Updated Successfully!');
    }
}
