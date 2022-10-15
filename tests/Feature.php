<?php

test('example', function () {
    $client = new \MidiaSimples\IntegratorSDK\Client('');

    $credentialsInputs = [
        '_user' => '',
        '_passwd' => '',
    ];

    $data = [
//        'cpf' => '022.990.555-25',
//        'codsercli' => 'IIUMEKDFUF',
        'codcli' => '4525',
    ];

//    dd($client->call('client.exists', array_merge($credentialsInputs, $data)));
//    dd($client->call('service.getInfo', array_merge($credentialsInputs, $data)));
//    dd($client->call('contacts.list', array_merge($credentialsInputs, $data)));
//    dd($client->call('datasource.lista_faturas', array_merge($credentialsInputs, $data)));
//    dd($client->call('datasource.cliente_cadastro', array_merge($credentialsInputs, $data)));
    dd($client->call('datasource.cliente_atendimentos', array_merge($credentialsInputs, $data)));
//    expect(true)->toBeTrue();
});
