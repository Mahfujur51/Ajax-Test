<?php

namespace App\Http\Controllers;

use App\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class CountryController extends Controller
{
    //
    public function store(Request $request)
    {
        $validator = Validator::make($request->only('country_name', 'capital_city'),
            [
                'country_name' => 'required|unique:countries,country_name,',
                'capital_city' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }else{
            $country=new Country();
            $country->country_name=$request->country_name;
            $country->capital_city=$request->capital_city;
            $query=$country->save();
            if (!$query){
                return response()->json(['errors' => __('Data Not Added successfully.')]);
            }else{
                return response()->json(['success' => __('Data Added successfully.')]);
            }
        }


    }
    public  function  index()
    {
        $countries=Country::all();
        return DataTables::of($countries)
            ->addIndexColumn()
            ->addColumn('actions',function ($row){
                return '<div class="btn-group">
                           <button class="btn btn-sm btn-primary" data-id="'.$row['id'].'" id="editCountryBtn">Update</button>
                           <button class="btn btn-sm btn-danger" data-id="'.$row['id'].'" id="deleteCountryBtn">Delete</button>
                        </div>';
            })
            ->addColumn('checkbox',function ($row){
                return '<input type="checkbox" name="country_checkbox" data-id="'.$row['id'].'"><label></label>';
            })
            ->rawColumns(['actions','checkbox'])
            ->make(true);

    }
    public  function edit($id)
    {
        if (request()->ajax()){
            $data=Country::findOrFail($id);
            return response()->json(['data' => $data]);
        }
    }

    public function delete($id)
    {
        $country=Country::findOrFail($id);
        $query=$country->delete();
        if ($query){
            return response()->json(['success' => __('Data Deleted successfully.')]);
        }else {
            return response()->json(['errors' => __('Data not Deleted successfully.')]);
        }

    }

    public function update(Request $request)
    {
        $id = $request->hidden_id;
        $validator = Validator::make($request->only('country_name', 'capital_city'),
            [
                'country_name' => 'required|unique:countries,country_name,'.$id,
                'capital_city' => 'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()]);
        }else{
            $country=Country::findOrFail($id);
            $country->country_name=$request->country_name;
            $country->capital_city=$request->capital_city;
            $query=$country->update();
            if (!$query){
                return response()->json(['errors' => __('Data Not Updated successfully.')]);
            }else{
                return response()->json(['success' => __('Data Updated successfully.')]);
            }
        }

    }
    public function bulk_delete(Request $request)
    {
        $country_id=$request['CountryIdArray'];
        $country=Country::whereIn('id',$country_id);

        if ($country->delete())
        {
            return response()->json(['success' => __('Multi Delete Successfully')]);
        } else
        {
            return response()->json(['error' => 'Error,selected users can not be deleted']);
        }

    }


}
