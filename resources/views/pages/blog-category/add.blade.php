<x-app-layout>
    <x-slot name="page_title"> Tambah Kategori Blog </x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="info" description="Menu <b>'Tambah Kategori Blog'</b> berfungsi untuk menambahkan data baru kategori blog pada sistem." />
    </x-slot>
    <x-slot name="page_action">
        @can('list_blog_category')
            <x-atoms.pure-button-redirect route="blog-category.list" class="btn-dark" icon="keyboard_return" label="Kembali" />
        @endcan
        @can('create_blog_category')
            <x-atoms.pure-button-redirect route="blog-category.create" class="btn-warning" icon="restart_alt" label="Atur Ulang" />
        @endcan
    </x-slot>
    <x-slot name="page_breadcrumb">
        <x-atoms.breadcrumb :links="[
            ['name' => 'Dasbor', 'url' => route('redirect.auth')],
            ['name' => 'Kategori Blog', 'url' => route('blog-category.list')],
            ['name' => 'Tambah Kategori Blog', 'url' => route('blog-category.create')],
        ]" />
    </x-slot>

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <form method="POST" class="row g-3 p-5 mt-1" action="{{ route('blog-category.store') }}">
            @csrf
            <div class="col-md-12">
                <x-atoms.form-input id="name" label="Nama Kategori" required="true" type="text" name="name" />
            </div>
            <div class="col-md-12">
                <x-atoms.form-input id="color_code" label="Kode Warna" required="true" type="color" name="color_code" style="height: 40px;" value="{{ random_hex_color() }}" />
            </div>
            @can('create_blog_category')
                <div class="col-md-12 text-end mt-4">
                    <x-atoms.form-button type="submit" class="btn-primary py-2" icon="save" label="Simpan" />
                </div>
            @endcan
        </form>
    </div>
</x-app-layout>