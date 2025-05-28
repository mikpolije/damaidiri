@props([
    'modal_name' => '',
    'icon' => '',
    'button_label' => '',
    'class' => '',
    'message_title' => '',
    'message_description' => '',
    'route' => '#',
    'method' => 'POST',
    'class_button_submit' => 'btn-danger',
])

<button type="button" class="btn {{ $class }}" data-bs-toggle="modal" data-bs-target="#{{ $modal_name }}">
    <i class="material-icons">{{ $icon }}</i>
    {{ $button_label }}
</button>

<div class="modal fade" id="{{ $modal_name }}" tabindex="-1" aria-labelledby="{{ $modal_name }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <i class="material-icons text-warning">warning</i>
                <h5 class="modal-title" id="{{ $modal_name }}Label">
                    &nbsp;&nbsp;&nbsp;&nbsp;Pesan Konfirmasi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start">
                <p class="fw-bold fs-5">{{ $message_title }}</p>
                <span><i>{{ $message_description }}</i></span>
            </div>
            <form method="POST" action="{{ $route }}" class="modal-footer">
                @method($method)
                @csrf
                <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Batalkan</button>
                <button type="submit" class="btn {{ $class_button_submit }}">Ya, {{ $button_label }}!</button>
            </form>
        </div>
    </div>
</div>