<x-app-layout>
    @push('css-internal')
        <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/plugins/summernote/summernote-lite.min.css') }}" rel="stylesheet">
    @endpush

    <x-slot name="page_title"> Tambah Blog </x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="info" description="Menu <b>'Tambah Blog'</b> berfungsi untuk menambahkan data baru blog pada sistem." />
    </x-slot>
    <x-slot name="page_action">
        @can('list_blog')
            <x-atoms.pure-button-redirect route="blog.list" class="btn-dark" icon="keyboard_return" label="Kembali" />
        @endcan
        @can('create_blog')
            <x-atoms.pure-button-redirect route="blog.create" class="btn-warning" icon="restart_alt" label="Atur Ulang" />
        @endcan
    </x-slot>
    <x-slot name="page_breadcrumb">
        <x-atoms.breadcrumb :links="[
            ['name' => 'Dasbor', 'url' => route('redirect.auth')],
            ['name' => 'Blog', 'url' => route('blog.list')],
            ['name' => 'Tambah Blog', 'url' => route('blog.create')],
        ]" />
    </x-slot>

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <form method="POST" class="row g-3 p-5 mt-1 align-items-center" action="{{ route('blog.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="col-md-12">
                <x-atoms.form-input id="title" label="Judul" required="true" type="text" name="title" />
            </div>
            <div class="col-md-3 text-center">
                <img src="{{ asset('assets/images/backgrounds/dummy-hd.png') }}" class="w-100 border rounded p-3" alt="preview-image" id="preview">
            </div>
            <div class="col-md-9">
                <x-atoms.form-input id="thumbnail" label="Gambar (Thumbnail)" required="true" type="file" name="thumbnail" />
            </div>
            <div class="col-md-12">
                <x-atoms.form-select-option id="blog_category_id" name="blog_category_id" label="Kategori Blog" class="select2" :options="array_merge(
                    $data_blog_category->map(function($item) {
                        return [
                            'value' => $item->id, 
                            'label' => $item->name, 
                            'config' => old('blog_category_id') == $item->id ? 'selected' : ''
                        ];
                    })->toArray()
                )" />
            </div>
            <div class="col-md-12">
                <x-atoms.form-input id="author" label="Penulis" required="true" type="text" name="author" value="{{ auth()->user()->name }}" readonly="true" />
            </div>
            <div class="col-md-12">
                <x-atoms.form-textarea id="description" label="Deskripsi Blog" required="true" name="description" class="summernote" />
            </div>

            <div class="col-md-12">
                <hr>
                <x-atoms.pure-label label="Galeri Blog (Opsional / Boleh Dikosongi)" />
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Gambar Galeri</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Keterangan</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="gallery-table-body">
                            <tr>
                                <td>
                                    <x-atoms.pure-input type="file" name="document[]" class="image-input"/>
                                </td>
                                <td>
                                    <x-atoms.pure-input type="text" name="caption[]" placeholder="Keterangan Gambar"/>
                                </td>
                                <td class="text-center">
                                    <x-atoms.form-button type="button" class="btn-danger py-2 delete-row" icon="delete" label="Hapus" />
                                </td>
                            </tr>
                            <tr>
                                <td class="text-center" colspan="3">
                                    <x-atoms.form-button type="button" id="add_row" class="btn-success py-2" icon="add" label="Tambah Baris" />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            @can('create_blog')
                <div class="col-md-12 text-end mt-4">
                    <x-atoms.form-button type="submit" class="btn-primary py-2" icon="save" label="Simpan" />
                </div>
            @endcan
        </form>
    </div>

    @push('js-internal')
        <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/summernote/summernote-lite.min.js') }}"></script>
        <script>
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#preview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $(document).ready(function() {
                $('.select2').select2();
                $('.summernote').summernote({
                    height: 250,
                    placeholder: 'Masukkan Deskripsi Postingan...'
                });
                
                $('#thumbnail').change(function() {
                    readURL(this);
                });

                $('#add_row').on('click', function () {
                    const new_row = `
                        <tr>
                            <td><x-atoms.pure-input type="file" name="document[]" class="image-input"/></td>
                            <td><x-atoms.pure-input type="text" name="caption[]" placeholder="Keterangan Gambar"/></td>
                            <td class="text-center"><x-atoms.form-button type="button" class="btn-danger py-2 delete-row" icon="delete" label="Hapus" /></td>
                        </tr>`;
                    $('#gallery-table-body tr:last').before(new_row);
                });

                $(document).on('click', '.delete-row', function () {
                    $(this).closest('tr').remove();
                });
            });
        </script>
    @endpush
</x-app-layout>
