<?php

namespace Feature;

use App\Models\Conversion;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConversionApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the conversion of an integer to roman numeral.
     *
     * @return void
     */
    public function testIntegerToRomanNumeralConversion()
    {
        $data = [
            'integer' => 42,
        ];

        $response = $this->postJson('/api/convert', $data);

        $response->assertStatus(201);

        $response->assertJsonFragment([
            'integer' => 42,
            'roman_numeral' => 'XLII',
        ]);

        $this->assertDatabaseHas('conversions', [
            'integer_value' => 42,
            'roman_numeral' => 'XLII',
        ]);
    }

    /**
     * Test listing top 10 most converted integers.
     *
     * @return void
     */
    public function testTopConversions()
    {
        $outputLimit = 10;
        $factoryLimit = 15;

        for ($i = 1; $i <= $factoryLimit; $i++) {
            Conversion::factory()->count($i)->create([
                'integer_value' => $i,
                'roman_numeral' => 'X' // for testing purposes, this will be X for all integers
            ]);
        }

        $response = $this->getJson('/api/top-conversions');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'integer',
                    'count',
                ],
            ],
        ]);

        $responseData = $response->json('data');

        $this->assertCount($outputLimit, $responseData);

        $this->assertEquals($responseData[0]['integer'], $factoryLimit);
        $this->assertEquals($responseData[0]['count'], $factoryLimit);

        $tenthHighestCount = $factoryLimit - $outputLimit + 1;
        $this->assertEquals($responseData[9]['integer'], $tenthHighestCount);
        $this->assertEquals($responseData[9]['count'], $tenthHighestCount);
    }

    /**
     * Test listing the most recent conversions.
     *
     * @return void
     */
    public function testRecentConversions()
    {
        $conversion1 = Conversion::factory()->create([
            'integer_value' => 10,
            'roman_numeral' => 'X',
            'created_at' => Carbon::now()->subDays(2),
        ]);

        $conversion2 = Conversion::factory()->create([
            'integer_value' => 20,
            'roman_numeral' => 'XX',
            'created_at' => Carbon::now()->subDay(),
        ]);

        $conversion3 = Conversion::factory()->create([
            'integer_value' => 30,
            'roman_numeral' => 'XXX',
            'created_at' => Carbon::now(),
        ]);

        $response = $this->getJson('/api/recent-conversions');

        $response->assertStatus(200);

        $response->assertJsonFragment(['integer' => 30, 'roman_numeral' => 'XXX']);
        $response->assertJsonFragment(['integer' => 20, 'roman_numeral' => 'XX']);
        $response->assertJsonFragment(['integer' => 10, 'roman_numeral' => 'X']);

        $responseData = $response->json('data');

        $this->assertTrue(
            $responseData[0]['id'] === $conversion3->id, // Most recent first
            'The most recent conversion is not first in the list.'
        );
        $this->assertTrue(
            $responseData[1]['id'] === $conversion2->id,
            'The second conversion is not in the correct position.'
        );
        $this->assertTrue(
            $responseData[2]['id'] === $conversion1->id,
            'The third conversion is not in the correct position.'
        );

        $this->assertCount(3, $response->json('data'));
    }
}
