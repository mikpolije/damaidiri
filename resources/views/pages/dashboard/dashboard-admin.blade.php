<x-app-layout>
    <x-slot name="page_title"> Dasbor </x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="waving_hand" description="Selamat Datang <b><i>{{ auth()->user()->name }}!</i></b>, di Dasbor <b>Damai Diri.</b> Solusi untuk kesehatan mental anda." />
    </x-slot>
    @canany(['my_account', 'my_profile', 'my_password'])
    <x-slot name="page_action">
        <x-atoms.pure-button-redirect class="btn-success" icon="account_circle" label="Profil Saya" route="profile.my-account" />
    </x-slot>
    @endcanany
    <x-slot name="page_breadcrumb">
        <x-atoms.breadcrumb :links="[
            ['name' => 'Dasbor', 'url' => route('dashboard.admin')],
            ['name' => 'Damai Diri.', 'url' => route('dashboard.admin')],
        ]" />
    </x-slot>
    
</x-app-layout>