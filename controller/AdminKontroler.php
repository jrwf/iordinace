<?php

class AdminKontroler extends Kontroler
{
    /**
     * @throws Exception
     */
    public function zpracuj($parametry)
    {
        $order = $_POST;
        $aktuality = new aktuality();

        if ($order && is_array($order)) {
            $aktuality->updateAktualitaOrder($order);
        }

        $this->hlavicka = array(
            'titulek' => 'administrace',
            'klicova_slova' => '',
            'popis' => ''
        );

        $aktuality = new \aktuality();

        $this->data['administrace'] = 'administrace je tady';
        $this->data['seznamAktualit'] = $aktuality->seznamAktualit();

        $this->pohled = 'admin';
    }
}