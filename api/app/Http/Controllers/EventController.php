<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\Event\EventService;
use Illuminate\Http\{Request, Response};

class EventController extends Controller
{
    protected $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    /**
     * List all events
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        return $this->eventService->index($request);
    }

    /**
     * Show an event
     *
     * @param Event $event
     * @param Request $request
     * @return Response
     */
    public function show(Event $event, Request $request): Response
    {
        return $this->eventService->show($event, $request);
    }

    /**
     * Store a new event
     */
    public function store(Request $request): Response
    {
        return $this->eventService->store($request);
    }

    /**
     * Update an event
     *
     * @param Event $event
     * @param Request $request
     * @return Response
     */
    public function update(Event $event, Request $request): Response
    {
        return $this->eventService->update($event, $request);
    }

    /**
     * Delete an event
     *
     * @param Event $event
     * @param Request $request
     * @return Response
     */
    public function destroy(Event $event, Request $request): Response
    {
        return $this->eventService->destroy($event, $request);
    }
}
