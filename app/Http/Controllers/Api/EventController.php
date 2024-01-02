<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Resources\SpotResource;
use App\Models\Event;
use App\Models\UserEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::all();

        if(sizeof($events) === 0){
            return $this->sendResponse('', 'No events yet!');
        }

        return $this->sendResponse(EventResource::collection($events), 'Fetched all events successfully!');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "title" => ['required', 'max:255'],
            "description" => ['required', 'max:255'],
            "latitude" => ['required', 'max:255'],
            "longitude" => ['required', 'max:255'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error!', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $event = Event::create($request->all());

        return $this->sendResponse(new EventResource($event), 'Event created successfuly!');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $event = Event::find($id);

        if(!$event){
            return $this->sendError('', 'Event not found!');
        }

        return $this->sendResponse(new SpotResource($event), 'Event fetched successfuly!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $event = Event::find($id);

        if(!$event){
            return $this->sendError('', 'Event not found!');
        }

        $validator = Validator::make($request->all(), [
            "title" => ['max:255'],
            "description" => ['max:255'],
            "latitude" => ['max:255'],
            "longitude" => ['max:255'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error!', $validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $event->update($request->all());

        return $this->sendResponse(new EventResource($event), 'Event updated successfuly!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = Event::find($id);

        if(!$event){
            return $this->sendError('', 'Event not found!');
        }

        $event->delete();

        return $this->sendResponse('', 'Event deleted successfuly!');
    }

    public function ooInterestingToggle(Request $request, string $id) {

        $event = Event::find($id);

        if(!$event){
            return $this->sendError('', 'Event not found!');
        }

        $user = $request->user();
        $userEvent = $event->userEvents()->where('user_id', $user->id)->first();

        if ($userEvent) {
            $userEvent->delete();

            return $this->sendResponse('', 'Unliked!');
        } else {
            $userEvent = new UserEvent();
            $userEvent->user_id = $user->id;
            $userEvent->event_id = $event->id;
            $userEvent->save();

            return $this->sendResponse('', 'Liked!');
        }

    }
}
