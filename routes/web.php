<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Home;
use App\Livewire\CertidaoNova;
use App\Livewire\CertidaoConsulta;

Route::get('/', Home::class)->name('home');
Route::get('/home', Home::class)->name('home');
Route::get('/certidao-nova', CertidaoNova::class)->name('certidao-nova');
Route::get('/certidao-consulta', CertidaoConsulta::class)->name('certidao-consulta');
//rota para download da certidao criada usando cod autenticidade como parametro da rota
Route::get('/certidoes/{codigo}/pdf', function ($codigo) {
    $certidao = \App\Models\Certidao::where('cod_autenticidade', $codigo)->firstOrFail();//busca nome arquivo baseado no codigo
    $caminho = storage_path('app/certidoes/' . $certidao->arquivo_nome);//monta o caminho com o nome do retorno da busca aciima
    abort_if(!file_exists($caminho), 404);//erro
    return response()->file($caminho);//sucesso
})->name('certidao.pdf');
