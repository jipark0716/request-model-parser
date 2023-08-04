<?php

namespace App\Http\Controllers;

use App\Http\Dtos\User\IndexDto;
use App\Http\Requests\Request;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return void
     * @throws \ReflectionException
     * @throws ValidationException
     */
    public function index(Request $request): void
    {
        $dto = $request->parse(IndexDto::class);
        dd($dto);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
