<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ImageController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $image = $request->file('image');
        $imagePath = $image->getRealPath();
        $imageName = time() . '.jpg';
        $destinationPath = public_path('/images');

        // Получаем ключ API из файла .env
        $apiKey = env('TINIFY_API_KEY');

        $client = new Client();

        try {
            // Отправляем изображение на TinyPNG API с изменением размера
            $response = $client->request('POST', 'https://api.tinify.com/shrink', [
                'auth' => ['api', $apiKey],
                'body' => fopen($imagePath, 'r')
            ]);

            $result = json_decode($response->getBody()->getContents(), true);
            $outputUrl = $result['output']['url'];

            // Добавляем изменение размера
            $resizeResponse = $client->request('POST', $outputUrl, [
                'auth' => ['api', $apiKey],
                'json' => [
                    "resize" => [
                        "method" => "cover", // Можно использовать cover, scale, fit
                        "width" => 70,
                        "height" => 70
                    ]
                ]
            ]);

            $optimizedImage = $resizeResponse->getBody();
            file_put_contents($destinationPath . '/' . $imageName, $optimizedImage);

            return response()->json(['success' => 'Image uploaded, resized, and optimized successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
