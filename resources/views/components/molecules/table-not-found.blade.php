@props([
    'colspan' => '',
    'title' => '',
    'description' => '',
])

<tr>
    <td colspan="{{ $colspan }}" class="text-center">
        <span class="fw-bold"><i>{{ $title }}</i></span>
        <br>
        <span>{{ $description }}</span>
    </td>
</tr>