<x-app-layout>
    <x-slot name="page_title"> Rincian Kategori Blog </x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="info" description="Menu <b>'Rincian Kategori Blog'</b> berfungsi untuk melihat data kategori blog secara rinci pada sistem." />
    </x-slot>
    <x-slot name="page_action">
        @can('list_blog_category')
            <x-atoms.pure-button-redirect route="blog-category.list" class="btn-dark" icon="keyboard_return" label="Kembali" />
        @endcan
        @can('update_blog_category')
            <x-atoms.pure-button-redirect-parameter route="{{ route('blog-category.edit', $detail_blog_category->id) }}" class="btn-primary" icon="draw" label="Ubah" />
        @endcan
    </x-slot>
    <x-slot name="page_breadcrumb">
        <x-atoms.breadcrumb :links="[
            ['name' => 'Dasbor', 'url' => route('redirect.auth')],
            ['name' => 'Kategori Blog', 'url' => route('blog-category.list')],
            ['name' => 'Rincian Kategori Blog', 'url' => route('blog-category.detail', $detail_blog_category->id)],
        ]" />
    </x-slot>

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <div class="row p-3">
            <div class="col-md-12">
                <div class="table-responsive px-3 pt-3">
                    <table class="table table-bordered">
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Nama Kategori</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">{{ $detail_blog_category->name }}</td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Kode Warna</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">
                                <span class="badge badge-style-light rounded-pill px-4 py-2" style="color: {{ dynamic_text_color($detail_blog_category->color_code) }}; background-color: {{ $detail_blog_category->color_code }}">{{ $detail_blog_category->color_code }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Ditambahkan Pada</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">{{ $detail_blog_category->created_at }}</td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Diperbarui Pada</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">{{ $detail_blog_category->updated_at }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>