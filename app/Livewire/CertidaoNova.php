<?php

namespace App\Livewire;
use Livewire\Component;
use TallStackUi\Traits\Interactions; //mensagem de alerta
use Illuminate\Support\Facades\DB; //acesso ao banco de dados
use App\Models\Certidao;
use Illuminate\Support\Str; //gera codigo randonico
use Exception; //gera exception
use Carbon\Carbon; //calculo de datas
use Symfony\Component\HttpFoundation\Response;

class CertidaoNova extends Component{
    use Interactions;
    public $cpf;
    public $militar = null;         // stdClass com os dados retornados da consulta (ou null)
    public bool $sucesso = false;
    public ?int $certidaoId = null;
    public ?Certidao $certidao = null;

    //função para gerar o pdf com TCPDF
   /*
    public function gerarPdf(){
        $service = app(\App\Services\GerarCertidaoPdfService::class);

        $arquivo = $service->gerar([
            'id' => $this->certidao->id,
            'numero' => $this->certidao->numero_certidao,
            'codigo_autenticidade' => $this->certidao->cod_autenticidade,
            'url_autenticacao' => route('certidao.validar', $this->certidao->cod_autenticidade),//ver como vai ficar aqui
        ]);

        return response()->file($arquivo);
    }
    */
    //função para realizar o donwload do pdf no botao download nas rotas
    public function baixarPdf(string $codigo){
        // Busca a certidão pelo código
        $certidao = Certidao::where('cod_autenticidade', $codigo)->first();
        if (!$certidao) {
            abort(Response::HTTP_NOT_FOUND, 'Certidão não encontrada.');
        }
        // Caminho do arquivo
        $caminho = storage_path('app/certidoes/' . $certidao->arquivo_nome);
        // Verifica se o arquivo existe
        if (!file_exists($caminho)) {
            abort(Response::HTTP_NOT_FOUND, 'Arquivo não encontrado.');
        }
        // Retorna o PDF
        return response()->file($caminho);
    }

    //validar o qrcode
    public function validarCertidao(string $codigo){
        $erro = null;
        // Busca certidão
        $certidao = Certidao::where('cod_autenticidade', $codigo)->first();
        if (!$certidao) {
            $erro = 'Certidão não encontrada.';
        }
        elseif ($certidao->situacao !== 'ATIVO') {
            $erro = 'Sua certidão não está ativa.';
        }
        elseif (strtotime($certidao->data_validade) < time()) {
            $erro = 'Sua certidão está vencida.';
        }
        return view('livewire.certidao-validar', [
            'certidao' => $certidao,
            'erro'     => $erro
        ]);
    }

