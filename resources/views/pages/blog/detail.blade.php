<x-app-layout>
    <x-slot name="page_title"> Rincian Blog </x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="info" description="Menu <b>'Rincian Blog'</b> berfungsi untuk melihat data blog secara rinci pada sistem." />
    </x-slot>
    <x-slot name="page_action">
        @can('list_blog')
            <x-atoms.pure-button-redirect route="blog.list" class="btn-dark" icon="keyboard_return" label="Kembali" />
        @endcan
        @can('update_blog')
            <x-atoms.pure-button-redirect-parameter route="{{ route('blog.edit', $detail_blog->id) }}" class="btn-primary" icon="draw" label="Ubah" />
        @endcan
    </x-slot>
    <x-slot name="page_breadcrumb">
        <x-atoms.breadcrumb :links="[
            ['name' => 'Dasbor', 'url' => route('redirect.auth')],
            ['name' => 'Blog', 'url' => route('blog.list')],
            ['name' => 'Rincian Blog', 'url' => route('blog.detail', $detail_blog->id)],
        ]" />
    </x-slot>

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <div class="row p-3">
            <div class="col-md-12">
                <div class="table-responsive px-3 pt-3">
                    <table class="table table-bordered">
                        <tr>
                            <td class="w-75 bg-ligh text-center" colspan="3">
                                <img src="{{ asset($detail_blog->thumbnail ?? 'assets/images/backgrounds/dummy-hd.png') }}" alt="photo" class="img-fluid p-2" style="height: 250px;"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Judul</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">{{ $detail_blog->title }}</td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Kategori</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">
                                <span class="badge badge-style-light rounded-pill px-4 py-2" style="color: {{ dynamic_text_color($detail_blog->blog_category->color_code) }}; background-color: {{ $detail_blog->blog_category->color_code }}">{{ $detail_blog->blog_category->name }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Penulis</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">{{ $detail_blog->author->name }}</td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Deskripsi</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">
                                <div id="description-content">{!! $detail_blog->description !!}</div>
                                <button id="toggle-button" style="display: none; background: none; border: none; color: blue; cursor: pointer;">Selengkapnya</button>
                            </td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Ditambahkan Pada</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">{{ $detail_blog->created_at }}</td>
                        </tr>
                        <tr>
                            <td class="w-25 fw-bold text-white" style="background-color: #21336e !important;">Diperbarui Pada</td>
                            <td class="fw-bold text-center bg-light">:</td>
                            <td class="w-75 bg-light">{{ $detail_blog->updated_at }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid mt-4">
        <div class="section-description d-flex align-items-center justify-content-between">
            <h1>Galeri Blog</h1>
            @can('list_blog_gallery')
                <x-atoms.pure-button-redirect-parameter route="{{ route('blog.blog-gallery.list', $detail_blog->id) }}" class="btn-dark" icon="collections_bookmark" label="Kelola Galeri" />
            @endcan
        </div>
        <div class="row">
            @forelse ($data_blog_gallery as $item)
                <div class="col-md-3">
                    <div class="card file-manager-group">
                        <div class="card-body align-items-center">
                            <div class="border text-center p-2 rounded">
                                <img src="{{ asset( $item->document ?? 'assets/images/backgrounds/dummy-hd.png') }}" class="img-fluid" alt="thumbnail-{{ $loop->iteration }}" style="height: 150px; object-fit: contain;" />
                            </div>
                            <div class="mt-2">
                                <span class="file-manager-group-title">Keterangan :</span>
                                <span class="file-manager-group-about">{{ $item->caption }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-md-12">
                    <div class="card file-manager-group">
                        <div class="card-body d-flex align-items-center">
                            <i class="material-icons text-success">image</i>
                            <div class="file-manager-group-info flex-fill">
                                <a href="#" class="file-manager-group-title">Maaf, Belum Ada Data.</a>
                                <span class="file-manager-group-about">Silahkan tambahkan data terlebih dahulu.</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    @push('js-internal')
        <script>
            document.addEventListener("DOMContentLoaded", function () {
            const description_cell = document.getElementById("description-content");
            const toggle_button = document.getElementById("toggle-button");

            const max_line = 5;
            const line_height = parseInt(window.getComputedStyle(description_cell).lineHeight);
            const max_height = max_line * line_height;

            if (description_cell.scrollHeight > max_height) {
                description_cell.style.maxHeight = max_height + "px";
                description_cell.style.overflow = "hidden";
                toggle_button.style.display = "block";
                toggle_button.dataset.expanded = "false";
            }

            toggle_button.addEventListener("click", function () {
                if (toggle_button.dataset.expanded === "false") {
                    description_cell.style.maxHeight = "none";
                    toggle_button.innerText = "Tampilkan lebih sedikit";
                    toggle_button.dataset.expanded = "true";
                } else {
                    description_cell.style.maxHeight = max_height + "px";
                    toggle_button.innerText = "Selengkapnya";
                    toggle_button.dataset.expanded = "false";
                }
            });
        });
        </script>
    @endpush
</x-app-layout>