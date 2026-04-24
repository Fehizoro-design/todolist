<?php

use Livewire\Component;
use Livewire\Attributes\Layout;


new #[Layout('layouts::guest', ['title' => 'Login'])] class extends Component {
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public function login()
    {
        $credentials = $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $this->remember)) {
            session()->regenerate();

            // Redirection vers les tâches après connexion
            return redirect()->route('tasks.index');
        }

        $this->addError('email', 'Les identifiants sont incorrects.');
    }

    // public function render()
    // {
    //     // On utilise un layout "guest" (vide) pour la page de login, pas le dashboard
    //     return $this->redirectRoute('login', navigate: true);
    // }
}
?>

<div class="min-h-screen flex items-center justify-center bg-zinc-50 dark:bg-zinc-950 p-4">
    <div
        class="max-w-md w-full bg-white dark:bg-zinc-900 rounded-2xl shadow-xl border border-zinc-200 dark:border-zinc-800 p-8">

        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Bienvenue</h1>
            <p class="text-sm text-zinc-500 mt-2">Connectez-vous pour gérer vos tâches.</p>
        </div>

        <form wire:submit="login" class="space-y-6">
            {{-- Email --}}
            <div class="space-y-2">
                <label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Email</label>
                <input wire:model="email" type="email" required
                    class="w-full px-4 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 focus:ring-2 focus:ring-blue-500 outline-none text-zinc-900 dark:text-white">
                @error('email') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
            </div>

            {{-- Mot de passe --}}
            <div class="space-y-2">
                <label class="text-sm font-medium text-zinc-700 dark:text-zinc-300">Mot de passe</label>
                <input wire:model="password" type="password" required
                    class="w-full px-4 py-2 rounded-lg border border-zinc-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 focus:ring-2 focus:ring-blue-500 outline-none text-zinc-900 dark:text-white">
            </div>

            {{-- Se souvenir de moi --}}
            <div class="flex items-center justify-between">
                <label class="flex items-center gap-2 text-sm text-zinc-600 dark:text-zinc-400">
                    <input wire:model="remember" type="checkbox"
                        class="rounded border-zinc-300 text-blue-600 focus:ring-blue-500">
                    Se souvenir de moi
                </label>
            </div>

            {{-- Bouton Connexion --}}
            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg transition-colors flex justify-center items-center">
                <span wire:loading.remove>Se connecter</span>
                <span wire:loading>Connexion...</span>
            </button>

            {{-- Bouton Google (Visuel pour l'instant) --}}
            <button type="button"
                class="w-full bg-white dark:bg-zinc-800 border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-300 font-medium py-2.5 rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-700 transition-colors flex items-center justify-center gap-2">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path fill="currentColor"
                        d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                    <path fill="#34A853"
                        d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                    <path fill="#FBBC05"
                        d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                    <path fill="#EA4335"
                        d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                </svg>
                Continuer avec Google
            </button>
        </form>
    </div>
</div>