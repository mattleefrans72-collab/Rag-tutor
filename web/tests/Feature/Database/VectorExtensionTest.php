<?php

use Illuminate\Support\Facades\DB;

test('the vector extension is enabled', function () {
    $extensionExists = DB::table('pg_extension')
        ->where('extname', 'vector')
        ->exists();

    expect($extensionExists)->toBeTrue();
});
