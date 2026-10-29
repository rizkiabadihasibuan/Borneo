<?php

namespace Tests\Feature;

use Tests\TestCase;

class FoundationRoutesTest extends TestCase
{
    /**
     * Test Beranda page renders according to reference specifications with all sections.
     */
    public function test_home_page_is_accessible_and_contains_editorial_elements(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('BORNEO SIAGA');
        $response->assertSee('UNTUK KALIMANTAN YANG LEBIH AMAN');
        $response->assertSee('Jaga Hutan Hari Ini, Selamatkan Masa Depan.');
        $response->assertSee('Pantau Karhutla');
        $response->assertSee('Buat Laporan');
        $response->assertSee('Layanan untuk masyarakat');
        $response->assertSee('Manfaat Platform');
        $response->assertSee('Artikel Edukasi Pilihan');
        
        // Assert local images are referenced
        $response->assertSee('images/borneo-hero.jpg');
        $response->assertSee('images/borneo-peatland.jpg');
        $response->assertSee('images/borneo-patrol.jpg');

        // Assert all route links are present
        $response->assertSee(route('pantauan.karhutla'));
        $response->assertSee(route('pengaduan'));
        $response->assertSee(route('lacak.pengaduan'));
        $response->assertSee(route('edukasi'));
        $response->assertSee(route('tentang.kami'));
        
        $response->assertViewIs('pages.home');
    }

    public function test_pantauan_karhutla_page_is_accessible(): void
    {
        $response = $this->get('/pantauan-karhutla');
        $response->assertStatus(200);
        $response->assertSee('Pantauan Karhutla di Kalimantan');
        $response->assertViewIs('pages.pantauan');
    }

    public function test_pengaduan_page_is_accessible(): void
    {
        $response = $this->get('/pengaduan');
        $response->assertStatus(200);
        $response->assertSee('Pengaduan Karhutla');
        $response->assertViewIs('pages.pengaduan');
    }

    public function test_lacak_pengaduan_page_is_accessible(): void
    {
        $response = $this->get('/lacak-pengaduan');
        $response->assertStatus(200);
        $response->assertSee('Lacak Status Pengaduan');
        $response->assertViewIs('pages.lacak');
    }

    public function test_edukasi_page_is_accessible(): void
    {
        $response = $this->get('/edukasi');
        $response->assertStatus(200);
        $response->assertSee('Pusat Edukasi');
        $response->assertViewIs('pages.edukasi');
    }

    public function test_tentang_kami_page_is_accessible(): void
    {
        $response = $this->get('/tentang-kami');
        $response->assertStatus(200);
        $response->assertSee('Menjaga Hutan Hujan Tropis Nusantara');
        $response->assertViewIs('pages.tentang');
    }
}
