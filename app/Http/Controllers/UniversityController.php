<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\University;
use Response;

class UniversityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {   
        $university = University::get();
        if (Auth::check()) {
            return response()->json($university, 200);
        } else {
            $response = [];
            $response['header'] = "Timeout";
            $response['message'] = "Your session expired";
            return response()->json(['header' => 'Error', 'message' => 'session not found'], 500);
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $university = new University;
        $university->name = $request->input('name');
        $university->address = $request->input('address');
        $university->phone = $request->input('phone');
        $university->fax = $request->input('fax');
        $university->touch();
        $university->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return University::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request)
    {
        $university = University::find($request->input('id'));
        $university->name = $request->input('name');
        $university->address = $request->input('address');
        $university->phone = $request->input('phone');
        $university->fax = $request->input('fax');
        $university->touch();
        $university->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request)
    {
        $university = University::find($request->input('id'));
        $university->delete();
    }

    public function validating ($name, $id = false)
    {
        if ($id) {
            return University::where('name', '=', $name)->where('id', '<>', $id)->get();
        } else {
            return University::where('name', '=', $name)->get();    
        }
        
    }
}