    //função para validar o cpf
    private function validarCPF(string $cpf): bool{
        // remove tudo que não for número
        $cpf = preg_replace('/\D/', '', $cpf);
        // valida tamanho e números repetidos
        if (strlen($cpf) !== 11 || preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }
        // valida dígitos verificadores
        for ($t = 9; $t < 11; $t++) {
            $d = 0;
            for ($c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ((int) $cpf[$c] !== $d) {
                return false;
            }
        }
        return true;
    }

    //função para destacar o nome de guerra em negrito
    private function nomeGuerraNegrito(string $nomeGuerra, string $postoGraduacao, string $quadro, string $nome): string {
        // Posto + Quadro em negrito
        $postoGraduacaoFormatado = "<strong>{$postoGraduacao} {$quadro}</strong> ";
        // Quebra nomes
        $nomeGuerraArray = explode(' ', $nomeGuerra);
        $nomeCompletoArray = explode(' ', $nome);
        $resultado = $nomeCompletoArray;
        foreach ($nomeGuerraArray as $guerra) {
            $guerra = str_replace('.', '', $guerra);
            foreach ($nomeCompletoArray as $key => $parteNome) {
                // Nome abreviado (ex: J.)
                if (strlen($guerra) <= 2) {
                    if (str_starts_with($parteNome, $guerra)) {
                        $resultado[$key] =
                            "<strong>" . substr($parteNome, 0, strlen($guerra)) . "</strong>" .
                            substr($parteNome, strlen($guerra));
                    }
                }
                // Nome completo
                else {
                    if ($parteNome === $guerra) {
                        $resultado[$key] = "<strong>{$parteNome}</strong>";
                    }
                }
            }
        }
        return $postoGraduacaoFormatado . implode(' ', $resultado);
    }

    //função gera codigo autenticidade 40 caracteres
    private function gerarCodigoAutenticidade(): string{
        return Str::ulid() . Str::random(14);
    }

    ///funcao gerar numero da certidoes
    private function gerarNumeroCertidao(): string{
        return DB::transaction(function () {

            $anoCorrente = now()->year;

            // Lock explícito (PostgreSQL)
            DB::statement(
                "LOCK TABLE corregedoria.certidoes IN EXCLUSIVE MODE"
            );

            $ultimaCertidao = DB::table('corregedoria.certidoes')
                ->orderByDesc('id')
                ->value('numero_certidao');

            if ($ultimaCertidao) {
                [, $anoCertidao] = explode('/', $ultimaCertidao);
            } else {
                $anoCertidao = $anoCorrente;
            }

            if ((int) $anoCertidao !== $anoCorrente) {
                DB::statement(
                    "ALTER SEQUENCE corregedoria.numero_certidoes_seq RESTART WITH 1"
                );
                $anoCertidao = $anoCorrente;
            }

            $sequencial = DB::scalar(
                "SELECT nextval('corregedoria.numero_certidoes_seq')"
            );

            return str_pad($sequencial, 6, '0', STR_PAD_LEFT) . '/' . $anoCertidao;
        });
    }

    //função para buscar informações do militar
    private function buscarMilitarPorCpf(string $cpfNumeros): ?object{
        return DB::table('admin.pessoas as pessoas')
            ->leftJoin('rh.cargos as cargos', 'pessoas.cargo_id', '=', 'cargos.id')
            ->leftJoin('rh.quadros as quadros', 'pessoas.quadro_id', '=', 'quadros.id')
            ->where('pessoas.cpf', $cpfNumeros)
            ->where('pessoas.tipo_pessoa_id', 1)
            ->whereNotIn('pessoas.situacao_id', [8, 7, 10, 73, 79])
            ->select(
                'pessoas.id as pessoa_id',
                'pessoas.matricula',
                'pessoas.rgm_numero',
                'pessoas.nome_guerra',
                'pessoas.nome',
                DB::raw('cargos.sigla as cargo_sigla'),
                DB::raw('quadros.sigla as quadro_sigla'),
                'pessoas.cpf'
            )
            ->first(); // retorna stdClass ou null
    }

    //clique do solicitar
    public function solicitar(){
        //imput cpf em branco
        if (!$this->cpf) {
            $this->dialog()->warning('Atenção', 'O CPF não pode ficar em branco.')->send();
            return;
        }

        //cpf invalido
        if (!$this->validarCPF($this->cpf)) {
            $this->dialog()->error('CPF inválido', 'O CPF informado não é válido.')->send();
            return;
        }

        // normaliza CPF (apenas dígitos) antes de consultar
        $cpfNumeros = preg_replace('/\D/', '', $this->cpf);

        // realiza a busca com militar que esta solicitando a certidao (usando o CPF normalizado)
        $militarCertidao = $this->buscarMilitarPorCpf($cpfNumeros);

        // não encontrou no banco do RH
        if (!$militarCertidao) {
            $this->dialog()->error('CPF inválido', 'Nenhum registro encontrado para esse CPF.')->send();
            return;
        }

        // sem nome de guerra no RH CBMAP
        if (!$militarCertidao->nome_guerra) {
            $this->dialog()->error('Nome de Guerra', 'Militar sem nome de guerra cadastro no RH do CBMAP.')->send();
            return;
        }

         // sem nome no RH CBMAP
        if (!$militarCertidao->nome) {
            $this->dialog()->error('Nome', 'Militar sem nome cadastro no RH do CBMAP.')->send();
            return;
        }

        // sem posto/graduação no RH CBMAP
        if (!$militarCertidao->cargo_sigla) {
            $this->dialog()->error('Posto/Graduação', 'Militar sem posto/graduação cadastro no RH do CBMAP.')->send();
            return;
        }

        // sem quadro no RH CBMAP
        if (!$militarCertidao->quadro_sigla) {
            $this->dialog()->error('Quadro', 'Militar sem quadro cadastro no RH do CBMAP.')->send();
            return;
        }

        // sem rgm no RH CBMAP
        if (!$militarCertidao->rgm_numero) {
            $this->dialog()->error('RGM', 'Militar sem rgm cadastro no RH do CBMAP.')->send();
            return;
        }

        // sem rgm no matricula CBMAP
        if (!$militarCertidao->matricula) {
            $this->dialog()->error('Matricula', 'Militar sem matricula cadastro no RH do CBMAP.')->send();
            return;
        }

        //usa função para formatar nome guerra negrito do militar solicitante da certidao
        $nomeFormatado = $this->nomeGuerraNegrito($militarCertidao->nome_guerra, $militarCertidao->cargo_sigla, $militarCertidao->quadro_sigla, $militarCertidao->nome);

        // adiciona nome_formatado ao objeto retornado do banco
        $militarCertidao->nome_formatado = $nomeFormatado;

        //busca todas as condenções em validade
        // realiza a busca com query builder (usando o CPF normalizado)
        $condenacoesValidas = DB::table('corregedoria.processos_membros as processos_membros')
            ->select('processos_membros.id as processos_membros_id')
            ->where('processos_membros.membro_cpf', $cpfNumeros)
            ->where('processos_membros.deletado', false)
            ->where('processos_membros.culpado', true)
            ->whereDate('processos_membros.fim_pena', '>', now()->toDateString())
            ->first();

        //encontrou condenções em validade
        if ($condenacoesValidas) {
            $this->dialog()->error('Erro', 'A certidão não pôde ser expedida automaticamente devido a inconsistências no cadastro ou a inscrições positivas. Favor procurar a Corregedoria-Geral para regularizar as pendências.')->send();
            return;
        }

        //busca quantidade de condenações positivas
        $qtdIncricoesPositivas = DB::table('corregedoria.processos as processos')
            ->join('corregedoria.tipos_processos as tipos_processos', 'processos.tipos_processos_id', '=', 'tipos_processos.id')
            ->join('corregedoria.tipos_situacoes as tipos_situacoes', 'processos.tipos_situacoes_id', '=', 'tipos_situacoes.id')
            ->join('corregedoria.processos_membros as processos_membros', 'processos.id', '=', 'processos_membros.processos_id')
            ->join('corregedoria.tipos_membros as tipos_membros', 'processos_membros.tipos_membros_id', '=', 'tipos_membros.id')
            ->where('processos.deletado', false)
            ->where('processos_membros.deletado', false)
            ->where('processos_membros.membro_cpf', $cpfNumeros)
            ->where('tipos_processos.emiti_certidao', false)
            ->where('tipos_situacoes.emiti_certidao', false)
            ->where('tipos_membros.emiti_certidao', false)
            ->count('processos.id');

        //se encontrou pelo menos 1 condenações positivas
        if ($qtdIncricoesPositivas > 0) {
            $this->dialog()->error('Erro', 'A certidão não pôde ser expedida automaticamente devido a inconsistências no cadastro ou a inscrições positivas. Favor procurar a Corregedoria-Geral para regularizar as pendências.')->send();
            return;
        }

        //gera codigo de autenticidade atraves da função
        $codigoAutenticidade = $this->gerarCodigoAutenticidade();

         //se erro ao gerrar codigo de autenticidade
        if (!$codigoAutenticidade) {
            $this->dialog()->error('Erro', 'Não foi possivel gerar a autenticidade da certidão.')->send();
            return;
        }

        //gera numero da certidao
        $numeroCertidao = $this->gerarNumeroCertidao();

        //se erro ao gerrar numero certidao
        if (!$numeroCertidao) {
            $this->dialog()->error('Erro', 'Não foi possivel geraro número da certidão.')->send();
            return;
        }

        //validade de 30 dias
        $data_validade = (new \DateTime())
            ->modify('+30 days')
            ->format('Y-m-d H:i:s');

        //busca o corregedor ativo
        $corregedor = DB::table('corregedoria.tipos_funcoes as tipos_funcoes')
            ->join('corregedoria.militares_funcoes as militares_funcoes', 'tipos_funcoes.id','=','militares_funcoes.tipos_funcoes_id')
            ->where('tipos_funcoes.ativo', true)
            ->where('militares_funcoes.ativo', true)
            ->where('tipos_funcoes.corregedor', true)
            ->select('militares_funcoes.militar_cpf')
            ->first();
        
        // se encontrou corregedor gera a variavel
        if ($corregedor) {
            $corregedorCpf = $corregedor->militar_cpf;
        }
        // se não exibir o erro
        else{
            $this->dialog()->error('Erro', 'Não foi encontrado um militar na função de corregor.')->send();
            return;
        }

         // realiza a busca com militar que esta solicitando a certidao (usando o CPF normalizado)
        $militarCorregedor= $this->buscarMilitarPorCpf($corregedorCpf);

        // sem nome de guerra no RH CBMAP
        if (!$militarCorregedor->nome_guerra) {
            $this->dialog()->error('Nome de Guerra', 'Corregedor sem nome de guerra cadastro no RH do CBMAP.')->send();
            return;
        }

         // sem nome no RH CBMAP
        if (!$militarCorregedor->nome) {
            $this->dialog()->error('Nome', 'Corregedor sem nome cadastro no RH do CBMAP.')->send();
            return;
        }

        // sem posto/graduação no RH CBMAP
        if (!$militarCorregedor->cargo_sigla) {
            $this->dialog()->error('Posto/Graduação', 'Corregedor sem posto/graduação cadastro no RH do CBMAP.')->send();
            return;
        }

        // sem quadro no RH CBMAP
        if (!$militarCorregedor->quadro_sigla) {
            $this->dialog()->error('Quadro', 'Corregedor sem quadro cadastro no RH do CBMAP.')->send();
            return;
        }

        //usa função para formatar nome guerra negrito do corregedor
        $nomeFormatadoCorregedor = $this->nomeGuerraNegrito($militarCorregedor->nome_guerra, $militarCorregedor->cargo_sigla, $militarCorregedor->quadro_sigla, $militarCorregedor->nome);

        // salva resultado em uma variável pública para a view
        $this->militar = $militarCertidao;

        // Mensagem de sucesso simples (evite concatenar objeto)
        //$this->dialog()->success('Sucesso', 'Pessoa encontrada: ' . ($row->nome_guerra ?? $row->nome))->send();

        // — se quiser inspecionar todo o objeto na mensagem (para debug), converta para JSON:
        // $this->dialog()->info('DEBUG', json_encode($row))->send();
        $nomeArquivo = str_replace('/', '_', $numeroCertidao) . '.pdf';

        // Agora criar a certidão usando Eloquent:
        try {
            $payload = [
                'cod_autenticidade'     => $codigoAutenticidade,
                'militar_cpf'           => $this->militar->cpf,
                'militar_nome'          => $nomeFormatado,
                'corregedor_cpf'        => $corregedorCpf,
                'data_validade'         => $data_validade,
                'arquivo_nome'          => $nomeArquivo,
                'numero_certidao'       => $numeroCertidao,
                'corregedor_nome'       => $nomeFormatadoCorregedor,
                'militar_rg'            => $this->militar->rgm_numero,
                'militar_matricula'     => $this->militar->matricula,
            ];

            // salvar via Eloquent e enviar as informaçoes para view:
            $this->certidao = Certidao::create($payload);
            //dd(Certidao::create($payload));
            
            //GERA O PDF IMEDIATAMENTE
            app(\App\Services\GerarCertidaoPdfService::class)->gerar([
                'id'                    => $this->certidao->id,
                'numero'                => $this->certidao->numero_certidao,
                'codigo_autenticidade'  => $this->certidao->cod_autenticidade,
                'url_autenticacao'      => route('certidao.validar', $this->certidao->cod_autenticidade),
                'arquivo_nome'          => $this->certidao->arquivo_nome,
                'militar_cpf'           => $this->militar->cpf,
                'militar_nome'          => $nomeFormatado,
                'corregedor_cpf'        => $corregedorCpf,
                'data_validade'         => (new \DateTime($data_validade))->format('d/m/Y'),
                'corregedor_nome'       => $nomeFormatadoCorregedor,
                'militar_rg'            => $this->militar->rgm_numero,
                'militar_matricula'     => $this->militar->matricula,
                'data_criacao'          => date('d/m/Y', $this->certidao->data_criacao),
            ]);

            // CONTROLA A VIEW
            $this->certidaoId = $this->certidao->id;
            $this->sucesso = true;

        } catch (Exception $e) {
            //$this->dialog()->error('Erro', 'Erro ao criar a certidão. Tente novamente.')->send();
            dd($e->getMessage(), $e->getTraceAsString());
        }
    }

    public function voltar(){
        return redirect()->to(route('home'));
    }

    public function render(){
        return view('livewire.certidao-nova');
    }
}