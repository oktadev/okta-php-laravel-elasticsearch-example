<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Elasticsearch;

class BlogController extends Controller
{
    public function home (Request $request) {
        $q = $request->get('q');
        if ($q) {
            $response = Elasticsearch::search([
                'index' => 'posts',
                'body'  => [
                    'query' => [
                        'multi_match' => [
                            'query' => $q,
                            'fields' => [
                                'title',
                                'content'
                            ]
                        ]
                    ]
                ]
            ]);

            $postIds = array_column($response['hits']['hits'], '_id');
            $posts = Post::query()->findMany($postIds);
        } else {
            $posts = Post::all();
        }

        return view('home', ['posts' => $posts]);
    }

    public function redirectToLogin () {
        return Socialite::driver('okta')->redirect();
    }

    public function handleLogin () {
        $oktaUser = Socialite::driver('okta')->user();

        //retrieve user from local database
        $user = User::where('email', $oktaUser->email)->first();

        if (!$user) {
            $user = User::create([
                'email' => $oktaUser->email,
                'name'  => $oktaUser->name,
                'token' => $oktaUser->token,
            ]);
        } else {
            $user->token = $oktaUser->token;
            $user->save();
        }

        try {
            Auth::login($user);
        } catch (\Throwable $e) {
            return redirect()->route('login');
        }

        return redirect('/');
    }

    public function savePost (Request $request) {
        //validate request
        $validator = Validator::validate($request->all(), [
            'title' => 'required|min:1',
            'content' => 'required|min:1'
        ]);

        //all valid, save post
        $user = Auth::user();

        $post = new Post([
            'title' => $request->get('title'),
            'content' => $request->get('content')
        ]);
        $post->user()->associate($user);
        $post->save();

        return redirect('/');
    }
}
