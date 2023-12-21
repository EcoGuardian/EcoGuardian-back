<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SpotResource;
use App\Models\Spot;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class SpotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->has('type_id')){
            $spots = Spot::where('type_id', $request->type_id)->get();
            return $this->sendResponse(SpotResource::collection($spots), 'Spots fetched successfuly!');
        }

        $spots = Spot::all();
        return $this->sendResponse(SpotResource::collection($spots), 'Spots fetched successfuly!');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
//            "name" => ['required', 'max:255'],
            "latitude" => ['required', 'max:255'],
            "longitude" => ['required', 'max:255'],
            "type_id" => ['required']
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error!', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $spot = new Spot($request->except('type_id'));
        $spot->type()->associate(Type::find($request->type_id));
        $spot->save();

        return $this->sendResponse(new SpotResource($spot), 'Spot created successfuly!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $spot = Spot::find($id);

        if(!$spot){
            return $this->sendError('', 'Spot not found!');
        }

        return $this->sendResponse(new SpotResource($spot), 'Spot fetched successfuly!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $validator = Validator::make($request->all(), [
//            "name" => ['max:255'],
            "latitude" => ['max:255'],
            "longitude" => ['max:255'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error!', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $spot = Spot::find($id);

        if(!$spot){
            return $this->sendError('', 'Spot not found!');
        }

        $spot->update($request->except('type_id'));
        $spot->type()->associate(Type::find($request->type_id));
        $spot->save();

        return $this->sendResponse(new SpotResource($spot), 'Spot updated successfuly!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $spot = Spot::find($id);

        if(!$spot){
            return $this->sendError('', 'Spot not found!');
        }

        $spot->delete();
        return $this->sendResponse('', 'Spot deleted successfuly!');

    }
}
