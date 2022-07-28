<?php

namespace App\Http\Controllers;

use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TourController extends Controller
{
    /**
     * Display a listing of the resource having possibility to choose specific filter
     * 
     * 
     * @return $data - specific or general data from tours
     * @param $request - requests from url
     */
    public function index(Request $request)
    {
        $data = DB::table('tours');

        if(!empty($request->all())){

        if($request->has('price.eq')){
           
        $data->where('price', '=', $request->all());

    }elseif($request->has('price.gte')){

        $data->where('price', '>=', $request->all());

    }elseif($request->has('price.lte')){

        $data->where('price', '<=', $request->all());

    }elseif($request->has('start.eq')){

        $data->where('start', '=', $request->all());

    }elseif($request->has('start.gte')){

        $data->where('start', '>=', $request->all());

    }elseif($request->has('start.lte')){

        $data->where('start', '<=', $request->all());

    }elseif($request->has('end.eq')){

        $data->where('end', '=', $request->all());

    }elseif($request->has('end.gte')){

        $data->where('end', '>=', $request->all());

    }elseif($request->has('end.lte')){

        $data->where('end', '<=', $request->all());

    }elseif($request->has('limit') && $request->has('offset')){

        $data->offset($request->limit)->limit($request->offset)->get();
    }
    }

     return response()->json($data->get());
    
    }

    /**
     * Creates a new tour
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [ //inputs are not empty or null
            'start' => 'required|date:Y-m-d H:i:s',
            'end' => 'required|date:Y-m-d H:i:s|gte:start',
            'price' => 'required|numeric'
        ]);
  
        $tour = new Tour;
        $tour->start = $request->input('start');
        $tour->end = $request->input('end'); 
        $tour->price = $request->input('price');
        $tour->save(); //storing values as an object
       
        return response()->json([
            'message' => "Tour successfully created",
            'data' => $tour
        ],200); // shows a message when the object is created
    }

    /**
     * Display the specified tour by id
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Tour::findorFail($id);
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
        $this->validate($request, [ // the new values should not be null
            'start' => 'required|date:Y-m-d H:i:s',
            'end' => 'required|date:Y-m-d H:i:s|gte:start',
            'price' => 'required|numeric'
        ]);
  
        $tour = Tour::findorFail($id); // uses the id to search values that need to be updated.

        if(!empty($tour)){

        $tour->start = $request->input('start'); 
        $tour->end = $request->input('end');
        $tour->save();//saves the values in the database. The existing data is overwritten.

        return response()->json([
            'message' => "Tour successfully updated",
            'data' => $tour
        ],200); // shows a message when the object is updated
    }else{
        return response()->json([
            'message' => "Tour not found"
        ],404); // shows a message when the object is not found
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
        $tour = Tour::findorFail($id); //searching for object in database using ID

        if(!empty($tour)){

        if($tour->delete()){ //deletes the object

            return response()->json([
                'message' => "Tour successfully deleted."
            ],204); //shows a message when the delete operation was successful.
      }
    }else{
        return response()->json([
            'message' => "Tour not found. Please try again",
        ],404); //shows a message when the delete operation was unsuccessful.
    }
    }
}
