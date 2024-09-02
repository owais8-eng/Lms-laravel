<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Stichoza\GoogleTranslate\GoogleTranslate;

class Translate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $lang = $request->header('Accept-Language', 'en');

        $translator = new GoogleTranslate();
        $responseData = json_decode($response->getContent(), true);

        if (is_array($responseData)) {
            // If the response is an array, translate it recursively
            $translatedData = $this->translateRecursive($responseData, $translator, $lang);
            return response()->json($translatedData, $response->getStatusCode(), $response->headers->all());
        } elseif (is_string($response->getContent())) {
            // If the response is a string, translate it directly
            $translatedString = $translator->setSource('en')->setTarget($lang)->translate($response->getContent());
            return response($translatedString, $response->getStatusCode(), $response->headers->all());
        }

        return $response;
    }

    private function translateRecursive($data, $translator, $lang)
    {
        if ($data === null) {
            return [];
        }

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->translateRecursive($value, $translator, $lang);
            } elseif (is_string($value)) {
                $data[$key] = $translator->setSource('en')->setTarget($lang)->translate($value);
            }
        }
        return $data;
    }
}
