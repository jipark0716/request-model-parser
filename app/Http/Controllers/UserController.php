<?php

namespace App\Http\Controllers;

use App\Attributes\FromBody;
use App\Attributes\FromHeader;
use App\Attributes\FromQuery;
use App\Http\Dtos\User\IndexDto;
use App\Http\Requests\Request;

class UserController extends Controller
{
    /**
     * @param IndexDto $request
     * @return void
     */
    public function index(#[FromQuery] IndexDto $request, #[FromHeader('accept')] string $contentType): void
    {
//        dd($contentType, $request);
        dd($contentType, $request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(#[FromBody] IndexDto $request)
    {
        dd($request);
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        dd(request());
//        dd($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
