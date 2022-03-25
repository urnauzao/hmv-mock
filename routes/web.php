<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return response()->json([
        ["GET|HEAD" => "/"],
        ["GET|HEAD" => "api/admin/metricas/{perfil}"],
        ["GET|HEAD" => "api/agendamento/estabelecimento/{estabelecimento}"],
        ["GET|HEAD" => "api/agendamento/medico/{perfil}"],
        ["GET|HEAD" => "api/agendamento/paciente/{perfil}"],
        ["GET|HEAD" => "api/agendamentos"],
        ["GET|HEAD" => "api/associativa-endereco/{id}"],
        ["GET|HEAD" => "api/associativa-enderecos"],
        ["GET|HEAD" => "api/atendente/agendamento/definir_medico/{agendamento}/{perfil}"],
        ["GET|HEAD" => "api/atendente/agendamento/estabelecimentos/{perfil}"],
        ["POST"     => "api/atendente/agendamento/novo/{perfil}"],
        ["PUT"      => "api/atendente/agendamento/situacao/{agendamento}/{situacao}"],
        ["GET|HEAD" => "api/atendente/metricas"],
        ["GET|HEAD" => "api/endereco/meus"],
        ["GET|HEAD" => "api/endereco/{id}"],
        ["GET|HEAD" => "api/enderecos"],
        ["GET|HEAD" => "api/estabelecimento/{id}"],
        ["GET|HEAD" => "api/estabelecimentos"],
        ["GET|HEAD" => "api/habito_saude/novo/{perfil}"],
        ["POST"     => "api/habito_saude/novo/{perfil}"],
        ["POST"     => "api/medico/atendimento/{perfil}"],
        ["GET|HEAD" => "api/medico/habito_saude/{perfil}"],
        ["GET|HEAD" => "api/medico/historico/{perfil}"],
        ["GET|HEAD" => "api/medico/metricas/{perfil}"],
        ["GET|HEAD" => "api/medico/questionario_emergencia/{perfil}"],
        ["POST"     => "api/medico/relatorio_atendimento/{perfil}"],
        ["POST"     => "api/paciente/chamado_emergencia/{perfil}"],
        ["GET|HEAD" => "api/paciente/metricas/{perfil}"],
        ["GET|HEAD" => "api/perfil/{id}"],
        ["GET|HEAD" => "api/perfis"],
        ["GET|HEAD" => "api/pergunta-emergencia/{id}"],
        ["GET|HEAD" => "api/perguntas-emergencia"],
        ["GET|HEAD" => "api/permissoes-perfis"],
        ["GET|HEAD" => "api/questionario-emergencia/{id}"],
        ["GET|HEAD" => "api/questionario_emergencia/novo/{perfil}"],
        ["POST"     => "api/questionario_emergencia/novo/{perfil}"],
        ["GET|HEAD" => "api/questionarios-emergencia"],
        ["POST"     => "api/sanctum/token"],
        ["GET|HEAD" => "api/socorrista/metricas/{perfil}"],
        ["GET|HEAD" => "api/usuario/dados"],
        ["GET|HEAD" => "api/usuario/{id}"],
        ["GET|HEAD" => "sanctum/csrf-cookie"]
    ]);
});
