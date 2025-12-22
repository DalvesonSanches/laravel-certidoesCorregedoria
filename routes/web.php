<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Home;
use App\Livewire\CertidaoNova;
use App\Livewire\CertidaoConsulta;

Route::get('/', Home::class)
    ->name('home');
Route::get('/home', Home::class)
    ->name('home');
Route::get('/certidao-nova', CertidaoNova::class)
    ->name('certidao-nova');
Route::get('/certidao-consulta', CertidaoConsulta::class)
    ->name('certidao-consulta');
//rota para download da certidao criada usando cod autenticidade como parametro da rota
Route::get('/certidoes/{codigo}/pdf', [CertidaoNova::class, 'baixarPdf'])
    ->name('certidao.pdf');
//rota autenticidade qrcode
Route::get('/certidoes/{codigo}/validar', [CertidaoNova::class, 'validarCertidao'])
    ->name('certidao.validar');
