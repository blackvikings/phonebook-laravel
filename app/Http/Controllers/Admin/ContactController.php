<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactResource;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->except(['index', 'show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ContactResource::collection(Contact::with('user')->paginate(25));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|array|min:1',
            "name.*"  => "required|string",
            'email' => 'required|array|min:1',
            "email.*"  => "required|string",
            'contactNo' => 'required|array|min:1',
            "contactNo.*"  => "required",
        ];

        $input = $request->only('name', 'email', 'contactNo');
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }

        for ($i = 0; $i < count($request->name); $i++){
            $contact[] = Contact::create([
                'name' => $request->name[$i],
                'contactNo' => $request->contactNo[$i],
                'email' => $request->email[$i],
                'user_id' => $request->user()->id,
            ]);
        }
        return new ContactResource($contact);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Contact $contact)
    {
        return new ContactResource($contact);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        $rules = [
            'id' => 'required|array|min:1',
            "id.*"  => "required|string",
            'name' => 'required|array|min:1',
            "name.*"  => "required|string",
            'email' => 'required|array|min:1',
            "email.*"  => "required|string",
            'contactNo' => 'required|array|min:1',
            "contactNo.*"  => "required",
        ];

        $input = $request->only('id', 'name', 'email', 'contactNo');
        $validator = Validator::make($input, $rules);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->messages()]);
        }

        $contact = Contact::whereIn('id', $request->id)->update($request->only(['name', 'contactNo', 'email']));

        return new ContactResource($contact);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();
        return response()->json(null, 204);
    }
}
