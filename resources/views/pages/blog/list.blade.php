<x-app-layout>
    <x-slot name="page_title"> Daftar Blog </x-slot>
    <x-slot name="page_description">
        <x-atoms.header-description icon="info" description="Menu <b>'Daftar Blog'</b> berisi keseluruhan data blog yang tersedia di sistem." />
    </x-slot>
    <x-slot name="page_action">
        @can('create_blog')
            <x-atoms.pure-button-redirect route="blog.create" class="btn-primary" icon="library_add" label="Tambah Data" />
        @endcan
    </x-slot>
    <x-slot name="page_breadcrumb">
        <x-atoms.breadcrumb :links="[
            ['name' => 'Dasbor', 'url' => route('redirect.auth')],
            ['name' => 'Blog', 'url' => route('blog.list')],
            ['name' => 'Daftar Blog', 'url' => route('blog.list')],
        ]" />
    </x-slot>

    <div class="container-fluid bg-white rounded mt-4 shadow">
        <div class="row p-3">
            <div class="col-md-12">
                <form method="GET" action="{{ route('blog.list') }}" class="row align-items-center p-3">
                    <div class="col-md-7">
                        <x-atoms.pure-input type="text" placeholder="Masukkan Kata Kunci..." name="search" id="search" value="{{ $search }}"/>
                    </div>
                    <div class="col-md-2 my-1">
                        <x-atoms.form-button type="submit" class="btn-primary w-100" icon="pageview" label="Cari"/>
                    </div>
                    <div class="col-md-3">
                        <x-atoms.pure-select-option id="show" name="show" :options="[
                            ['value' => 5, 'label' => 'Data Ditampilkan : 5 Data.', 'config' => $per_page == 5 ? 'selected' : '' ],
                            ['value' => 10, 'label' => 'Data Ditampilkan : 10 Data.', 'config' => $per_page == 10 ? 'selected' : '' ],
                            ['value' => 25, 'label' => 'Data Ditampilkan : 25 Data.', 'config' => $per_page == 25 ? 'selected' : '' ],
                            ['value' => 50, 'label' => 'Data Ditampilkan : 50 Data.', 'config' => $per_page == 50 ? 'selected' : '' ],
                            ['value' => 100, 'label' => 'Data Ditampilkan : 100 Data.', 'config' => $per_page == 100 ? 'selected' : '' ],
                        ]" />
                    </div>
                </form>
            </div>
            <div class="col-md-12">
                <div class="table-responsive px-3">
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">No</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Gambar (Thumbnail) </th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Judul</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Kategori</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Penulis</th>
                                <th scope="col" class="text-white text-center fw-bold align-middle" style="background-color: #21336e;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data_blog as $item)
                                <tr>
                                    <th scope="row" class="align-middle text-center fw-bold">{{ $loop->iteration + ($data_blog->currentPage() - 1) * $data_blog->perPage() }}</th>
                                    <td class="align-middle text-center">
                                        <img src="{{ asset($item->thumbnail ?? 'assets/images/backgrounds/dummy-hd.png') }}" class="img-fluid" alt="thumbnail-{{ $loop->iteration }}" style="height: 150px; object-fit: contain;" />
                                    </td>
                                    <td class="align-middle">{{ $item->title }}</td>
                                    <td class="align-middle text-center">
                                        <span class="badge badge-style-light rounded-pill px-4 py-2" style="color: {{ dynamic_text_color($item->blog_category->color_code) }}; background-color: {{ $item->blog_category->color_code }}">{{ $item->blog_category->name }}</span>
                                    </td>
                                    <td class="align-middle text-center">{{ $item->author->name }}</td>
                                    <td class="align-middle">
                                        <div class="row g-3">
                                            @can('list_blog_gallery')
                                            <div class="col-6">
                                                <x-atoms.pure-button-redirect-parameter route="{{ route('blog.blog-gallery.list', $item->id) }}" class="btn-dark btn-style-light w-100" icon="collections_bookmark" label="Galeri" />
                                            </div>
                                            @endcan
                                            @can('update_blog')
                                            <div class="col-6">
                                                <x-atoms.pure-button-redirect-parameter route="{{ route('blog.edit', $item->id) }}" class="btn-primary btn-style-light w-100" icon="draw" label="Ubah" />
                                            </div>
                                            @endcan
                                            @can('read_blog')
                                            <div class="col-6">
                                                <x-atoms.pure-button-redirect-parameter route="{{ route('blog.detail', $item->id) }}" class="btn-warning btn-style-light w-100" icon="preview" label="Lihat" />
                                            </div>
                                            @endcan
                                            @can('delete_blog')
                                                <div class="col-6">
                                                    <x-atoms.form-button-confirmation modal_name="modalDelete{{ $item->id }}" icon="delete" button_label="Hapus" class="btn-danger btn-style-light w-100" message_title="Apakah anda yakin ingin menghapus data?" message_description="Data yang sudah dihapus tidak dapat dikembalikan kembali." route="{{ route('blog.destroy', $item->id) }}" method="DELETE" />
                                                </div>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <x-molecules.table-not-found colspan="6" title="Maaf, Data Tidak Ditemukan / Belum Ada Data." description="Silahkan masukkan kata kunci yang sesuai / tambahkan data terlebih dahulu." />
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center px-3 pb-3">
                    <div>
                        <span>Menampilkan <b>{{ number_format($data_blog->count(), 0, ',', '.') }}</b> data dari <b>{{ number_format($data_blog->total(), 0, ',', '.') }}</b> data.</span>
                    </div>
                    <div>
                        {{ $data_blog->appends(request()->query())->links('components.molecules.table-pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js-internal')
        <script>
            document.addEventListener("DOMContentLoaded", function(e) {
                document.getElementById('show').addEventListener('change', function() {
                    var show = this.value;
                    var url = new URL(window.location.href);
                    url.searchParams.set('show', show);
                    window.location.href = url.toString();
                });
            });
        </script>
    @endpush
</x-app-layout>