@props([
    'status' => '',
    'icon' => '',
    'label_title' => '',
    'label_description' => '',
])

<div class="alert alert-custom alert-indicator-left indicator-{{ $status }} alert-dismissible d-flex mt-4 shadow" role="alert">
    <div class="custom-alert-icon icon-{{ $status }}"><i class="material-icons-outlined">{{ $icon }}</i></div>
    <div class="alert-content">
        <span class="alert-title">{{ $label_title }}!</span>
        <span class="alert-text">{!! $label_description !!}.</span>
    </div>
    <button type="button" class="btn" data-bs-dismiss="alert" aria-label="Close"><i class="material-icons-two-tone">close</i></button>
</div>