<?php
namespace Aggregator\Helper;
class WebHelper {

    /**
     * @param string $url
     * @return bool
     * if given string is has valid url format return true
     */
    public static function isValidUrl(string $url):bool {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * @param string $email
     * @return bool
     * if given string is has valid email format return true
     */
    public static function isValidEmail(string $email) {
        // Use filter_var with FILTER_VALIDATE_EMAIL
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * @param string $url_string
     *                           check if given string is valid URL
     *
     * @retrun given string in case of valid and null if string is not valid url format.
     */
    public static function safeURL(string $url_string): null|string
    {
        if (filter_var($url_string, FILTER_VALIDATE_URL)) {
            return $url_string;
        }

        return null;
    }

    /**
     * if string is valid email format return string else return null.
     */
    public static function safeEmail(string $emailString): null|string
    {
        $safe = null;
        $emailString = strtolower(trim($emailString));
        if (filter_var($emailString, FILTER_VALIDATE_EMAIL)) {
            $safe = $emailString;
        }

        return $safe;
    }

    
    /**
     * @param string $tokenStringInHttpHeader
     * @return string|null
     * @description BearerToken in header is like Brearer ey... this function remove Bearer and space return pure token to be used in JWT
     */
    public static function BearerTokenPurify(string $tokenStringInHttpHeader): null|string
    {
        if (preg_match('/Bearer\s(\S+)/', $tokenStringInHttpHeader, $matches)) {
            $tokenStringInHttpHeader = $matches[1];
            return $tokenStringInHttpHeader;
        }
        return null;
    }
    
    public static function detectWebServer():false|string
    {
        $serverSoftware = isset($_SERVER['SERVER_SOFTWARE']) ? $_SERVER['SERVER_SOFTWARE'] : '';
        if (strpos($serverSoftware, 'Apache') !== false) {
            return 'Apache';
        } elseif (strpos($serverSoftware, 'swoole') !== false) {
            return 'swoole';
        } elseif(strpos($serverSoftware, 'nginx') !== false){
            return "nginx";
        }
        return false;
    }

}