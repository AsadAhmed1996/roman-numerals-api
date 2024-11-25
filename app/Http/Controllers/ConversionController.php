<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConvertIntegerRequest;
use App\Http\Resources\ConversionCollection;
use App\Http\Resources\ConversionCountCollection;
use App\Http\Resources\ConversionResource;
use App\Models\Conversion;
use App\Services\IntegerConverterInterface;

class ConversionController extends Controller
{
    public function __construct(
        private readonly IntegerConverterInterface $integerConverter,
    ) {
    }

    public function convertInteger(ConvertIntegerRequest $request): ConversionResource
    {
        $integer = $request->input('integer');
        $roman = $this->integerToRomanNumeral($integer);

        $conversion = Conversion::create([
            'integer_value' => $integer,
            'roman_numeral' => $roman,
        ]);

        return new ConversionResource($conversion);
    }

    public function listRecentConversions(): ConversionCollection
    {
        $conversions = Conversion::latest()->get();

        return new ConversionCollection($conversions);
    }

    public function listTop10Conversions(): ConversionCountCollection
    {
        $topConversions = Conversion::selectRaw('integer_value, COUNT(*) as count')
            ->groupBy('integer_value')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        return new ConversionCountCollection($topConversions);
    }

    private function integerToRomanNumeral($integer): string
    {
        return $this->integerConverter->convertInteger($integer);
    }
}

