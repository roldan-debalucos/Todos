<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Todos;

class TodosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $user_id = Auth::user()->id;

       $data = Todos::orderBy('id')->with('user')->where('user_id', '=', $user_id)->get(); 

       return view('home', compact('data'));
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
        $flag = 'true';

        $userid = Auth::user()->id;
        $title = $request->title;
        $description = $request->description;

        $todos = new Todos;

        $todos->user_id = $userid;
        $todos->title = $title;
        $todos->description = $description;
        $todos->save();

        return $flag;
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
        $getdata = Todos::orderBy('id')->where('id', '=', $id)->get();

        $title = $getdata[0]->title;
        $description = $getdata[0]->description;

        $data = [$title, $description];

        return $data;
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
    public function update(Request $request, $id)
    {
        $flag = 'true';

        $title = $request->title;
        $description = $request->description;

        $todos = new Todos;

        $todos::where('id', $id)
          ->update(['title' => $title,
                    'description' => $description
                  ]);

        return $flag;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = Todos::where('id',$id)->delete();

        return $delete;
    }
}
