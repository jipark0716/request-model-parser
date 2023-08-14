<?php

namespace App\Http\Controllers;

use App\Attributes\FromBody;
use App\Attributes\FromRequest;
use App\Http\Dtos\User\IndexDto;
use App\Http\Dtos\User\Request;
use OpenApi\Attributes as OA;

class UserController extends Controller
{
    #[OA\Get(
        path: "/api/v1/admin/user-listing",
        summary: "List all non-admin users",
        tags: ["Admin"],
        responses: [
            new OA\Response(response: 200, description: "users retrieved success"),
        ]
    )]
    public function index(#[FromRequest] Request $request): Request
    {
        dd($request);
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
    public function show(#[FromRequest] Request $request, string $id)
    {
        dd($request, $id);
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
