<?php

it('check if root path can be resolved', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});
