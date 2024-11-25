<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ConversionCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->transform(function ($conversion) {
            return [
                'id' => $conversion->id,
                'integer' => $conversion->integer_value,
                'roman_numeral' => $conversion->roman_numeral,
                'created_at' => $conversion->created_at?->toDateTimeString(),
                'updated_at' => $conversion->updated_at?->toDateTimeString(),
            ];
        })->toArray();
    }
}
