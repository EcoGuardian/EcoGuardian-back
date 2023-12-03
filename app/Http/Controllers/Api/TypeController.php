<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TypeResource;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $types = Type::all();

        if(!$types) {
            return $this->sendError('No types found or exist');
        }

        return $this->sendResponse(TypeResource::collection($types), 'Fetched all types successfuly!');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => ['required', 'max:255'],
            "color" => ['required', 'size:6', 'alpha_num']
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error!', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $type = Type::create($request->all());

        return $this->sendResponse(new TypeResource($type), 'Created type successfuly!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            "name" => ['max:255'],
            "color" => ['size:6', 'alpha_num']
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error!', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $type = Type::find($id);

        if(!$type) {
            return $this->sendError('No type found');
        }

        $type->update($request->all());

        return $this->sendResponse(new TypeResource($type), 'Updated type successfuly!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
