<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReportResource;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reports = Report::all();

        if(sizeof($reports) === 0){
            return $this->sendError('', 'No reports yet!');
        }

        return $this->sendResponse(ReportResource::collection($reports), 'Fetched all reports successfully!');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'photo_path' => ['required', 'string'],
            'description' => ['string', 'max:500'],
            'location' => ['required', 'string'],
            'status' => ['required']
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error!', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $report = new Report();

        $report->photo_path = $request->photo_path;
        $report->description = $request->description;
        $report->location = $request->location;
        $report->status = $request->status;

        $request->user()->reports()->save($report);

        return $this->sendResponse(new ReportResource($report), 'Issued a report successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        $report = Report::find($id);

        if(!$report){
            return $this->sendError('', 'Report not found!');
        }

        if($request->user()->role->name == 'Employee'){
            return $this->sendResponse(new ReportResource($report), 'Fetched the report successfully!');
        }

        if($request->user()->id != $report->user_id){
            return $this->sendError('', 'Forbidden access to the report!', 403);
        }

        return $this->sendResponse(new ReportResource($report), 'Fetched the report successfully!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'photo_path' => ['string'],
            'description' => ['string', 'max:500'],
            'location' => ['string']
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error!', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $report = Report::find($id);

        if(!$report){
            return $this->sendError('', 'Report not found!');
        }

        if($request->user()->role->name === 'Employee') {

            $report->update($request->all());

            return $this->sendResponse(new ReportResource($report), 'Updated the report successfully!');
        }

        if($request->user()->id != $report->user_id){
            return $this->sendError('', 'Forbidden request!', 403);
        }

        $report->update($request->all());

        return $this->sendResponse(new ReportResource($report), 'Updated the report successfully!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, Request $request)
    {
        $report = Report::find($id);

        if(!$report){
            return $this->sendError('', 'Report not found!');
        }

        if($request->user()->role->name == 'Employee'){

            $report->delete();

            return $this->sendResponse('', 'Deleted the report successfully!');
        }

        if($request->user()->id != $report->user_id){
            return $this->sendError('', 'Forbidden access to the report!', 403);
        }

        $report->delete();

        return $this->sendResponse('', 'Deleted the report successfully!');
    }
}
