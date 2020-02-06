<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;

class AuthController extends Controller
{
    /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }

    public function login(Request $request)
    {
        try {
            $client = new Client();
            $response = $client->request('POST', 'http://89.92.37.229/API/AUTHENTIFICATION', [
                'headers' => [
//                'Authorization' => 'Bearer '.$token,
                    'Accept' => 'application/json',
                ],
                'json' => [
                    'username' => $request->username,
                    'password' => $request->password,
                ]
            ]);
            return collect(json_decode($response->getBody()))->map(function($item) {
                $user = collect();
                $user->offsetSet('alias', $item->CODEUTILISATEUR);
                $user->offsetSet('name', $item->PRENOM);
                $user->offsetSet('surname', $item->NOM);
                $user->offsetSet('admin', $item->SUPERVISEUR);
                $user->offsetSet('company', $item->CODESOCIETE);
                $user->offsetSet('establishment', $item->CODEETABLISSEMENT);
                return $user;
            });
        } catch (\GuzzleHttp\Exception\ClientException $exception) {
            $response = $exception->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            return $responseBodyAsString;
        }

//        $this->validate($request, [
//            'email'    => 'required|email|max:255',
//            'password' => 'required',
//        ]);
//
//        $credentials = $request->only(['email', 'password']);
//
//        try {
//            if (!$token = $this->jwt->attempt($credentials)) {
//                return response()->json(['user_not_found'], 404);
//            }
//        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
//            return response()->json(['token_expired'], 500);
//        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
//            return response()->json(['token_invalid'], 500);
//        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
//            return response()->json(['token_absent' => $e->getMessage()], 500);
//        }
//
//        return response()->json([
//            'token' => $token,
//            'token_type' => 'bearer',
//            'expires_in' => Auth::factory()->getTTL() * 60,
//            'user' => Auth::user()
//        ], 200);
    }

    public function logout(Request $request)
    {
//        $this->validate($request, [
//        'token' => 'required'
//    ]);
//
//
//        try {
//            $this->jwt->parseToken()->invalidate();
//
//            return response()->json([
//                'success' => true,
//                'message' => 'User logged out successfully'
//            ]);
//        } catch (JWTException $exception) {
//            return response()->json([
//                'success' => false,
//                'message' => 'Sorry, the user cannot be logged out'
//            ], 500);
//        }

    }
}
