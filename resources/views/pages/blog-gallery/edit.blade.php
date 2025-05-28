<x-app-layout>
    <x-slot name="page_title"> Ubah Galeri Blog </x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="info" description="Menu <b>'Ubah Galeri Blog'</b> berfungsi untuk memperbarui data galeri blog pada sistem." />
    </x-slot>
    <x-slot name="page_action">
        @can('list_blog_gallery')
            <x-atoms.pure-button-redirect route="blog.list" class="btn-dark" icon="keyboard_return" label="Kembali" />
        @endcan
    </x-slot>
    <x-slot name="page_breadcrumb">
        <x-atoms.breadcrumb :links="[
            ['name' => 'Dasbor', 'url' => route('redirect.auth')],
            ['name' => 'Blog', 'url' => route('blog.list')],
            ['name' => 'Galeri Blog', 'url' => route('blog.blog-gallery.list', $detail_blog->id)],
            ['name' => 'Ubah Galeri Blog', 'url' => route('blog.blog-gallery.edit', [$detail_blog->id, $detail_blog_gallery->id])],
        ]" />
    </x-slot>

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <div class="row p-3">
            <div class="col-md-12">
                <div class="px-3 pb-3">
                    <form method="POST" class="row g-3 mt-1 align-items-center" action="{{ route('blog.blog-gallery.update', [$detail_blog->id, $detail_blog_gallery->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="col-md-3 text-center">
                            <img src="{{ asset( $detail_blog_gallery->document ?? 'assets/images/backgrounds/dummy-hd.png') }}" class="w-100 border rounded p-3" alt="preview-image" id="preview">
                        </div>
                        <div class="col-md-9">
                            <x-atoms.form-input id="image" label="Gambar" required="true" type="file" name="image" />
                        </div>
                        <div class="col-md-12">
                            <x-atoms.form-input id="caption" label="Keterangan" required="true" type="text" name="caption" value="{{ $detail_blog_gallery->caption }}" />
                        </div>
                        @can('create_blog_gallery')
                        <div class="col-md-12 text-end mt-4">
                            <x-atoms.form-button type="submit" class="btn-primary py-2" icon="save" label="Simpan" />
                        </div>
                        @endcan
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('js-internal')
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
                $('#image').change(function() {
                    readURL(this);
                });
            });
        </script>
    @endpush
</x-app-layout>