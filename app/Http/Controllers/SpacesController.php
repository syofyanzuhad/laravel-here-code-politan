<?php

namespace App\Http\Controllers;

use App\Spaces;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SpacesController extends Controller
{
    public function __construct() {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $spaces = Spaces::orderBy('created_at', 'DESC')->paginate(5);

        return view('pages.space.index', compact('spaces'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.space.create');
    }

    public function browse() 
    {
        return view('pages.space.browse');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title'         => 'required|min:3',
            'address'       => 'required|min:5',
            'description'   => 'required|min:10',
            'latitude'      => 'required',
            'longitude'     => 'required',
            'photo'         => 'required',
            'photo.*'       => 'mimes:png,jpg'
        ]);

        $space = $request->user()->spaces()->create($request->except('photo'));

        $spacePhoto = [];

        foreach ($request->file('photo') as $file) {
            $path = Storage::disk('public')->putFile('spaces', $file);
            $spacesPhoto[] = [
                'space_id'  => $space->id,
                'path'      => $path
            ];
        }

        $photos = $space->photos()->insert($spacesPhoto);

        if ($photos) {
            return redirect()->route('space.index')->with('status', 'Space created!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Spaces  $spaces
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $space = Spaces::findOrFail($id);
        return view('pages.space.show', compact('space'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Spaces  $spaces
     * @return \Illuminate\Http\Response
     */
    public function edit(Spaces $spaces, $id)
    {
        $space = Spaces::findOrFail($id);
        if ($space->user_id != request()->user()->id) {
            return redirect()->back();
        }

        return view('pages.space.edit', compact('space'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Spaces  $spaces
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Spaces $spaces, $id)
    {
        $space = Spaces::findOrFail($id);
        if ($space->user_id != request()->user()->id) {
            return redirect()->back();
        }

        $this->validate($request, [
            'title'         => 'required|min:3',
            'address'       => 'required|min:5',
            'description'   => 'required|min:10',
            'latitude'      => 'required',
            'longitude'     => 'required',
        ]);

        $space->update($request->all());

        return redirect()->route('space.index')->with('status', 'Space Updated !');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Spaces  $spaces
     * @return \Illuminate\Http\Response
     */
    public function destroy(Spaces $spaces, $id)
    {
        $space = Spaces::findOrFail($id);
        if ($space->user_id != request()->user()->id) {
            return redirect()->back();
        }

        if ($space->photo != null) {
            foreach ($space->photo as $photo ) {
                Storage::delete('public/'.$photo->path);
            }
        }

        $space->delete();
        return redirect()->route('space.index')->with('status', 'Space deleted !');
    }
}
