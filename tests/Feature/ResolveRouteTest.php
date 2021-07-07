<?php

declare(strict_types=1);

it('check if root path can be resolved', function (): void {
    $response = $this->get('/');

    $response->assertStatus(200);
});

it('should be healthy', function (): void {
    $response = $this->get('/api/health');

    $response->assertStatus(200);
});
