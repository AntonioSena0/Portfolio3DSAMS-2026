<?php

namespace Tests\Feature;

use Tests\TestCase;

class PortalAccessTest extends TestCase
{
    public function test_portal_displays_messages_shared_by_middleware(): void
    {
        $response = $this->get('/portal');

        $response
            ->assertOk()
            ->assertSeeText('Bem vindo ao portal')
            ->assertSeeText('Seu acesso não foi autorizado.')
            ->assertSeeText('Entrar em contato com o administrador.');
    }
}
