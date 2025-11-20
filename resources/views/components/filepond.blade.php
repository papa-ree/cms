<style>
    .filepond--root {
        z-index: 1;
    }
</style>
<div wire:ignore x-data x-init="() => {
    const post = FilePond.create($refs.{{ $attributes->get('ref') ?? 'input' }});
    post.setOptions({
        allowMultiple: {{ $attributes->has('multiple') ? 'true' : 'false' }},
        server: {
            process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                @this.upload('{{ $attributes->whereStartsWith('wire:model')->first() }}', file, load, error, progress);
            },
            revert: (filename, load) => {
                @this.removeUpload('{{ $attributes->whereStartsWith('wire:model')->first() }}', filename, load)
            },
        },
        allowImagePreview: {{ $attributes->has('allowFileTypeValidation') ? 'true' : 'false' }},
        imagePreviewMaxHeight: {{ $attributes->has('imagePreviewMaxHeight') ? $attributes->get('imagePreviewMaxHeight') : '256' }},
        allowFileTypeValidation: {{ $attributes->has('allowFileTypeValidation') ? 'true' : 'false' }},
        acceptedFileTypes: {!! $attributes->get('acceptedFileTypes') ?? config('admin.allow_upload_type') !!},
        allowFileSizeValidation: {{ $attributes->has('allowFileSizeValidation') ? 'true' : 'false' }},
        maxFileSize: {!! $attributes->has('maxFileSize') ? "'" . $attributes->get('maxFileSize') . "'" : 'null' !!}
    });
}">
    <input type="file" hidden x-ref="{{ $attributes->get('ref') ?? 'input' }}" />
</div>

@assets
<link href="{{ route('cms.assets', 'filepond.css') }}" rel="stylesheet">
<link href="{{ route('cms.assets', 'filepond-plugin-image-preview.css') }}" rel="stylesheet">

<script src="{{ route('cms.assets', 'filepond-plugin-file-validate-size.js') }}"></script>
<script src="{{ route('cms.assets', 'filepond-plugin-file-validate-type.js') }}"></script>
<script src="{{ route('cms.assets', 'filepond-plugin-image-preview.js') }}"></script>
<script src="{{ route('cms.assets', 'filepond.js') }}"></script>
@endassets

@script
<script>
    FilePond.registerPlugin( FilePondPluginFileValidateType );
    FilePond.registerPlugin( FilePondPluginFileValidateSize );
    FilePond.registerPlugin( FilePondPluginImagePreview );
</script>
@endscript