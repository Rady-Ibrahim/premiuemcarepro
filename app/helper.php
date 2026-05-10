<?php

use Illuminate\Support\Facades\Storage;

function store_file($file, $path)
{
    $name = time() . $file->getClientOriginalName();
    return $value = $file->storeAs($path, $name, 'uploads');
}
function delete_file($file)
{
    if ($file != '' and !is_null($file) and Storage::disk('uploads')->exists($file)) {
        unlink('uploads/' . $file);
    }
}
function display_file($name)
{
    return asset('uploads') . '/' . $name;
}
