<?php

namespace App\Http\Controllers\Api;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ItemUnits
{
    public function select(Request $request)
    {
        return Unit::query()
            ->select('id', 'name as value')
            ->orderBy('value')
            ->when(
                $request->q,
                fn(Builder $query) => $query
                    ->where('name', 'like', "%{$request->q}%")
            )
            ->when(
                $request->exists('selected'),
                fn(Builder $query) => $query->whereIn('id', $request->input('selected', []))
            )
            ->get();
    }

}
