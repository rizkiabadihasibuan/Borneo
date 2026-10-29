@extends('admin.layout')

@section('title', 'Edit Penerima ' . $recipient->name . ' - Posko BORNEO SIAGA')
@section('header_title', 'Perbarui Data Penerima Laporan')

@section('admin_content')
    <div style="max-width: 820px; margin: 0 auto;">
        
        <div style="margin-bottom: 1.5rem;">
            <a href="{{ route('admin.recipients.index') }}" style="display: inline-flex; align-items: center; gap: 0.4rem; color: var(--color-forest-moss); font-weight: 600; font-size: 0.88rem; text-decoration: none;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                <span>Kembali ke Direktori</span>
            </a>
        </div>

        <div style="background: #FFFFFF; border: 1px solid var(--color-border-subtle); border-radius: 12px; overflow: hidden; box-shadow: var(--shadow-editorial);">
            <div style="background-color: var(--color-deep-forest); color: var(--color-warm-paper); padding: 1.5rem 2rem;">
                <h2 style="font-family: var(--font-heading); font-size: 1.6rem; font-weight: 700; color: #FFFFFF; margin: 0; line-height: 1.2;">
                    Perbarui Entitas: {{ $recipient->name }}
                </h2>
                <span style="font-size: 0.8rem; color: var(--color-earth-sand); display: block; margin-top: 0.25rem;">
                    ID Registrasi Direktori: #{{ $recipient->id }}
                </span>
            </div>

            <form action="{{ route('admin.recipients.update', $recipient) }}" method="POST" style="padding: 2rem;">
                @csrf
                @method('PUT')

                <!-- Nama Resmi -->
                <div style="margin-bottom: 1.35rem;">
                    <label for="name" style="display: block; font-weight: 600; font-size: 0.88rem; color: var(--color-deep-forest); margin-bottom: 0.4rem;">
                        Nama Resmi Entitas / Regu <span style="color: #B83B26;">*</span>
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $recipient->name) }}" 
                           required 
                           style="width: 100%; padding: 0.75rem 1rem; border: 1px solid {{ $errors->has('name') ? '#B83B26' : 'var(--color-border-subtle)' }}; border-radius: 8px; font-size: 0.92rem; background: #FAF9F5;">
                    @error('name')
                        <span style="font-size: 0.78rem; color: #B83B26; margin-top: 0.35rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.35rem;">
                    <!-- Kategori -->
                    <div>
                        <label for="category" style="display: block; font-weight: 600; font-size: 0.88rem; color: var(--color-deep-forest); margin-bottom: 0.4rem;">
                            Kategori Penerima <span style="color: #B83B26;">*</span>
                        </label>
                        <select id="category" name="category" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid {{ $errors->has('category') ? '#B83B26' : 'var(--color-border-subtle)' }}; border-radius: 8px; font-size: 0.9rem; background: #FAF9F5;">
                            <option value="pemerintah" {{ old('category', $recipient->category) == 'pemerintah' ? 'selected' : '' }}>Instansi Pemerintah</option>
                            <option value="komunitas_mpa" {{ old('category', $recipient->category) == 'komunitas_mpa' ? 'selected' : '' }}>Komunitas Masyarakat Peduli Api (MPA)</option>
                        </select>
                    </div>

                    <!-- Tipe Entitas -->
                    <div>
                        <label for="agency_type" style="display: block; font-weight: 600; font-size: 0.88rem; color: var(--color-deep-forest); margin-bottom: 0.4rem;">
                            Tipe / Badan <span style="color: #B83B26;">*</span>
                        </label>
                        <input type="text" 
                               id="agency_type" 
                               name="agency_type" 
                               value="{{ old('agency_type', $recipient->agency_type) }}" 
                               required 
                               style="width: 100%; padding: 0.75rem 1rem; border: 1px solid {{ $errors->has('agency_type') ? '#B83B26' : 'var(--color-border-subtle)' }}; border-radius: 8px; font-size: 0.9rem; background: #FAF9F5;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.35rem;">
                    <!-- Wilayah Provinsi -->
                    <div>
                        <label for="province" style="display: block; font-weight: 600; font-size: 0.88rem; color: var(--color-deep-forest); margin-bottom: 0.4rem;">
                            Wilayah Provinsi Kerja <span style="color: #B83B26;">*</span>
                        </label>
                        <select id="province" name="province" required style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-subtle); border-radius: 8px; font-size: 0.9rem; background: #FAF9F5;">
                            @foreach($provinces as $prov)
                                <option value="{{ $prov }}" {{ old('province', $recipient->province) == $prov ? 'selected' : '' }}>{{ $prov }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Kabupaten / Kota -->
                    <div>
                        <label for="regency" style="display: block; font-weight: 600; font-size: 0.88rem; color: var(--color-deep-forest); margin-bottom: 0.4rem;">
                            Kabupaten / Kota (Opsional)
                        </label>
                        <input type="text" 
                               id="regency" 
                               name="regency" 
                               value="{{ old('regency', $recipient->regency) }}" 
                               style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-subtle); border-radius: 8px; font-size: 0.9rem; background: #FAF9F5;">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.35rem;">
                    <!-- Kontak Telepon Darurat -->
                    <div>
                        <label for="phone" style="display: block; font-weight: 600; font-size: 0.88rem; color: var(--color-deep-forest); margin-bottom: 0.4rem;">
                            Nomor Telepon Kantor / Darurat <span style="color: #B83B26;">*</span>
                        </label>
                        <input type="text" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone', $recipient->phone) }}" 
                               required 
                               style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-subtle); border-radius: 8px; font-size: 0.9rem; background: #FAF9F5;">
                    </div>

                    <!-- Nomor WhatsApp Resmi -->
                    <div>
                        <label for="whatsapp_number" style="display: block; font-weight: 600; font-size: 0.88rem; color: var(--color-deep-forest); margin-bottom: 0.4rem;">
                            Nomor WhatsApp Resmi (Format Internasional)
                        </label>
                        <input type="text" 
                               id="whatsapp_number" 
                               name="whatsapp_number" 
                               value="{{ old('whatsapp_number', $recipient->whatsapp_number) }}" 
                               placeholder="Contoh: 6281234567890" 
                               style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-subtle); border-radius: 8px; font-size: 0.9rem; background: #FAF9F5;">
                        <span style="font-size: 0.75rem; color: var(--color-forest-moss); display: block; margin-top: 0.25rem;">
                            Format bersih: {{ $recipient->clean_whatsapp_number ?: 'Belum diisi' }}
                        </span>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.35rem;">
                    <!-- Alamat Email -->
                    <div>
                        <label for="email" style="display: block; font-weight: 600; font-size: 0.88rem; color: var(--color-deep-forest); margin-bottom: 0.4rem;">
                            Alamat Surel Resmi (Tujuan Disposisi Email)
                        </label>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               value="{{ old('email', $recipient->email) }}" 
                               style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-subtle); border-radius: 8px; font-size: 0.9rem; background: #FAF9F5;">
                    </div>

                    <!-- Narahubung / PJ -->
                    <div>
                        <label for="contact_person" style="display: block; font-weight: 600; font-size: 0.88rem; color: var(--color-deep-forest); margin-bottom: 0.4rem;">
                            Nama Narahubung / PJ Piket (Opsional)
                        </label>
                        <input type="text" 
                               id="contact_person" 
                               name="contact_person" 
                               value="{{ old('contact_person', $recipient->contact_person) }}" 
                               style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-subtle); border-radius: 8px; font-size: 0.9rem; background: #FAF9F5;">
                    </div>
                </div>

                <!-- Kanal Komunikasi Terverifikasi & Kanal Utama -->
                @php
                    $verified = old('verified_channels', $recipient->verified_channels ?: []);
                @endphp
                <div style="background: #FAF9F5; border: 1px solid var(--color-border-subtle); border-radius: 8px; padding: 1.25rem; margin-bottom: 1.35rem;">
                    <label style="display: block; font-weight: 700; font-size: 0.88rem; color: var(--color-deep-forest); margin-bottom: 0.5rem;">
                        Kanal Komunikasi Terverifikasi & Kanal Utama
                    </label>
                    <p style="font-size: 0.8rem; color: var(--color-charcoal); margin: 0 0 1rem 0;">
                        Centang kanal yang telah dikonfirmasi kebenarannya untuk dapat menerima pesan otomatis.
                    </p>

                    <div style="display: flex; gap: 2rem; margin-bottom: 1rem; flex-wrap: wrap;">
                        <label style="display: inline-flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.88rem; font-weight: 600; color: var(--color-deep-forest);">
                            <input type="checkbox" name="verified_channels[]" value="email" {{ in_array('email', $verified) ? 'checked' : '' }} style="accent-color: var(--color-forest-moss); width: 16px; height: 16px;">
                            <span>📧 Email Resmi Terverifikasi</span>
                        </label>
                        <label style="display: inline-flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.88rem; font-weight: 600; color: #128C7E;">
                            <input type="checkbox" name="verified_channels[]" value="whatsapp" {{ in_array('whatsapp', $verified) ? 'checked' : '' }} style="accent-color: #25D366; width: 16px; height: 16px;">
                            <span>💬 WhatsApp Resmi Terverifikasi</span>
                        </label>
                    </div>

                    <div style="max-width: 350px;">
                        <label for="preferred_channel" style="display: block; font-weight: 600; font-size: 0.82rem; color: var(--color-deep-forest); margin-bottom: 0.35rem;">
                            Prioritas Kanal Utama:
                        </label>
                        <select id="preferred_channel" name="preferred_channel" required style="width: 100%; padding: 0.6rem 0.9rem; border: 1px solid var(--color-border-subtle); border-radius: 6px; font-size: 0.88rem; background: #FFFFFF;">
                            <option value="email" {{ old('preferred_channel', $recipient->preferred_channel) == 'email' ? 'selected' : '' }}>Surel (Email Resmi)</option>
                            <option value="whatsapp" {{ old('preferred_channel', $recipient->preferred_channel) == 'whatsapp' ? 'selected' : '' }}>WhatsApp Business / Resmi</option>
                            <option value="manual" {{ old('preferred_channel', $recipient->preferred_channel) == 'manual' ? 'selected' : '' }}>Koordinasi Posko Darat (Telepon/HT)</option>
                        </select>
                    </div>
                </div>

                <!-- Alamat / Wilayah Jangkauan -->
                <div style="margin-bottom: 1.35rem;">
                    <label for="address" style="display: block; font-weight: 600; font-size: 0.88rem; color: var(--color-deep-forest); margin-bottom: 0.4rem;">
                        Alamat Markas / Posko / Wilayah Jangkauan Khusus
                    </label>
                    <textarea id="address" 
                              name="address" 
                              rows="2" 
                              style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-subtle); border-radius: 8px; font-size: 0.9rem; background: #FAF9F5;">{{ old('address', $recipient->address) }}</textarea>
                </div>

                <!-- Catatan Administratif -->
                <div style="margin-bottom: 1.35rem;">
                    <label for="admin_notes" style="display: block; font-weight: 600; font-size: 0.88rem; color: var(--color-deep-forest); margin-bottom: 0.4rem;">
                        Catatan Administratif Posko Siaga (Internal)
                    </label>
                    <textarea id="admin_notes" 
                              name="admin_notes" 
                              rows="2" 
                              placeholder="Catatan SOP, jadwal piket regu, frekuensi radio komunikasi, atau catatan khusus penanganan..." 
                              style="width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--color-border-subtle); border-radius: 8px; font-size: 0.9rem; background: #FAF9F5;">{{ old('admin_notes', $recipient->admin_notes) }}</textarea>
                    <span style="font-size: 0.75rem; color: var(--color-forest-moss); display: block; margin-top: 0.25rem;">
                        Catatan ini hanya terlihat oleh admin internal posko dan tidak dikirim ke pihak luar.
                    </span>
                </div>

                <!-- Status Aktif -->
                <div style="margin-bottom: 2rem;">
                    <label style="display: inline-flex; align-items: center; gap: 0.5rem; cursor: pointer; font-size: 0.88rem; color: var(--color-charcoal);">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $recipient->is_active) ? 'checked' : '' }} style="accent-color: var(--color-forest-moss); width: 16px; height: 16px;">
                        <span>Entitas ini berstatus <strong>aktif</strong> dan siap menerima disposisi laporan</span>
                    </label>
                </div>

                <!-- Action Button -->
                <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                    <a href="{{ route('admin.recipients.index') }}" class="btn btn-outline" style="padding: 0.75rem 1.4rem; font-size: 0.9rem; text-decoration: none;">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-forest" style="padding: 0.75rem 1.6rem; font-size: 0.9rem;">
                        Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>

    </div>
@endsection
