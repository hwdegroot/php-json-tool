<?php

it('check if root path can be resolved', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

it('should be healthy', function () {
    $response = $this->get('/api/health');

    $response->assertStatus(200);
});
