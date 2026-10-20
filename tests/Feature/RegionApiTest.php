<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class RegionApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_it_returns_provinces_from_the_region_api(): void
    {
        Http::fake([
            'www.emsifa.com/api-wilayah-indonesia/api/provinces.json' => Http::response([
                ['id' => '32', 'name' => 'JAWA BARAT'],
            ]),
        ]);

        $this->getJson('/api/wilayah/provinces')
            ->assertOk()
            ->assertExactJson([
                ['id' => '32', 'name' => 'Jawa Barat'],
            ]);

        Http::assertSent(fn ($request) => $request->url() === 'http://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
    }

    public function test_it_returns_regencies_for_a_province(): void
    {
        $this->assertRegionChildren('regencies', '32', '3201', 'KABUPATEN BOGOR', 'Kabupaten Bogor');
    }

    public function test_it_returns_districts_for_a_regency_or_city(): void
    {
        $this->assertRegionChildren('districts', '3273', '327301', 'BOGOR BARAT', 'Bogor Barat');
    }

    public function test_it_returns_villages_for_a_district(): void
    {
        $this->assertRegionChildren('villages', '327301', '3273011001', 'PASIR JAYA', 'Pasir Jaya');
    }

    public function test_it_returns_an_empty_list_when_a_child_level_has_no_parent(): void
    {
        Http::fake();

        $this->getJson('/api/wilayah/regencies')
            ->assertOk()
            ->assertExactJson([]);

        Http::assertNothingSent();
    }

    public function test_it_rejects_unknown_region_levels(): void
    {
        Http::fake();

        $this->getJson('/api/wilayah/unknown')
            ->assertNotFound();

        Http::assertNothingSent();
    }

    private function assertRegionChildren(string $level, string $parent, string $id, string $apiName, string $expectedName): void
    {
        $url = "http://www.emsifa.com/api-wilayah-indonesia/api/{$level}/{$parent}.json";

        Http::fake([
            $url => Http::response([
                ['id' => $id, 'name' => $apiName],
            ]),
        ]);

        $this->getJson("/api/wilayah/{$level}/{$parent}")
            ->assertOk()
            ->assertExactJson([
                ['id' => $id, 'name' => $expectedName],
            ]);

        Http::assertSent(fn ($request) => $request->url() === $url);
    }
}
