<?php 

namespace App\Core;

class Response
{
    /**
	 * 
     * The function sets the HTTP response code for the current request.
	 * 
     * @param int code The code parameter is an integer that represents the HTTP status code that you
     * want to set for the response.
	 * 
     */
    public function setStatusCode(int $code): void
    {
        http_response_code($code);
    }

    /**
	 * 
     * The function redirects the user to a specified URL.
     * 
     * @param string url The "url" parameter is a string that represents the URL to which the user will
     * be redirected.
	 * 
     */
    public function redirect(string $url): void
    {
        header('Location: ' . $url);
    }
}