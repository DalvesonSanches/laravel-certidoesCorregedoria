<?php

namespace App\Pdf;

use TCPDF;

class MYPDF extends TCPDF
{
    public $grid = false;

    protected ?string $nomeUsuario = null;
    protected ?string $imagem = null;

    protected ?string $tituloLinha1 = null;
    protected ?string $tituloLinha2 = null;
    protected ?string $tituloLinha3 = null;
    protected ?string $tituloLinha4 = null;

    protected ?string $rodape = null;
    protected ?string $rodape2 = null;

    /* ==============================
     * SETTERS (iguais ao ScriptCase)
     * ============================== */

    public function SetUser(string $usuario): void{
        $this->nomeUsuario = $usuario;
    }

    public function SetLogo(string $logo): void{
        // espera que a imagem esteja em /public/images
        $this->imagem = public_path('images/' . $logo);
    }

    public function SetLin1(string $linha): void{
        $this->tituloLinha1 = $linha;
    }

    public function SetLin2(string $linha): void{
        $this->tituloLinha2 = $linha;
    }

    public function SetLin3(string $linha): void{
        $this->tituloLinha3 = $linha;
    }

    public function SetLin4(string $linha): void{
        $this->tituloLinha4 = $linha;
    }

    public function Setrodape(string $texto): void{
        $this->rodape = $texto;
    }

    public function Setrodape2(string $texto): void{
        $this->rodape2 = $texto;
    }

    /* ==============================
     * GRID (IGUAL AO ORIGINAL)
     * ============================== */

    protected function DrawGrid(): void{
        $spacing = $this->grid === true ? 5 : $this->grid;

        $this->SetDrawColor(204, 255, 255);
        $this->SetLineWidth(0.35);

        for ($i = 0; $i < $this->w; $i += $spacing) {
            $this->Line($i, 0, $i, $this->h);
        }

        for ($i = 0; $i < $this->h; $i += $spacing) {
            $this->Line(0, $i, $this->w, $i);
        }

        $this->SetDrawColor(0, 0, 0);

        $x = $this->GetX();
        $y = $this->GetY();

        $this->SetTextColor(204, 204, 204);

        for ($i = 20; $i < $this->h; $i += 20) {
            $this->SetXY(1, $i - 3);
            $this->Write(4, $i);
        }

        for ($i = 20; $i < ($this->w - $this->rMargin - 10); $i += 20) {
            $this->SetXY($i - 1, 1);
            $this->Write(4, $i);
        }

        $this->SetXY($x, $y);
    }

    /* ==============================
     * HEADER
     * ============================== */

    public function Header(): void{
        if ($this->grid) {
            $this->DrawGrid();
        }

        $this->SetFont('Times', 'B', 13);

        // Logo (posição idêntica ao ScriptCase)
        if ($this->imagem && file_exists($this->imagem)) {
            $this->Image($this->imagem, 97, 5, 16, 0, 'PNG');
        }

        $this->Ln(2);

        $this->SetY(25);

        if ($this->tituloLinha1) {
            $this->Cell(0, 0, $this->tituloLinha1, 0, false, 'C');
            $this->Ln(5);
        }

        if ($this->tituloLinha2) {
            $this->Cell(0, 0, $this->tituloLinha2, 0, false, 'C');
            $this->Ln(5);
        }

        if ($this->tituloLinha3) {
            $this->Cell(0, 0, $this->tituloLinha3, 0, false, 'C');
            $this->Ln(5);
        }

        if ($this->tituloLinha4) {
            $this->Cell(0, 0, $this->tituloLinha4, 0, false, 'C');
        }
    }

    /* ==============================
     * FOOTER
     * ============================== */

    public function Footer(): void{
        $this->SetFont('helvetica', '', 9);

        // Número da página
        $this->SetXY(185, 285);
        $this->Cell(
            0,
            10,
            'Página ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(),
            0,
            false,
            'L'
        );

        // Rodapé principal
        $this->SetY(283);
        if ($this->rodape) {
            $this->Cell(0, 0, $this->rodape, 0, false, 'L');
        }

        $this->Ln(5);

        // Rodapé secundário
        if ($this->rodape2) {
            $this->SetFont('helvetica', 'B', 8);
            $this->Cell(0, 0, $this->rodape2, 0, false, 'L');
        }
    }
}