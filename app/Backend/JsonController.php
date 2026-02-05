<?php

namespace App\Backend;

use App\Helpers\DataHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;

class JsonController extends Controller
{
    #[OA\Get(
        path: "/api/wilayah",
        tags: ["wilayah"],
        summary: "Ambil data wilayah",
        description: "List semua wilayah",
        security: [["bearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "tipe",
                in: "query",
                required: true,
                description: "Tipe wilayah",
                schema: new OA\Schema(type: "string", example: "kecamatan")
            ),
            new OA\Parameter(
                name: "search",
                in: "query",
                required: false,
                description: "Kata kunci pencarian",
                schema: new OA\Schema(type: "string", example: "")
            ),
            new OA\Parameter(
                name: "parent_id",
                in: "query",
                required: false,
                description: "ID parent",
                schema: new OA\Schema(type: "integer", example: 2)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Berhasil"
            )
        ]
    )]
    public function wilayah(Request $request)
    {
        $wilayah = DataHelper::getWilayah($request->tipe, $request->search, $request->parent_id);
        return response()->json([
            'success' => true,
            'data' => $wilayah
        ]);
    }

    #[OA\Get(
        path: "/api/test",
        tags: ["test"],
        summary: "Test API",
        security: [["bearerAuth" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "OK"
            )
        ]
    )]
    public function test()
    {
        return 'ok';
    }

    #[OA\Post(
        path: "/api/login",
        tags: ["auth"],
        summary: "Login dan dapatkan token",
        description: "Login untuk mendapatkan Bearer token",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["username", "password"],
                properties: [
                    new OA\Property(property: "username", type: "string", example: "admin"),
                    new OA\Property(property: "password", type: "string", example: "password")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Login berhasil",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "token", type: "string", example: "1|abc123..."),
                        new OA\Property(property: "user", type: "object")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Login gagal"
            )
        ]
    )]
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau password salah'
            ], 401);
        }

        $user->tokens()->delete();

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => $user
        ]);
    }
}
