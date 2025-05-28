<?php

use App\Models\Psychologist;
use App\Models\Screening;
use App\Models\Response;
use App\Models\Profile;

function dynamic_text_color($color_code)
{
    $hex = str_replace('#', '', $color_code);

    if (strlen($hex) === 3) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }

    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));

    $luminance = (0.299 * $r + 0.587 * $g + 0.114 * $b) / 255;

    return ($luminance > 0.5) ? '#000000' : '#FFFFFF';
}

function random_hex_color()
{
    return sprintf('#%06X', mt_rand(0, 0xFFFFFF));
}

function mask_email($email) {
    [$name, $domain] = explode('@', $email);
    
    return substr($name, 0, 1) . str_repeat('*', max(strlen($name) - 2, 1)) . substr($name, -1) . '@' . $domain;
}

function mask_text($start, $end, $text)
{
    $text_length = strlen($text);

    if ($text_length <= ($start + $end)) {
        return $text;
    }

    $start_part = substr($text, 0, $start);
    $end_part = substr($text, -$end);

    $mask_length = $text_length - ($start + $end);

    return $start_part . str_repeat('*', $mask_length) . $end_part;
}

function check_filled_profile_patient($id)
{
    $check = Profile::where('user_id', $id)->first();

    if ($check) {
        return true;
    } else {
        return false;
    }
}

function check_filled_profile_psychologist($id)
{
    $check = Psychologist::where('user_id', $id)->first();

    if ($check) {
        return true;
    } else {
        return false;
    }
}

function generate_screening_code()
{
    $screening_code = 'SCDD-' . rand(100000, 999999);
    $check = Screening::where('screening_code', $screening_code)->first();

    if ($check) {
        return generate_screening_code();
    } else {
        return $screening_code;
    }
}

function generate_response_code()
{
    $response_code = 'RSDD-' . rand(100000, 999999);
    $check = Response::where('response_code', $response_code)->first();

    if ($check) {
        return generate_response_code();
    } else {
        return $response_code;
    }
}

 function get_initial($name) {
    $words = explode(' ', $name);
    $initials = '';
    foreach ($words as $word) {
        if (isset($word[0])) {
            $initials .= strtoupper($word[0]);
        }
        if (strlen($initials) >= 2) break;
    }
    return substr($initials, 0, 2);
}