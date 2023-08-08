<?php

namespace App\Services\Event;

use App\Models\Event;
use App\Traits\GlobalTrait;
use Illuminate\Http\{Request, Response};
use Illuminate\Support\Facades\Validator;

class EventService
{
    use GlobalTrait;


    /**
     * EventService index
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {

        return response([
            'records' => Event::all(),
        ]);
    }

    /**
     * EventService show
     *
     * @param Event $event
     * @param Request $request
     * @return Response
     */
    public function show(Event $event, Request $request): Response
    {
        return response([
            'record' => $event
        ]);
    }

    /**
     * EventService store
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        $validator = Validator::make($request->all(), [
            'title'  => 'required',
            'color'  => 'required',
            'from'   => 'required|date_format:Y-m-d',
            'to'     => 'required|date_format:Y-m-d',
            'days.*' => 'integer'
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $event = Event::create([
            'title' => $request->title,
            'color' => $request->color,
            'from'  => $request->from,
            'to'    => $request->to,
            'days'  => $request->days
        ]);

        return response([
            'record' => $event
        ]);
    }

    /**
     * EventService update
     *
     * @param Event $event
     * @param Request $request
     * @return Response
     */
    public function update(Event $event, Request $request): Response
    {
        $validator = Validator::make($request->all(), [
            'title'  => 'required',
            'color'  => 'sometimes',
            'from'   => 'sometimes|date_format:Y-m-d',
            'to'     => 'sometimes|date_format:Y-m-d',
            'days.*' => 'integer'
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $event->update([
            'title' => $request->title,
            'color' => $request->color,
            'from'  => $request->from,
            'to'    => $request->to,
            'days'  => $request->days
        ]);

        return response([
            'record' => $event
        ]);
    }

    /**
     * EventService destroy
     *
     * @param Event $event
     * @param Request $request
     */
    public function destroy(Event $event, Request $request): Response
    {
        $event->delete();

        return response([
            'record' => 'Event deleted'
        ]);
    }
}
