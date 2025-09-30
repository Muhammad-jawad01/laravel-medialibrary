<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\GeminiInteraction;


class OpenAiController extends Controller
{
    public function askGemini(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string',
        ]);

        $prompt = $request->input('prompt');

        // **Correction 1: Use an environment variable for the model name**
        $model = env('GEMINI_MODEL', 'gemini-2.5-flash');

        // **Correction 2: Retrieve API Key securely from the environment**
        $apiKey = env('GEMINI_API_KEY');

        // **Correction 3: Use a more standard and reliable approach for URL construction**
        // The Generative Language API is typically accessed via 'ai.google.com'
        // and the model endpoint should be /v1/models/{model}:generateContent
        $url = 'https://generativelanguage.googleapis.com/v1/models/' . $model . ':generateContent?key=' . $apiKey;

        // **Alternative/Modern Approach (Highly Recommended for Laravel/PHP)**
        // Use the official Google Cloud PHP SDK or a dedicated HTTP client (like Guzzle)
        // This is more robust and secure than manually constructing the URL.

        // Example using a simple, modern string interpolation/concatenation
        // $url = "https://generativelanguage.googleapis.com/v1/models/{$model}:generateContent?key={$apiKey}";
        $response = Http::withOptions([
            'verify' => false,
            'timeout' => 60,
            'connect_timeout' => 15,
        ])->post($url, [
            'contents' => [
                ['parts' => [['text' => $prompt]]]
            ]
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $answer = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No response from Gemini.';

            // Store prompt and answer in database
            GeminiInteraction::create([
                'prompt' => $prompt,
                'response' => $answer,
            ]);

            return response()->json(['answer' => $answer]);
        } else {
            $errorBody = $response->body();
            GeminiInteraction::create([
                'prompt' => $prompt,
                'response' => $errorBody
            ]);
            return response()->json([
                'error' => 'Failed to get response from Gemini.',
                'details' => $errorBody
            ], 500);
        }
    }
    public function postGemini(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string',
        ]);

        $prompt = $request->input('prompt');

        $model = env('GEMINI_MODEL', 'gemini-2.5-flash');
        $apiKey = env('GEMINI_API_KEY');

        $url = "https://generativelanguage.googleapis.com/v1/models/{$model}:generateContent?key={$apiKey}";

        $multiPrompt = "
        Generate engaging social media posts for the following platforms based on this prompt:
        \"{$prompt}\"

        Format the output as JSON with keys: facebook, instagram, twitter, snapchat.
        Each value should be a short text suitable for that platform.
    ";

        $response = Http::withOptions([
            'verify' => false,
            'timeout' => 60,
            'connect_timeout' => 15,
        ])->post($url, [
            'contents' => [
                ['parts' => [['text' => $multiPrompt]]]
            ]
        ]);

        if ($response->successful()) {
            $data = $response->json();
            $answer = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No response from Gemini.';

            $posts = json_decode($answer, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $posts = ['raw' => $answer];
            }

            // Store in DB if you want
            GeminiInteraction::create([
                'prompt' => $prompt,
                'type' => 'post',
                'response' => json_encode($posts),
            ]);

            return response()->json([
                'posts' => $posts
            ]);
        } else {
            $errorBody = $response->body();
            GeminiInteraction::create([
                'prompt' => $prompt,
                'type' => 'post',
                'response' => $errorBody
            ]);
            return response()->json([
                'error' => 'Failed to get response from Gemini.',
                'details' => $errorBody
            ], 500);
        }
    }
}
