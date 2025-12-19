<?php

namespace App\Services;

use App\Pdf\MYPDF;
use Illuminate\Support\Facades\DB;

class GerarCertidaoPdfService{
    public function gerar(array $dados): string{
        $pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        // Configurações gerais
        $pdf->SetCreator('CBMAP');
        $pdf->SetAuthor('CBMAP');
        $pdf->SetTitle('certidao_' . str_replace('/', '_', $dados['numero']));
        $pdf->SetMargins(15, 45, 15);
        $pdf->SetAutoPageBreak(true, 25);

        // Cabeçalho
        $pdf->SetLogo('gea.png');
        $pdf->SetLin1('GOVERNO DO ESTADO DO AMAPÁ');
        $pdf->SetLin2('CORPO DE BOMBEIROS MILITAR DO AMAPÁ');
        $pdf->SetLin3('CORREGEDORIA');

        // Rodapé
        $pdf->Setrodape(
            'Tratamos os dados de acordo com a Lei Geral de Proteção de Dados Pessoais, Lei nº 13.709/2018'
        );

        $pdf->Setrodape2(
            'Emitido eletronicamente em: ' . now()->format('d/m/Y \à\s H:i')
        );

        // Página
        $pdf->AddPage();

        // Conteúdo
        $pdf->SetFont('Times', '', 20);
        $pdf->writeHTMLCell(
            0,
            0,
            '',
            '',
            '<b>CERTIDÃO Nº: ' . $dados['numero'] . '</b>',
            0,
            1,
            0,
            true,
            'C'
        );

        $pdf->Ln(5);
        $pdf->SetFont('Times', '', 12);

        // QR Code
        $pdf->write2DBarcode(
            $dados['url_autenticacao'],
            'QRCODE,L',
            35,
            60,
            25,
            25
        );

        $pdf->SetXY(62, 62);
        $pdf->writeHTML(
            'A autenticação desta certidão poderá ser conferida pelo código '
            . $dados['codigo_autenticidade']
        );

        // Caminho
        $nomeArquivo = str_replace('/', '_', $dados['numero']) . '.pdf';
        $caminho = storage_path('app/certidoes/' . $nomeArquivo);//localmente salvo

        if (!is_dir(dirname($caminho))) {
            mkdir(dirname($caminho), 0755, true);
        }

        // Salva PDF
        $pdf->Output($caminho, 'F');

        // Atualiza banco
        /*
        DB::table('corregedoria.certidoes')
            ->where('id', $dados['id'])
            ->update(['arquivo_nome' => $nomeArquivo]);

        */
        return $caminho;
    }
}