<?php

namespace App\Contracts;

/**
 * Interface Notifiable
 *
 * Kontrak OOP untuk semua jenis notifikasi/aktivitas yang bisa
 * dicatat ke tabel riwayat. Setiap jenis aktivitas punya format
 * pesan yang berbeda (polymorphism via interface).
 *
 * Digunakan oleh: AktivitasLogin, AktivitasPeminjaman, AktivitasPengembalian
 */
interface Notifiable
{
    /**
     * Mendapatkan nama aktivitas yang akan dicatat.
     *
     * @return string
     */
    public function getAktivitas(): string;

    /**
     * Mendapatkan deskripsi detail aktivitas.
     *
     * @return string
     */
    public function getDeskripsi(): string;

    /**
     * Mendapatkan data lengkap siap simpan ke tabel riwayat.
     *
     * @return array
     */
    public function toRiwayatArray(): array;
}
