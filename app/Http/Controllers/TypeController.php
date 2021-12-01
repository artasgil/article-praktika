<?php

namespace App\Http\Controllers;

use App\Type;
use App\Article;
use Illuminate\Http\Request;
use Validator;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $types = Type::all();
        return view('type.index', ['types'=>$types]);
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
    public function storeAjax(Request $request) {


        $type = new Type;

        $input = [
            'typeTitle' => $request->typeTitle,
            'typeDescription' => $request->typeDescription,
        ];
        $rules = [
            'typeTitle' => 'required|min:3',
            'typeDescription' => 'required|min:3',
        ];

        $validator = Validator::make($input, $rules);

        if($validator->passes()) {
            $type->title = $request->typeTitle;
            $type->description = $request->typeDescription;

            $type->save();

            $success = [
                'success' => 'Type added successfully',
                'typeid' => $type->id,
                'typeTitle' => $type->title,
                'typeDescription' => $type->description,
            ];

            $success_json = response()->json($success);

            return $success_json;
        }

        $errors = [
            'error' => $validator->messages()->get('*')
        ];

        $errors_json = response()->json($errors);

        return $errors_json;

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function show(Type $type)
    {
        //
    }
    public function showAjax(Type $type) {

        $success = [
            'success' => 'Type recieved successfully',
            'typeid' => $type->id,
            'typeTitle' => $type->title,
            'typeDescription' => $type->description,
        ];

        $success_json = response()->json($success);

        return $success_json;
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function edit(Type $type)
    {
        //
    }
    public function editAjax(Type $type) {
        $success = [
            'success' => 'Type recieved successfully',
            'typeid' => $type->id,
            'typeTitle' => $type->title,
            'typeDescription' => $type->description,
        ];

        $success_json = response()->json($success);

        return $success_json;
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Type $type)
    {
        //
    }
    public function updateAjax(Request $request, Type $type) {

        $input = [
            'typeTitle' => $request->typeTitle,
            'typeDescription' => $request->typeDescription,
        ];
        $rules = [
            'typeTitle' => 'required|min:3',
            'typeDescription' => 'required|min:3',
        ];

        $validator = Validator::make($input, $rules);

        if($validator->passes()) {
            $type->title = $request->typeTitle;
            $type->description = $request->typeDescription;

            $type->save();

            $success = [
                'success' => 'Type updated successfully',
                'typeid' => $type->id,
                'typeTitle' => $type->title,
                'typeDescription' => $type->description,
            ];

            $success_json = response()->json($success);

            return $success_json;
        }

        $errors = [
            'error' => $validator->messages()->get('*')
        ];

        $errors_json = response()->json($errors);

        return $errors_json;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy(Type $type)
    {
        //
    }

    public function destroySelected(Request $request) {


        $checkedTypes = $request->checkedTypes; // visus id

        $messages = array();

        //error 0
        //success 1

        //error - 'danger'
        //success - 'success'

        $errorsuccess = array();

        foreach($checkedTypes as $typeid) {
            //kaip pasirinkti kompanija pagal Id?
            // $company = Company::where("id", $companyId);
            $type = Type::find($typeid);
            // if($clients_count > 0) {
            //    $errorsuccess[] = 'danger';
            //    $messages[] = "Company ".$companyId."cannot be deleted because it has clients";

            // } else {
                $deleteAction = $type->delete();
                if($deleteAction) {
                    $errorsuccess[] = 'success';
                    $messages[] = "Company ".$typeid." deleted successfully";
                } else {
                    $messages[] = "Something went wrong";
                    $errorsuccess[] = 'danger';
                }
        }


        $success = [
            'success' => $checkedTypes,
            'messages' => $messages,
            'errorsuccess' => $errorsuccess
        ];

        $success_json = response()->json($success);

        return $success_json;

    }
}
