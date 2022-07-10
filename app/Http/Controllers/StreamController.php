<?php

namespace App\Http\Controllers;

use App\DTO\StreamForm;
use App\Http\Requests\StreamStoreRequest;
use App\Http\Requests\StreamUpdateRequest;
use App\Http\Traits\AntMedia;
use App\Models\Stream;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class StreamController extends Controller
{
    use AntMedia;

    public function __construct()
    {
        return $this->middleware('auth')->except('index', 'show');
    }

    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $streams = Stream::paginate(3);

        foreach ($streams as $stream) {
            $stream->status = $this->getBroadcast($stream->stream_id)['data']['status'];
        }

        return view('streams.index', compact('streams'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        return view('streams.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StreamStoreRequest $request
     * @return RedirectResponse
     * @throws UnknownProperties
     */
    public function store(StreamStoreRequest $request): RedirectResponse
    {
        $data = new StreamForm($request->validated());

        try {
            $response = $this->createBroadcast($data)['data'];
        } catch (GuzzleException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        $stream = new Stream();
        $stream->user_id = Auth::id();
        $stream->name = $response['name'];
        $stream->description = $response['description'];
        $stream->rtmp_url = $response['rtmpURL'];
        $stream->stream_id = $response['streamId'];

        if ($data->preview) {
            $path = $data->preview->store('pictures', 'public');
            $stream->preview = $path;
        }
        $stream->save();

        return redirect()->route('streams.index')->with('success', 'Successfully created');
    }

    /**
     * @param Stream $stream
     * @return Factory|View|Application
     */
    public function show(Stream $stream): Factory|View|Application
    {
        return view('streams.show', compact('stream'));
    }

    /**
     * @param Stream $stream
     * @return Factory|View|Application
     * @throws AuthorizationException
     */
    public function edit(Stream $stream): Factory|View|Application
    {
        $this->authorize('update', $stream);
        return view('streams.edit', compact('stream'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StreamUpdateRequest $request
     * @param Stream $stream
     * @return RedirectResponse
     * @throws AuthorizationException
     * @throws UnknownProperties
     */
    public function update(StreamUpdateRequest $request, Stream $stream): RedirectResponse
    {
        $this->authorize('update', $stream);

        $data = new StreamForm($request->validated());

        try {
            $this->updateBroadcast($stream->stream_id, $data);
        } catch (GuzzleException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        if ($data->preview) {
            $path = $data->preview->store('pictures', 'public');
            $stream->preview = $path;
        }
        $stream->name = $data->name;
        $stream->description = $data->description;
        $stream->update();

        return redirect()->route('streams.index')->with('success', 'Successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Stream $stream
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Stream $stream)
    {
        $this->authorize('delete', $stream);

        try {
            $this->deleteBroadcast($stream->stream_id);
        } catch (GuzzleException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        $stream->delete();

        return redirect()->route('streams.index')->with('success', 'Successfully deleted');
    }
}
