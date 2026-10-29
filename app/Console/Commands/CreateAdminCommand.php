<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin 
                            {--name= : Nama lengkap administrator}
                            {--email= : Alamat email administrator}
                            {--password= : Kata sandi akun administrator}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Membuat akun administrator baru untuk dashboard Borneo Siaga secara aman';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('=== Pembuatan Akun Administrator Borneo Siaga ===');

        $name = $this->option('name') ?: $this->ask('Masukkan Nama Lengkap Administrator');
        $email = $this->option('email') ?: $this->ask('Masukkan Alamat Email Administrator');
        $password = $this->option('password') ?: $this->secret('Masukkan Kata Sandi (minimal 8 karakter)');

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ], [
            'name.required' => 'Nama administrator wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email tersebut sudah terdaftar di sistem.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal berjumlah 8 karakter.',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return self::FAILURE;
        }

        $user = User::create([
            'name' => trim($name),
            'email' => strtolower(trim($email)),
            'password' => Hash::make($password),
            'is_admin' => true,
            'email_verified_at' => now(),
        ]);

        $this->info("✓ Akun administrator berhasil dibuat untuk: {$user->email} (ID: {$user->id})");
        return self::SUCCESS;
    }
}
