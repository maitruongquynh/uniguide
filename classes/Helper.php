<?php
class Helper
{

    public static function getActive($page = null) {
        if (!empty($page)) {
            if (is_array($page)) {
                $error = array();
                foreach ($page as $key => $value) {
                    if (Url::getParam($key) != $value) {
                        array_push($error, $key);
                    }
                }
                return empty($error) ? " class=\"act\"" : null;
            }
        }
        return $page == Url::cPage() ? " class=\"act\"" : null;
    }
    
    public static function encodeHTML($string, $case = 2) {
        switch ($case) {
            case 1:
                return htmlentities($string, ENT_NOQUOTES, 'UTF-8', false);
                break;

            case 2:
                $pattern = '<([a-zA-Z0-9\.\, "\'_\/\-\+~=;:\(\)?&#%![\]@]+)>';
                
                // put text only, devided with html tags into array
                $textMatches = preg_split('/' . $pattern . '/', $string);
                
                // array for sanitised output
                $textSanitised = array();
                foreach ($textMatches as $key => $value) {
                    $textSanitised[$key] = htmlentities(html_entity_decode($value, ENT_QUOTES, 'UTF-8'), ENT_QUOTES, 'UTF-8');
                }
                foreach ($textMatches as $key => $value) {
                    $string = str_replace($value, $textSanitised[$key], $string);
                }
                return $string;
                break;
        }
    }
    
    public static function getImgSize($image, $case) {
        if (is_file($image)) {
            
            // 0 => width, 1 => height, 2 => type, 3 => attribute
            $size = getimagesize($image);
            return $size[$case];
        }
    }
    
    public static function shortenString($string, $len = 150) {
        if (strlen($string) > $len) {
            $string = trim(substr($string, 0, $len));
            $string = substr($string, 0, strrpos($string, " ")) . "&hellip;";
        } 
        else {
            $string.= "&hellip;";
        }
        return $string;
    }
    
    public static function redirect($Url = null) {
        if (!empty($Url)) {
            header("Location: {$Url}");
            exit;
        }
    }
    
    public static function setDate($case = null, $date = null) {
        $date = empty($date) ? time() : strtotime($date);
        
        switch ($case) {
            case 1:
                
                // 01/01/2010
                return date('d/m/Y', $date);
                break;

            case 2:
                
                // Monday, 1st January 2010, 09:30:56
                return date('l, jS F Y, H:i:s', $date);
                break;

            case 3:
                
                // 2010-01-01-09-30-56
                return date('Y-m-d-H-i-s', $date);
                break;

            default:
                return date('Y-m-d H:i:s', $date);
        }
    }
    
    public static function process($case = null, $array = null) {
        if (!empty($case)) {
            switch ($case) {
                case 1:
                    
                    // add Url to the array
                    $link = "<a href=\"" . SITE_Url . ":8888" . "/start/?page=activate&code=";
                    $link .= $array['hash'];
                    $link .= "\">";
                    $link .= SITE_Url . ":8888" . "/start/?page=activate&code=" . $array['hash'] . "</a>";
                    $array['link'] = $link;
                    $_POST['link'] = $link;

                    break;
            }
            return true;
        }
    }

    public static function cleanString($name = null) {
        if (!empty($name)) {
            return strtolower(preg_replace('/[^a-zA-Z0-9.]/', '-', $name));
        }
    }

    public static function getSchoolUrl() {
        $uri = $_SERVER['REQUEST_URI'];
        $firstChar = substr($uri, 0, 1);
        if ($firstChar == "/") {
            $uri = substr($uri, 1);
        }

        $lastChar = substr($uri, -1);
        if ($lastChar == "/") {
            $uri = substr($uri, 0, -1);
        }

        $uri = explode('/', $uri);
        return $uri[1];
    }

    public static function isEmpty($value = null) {
        return empty($value) && !is_numeric($value) ? true : false;
        //so 0 van bi ham empty coi la empty nhung thuc ra la van co gia tri nen phai la not empty
        //nen moi phai dung method rieng de kiem tra xem vua empty va vua khong phai la so 0
        //luc do moi tra ve la true
    }

    public static function makeArray($array = null) {
        return is_array($array) ? $array : array($array);
    }

    public static function json($input = null) {
            if(!empty($input)) {
                if(defined("JSON_UNESCAPED_UNICODE")) {
                    return json_encode($input, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
                } else {
                    return json_encode($input, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
                }
            }
        }

}
?>