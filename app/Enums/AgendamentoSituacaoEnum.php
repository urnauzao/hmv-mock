<?php

namespace App\Enums;

abstract class AgendamentoSituacaoEnum
{
    const AGENDADO = 'agendado';
    const NA_ESPERA = 'na_espera';
    const EM_REALIZACAO = 'em_realizacao';
    const REALIZADO = 'realizado';
    const NAO_REALIZADO = 'nao_realizado';
    const EMERGENCIA = 'emergencia';
}

