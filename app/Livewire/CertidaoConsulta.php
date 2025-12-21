<?php

namespace App\Livewire;
use Livewire\Component;
use TallStackUi\Traits\Interactions;
use App\Models\Certidao;
use Carbon\Carbon;


class CertidaoConsulta extends Component{
    use Interactions;
    public bool $mostrarResultado = false;
    public $certidao = null;
    public string $codigoAutenticidade = '';

    public function quantidadeCodigoAutenticidade($codigoAutenticidade){
        if (mb_strlen($codigoAutenticidade, 'UTF-8') !== 40) {
           return false;
        } 
        return true;
    }

    public function consultar(){
        if (!$this->codigoAutenticidade) {
            $this->dialog()->warning('Atenção', 'Autenticidade não pode ficar em branco.')->send();
            return;
        }

        if (!$this->quantidadeCodigoAutenticidade($this->codigoAutenticidade)) {
            $this->dialog()->error('Autenticidade inválida', 'O codigo informado não é válido.')->send();
            return;
        }

        // Busca a certidão pelo código de autenticidade
        $certidao = Certidao::where('cod_autenticidade', $this->codigoAutenticidade)
            ->select('data_validade', 'situacao', 'arquivo_nome', 'militar_nome', 'numero_certidao', 'cod_autenticidade')
            ->first();

        // Não encontrou
        if (!$certidao) {
            $this->dialog()->error('Não encontrada', 'Nenhuma certidão encontrada para este código.')->send();
            return;
        }

        // Verifica validade (data_validade está em TIMESTAMP)
        $agora = now()->timestamp;

        if ($certidao->data_validade < $agora) {
            $this->dialog()->error('Certidão vencida', 'Esta certidão não está mais válida.')->send();
            return;
        }

        // Verifica situação (exemplo)
        if ($certidao->situacao !== 'ATIVO') {
            $this->dialog()->error('Certidão inválida', 'Esta certidão não está ativa.')->send();
            return;
        }

        $this->certidao = $certidao; //envia os valores do select para uma variavel publica
        $this->mostrarResultado = true; //altera a flag somente se tiver encontrado o cod autenticidade

        // Tudo OK
        //$this->dialog()->success('Successo', 'Autenticidade válido! Prosseguindo..')->send();
    }

     public function voltar(){
        return redirect()->to(route('home'));
    }

    public function render(){
        return view('livewire.certidao-consulta');
    }
}
