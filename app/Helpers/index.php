<?php

/**
 * Upload file
 *
 * @param String $inputName
 * @param Illuminate\Http\Request $request
 *
 * @return String
 */
function uploadFile($inputName, $request)
{
    if (isValidLink($request->$inputName)) {
        return $request->$inputName;
    } elseif (!isValidLink($request->$inputName)) {
        return $request->$inputName;
    }

    $request->file($inputName)->store('public');
    $path = Storage::putFile('storage', $request->file($inputName));

    return $path;
}

/**
 * Check link is valid or not
 *
 * @param String $url
 *
 * @return boolean
 */
function isValidLink($url)
{
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        return true;
    }

    return false;
}
