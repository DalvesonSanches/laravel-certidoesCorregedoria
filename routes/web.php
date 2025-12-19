<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Home;
use App\Livewire\CertidaoNova;
use App\Livewire\CertidaoConsulta;

Route::get('/', Home::class)->name('home');
Route::get('/home', Home::class)->name('home');
Route::get('/certidao-nova', CertidaoNova::class)->name('certidao-nova');
Route::get('/certidao-consulta', CertidaoConsulta::class)->name('certidao-consulta');