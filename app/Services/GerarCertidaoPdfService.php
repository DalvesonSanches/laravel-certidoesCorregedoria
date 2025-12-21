<?php

namespace App\Services;

use App\Pdf\MYPDF;

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
        $pdf->Setrodape('Tratamos os dados de acordo com a Lei Geral de Proteção de Dados Pessoais, Lei nº 13.709/2018' );
        $pdf->Setrodape2('Emitido eletronicamente pelo Sistema da Corregedoria do CBMAP em: ' . now()->format('d/m/Y \à\s H:i') );

        // Página
        $pdf->AddPage();

        // Conteúdo
        $pdf->SetFont('Times', '', 20);
        $pdf->writeHTMLCell(0, 0, '', '', '<b>CERTIDÃO Nº: ' . $dados['numero'] . '</b>', 0, 1, 0, true, 'C' );
        $pdf->Ln(5);
        $pdf->SetFont('Times', '', 12);

        // QR Code
        //estilo do qrcode
		$style = array(
			'border' => 2,
			'vpadding' => 'auto',
			'hpadding' => 'auto',
			'fgcolor' => array(0,0,0),
			'bgcolor' => false, //array(255,255,255)
			'module_width' => 1, // width of a single module in points
			'module_height' => 1 // height of a single module in points
		);
        $pdf->write2DBarcode( $dados['url_autenticacao'], 'QRCODE,L', 35, 60, 25, 25, $style, 'N' );
        $pdf->SetXY(62, 62);
        $pdf->writeHTMLCell(115, 0, '', '', 'A autenticação desta certidão poderá ser conferida pelo código ' . $dados['codigo_autenticidade'].' decodificando o códiro QR ao lado.', 0, 1, 0, true, 'J', true);
        $pdf->ln(10);
        //recuo
        $recuo = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        
        //fonte
		$pdf->SetFont('Times', '', 13, '', true);

        //Informações gerais
		$pdf->writeHTMLCell(0, 0, '', '',$recuo.'A Corregedoria do Corpo de Bombeiros Militar do Amapá, <span style="font-weight: bold;">CERTIFICA</span> que, <span style="font-weight: bold;">NÃO CONSTA</span> nos seus registros, processo administrativo disciplinar e/ou procedimentos do Código de Processo Penal Militar em andamento, nem penalidade disciplinar com efeitos jurídicos ainda vigentes, em desfavor do(a) servidor(a) '. $dados['militar_nome'] .', CPF nº <span style="font-weight: bold;">'.$dados['militar_cpf'].'</span>, matricula nº <span style="font-weight: bold;">'.$dados['militar_matricula'].'</span> pertencente ao Quadro de Servidor Militar do Governo do Estado do Amapá.', 0, 1, 0, true, 'J', true);
		
        //veracidade
		$pdf->ln(8);
		$pdf->writeHTMLCell(0, 0, '', '',$recuo.'<span style="font-weight: bold;">O REFERIDO É VERDADEIRO E DOU FÉ.</span> Dada e passada nesta cidade de Macapá-AP, <span style="font-weight: bold;">'.$dados['data_criacao'].'</span>.', 0, 1, 0, true, 'L', true);
		
        //validade
		$pdf->ln(8);
		$pdf->writeHTMLCell(0, 0, '', '','<span style="font-weight: bold;">Esta certidão é valida até '.$dados['data_validade'].'.</span>', 0, 1, 0, true, 'L', true);
		
        //observaçõs
		$pdf->ln(5);
		$pdf->writeHTMLCell(0, 0, '', '','<span style="font-weight: bold;">Observação:</span> as informações referente ao posto/graduação, quadro, cpf e matrícula do servidor refletem os registros do sistema de recursos humanos do Corpo de Bombeiros Militar do Amapá. Em caso de divergência, procurar o setorial de pessoal para atualização de cadastro.', 0, 1, 0, true, 'L', true);
		
        //corregedor
		$pdf->ln(20);
		$pdf->writeHTMLCell(0, 0, '', '',$dados['corregedor_nome'].'<br><span style="font-weight: bold;">Corregedor(a) do CBMAP</span>', 0, 1, 0, true, 'C', true);
        
        // Caminho
        //$nomeArquivo = str_replace('/', '_', $dados['numero']) . '.pdf';
        $nomeArquivo = $dados['arquivo_nome'];
        $caminho = storage_path('app/certidoes/' . $nomeArquivo);//localmente salvo
        if (!is_dir(dirname($caminho))) {
            mkdir(dirname($caminho), 0755, true);
        }

        // Salva PDF
        $pdf->Output($caminho, 'F');

        return $caminho;
    }
}