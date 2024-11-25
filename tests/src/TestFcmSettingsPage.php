<?php

namespace TomatoPHP\FilamentFcmDriver\Tests;

use TomatoPHP\FilamentFcmDriver\Filament\Pages\FcmSettingsPage;
use TomatoPHP\FilamentFcmDriver\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    actingAs(User::factory()->create());
});

it('can render Discord Settings Page', function () {
    get(FcmSettingsPage::getUrl())->assertSuccessful();
});
