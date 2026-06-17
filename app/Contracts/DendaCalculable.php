<?php

namespace App\Contracts;
interface DendaCalculable
{
    /**
     * Hitung total denda berdasarkan jumlah hari keterlambatan.
     *
     *@param  int  $hariTerlambat
     *@return float
     */
    public function hitungDenda(int $hariTerlambat): float;

    /**
     * Mendapatkan nama/jenis kebijakan denda.
     *
     * @return string
     */
    public function getNamaKebijakan(): string;

    /**
     * Mendapatkan keterangan detail kebijakan.
     *
     * @return string
     */
    public function getKeterangan(): string;
}
