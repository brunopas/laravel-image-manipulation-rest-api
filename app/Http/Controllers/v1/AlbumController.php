<?php

namespace App\Http\Controllers\v1;

use App\Models\Album;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAlbumRequest;
use App\Http\Requests\UpdateAlbumRequest;
use App\Http\Resources\v1\AlbumResource;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return AlbumResource::collection(
            Album::where('user_id', $request->user()->id)->paginate()
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAlbumRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAlbumRequest $request)
    {
        $data = $request->all();
        $data['user_id'] = $request->user()->id;

        $album = Album::create($data);
        return new AlbumResource($album);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Http\Requests\Request  $request
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Album $album)
    {
        if ($request->user()->id != $album->user_id) {
            return abort(403, 'Unauthorized');
        }

        return $album;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAlbumRequest  $request
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAlbumRequest $request, Album $album)
    {
        if ($request->user()->id != $album->user_id) {
            return abort(403, 'Unauthorized');
        }

        $album->update($request->all());
        return new AlbumResource($album);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Http\Requests\Request  $request
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Album $album)
    {
        if ($request->user()->id != $album->user_id) {
            return abort(403, 'Unauthorized');
        }

        $album->delete();
        return response('', 204);
    }
}
