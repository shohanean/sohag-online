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
        $works = Work::with('subscription')->latest()->get();
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
        $work->user_id = $request->user_id;
        if ($request->user_id) {
            $work->status = "running";
        } else {
            $work->status = "open";
        }
        $work->save();
        return back()->with('update_success', 'Updated Successfully!');
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
}
