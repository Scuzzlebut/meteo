<?php
function custom_json_decode($json, $assoc = false)
{
    $i = 0;
    $n = strlen($json);
    $result = json_decode_value($json, $i, $assoc);
    while ($i < $n && $json[$i] && $json[$i] <= ' ') $i++;
    if ($i < $n) {
        return null;
    }
    return $result;
}

function json_decode_value($json, &$i, $assoc = false)
{
    $n = strlen($json);
    while ($i < $n && $json[$i] && $json[$i] <= ' ') $i++;

    switch ($json[$i]) {
        // object
        case '{':
            $i++;
            $result = $assoc ? array() : new stdClass();
            while ($i < $n && $json[$i] && $json[$i] <= ' ') $i++;
            if ($json[$i] === '}') {
                $i++;
                return $result;
            }
            while ($i < $n) {
                $key = json_decode_string($json, $i);
                while ($i < $n && $json[$i] && $json[$i] <= ' ') $i++;
                if ($json[$i++] != ':') {
                    return null;
                }
                if ($assoc) {
                    $result[$key] = json_decode_value($json, $i, $assoc);
                } else {
                    $result->$key = json_decode_value($json, $i, $assoc);
                }
                while ($i < $n && $json[$i] && $json[$i] <= ' ') $i++;
                if ($json[$i] === '}') {
                    $i++;
                    return $result;
                }
                if ($json[$i++] != ',') {
                    return null;
                }
                while ($i < $n && $json[$i] && $json[$i] <= ' ') $i++;
            }
            return null;
        // array
        case '[':
            $i++;
            $result = array();
            while ($i < $n && $json[$i] && $json[$i] <= ' ') $i++;
            if ($json[$i] === ']') {
                $i++;
                return array();
            }
            while ($i < $n) {
                $result[] = json_decode_value($json, $i, $assoc);
                while ($i < $n && $json[$i] && $json[$i] <= ' ') $i++;
                if ($json[$i] === ']') {
                    $i++;
                    return $result;
                }
                if ($json[$i++] != ',') {
                    return null;
                }
                while ($i < $n && $json[$i] && $json[$i] <= ' ') $i++;
            }
            return null;
        // string
        case '"':
            return json_decode_string($json, $i);
        // number
        case '-':
            return json_decode_number($json, $i);
        // true
        case 't':
            if ($i + 3 < $n && substr($json, $i, 4) === 'true') {
                $i += 4;
                return true;
            }
        // false
        case 'f':
            if ($i + 4 < $n && substr($json, $i, 5) === 'false') {
                $i += 5;
                return false;
            }
        // null
        case 'n':
            if ($i + 3 < $n && substr($json, $i, 4) === 'null') {
                $i += 4;
                return null;
            }
        default:
            // number
            if ($json[$i] >= '0' && $json[$i] <= '9') {
                return json_decode_number($json, $i);
            } else {
                return null;
            };
    }
}

function json_decode_string($json, &$i)
{
    $result = '';
    $escape = array('"' => '"', '\\' => '\\', '/' => '/', 'b' => "\b", 'f' => "\f", 'n' => "\n", 'r' => "\r", 't' => "\t");
    $n = strlen($json);
    if ($json[$i] === '"') {
        while (++$i < $n) {
            if ($json[$i] === '"') {
                $i++;
                return $result;
            } elseif ($json[$i] === '\\') {
                $i++;
                if ($json[$i] === 'u') {
                    $code = "&#".hexdec(substr($json, $i + 1, 4)).";";
                    $result .= html_entity_decode($code, ENT_QUOTES, 'UTF-8');
                    $i += 4;
                } elseif (isset($escape[$json[$i]])) {
                    $result .= $escape[$json[$i]];
                } else {
                    break;
                }
            } else {
                $result .= $json[$i];
            }
        }
    }
    return null;
}

function json_decode_number($json, &$i)
{
    $result = '';
    if ($json[$i] === '-') {
        $result = '-';
        $i++;
    }
    $n = strlen($json);
    while ($i < $n && $json[$i] >= '0' && $json[$i] <= '9') {
        $result .= $json[$i++];
    }

    if ($i < $n && $json[$i] === '.') {
        $result .= '.';
        $i++;
        while ($i < $n && $json[$i] >= '0' && $json[$i] <= '9') {
            $result .= $json[$i++];
        }
    }
    if ($i < $n && ($json[$i] === 'e' || $json[$i] === 'E')) {
        $result .= $json[$i];
        $i++;
        if ($json[$i] === '-' || $json[$i] === '+') {
            $result .= $json[$i++];
        }
        while ($i < $n && $json[$i] >= '0' && $json[$i] <= '9') {
            $result .= $json[$i++];
        }
    }

    return (0 + $result);
}

